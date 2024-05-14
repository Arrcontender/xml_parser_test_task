<?php

use App\Http\Controllers\FileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [FileController::class, 'index'])->name('index');
Route::get('/get-file', [FileController::class, 'getFile']);
Route::get('/file', [FileController::class, 'showFile']);
Route::post('/upload', [FileController::class, 'upload'])->name('upload');
