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

Route::get('/', 'ScheduleController@index');

Route::post('/appointments', 'AppointmentController@store')->name('appointments.store');
Route::put('/appointments/{appointment}', 'AppointmentController@update')->name('appointments.update');
Route::delete('/appointments/{appointment}', 'AppointmentController@destroy')->name('appointments.destroy');
