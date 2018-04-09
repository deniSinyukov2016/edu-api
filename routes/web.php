<?php

Route::get('/', 'PageController@index')->name('site.page.index');
Route::get('categories', 'PageController@showCategories')->name('site.categories.show');
Route::get('sub-category/{category}', 'PageController@showCourses')->name('site.courses.index');
Route::get('course/{course}/', 'PageController@showCourse')->name('site.course.show');
Route::get('about', 'PageController@about')->name('site.about.show');
Route::get('contacts', 'PageController@contact')->name('site.contact.show');