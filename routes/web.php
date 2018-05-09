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

Route::get('/', 'IndexController@index')->name('index');

Route::get('/dashboard', 'DashboardController@index')->name('dashboard');

Route::get('/tasks', 'TaskController@index')->name('tasks');
Route::post('/tasks/store', 'TaskController@store')->name('store-task');
Route::put('/tasks/delete/{task}', 'TaskController@destroy')->name('delete-task');
