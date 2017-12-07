<?php

Route::get('api/courses/load-cards', 'CoursesController@apiLoadCards');
Route::get('image/{type}/{template?}/{filename?}', 'ImageController@image')->name('image');

Route::group(['domain' => 'www.{company}.'.env('APP_DOMAIN')], function() {
    Route::get('/', 'CompaniesController@index')->name('wla.company');
    Route::get('/teams', 'CompanyTeamsController@index')->name('wla.teams');
    Route::post('/teams/store', 'CompanyTeamsController@store')->name('wla.teams.store');
    Route::delete('/teams/{team}/destroy', 'CompanyTeamsController@destroy')->name('wla.teams.destroy');
    Route::post('api/member/{user}/team/update', 'CompanyTeamsController@updateMemberTeam');
});

Route::group(['domain' => 'www.'.env('APP_DOMAIN')], function() {
    Route::group(['middleware' => 'wlachanel'], function () {
        Route::get('ch/{chanel}/', 'CoursesController@chanel')->name('wla.chanel');
        Route::get('/courses/{course}', 'CoursesController@course')->name('wla.course');
        Route::get('/courses/{course}/lectures/{video}', 'CoursesController@video')->name('wla.video');  
        Route::get('/course/{course}/quizzes/{quiz}', 'QuizzesController@show')->name('wla.quiz');
        Route::post('/course/{course}/quiz/{content}/answers', 'QuizzesController@answers');
        Route::post('/course/{course}/quiz/{content}/process', 'QuizzesController@process');
        // Route::get('/course/{course}/quiz/{quiz}/result', 'QuizzesController@result')->name('wla.course.quiz.result');
        Route::get('/classroom-courses/{course}', 'CoursesController@classroomCourse')->name('wla.classroom-course');    
    });

    Auth::routes();
    Route::get('about-us', 'PagesController@aboutUs')->name('about-us');
    Route::get('where-we-are', 'PagesController@whereWeAre')->name('where-we-are');
    Route::get('contact-us', 'PagesController@contactUs')->name('contact-us');
    Route::get('terms-of-use', 'PagesController@termsOfUse')->name('terms-of-use');
    Route::get('privacy-policy', 'PagesController@privacyPolicy')->name('privacy-policy');
    Route::get('courses', 'CoursesController@courses')->name('courses');

    Route::get('@/{slug}', 'Account\ProfileController@profile')->name('user.profile');

    Route::group(['middleware' => 'guest'], function() {
        Route::get('/', 'PagesController@home')->name('home');

        Route::get('signup', 'Auth\RegisterController@showRegistrationForm')->name('signup');
        Route::post('signup/check-email', 'Auth\RegisterController@checkEmailAddress');
        Route::post('signup', 'Auth\RegisterController@register')->name('signup');            
    });

    Route::group(['middleware' => ['auth', 'registered']],function() {
        Route::get('dashboard', 'PagesController@dashboard')->name('dashboard'); 

        //inbox section
        Route::get('inbox', 'InboxController@inbox')->name('inbox');
        Route::get('inbox/m/{id}', 'InboxController@conversation')->name('inbox.conversation');
        Route::get('inbox/compose/{id?}', 'InboxController@compose')->name('inbox.create');
        Route::post('inbox/compose', 'InboxController@send')->name('inbox.send');
        Route::post('inbox/reply', 'InboxController@reply')->name('inbox.reply');

        //Profile settiongs section
        Route::group(['prefix' => 'settings', 'namespace' => 'Account'], function() {
            Route::get('account', 'ProfileSettingsController@account')->name('user.settings.account');
            Route::post('account', 'ProfileSettingsController@accountUpdate')->name('user.settings.account');
            Route::get('wla-chanel', 'ProfileSettingsController@wlaChanel')->name('user.settings.wla-chanel');
            Route::post('wla-chanel', 'ProfileSettingsController@updateWlaChanel')->name('user.settings.wla-chanel');
        });

        Route::group(['prefix' => 'manager', 'namespace' => 'Manager', 'middleware' => 'wlamanager'], function() {

            Route::get('courses', 'WlaCoursesController@index')->name('manager.courses.index');
            Route::get('courses/create', 'WlaCoursesController@create')->name('manager.courses.create');
            Route::post('courses/create', 'WlaCoursesController@store')->name('manager.courses.store');
            Route::get('courses/{course}/general', 'WlaCoursesController@general')->name('manager.courses.general');
            Route::post('courses/{course}/general', 'WlaCoursesController@updateGeneral')->name('manager.courses.general');
            Route::get('courses/{course}/content', 'WlaCoursesController@content')->name('manager.courses.content');

            Route::get('wla/courses/{course}/preview/create', 'WlaCoursesController@uploadPreview')
                ->name('manager.courses.preview.create');
            Route::post('wla/courses/{course}/preview/store', 'WlaCoursesController@storePreview')
                ->name('manager.courses.preview.store');

            Route::resource('wla/classroom-courses', 'WlaClassroomCoursesController', [
                'except' => 'show', 
                'as' => 'manager'
            ]);

            Route::post('wla/courses/{course}/feature', 'WlaCoursesController@feature');

            Route::resource('courses.sections', 'WlaCourseSectionsController', [
                'only' => ['store', 'update', 'destroy'],
                'as' => 'manager'
            ]);  
            Route::post('courses/{course}/sections/reorder', 'WlaCourseSectionsController@reorder'); 

            Route::delete('courses/{course}/content/{content}', 'WlaContentController@destroy')->name('manager.courses.content.destroy');     
            Route::post('courses/{course}/sections/{section}/content/reorder', 'WlaContentController@reorder'); 

            Route::resource('courses.videos', 'WlaVideosController', [
                'only' => ['store', 'update'], 
                'as' => 'manager'
            ]);
            Route::get('wla/courses/{course}/videos/duplicate', 'WlaVideosController@duplicate')->name('manager.courses.videos.duplicate');
            Route::post('wla/courses/{course}/videos/duplicate/create', 'WlaVideosController@duplicateCreate')->name('manager.courses.videos.duplicate.create');
            Route::post('wla/courses/{course}/videos/duplicate/store', 'WlaVideosController@duplicateStore')->name('manager.courses.videos.duplicate.store');
            Route::get('wla/courses/videos/duplicate/load-cards', 'WlaVideosController@apiLoadDuplicateCards');

            Route::get('courses/{course}/videos/{content}/poster/modal', 'WlaVideosController@posterModalForm')->name('manager.video.poster.modal');
            Route::post('courses/{course}/videos/{content}/poster/modal', 'WlaVideosController@updatePoster');

            Route::put('courses/{course}/videos/{video}/parts', 'WlaVideoPartsController@update')->name('manager.courses.videos.parts.update');        
            
            Route::resource('courses.quizzes', 'WlaQuizzesController', [
                'except' => ['index', 'create', 'edit'],
                'as' => 'manager'
            ]);

            Route::resource('courses.quiz-content.questions', 'WlaQuizQuestionsController', [
                'except' => ['index', 'show'],
                'as' => 'manager'
            ]);

            Route::put('wla/transcript/update', 'WlaCoursesController@updateTranscript')->name('manager.transcript.update');

        });  

        Route::post('api/courses/video/{video}/state/update', 'CoursesController@updateLectureState');
        Route::post('api/courses/video/{video}/part/{part}/state/update', 'CoursesController@updateVideoPartState');

        Route::get('/upload/as3-signed', 'UploadController@as3Signed');

        Route::post('api/comments/store', 'CommentsController@store')->name('comments.store');
        Route::post('api/company/{company}/member/{user}/admin-status/update', 'Admin\AdminCompanyMembersController@updateAdminStatus'); 
        Route::post('api/uploader/content-image/upload', 'UploadController@uploadContentImage');       
    });

    Route::post('api/companies/search', 'CompaniesController@apiTypeaheadSearch');
});

  
Route::get('test', 'TestController@index');
Route::post('test', 'TestController@post');




