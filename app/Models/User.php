<?php

namespace App\Models;

use File;
use App\Models\WlaChanel;
use Laravel\Scout\Searchable;
use App\Models\Traits\PictureTrait;
use App\Components\Msg\Traits\MsgTrait;
use Illuminate\Notifications\Notifiable;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\Access\Authorizable;

use App\Models\WlaCourse;
use App\Models\WlaCompany;
use App\Models\UserAuthenticatable;

use App\Events\User\UserWasDeactivated;
use App\Events\User\UserWasRestored;
use App\Events\User\UserWasDeleted;

class User extends UserAuthenticatable
{
    use Notifiable, 
        Sluggable,
        Searchable,
        MsgTrait,
        PictureTrait;

    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'email', 'password', 'first_name', 'last_name', 'about', 'slug', 'chanel', 'wla', 'picture', 'registered', 'confirmed', 'job'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $dates = ['deleted_at'];

    public static function boot() {
        parent::boot();

        static::deleted(function($model) {
            if (! property_exists($model, 'forceDeleting') || !!$model->forceDeleting ) {
                // Deleted
                $model->deletePictureFile();
                event(new UserWasDeleted($model->id));
            } else {
                // Soft Deleted (Deactivated)                   
                event(new UserWasDeactivated($model->id));                          
            }
        });

        static::restored(function($model) {
            event(new UserWasRestored($model->id));
        });
    }

    public function sluggable()
    {
        return [
            'slug' => [
                'source' => ['first_name', 'last_name'],
                'separator' => '-'
            ]
        ];
    }

    public function courses() {
        return $this->belongsToMany(WlaCourse::class, 'wla_course_subscriptions', 'user_id', 'course_id')
            ->withTimestamps();
    }

    public function companies() {
        return $this->belongsToMany(WlaCompany::class, 'wla_company_employees', 'user_id', 'company_id')
            ->withTimestamps()
            ->withPivot('admin');
    }

    public function getCompanyAttribute() {
        return $this->companies->count() ? $this->companies->first() : null;
    }

    public function getFullName() {
        return "{$this->first_name} {$this->last_name}";
    }

    public function getCompanyJob() {
        $company = $this->company;
        $companyLink = !is_null($company) ? \Html::link(route('wla.company', $company->slug), $company->name) : null;
        return $companyLink . (($companyLink && $this->job) ? ', ' : '') . $this->job ;
    }

    public function isRegistered() {
        return !! $this->registered;
    } 

    public function isSubscribed(WlaCourse $course) {
        return true;
    }   


    public function wlaChanel() {
        return $this->hasOne(WlaChanel::class, 'user_id');
    }

    public function isWla() {
        return !! $this->wla;
    }

    //Search section

    /**
     * Get the index name for the model.
     *
     * @return string
     */
    public function searchableAs()
    {
        return 'users_index';
    }

    /**
     * Get the indexable data array for the model.
     *
     * @return array
     */
    public function toSearchableArray()
    {
        return [
            'id' => $this->id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name            
        ];
    }

}
