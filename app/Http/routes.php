<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return view('welcome');
});

//notes
Route::post('/notes/create', 'NoteController@create');
Route::get('/notes/get', 'NoteController@get');
Route::get('/notes/getAll', 'NoteController@getAll');
Route::post('/notes/edit', 'NoteController@edit');
Route::post('/notes/delte', 'NoteController@delete');