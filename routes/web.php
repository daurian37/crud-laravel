<?php

use App\Http\Controllers\IngredientController;
use App\Http\Controllers\RecipeController;
use App\Models\Ingredient;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


Route::match(['get','post'],'/', [RecipeController::class, 'index']);
Route::match(['get','post'],'/store', [RecipeController::class, 'store'])->name('store');
Route::match(['get','post'],'/edit', [RecipeController::class, 'edit'])->name('edit');
Route::match(['get','post'],'/update', [RecipeController::class, 'update'])->name('update');
Route::delete('/delete', [RecipeController::class, 'delete'])->name('delete');
Route::match(['get','post'],'/fetchall', [RecipeController::class, 'fetchAll'])->name('fetchAll');
Route::match(['get','post'],'ingredient', [RecipeController::class, 'getIngredient'])->name('get-ingredient');
