<?php

use IFrankSmith\Blogman\Controllers\PostController;

Route::group(['prefix'=>'api/blag'], function(){
    Route::group(['middleware'=>'auth:api'], function(){
        Route::post('posts', [PostController::class, 'store'])->middleware('blogpermission:add-blog');
        Route::put('posts/{post}', 'PostController@update')->middleware('auth:api');
        Route::delete('posts/{post}', 'PostController@destroy')->middleware('auth:api');

        Route::post('pages', 'PageController@store')->middleware('auth:api');
        Route::put('pages/{page}', 'PageController@update')->middleware('auth:api');
        Route::delete('pages/{page}', 'PageController@destroy')->middleware('auth:api');
    });

    Route::get('posts', 'PostController@index');
    Route::get('posts/{post}', 'PostController@show');
    Route::post('posts/{post}/comment', 'PostController@comment');

    Route::get('pages', 'PageController@index');
    Route::get('pages/{page}', 'PageController@show');


});