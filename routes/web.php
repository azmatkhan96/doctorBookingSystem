<?php

use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

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
    return view('auth.login');
});

Auth::routes();

Route::controller(HomeController::class)->middleware('auth')->group(function () {
    Route::get('/home', 'index')->name('home'); // home page
    Route::post('/appointments', 'filterData')->name('appointments.index'); // THIS FOR FILTER THE APPOINTMENT
    Route::post('/make-appointment', 'store')->name('make.appointment'); // CREATE THE APPOINTMENT
    Route::post('/status-update', 'updateStatus')->name('statusUpdate'); // CHANGE STATUS
});
