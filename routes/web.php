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

// Route::get('/', function () {
//     return view('welcome');
// });


Route::get('/', 'mainController@index');
Route::get('/gorgian', 'mainController@gorgianCalender');
Route::get('/testhijri2', 'testController@index2');


// taqwim-hijri/date/24/mo7aram/1443
// taqwim-hijri/date/month/mo7aram/1443

Route::get('/{calender?}/date/{day?}/{mon?}/{year?}', 'mainController@single');

// date/2/jan/1443
Route::get('/date/{day?}/{mon?}/{year?}', 'testController@index4');
Auth::routes();


Route::get('changelocale',  'mainController@changeLocale');


Route::get('/home', 'HomeController@index')->name('home');
