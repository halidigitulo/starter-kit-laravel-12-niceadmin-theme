<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\Rules\Can;
use Spatie\Permission\Contracts\Role;

Route::middleware(['guest'])->group(function () {
    Route::get('/', [AuthController::class, 'login'])->name('login');
    Route::post('/', [AuthController::class, 'auth_login'])->name('auth_login');
});

Route::middleware(['auth:admin'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::group(['prefix' => 'menus/'], function () {
        Route::get('',[MenuController::class,'index'])->name('menus.index');
        Route::post('',[MenuController::class,'store'])->name('menus.store');
        Route::get('{menu}',[MenuController::class,'show'])->name('menus.show');
        Route::put('{menu}',[MenuController::class,'update'])->name('menus.update');
        Route::delete('{menu}',[MenuController::class,'destroy'])->name('menus.destroy');
        Route::get('order', [MenuController::class, 'order'])->name('menus.order');
        Route::post('reorder', [MenuController::class, 'reorder'])->name('menus.reorder');
    });

    Route::group(['prefix' => 'roles/'], function () {
        Route::get('',[RoleController::class,'index'])->name('roles.index')->middleware('permission:roles.read');
        Route::post('',[RoleController::class,'store'])->name('roles.store')->middleware('permission:roles.create');
        Route::get('{role}',[RoleController::class,'show'])->name('roles.show')->middleware('permission:roles.read');
        Route::put('{role}',[RoleController::class,'update'])->name('roles.update')->middleware('permission:roles.update');
        Route::delete('{role}',[RoleController::class,'destroy'])->name('roles.destroy')->middleware('permission:roles.delete');
    });

    Route::group(['prefix' => 'permission/'], function () {
        Route::get('', [PermissionController::class, 'index'])->name('permissions.index');
        Route::post('generator', [PermissionController::class, 'generate'])->name('permissions.generate');
        Route::put('{permission}', [PermissionController::class, 'update'])->name('permissions.update');
        Route::delete('{permission}', [PermissionController::class, 'destroy'])->name('permissions.destroy');
    });

    Route::group(['prefix' => 'users/'], function () {
        Route::get('', [UserController::class, 'index'])->name('users.index');
        Route::post('', [UserController::class, 'storeOrUpdate'])->name('users.store');
        Route::put('{id}', [UserController::class, 'storeOrUpdate'])->name('users.update');
        Route::get('{id}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::delete('destroy/{id}', [UserController::class, 'destroy'])->name('users.destroy');
        Route::post('/profile/update', [UserController::class, 'updateProfile'])->name('users.updateProfile');
    });

    Route::group(['prefix'=>'profile/'],function(){
        Route::get('',[ProfileController::class,'index'])->name('profile.index');
        Route::post('', [ProfileController::class, 'update'])->name('profile.update');
    });

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});
