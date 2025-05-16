<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PlaceController;
use App\Http\Controllers\SearchController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PlaceController::class, 'index'])->name('home');
Route::get('/category/{category:slug}',[CategoryController::class,'show'])->name('category.show');
Route::get('/place/{place:slug}',[PlaceController::class,'show'])->name('place.show');
Route::post('/place/{place:slug}',[PlaceController::class,'store'])->name('place.store');
Route::post('/search',SearchController::class)->name('search');
Route::get('/bookmarks',[PlaceController::class,'bookmarks'])->name('bookmarks')->middleware('auth');
Route::get('/top-rated',[PlaceController::class,'topRated'])->name('top-rated');
Route::get('/top-views',[PlaceController::class,'topViews'])->name('top-views');


Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});
