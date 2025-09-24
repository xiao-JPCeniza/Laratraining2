<?php

use App\Http\Controllers\AssetController;
use App\Http\Controllers\ManufacturerController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\CategoryController;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');



Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/about', function () {
    return '<h1>About Us </h1><p> We are a company that does cool stuff.<p>';
    });

Route::get('/users/{id}', function ( string $id ) {
    return 'User ID:($id)'  ;
});

Route::get('/products/{category?}/{name}', function ( ?string $category = null,?string $name ) {
    if ( $category) {
        return "Product: {$name} in Category: ($category)";
    }
        return "Product:{$name}";
    }) ;

    Route::prefix("admin")->group(function () {
        Route::get('/dashboard', function(){
        return'Admin Dashboard';
        });
        Route::get('/users', function () {
            return 'Manage Admin USers';
        });

    });

Route::get('categories',[CategoryController::class,'index']) ->name('categories');



Route::get('/location', function () {
 return Inertia::render('Category/Locations');
})->middleware(['auth', 'verified', 'role:inventory_manager'])->name('locations');

Route::get('/manufacturer', function () {
 return Inertia::render('Category/Manufacturer');
})->middleware(['auth', 'verified','role:inventory_manager'])->name('manufacturers');

Route::get('/assets', function () {
 return Inertia::render('Category/Assets');
})->middleware(['auth', 'verified', 'role:inventory_user'])->name('assets');

// Route::get('/manufacturer', [ManufacturerController::class, 'index'])->middleware(['auth', 'verified'])->name('manufacturers');


// Route::get(uri: '/asset', [AssetController::class,'index'])->middleware(['auth', 'verified'])->name('assets');

// Route::get('/asset', [AssetController::class,'index'])->middleware(['auth', 'verified'])->name('assets');


require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
