<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CarsController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\BookingController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

/*
|--------------------------------------------------------------------------
| Cars Routes
|--------------------------------------------------------------------------
|
*/

Route::controller(CarsController::class)->group(function (){
    Route::get('/cars', 'index');
    Route::post('/store-car', 'store');
    Route::post('/update-car', 'update');
    Route::get('/delete-car/{id}', 'delete');
})

/*
|--------------------------------------------------------------------------
| Users Routes
|--------------------------------------------------------------------------
|
*/;

Route::controller(UsersController::class)->group(function () {
    Route::get('/users', 'index');
    Route::post('/store-user', 'store');
    Route::post('/update-user', 'update');
    Route::get('/delete-user/{id}', 'delete');
});

/*
|--------------------------------------------------------------------------
| Booking Routes
|--------------------------------------------------------------------------
|
*/

Route::controller(BookingController::class)->group(function (){
    Route::get('/bookings', 'index');
    Route::post('/store-booking', 'store');
    Route::post('/update-booking', 'update');
    Route::get('/delete-booking/{id}', 'delete');
});