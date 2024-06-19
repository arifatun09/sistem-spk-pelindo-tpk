<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AlatController;
use App\Http\Controllers\AlatMasterController;
use App\Http\Controllers\KriteriaController;
use App\Http\Controllers\BobotController;
use App\Http\Controllers\HasilController;
use App\Http\Controllers\HasilExportController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SettingController;

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

Auth::routes();

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::middleware(['auth'])->prefix('setting')->group(function () {
    Route::get('/', [SettingController::class, 'resetPassword'])->name('resetPassword');
    Route::post('/save-password', [SettingController::class, 'savePassword'])->name('savePassword');
});

Route::name('Alat-Master::')->prefix('alat-master')->group(function () {
    Route::resource('/', AlatMasterController::class)
        ->only('index', 'create', 'store', 'edit', 'update', 'destroy')
        ->parameter('', 'alat');
});

Route::name('Alat::')->prefix('alat')->group(function () {
    Route::get('/',  [AlatController::class, 'index'])->name('index');
    Route::post('/import',  [AlatController::class, 'import'])->name('import');
});

Route::name('Kriteria::')->prefix('kriteria')->group(function () {
    Route::get('/',  [KriteriaController::class, 'index'])->name('index');
    Route::get('/create', [KriteriaController::class, 'create'])->name('create');
    Route::post('/store', [KriteriaController::class, 'store'])->name('store');
    Route::get('/edit/{id}', [KriteriaController::class, 'edit'])->name('edit');
    Route::post('/update/{id}', [KriteriaController::class, 'update'])->name('update');
    Route::delete('/destroy/{id}', [KriteriaController::class, 'destroy'])->name('destroy');
});

Route::name('Bobot::')->prefix('bobot')->group(function () {
    Route::get('/',  [BobotController::class, 'index'])->name('index');
    Route::get('/create', [BobotController::class, 'create'])->name('create');
    Route::get('/edit/{id}', [BobotController::class, 'edit'])->name('edit');
    Route::put('/update/{id}', [BobotController::class, 'update'])->name('update');
    Route::resource('/', BobotController::class)
        ->only('store', 'destroy')
        ->parameter('', 'bobot');
});

Route::name('Hasil::')->prefix('hasil')->group(function () {
    Route::resource('/', HasilController::class)
        ->only('index', 'store')
        ->parameter('', 'hasil');
    Route::post('/export', HasilExportController::class)->name('export');
});

Route::name('User::')->prefix('user')->group(function () {
    Route::get('/',  [UserController::class, 'index'])->name('index');
    Route::get('/create', [UserController::class, 'create'])->name('create');
    Route::post('/store', [UserController::class, 'store'])->name('store');
    Route::get('/edit/{id}', [UserController::class, 'edit'])->name('edit');
    Route::post('/update/{id}', [UserController::class, 'update'])->name('update');
    Route::delete('/destroy/{id}', [UserController::class, 'destroy'])->name('destroy');
    Route::get('/reset-password/{id}', [UserController::class, 'resetPassword'])->name('resetPassword');
    Route::post('/save-password/{id}', [UserController::class, 'savePassword'])->name('savePassword');
});
