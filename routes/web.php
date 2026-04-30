<?php

// Definir rutas

use Core\Route;

require_once RUTA_APP . '/core/middlewares.php';

use App\Controllers\ContactController;
use App\Controllers\HomeController;
use App\Controllers\LoginController;
use App\Controllers\UserController;

Route::get('/', [HomeController::class, 'index']);

Route::post('/auth/login', [LoginController::class, 'login']);
Route::post('/auth/register', [LoginController::class, 'register']);
Route::get('/auth/logout', [LoginController::class, 'logout']);

Route::get('/auth/dashboard', [LoginController::class, 'dashboard'], [$authMiddleware]);

Route::get('/contacts', [ContactController::class, 'index']);

Route::get('/showLoginForm', [LoginController::class, 'showLoginForm']);

Route::get('/showregisterForm', [LoginController::class, 'showregisterForm']);

Route::get('/users', [UserController::class, 'index'], [$authMiddleware]);
Route::get('/users/create', [UserController::class, 'create'], [$authMiddleware]);
Route::post('/users', [UserController::class, 'store'], [$authMiddleware]);

Route::dispatch();
