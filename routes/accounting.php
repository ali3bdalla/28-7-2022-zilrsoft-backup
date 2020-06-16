<?php
use Illuminate\Support\Facades\Route;


Route::middleware(['auth', 'verified'])->get('/dashboard', 'HomeController@index')->name('dashboard.index');
Route::middleware(['guest'])->group(base_path('routes/auth.php'));
Route::middleware(['auth', 'verified'])->group(base_path('routes/items.php'));
Route::middleware(['auth', 'verified'])->group(base_path('routes/categories.php'));
Route::middleware(['auth', 'verified'])->group(base_path('routes/organizations.php'));
Route::middleware(['auth', 'verified'])->group(base_path('routes/users.php'));
Route::middleware(['auth', 'verified'])->group(base_path('routes/orders.php'));
Route::middleware(['auth', 'verified'])->group(base_path('routes/vouchers.php'));
Route::middleware(['auth', 'verified'])->group(base_path('routes/transactions.php'));
Route::middleware(['auth', 'verified'])->group(base_path('routes/datatables.php'));
Route::middleware([])->group(base_path('routes/exports.php'));
