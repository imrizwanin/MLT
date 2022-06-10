<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Author\AuthorController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Reader\ReaderController;
use App\Http\Controllers\User\UserController;
use Illuminate\Support\Facades\Auth;

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

//Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::prefix('user')->name('user.')->group(function () {
    Route::middleware(['guest:web', 'PreventBackHistory'])->group(function () {
        Route::view('/login', 'dashboard.user.login')->name('login');
        Route::view('/register', 'dashboard.user.register')->name('register');
        Route::post('/create', [UserController::class, 'create'])->name('create');
        Route::post('/check', [UserController::class, 'check'])->name('check');
    });
    Route::middleware(['auth:web', 'PreventBackHistory'])->group(function () {
        Route::view('/home', 'dashboard.user.home')->name('home');
        Route::post('/logout', [UserController::class, 'logout'])->name('logout');
        Route::get('/add-new', [UserController::class, 'add'])->name('add');
    });
});

Route::prefix('author')->name('author.')->group(function () {
    Route::middleware(['guest:author', 'PreventBackHistory'])->group(function () {
        Route::view('/login', 'dashboard.author.login')->name('login');
        Route::view('/register', 'dashboard.author.register')->name('register');
        Route::post('/create', [AuthorController::class, 'create'])->name('create');
        Route::post('/check', [AuthorController::class, 'check'])->name('check');
    });
    Route::middleware(['auth:author', 'PreventBackHistory'])->group(function () {
        Route::view('/home', 'dashboard.author.home')->name('home');
        Route::post('/logout', [AuthorController::class, 'logout'])->name('logout');
        Route::get('/add-new', [AuthorController::class, 'add'])->name('add');
    });
});

Route::prefix('admin')->name('admin.')->group(function () {
    Route::middleware(['guest:admin', 'PreventBackHistory'])->group(function () {
        Route::view('/login', 'dashboard.admin.login')->name('login');
        Route::post('/check', [AdminController::class, 'check'])->name('check');
    });
    Route::middleware(['auth:admin', 'PreventBackHistory'])->group(function () {
        Route::view('/home', 'dashboard.admin.home')->name('home');
        Route::post('/logout', [AdminController::class, 'logout'])->name('logout');
    });
});

Route::prefix('reader')->name('reader.')->group(function () {
    Route::middleware(['guest:reader', 'PreventBackHistory'])->group(function () {
        Route::view('/login', 'dashboard.reader.login')->name('login');
        Route::view('/register', 'dashboard.reader.register')->name('register');
        Route::post('/create', [ReaderController::class, 'create'])->name('create');
        Route::post('/check', [ReaderController::class, 'check'])->name('check');
    });
    Route::middleware(['auth:reader', 'PreventBackHistory'])->group(function () {
        Route::view('/home', 'dashboard.reader.home')->name('home');
        Route::post('logout', [ReaderController::class, 'logout'])->name('logout');
    });
});
