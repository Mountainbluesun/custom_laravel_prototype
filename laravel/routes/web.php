<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\StockMovementController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {

    // Profile (Breeze)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // --- App stock ---
    Route::resource('products', ProductController::class);

    // Global history + export (policy)
    Route::get('/movements', [StockMovementController::class, 'global'])
        ->middleware('can:viewGlobal,App\Models\StockMovement')
        ->name('movements.global');

    Route::get('/movements/export', [StockMovementController::class, 'export'])
        ->middleware('can:export,App\Models\StockMovement')
        ->name('movements.export');

    // Per-product history + create/store
    Route::get('products/{product}/movements', [StockMovementController::class, 'index'])
        ->name('movements.index');

    Route::get('products/{product}/movements/create', [StockMovementController::class, 'create'])
        ->name('movements.create');

    Route::post('products/{product}/movements', [StockMovementController::class, 'store'])
        ->name('movements.store');
});

require __DIR__.'/auth.php';
