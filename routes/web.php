<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\SaleController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/home', function () {
    $hw = "Tela Inicial";
    return view('home', ['hw' => $hw]);
});

Route::resource('inventories', InventoryController::class);

// Coloque a rota de pesquisa antes da rota de recurso products
Route::get('/products/search', [ProductController::class, 'search'])->name('products.search');
Route::resource('products', ProductController::class);

Route::get('/sales/create', [\App\Http\Controllers\SaleController::class, 'create'])->name('sales.create');
Route::post('/sales', [SaleController::class, 'store'])->name('sales.store');
Route::get('/sales', [SaleController::class, 'index'])->name('sales.index');
Route::get('/sales/{id}', [SaleController::class, 'show'])->name('sales.show');

Route::get('/sales/test', [SaleController::class, 'test'])->name('sales.test');


Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});

Auth::routes();
