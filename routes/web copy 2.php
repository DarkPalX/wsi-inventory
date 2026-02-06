<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\LoginController;

use App\Http\Controllers\{DashboardController, FrontController};
use App\Http\Controllers\Custom\{ItemController, CategoryController, AuthorController, PublisherController};

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


Auth::routes();

Route::get('/', [FrontController::class, 'home'])->name('home');
Route::get('/privacy-policy/', [FrontController::class, 'privacy_policy'])->name('privacy-policy');
Route::post('/contact-us', [FrontController::class, 'contact_us'])->name('contact-us');
Route::get('/search', [FrontController::class, 'search'])->name('search');

Route::get('/search-result',[FrontController::class, 'seach_result'])->name('search.result');

// BOOK MANAGENMENT

Route::group(['prefix' => 'books', 'as' => 'books.'], function () {
    // BOOKS
    Route::resource('/', ItemController::class);

    // CATEGORIES
    Route::resource('categories', CategoryController::class);

    // AUTHORS
    Route::resource('authors', AuthorController::class);
    Route::post('authors/single-delete', [AuthorController::class, 'single_delete'])->name('books.authors.single.delete');

    // PUBLISHERS
    Route::resource('publishers', PublisherController::class);
});
