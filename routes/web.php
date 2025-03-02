<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});











Route::resource('roles', App\Http\Controllers\RoleController::class);

Route::resource('permissions', App\Http\Controllers\PermissionController::class);

Route::resource('features', App\Http\Controllers\FeatureController::class);

Route::resource('users', App\Http\Controllers\UserController::class);


Route::resource('roles', App\Http\Controllers\RoleController::class);

Route::resource('permissions', App\Http\Controllers\PermissionController::class);

Route::resource('features', App\Http\Controllers\FeatureController::class);

Route::resource('users', App\Http\Controllers\UserController::class);
