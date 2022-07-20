<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

use App\Http\Controllers\AdminPanel\Product\ProductCategoryController;
use App\Http\Controllers\AdminPanel\Product\ProductController;
use App\Http\Controllers\AdminPanel\BannerController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');

    Route::get('users', [App\Http\Controllers\UserController::class, 'getAllUsers'])->name('users');

    Route::get('/manageProductCategory', [ProductCategoryController::class, 'getProductCategory'])->name('manageProductCategory');
    Route::get('/addProductCategory', function () {
        return Inertia::render('Product/AddProductCategory');
    })->name('addProductCategory');
    Route::post('/addProductCategory', [ProductCategoryController::class, 'addProductCategory'])->name('addProductCategory.add');
    Route::delete('/deleteProductCategory/{productCategoryID}', [ProductCategoryController::class, 'deleteProductCategory'])->name('deleteProductCategory');
    Route::post('/updateProductCategory', [ProductCategoryController::class, 'updateProductCategory'])->name('updateProductCategory');

    Route::get('/manageProduct', [ProductController::class, 'getProducts'])->name('manageProduct');
    Route::get('/addProduct', [ProductController::class, 'getAddProductPageData'])->name('addProduct');
    Route::post('/addProduct', [ProductController::class, 'addProduct'])->name('addProduct.add');
    Route::delete('/deleteProduct/{productID}', [ProductController::class, 'deleteProduct'])->name('deleteProduct');
    Route::get('/editProduct/{productID}', [ProductController::class, 'getEditProductPageData'])->name('editProduct');
    Route::post('/editProduct/{productID}', [ProductController::class, 'editProduct'])->name('editProduct.edit');

    Route::get('/manageBanner', [BannerController::class, 'getBanners'])->name('manageBanner');
    Route::get('/addBanner', function () {
        return Inertia::render('Banner/AddBanner');
    })->name('addBanner');
    Route::post('/addBanner', [BannerController::class, 'uploadBanner'])->name('addBanner.add');
    Route::post('/updateBannerPosition', [BannerController::class, 'updateBannerPosition'])->name('updateBannerPosition');

});
