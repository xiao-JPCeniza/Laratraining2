<?php

use App\Http\Controllers\AssetController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\ManufacturerController;

use Illuminate\Support\Facades\Route;

use Inertia\Inertia;




Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');


Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('dashboard/stats', [DashboardController::class, 'stats'])->name('dashboard.stats');
});

Route::middleware(['auth', 'verified', 'role:super_admin'])->group(function () {
    Route::post('categories/list', [CategoryController::class,'list'])->name('categories.list');
    Route::resource('categories', CategoryController::class)->except(['create', 'edit']);
});

Route::middleware(['auth', 'verified', 'role:super_admin,inventory_manager'])->group(function () {
    Route::resource('manufacturers', ManufacturerController::class)->except(['create', 'edit']);
     Route::post('manufacturers/list', [ManufacturerController::class,'list'])->name('manufacturers.list');
    Route::resource('locations', LocationController::class)->except(['create', 'edit']);
     Route::post('locations/list', [LocationController::class,'list'])->name('location.list');
});

Route::middleware(['auth', 'verified', 'role:super_admin,inventory_user'])->group(function () {
    Route::resource('assets', AssetController::class)->except(['create', 'edit']);
     Route::post('assets/list', [AssetController::class,'list'])->name('assets.list');
});

// Route::resource('categories', CategoryController::class)->except(['create', 'edit']);
// Route::resource('manufacturers', ManufacturerController::class)->except(['create', 'edit']);
// Route::resource('locations', LocationController::class)->except(['create', 'edit']);
// Route::resource('assets', AssetController::class)->except(['create', 'edit']);

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';

// Route::get('/', function () {
//     return Inertia::render('Welcome');
// })->name('home');

// // Route::get('dashboard', function () {
// //     return Inertia::render('Dashboard');
// // })->middleware(['auth', 'verified'])->name('dashboard');

// Route::get('/about', function () {
//     return '<h1>About Us </h1><p> We are a company that does cool stuff.<p>';
// });

// // Route::get('/users/{id}', function (string $id) {
// //     return 'User ID:($id)';
// // });

// // Route::get('/products/{category?}/{name}', function (?string $category, ?string $name) {
// //     if ($category) {
// //         return "Product: {$name} in Category: ($category)";
// //     }

// //     return "Product:{$name}";
// // });

// // Route::prefix('admin')->group(function () {
// //     Route::get('/dashboard', function () {
// //         return 'Admin Dashboard';
// //     });
// //     Route::get('/users', function () {
// //         return 'Manage Admin USers';
// //     });

// // });
// // Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

// Route::middleware(['auth', 'verified'])->group(function () {
//     // Route::get('categories', [CategoryController::class, 'index'])->name('categories.index');
//     Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
// });

// Route::middleware('role:super_admin')->group(function () {
//     // Route::get('categories', [CategoryController::class, 'index'])->name('categories.index');
//     // Route::resource('categories', CategoryController::class)->except(['create', 'edit']);

//     Route::resource('manufacturers', ManufacturerController::class)->except(['create', 'edit']);
//     Route::resource('locations', LocationController::class)->except(['create', 'edit']);
//     Route::resource('assets', AssetController::class)->except(['create', 'edit']);
//     Route::resource('categories', CategoryController::class)->except(['create', 'edit']);
// });

// Route::middleware('role:super_admin,inventory_manager')->group(function () {
//     // Route::get('manufacturers', [ManufacturerController::class, 'index'])->name('manufacturers');
//     // Route::get('locations', [LocationController::class, 'index'])->name('locations');
//     // Route::resource('manufacturers', ManufacturerController::class)->except(['create', 'edit']);
//     // Route::resource('locations', LocationController::class)->except(['create', 'edit']);
// });
// // edit here for location
// Route::middleware('role:super_admin,inventory_user')->group(function () {
//     // Route::get('assets', [AssetController::class, 'index'])->name('assets');
//     // Route::resource('assets', AssetController::class)->except(['create', 'edit']);
// });


// require __DIR__.'/settings.php';
// require __DIR__.'/auth.php';
