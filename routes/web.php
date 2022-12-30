<?php

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
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/post/create', [App\Http\Controllers\HomeController::class, 'create']);
Route::post('/post/create', [App\Http\Controllers\HomeController::class, 'store']);
Route::get('/post/{id}/edit', [App\Http\Controllers\HomeController::class, 'edit']);
Route::put('/post/{id}/edit', [App\Http\Controllers\HomeController::class, 'update']);
Route::delete('/post/{id}', [App\Http\Controllers\api\v1\PostController::class, 'destroy']);
