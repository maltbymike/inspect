<?php

use App\Livewire\Items\Item;
use App\Livewire\Items\Category;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
    Route::get('/category/{category?}', Category::class)->name('item_categories');
    Route::get('/items', Item::class)->name('items');
});
