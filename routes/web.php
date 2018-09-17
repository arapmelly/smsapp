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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('outboxes/bulk', 'OutboxController@sendBulk');

Route::get('bulks/send-bulk', 'BulkController@sendBulk');


Route::get('admindash/{component}', function ($component) {

    return view('dashboard', compact('component'));
    
})->name('admindash');
