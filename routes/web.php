<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes();

//Route::get('/', 'HomeController@index');
//Route::get('/home', 'HomeController@index');
Route::get('/', 'PleskController@homeView');
Route::get('/home', 'PleskController@homeView');

Route::group(['prefix' => '/plesk'], function() {
    Route::get('listusers', [
        'as' => 'plesk.listusers',
        'uses' => 'PleskController@listUsers'
    ]);

    Route::get('adduser', [
        'as' => 'plesk.adduser',
        'uses' => 'PleskController@addUserForm'
    ]);
    Route::post('adduser', 'PleskController@addUser');

    Route::get('enablespamfilter', 'PleskController@spamFilter');
    Route::get('enableantivirus', 'PleskController@antivirus');

});

Route::get('default', function () {
    return view('welcome');
});

