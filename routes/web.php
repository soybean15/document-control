<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\PushDataController;
use App\Http\Controllers\QrCodeController;
use App\Http\Controllers\UserController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;


use Illuminate\Support\Facades\Storage;
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

    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});
;



Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', [DashboardController::class,'index'])->name('dashboard');

    Route::prefix('document')->group(function () {


        Route::get('/', [DocumentController::class, 'index'])->name('document');
        Route::post('/type', [DocumentController::class, 'storeType'])->name('documentType.create');
        Route::post('/upload', [DocumentController::class, 'upload'])->name('upload');
        Route::get('/show/{id}',[DocumentController::class,'showDocument'])->name('display');

    });
    Route::prefix('user')->group(function () {

        Route::post('/create', [UserController::class, 'store'])->name('user.create');
        Route::post('/update',[UserController::class,'update'])->name('user.update');
        Route::delete('destroy/{id}',[UserController::class,'destroy'])->name('user.destroy');
    });


    Route::get('/push',[PushDataController::class, 'pushToClient'])->name('push');


});
Route::get('/qr', [QrCodeController::class, 'show']);

