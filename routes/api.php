<?php

Route::group(['prefix' => 'v1', 'namespace' => '\API'], function () {
    Route::get('account/confirmation/{token}', 'Auth\ConfirmationController@handle')->name('confirmation-account');
    Route::resource('categories', 'CategoryController', ['only' => ['index','show']]);
    Route::get('courses', 'CourseController@index')->name('courses.index');
    Route::post('login', 'Auth\LoginController@handle')->name('login');
    Route::post('password/email', 'Auth\ForgotPasswordController@handle')->name('password.email');
    Route::post('password/reset', 'Auth\ResetPasswordController@handle')->name('password.reset');
    Route::post('feedback', 'FeedbackController@store')->name('feedback.store');
    Route::get('users/email/{token}', 'Auth\ConfirmationController@show')->name('users.email');

    Route::group(['middleware' => ['auth:api', 'confirm']], function () {
        Route::resource('events', 'EventController', ['only' => ['index', 'show', 'destroy']]);
        Route::get('events-type', 'TypeEventController@index')->name('eventstype.index');
        Route::resource('users', 'UsersController', ['only' => ['index','show', 'store', 'update', 'destroy']]);

        Route::group(['prefix'=>'users', 'as'=>'users.'], function () {
            Route::get('{user}/courses', 'CourseUserController@courses')->name('users.courses');
            Route::post('{user}/avatar', 'UsersController@updateAvatar')->name('avatar.update');
            Route::get('{user}/courses/{course}', 'CourseUserController@show')->name('courses.show');
        });

        Route::resource('feedback', 'FeedbackController', ['only' => ['show', 'destroy', 'index']]);
        Route::resource('categories', 'CategoryController', ['only' => ['store', 'update', 'destroy']]);
        Route::resource('courses', 'CourseController', ['except' => ['index']]);

        Route::group(['prefix'=>'courses', 'as'=>'courses.'], function () {
            Route::get('{course}/users', 'CourseUserController@users')->name('accept.users');
            Route::post('{course}/image', 'ImageController@store')->name('store.image');
            Route::delete('{course}/image', 'ImageController@destroy')->name('destroy.image');
            Route::post('{course}/image/update', 'ImageController@update')->name('update.image');
            Route::post('{course}/file', 'FileCourseController@store')->name('store.file');
            Route::get('{course}/file', 'FileCourseController@show')->name('show.file');
            Route::post('{course}/files/{file}', 'FileCourseController@update')->name('update.file');
            Route::delete('{course}/file', 'FileCourseController@destroy')->name('destroy.file');
            Route::get('{course}/sertificates', 'SertificateController@show')->name('sertificate.show');
            Route::post('{course}/finish', 'CourseStatusController@store')->name('course.finish');
            Route::delete('{course}/users', 'CourseUserController@destroy')->name('course.users.delete');
        });
        Route::get('sertificates', 'SertificateController@index')->name('sertificate.index');

        Route::post('courses/{course}/accept', 'CourseUserController@store')->name('courses.accept');
        Route::post('courses/{course}/success', 'CourseUserController@success')->name('courses.success');
        Route::resource('tests', 'TestController', ['only' => ['index','show','store', 'update', 'destroy']]);
        Route::group(['prefix'=>'tests', 'as'=>'tests.'], function () {
            Route::post('{test}/users/{user}', 'TestUserController@store')->name('store.test');
            Route::get('{test}/users/{user}', 'TestUserController@time')->name('time.test');
            Route::delete('{test}/users/{user}', 'TestUserController@destroy')->name('destroy.test');
            Route::get('{test}/show', 'TestUserController@show')->name('show.test.all');
            Route::post('{test}/users/{user}/result', 'TestUserController@testSuccess')->name('result');
            Route::post('{test}/checkup', 'TestUserController@checkup')->name('checkup.test');
        });

        Route::resource('questions', 'QuestionController', ['only' => ['index','show','store', 'update']]);
        Route::delete('questions', 'QuestionController@destroy')->name('questions.destroy');
        Route::resource('answers', 'AnswerController', ['only' => ['index','show','store', 'update']]);
        Route::delete('answers', 'AnswerController@destroy')->name('answers.destroy');
        Route::resource('users', 'UsersController', ['only' => ['index','show', 'store', 'update', 'destroy']]);
        Route::resource('modules', 'ModuleController', ['only' => ['index','show', 'store', 'update', 'destroy']]);
        Route::resource('lessons', 'LessonController', ['only' => ['index','show', 'store', 'update', 'destroy']]);
        Route::resource('targets', 'TargetAudienceController', ['only' => ['index','show', 'store', 'destroy']]);

        Route::group(['prefix'=>'lessons', 'as'=>'lessons.'], function () {
            Route::post('{lesson}/file', 'FileLessonController@store')->name('store.file');
            Route::get('{lesson}/file', 'FileLessonController@show')->name('show.file');
            Route::delete('{lesson}/file', 'FileLessonController@destroy')->name('destroy.file');
            Route::post('{lesson}/file/update', 'FileLessonController@update')->name('update.file');
            Route::get('{lesson}/files/{file}/download', 'FileLessonController@download')->name('download.file');
            Route::post('{lesson}/complete', 'LessonUserController@store')->name('complete.lesson');
        });
        Route::resource('type_lessons', 'TypeLessonController', ['only' => ['index', 'store']]);
        Route::put('profile/update', 'ProfileController@update')->name('profile.update');
        Route::get('profile', 'ProfileController@show')->name('profile.show');

        Route::group(['prefix'=>'categories', 'as'=>'categories.'], function () {
            Route::post('{category}/image', 'ImageCategoryController@store')->name('store.image');
            Route::delete('{category}/image', 'ImageCategoryController@destroy')->name('destroy.image');
        });
    });
});
