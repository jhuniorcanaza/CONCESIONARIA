<?php

use App\Http\Controllers\CarController;
use App\Http\Controllers\FavoriteCarController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\SocialiteController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::controller(SocialiteController::class)->group(function () {
  Route::middleware(["guest"])->group(function () {
    Route::get('/auth/{provider}', 'redirectToProvider')->name('socialite.redirect');
    Route::get('/auth/{provider}/callback', 'callback')->name('socialite.callback');
  });
});

Route::controller(UserController::class)->group(function () {

  Route::middleware(["guest"])->group(function () {
    Route::get('/register', 'create')->name('register.create');
    Route::post('/register', 'store')->name('register.store');

    Route::get('/login', 'login')->name('login');
    Route::post('/login', 'auth')->name('login.auth')->middleware('throttle:5,1');

    Route::get('forgot-password', 'passwordForgot')
    ->name('password.forgot');

    Route::post('forgot-password', 'passwordEmail')
        ->name('password.email');

    Route::get('reset-password/{token}', 'passwordReset')
        ->name('password.reset');

    Route::post('reset-password', 'passwordStore')
        ->name('password.store');
  });

  Route::middleware(["auth"])->group(function () {
    Route::post('/logout', 'logout')->name('logout');
    Route::get('/profile', 'edit')->name('user.profile');
    Route::put('/user-update', 'update')->name('user.update');
    Route::put('/user-update-password', 'updatePassword')->name('user.update.password');
    Route::delete('/user-delete', 'destroy')->name('user.destroy');    
  });
});

// Route::resource('car', CarController::class);


Route::controller(CarController::class)
  ->prefix('car')
  ->name('car.')
  ->group(function () {

    Route::middleware('auth')->group(function () {

      Route::get('/', 'index')->name('index');
      Route::get('/create', 'create')->name('create');
      Route::post('/', 'store')->name('store');
      Route::get('/{car}/edit', 'edit')->name('edit')->can('update', 'car');
      Route::put('/{car}', 'update')->name('update')->can('update', 'car');
      Route::delete('/{car}', 'destroy')->name('destroy')->can('delete', 'car');

      Route::get('/watchlist', 'watchlist')->name('watchlist');
    });

    Route::get('/search', 'search')->name('search');
    Route::post('/get-phone/{car}', 'getPhone')->name('get-phone');
    Route::get('/{car}', 'show')->name('show');

  });


Route::middleware('auth')->group(function () {

  Route::post('upload', [ImageController::class, 'upload'])->name('image.upload');
  Route::delete('delete', [ImageController::class, 'delete'])->name('image.delete');

  Route::post('/favorite/toggle/{car}', [FavoriteCarController::class, 'toggle'])
    ->name('favorite.toggle');
});