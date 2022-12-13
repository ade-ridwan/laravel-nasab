<?php

use App\Http\Controllers\ChildController;
use App\Http\Controllers\CoupleController;
use App\Http\Controllers\NasabController;
use App\Http\Controllers\TreeController;
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


Route::get('/', [TreeController::class, 'getChilds']);


Route::resource('/child', ChildController::class);
Route::resource('/couple', CoupleController::class);
