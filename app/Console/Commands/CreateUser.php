<?php

namespace App\Console\Commands;

use Validator;
use App\Models\User;
use Illuminate\Console\Command;

class CreateUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create new user';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $user = User::create([
            'name' => $this->getNewUserName(),
            'email' => $this->getNewUserEmail(),
            'password' => bcrypt($this->getNewUserPassword())
        ]);
        $this->info("New user has been created: {$user->name} - {$user->email}");
    }

    private function getNewUserName() 
    {
        while(true) {
            $name = $this->ask('New user name');    
            $isValid = $this->validate(
                ['name' => $name ],
                ['name' => 'required']
            );
            if($isValid) {
                return $name;
            }            
        }        
    }

    private function getNewUserEmail() {
        while(true) {
            $email = $this->ask('User email');
            $isValid = $this->validate(
                ['email' => $email],
                ['email' => 'required|email|unique:users,email']
            );
            if($isValid) {
                return $email;
            }
        }
    }

    private function getNewUserPassword()
    {
        while (true) {
            $password = $this->secret("User Password");
            $passwordConfirmation = $this->secret("Confirm Password");
            $isValid = $this->validate(
                ['password' => $password, 'password_confirmation' => $passwordConfirmation],
                ['password' => 'confirmed|min:6']
            );
            if($isValid) {
                return $password;
            }
        }
    }

    private function validate($data, $rules) {
        $validator = Validator::make($data, $rules);
        if($validator->fails()) {
            $this->error($validator->errors()->first());            
        }
        return $validator->passes();
    }
}
