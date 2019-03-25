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

Route::get('/', 'PagesController@index')->name('index');
Route::get('/properties', 'PagesController@properties');
Route::get('/contact', 'PagesController@contact');
Route::get('/login', 'PagesController@login');
Route::get('/register', 'PagesController@register');
Route::get('/logout', 'PagesController@logout');
Route::get('/add-property', 'PagesController@addProperty');
Route::get('/viewproperty/{id}', 'PagesController@viewProperty');

Route::post('/registerForm', 'FirebaseController@register')->name('registerForm');
Route::post('/loginForm', 'FirebaseController@login')->name('loginForm');
Route::post('/addPropertyForm', 'FirebaseController@addProperty')->name('addPropertyForm');
Route::post('/updateProperty', 'FirebaseController@updateProperty')->name('updateProperty');

Route::delete('/deleteProperty/{id}', 'FirebaseController@deleteProperty');
