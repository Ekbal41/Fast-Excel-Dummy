<?php

use App\Http\Controllers\ProfileController;
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

Route::get('/','App\Http\Controllers\Controller@welcome')->name('welcome');
Route::get('/exportusers','App\Http\Controllers\Controller@exportusers')->name('exportusers');
Route::post('/import','App\Http\Controllers\Controller@import')->name('import');
Route::get('/export_with_password','App\Http\Controllers\Controller@export_with_password')->name('pass');
Route::post('/import_data_with_pass','App\Http\Controllers\Controller@import_data_with_pass')->name('importpass');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
