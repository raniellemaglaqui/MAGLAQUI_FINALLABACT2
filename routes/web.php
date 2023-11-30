<?php

use Illuminate\Support\Facades\Route;
use App\Models\User;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\BrandController;


Route::get('/', function () {
    return view('welcome');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {     
    Route::get('/dashboard', function () {
        $users = User::all();
        return view('dashboard', compact ('users'));
    })->name('dashboard');
});

Route::get('/categories', [CategoryController::class, 'index'])->name('AllCat');
Route::post('/categories', [CategoryController::class, 'store'])->name('AllCat'); 
Route::get('/category/update/{id}', [CategoryController::class, 'edit'])->name('editCategory');
Route::post('/category/update/{id}', [CategoryController::class, 'update']);

Route::get('/category/remove/{id}', [CategoryController::class, 'remove'])->name('removeCategory');

Route::get('/category/restore/{id}', [CategoryController::class, 'restore'])->name('restoreCategory');
Route::get('/category/delete/{id}', [CategoryController::class, 'delete'])->name('deleteCategory');

// Brand
Route::get('/brand/all', [BrandController::class, 'AllBrand'])->name('brand'); 
Route::post('/brand/add', [BrandController::class, 'AddBrand'])->name('add.brand');

Route::get('/brand/edit/{id}', [BrandController::class, 'edit'])->name('editBrand');
Route::post('/brand/update/{id}', [BrandController::class, 'update'])->name('updateBrand');

Route::get('/brand/delete/{id}', [BrandController::class, 'delete'])->name('deleteBrand');