<?php

use IFrankSmith\Blogman\Controllers\PostController;

Route::group(['prefix'=>'api/blog'], function(){
    Route::group(['middleware'=>'auth:api'], function(){
        Route::post('posts', [PostController::class, 'store'])->middleware('blogpermission:add-blog');
        Route::put('posts/{post}', 'PostController@update')->middleware('blogpermission:update-blog');
        Route::delete('posts/{post}', 'PostController@destroy')->middleware('blogpermission:delete-blog');

        Route::post('pages', 'PageController@store')->middleware('blogpermission:add-blog');
        Route::put('pages/{page}', 'PageController@update')->middleware('blogpermission:update-blog');
        Route::delete('pages/{page}', 'PageController@destroy')->middleware('blogpermission:delete-blog');
    });

    Route::get('posts', 'PostController@index');
    Route::get('posts/{post}', 'PostController@show');
    Route::post('posts/{post}/comment', 'PostController@comment');

    Route::get('pages', 'PageController@index');
    Route::get('pages/{page}', 'PageController@show');

});