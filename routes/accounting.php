<?php
use Illuminate\Support\Facades\Route;


Route::middleware('lang:ar')->group(function(){
    Route::middleware(['auth', 'verified'])->group(base_path('routes/items.php'));
    Route::middleware(['auth', 'verified'])->group(base_path('routes/categories.php'));
    Route::middleware(['auth', 'verified'])->group(base_path('routes/organizations.php'));
    Route::middleware(['auth', 'verified'])->group(base_path('routes/users.php'));
    Route::middleware(['auth', 'verified'])->group(base_path('routes/transactions.php'));
    Route::middleware([])->group(base_path('routes/exports.php'));
});
