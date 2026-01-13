<?php

use App\Http\Controllers\V1\{
	LevelController,
	LocationController
};
use App\Http\Controllers\V1\Auth\Admin\AuthController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn() => _200('API is working on V1'));

Route::prefix('v1')->group(function () {
	Route::prefix('admin/auth')->controller(AuthController::class)->group(function () {
		Route::post('login', 'login');
		Route::post('confirm-password', 'confirmPassword');
		Route::post('logout', 'logout');
	});

	Route::apiResource('locations', LocationController::class);
	Route::apiResource('levels', LevelController::class);
});
