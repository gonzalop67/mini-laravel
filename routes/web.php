<?php

// Definir rutas

use Core\Route;

require_once RUTA_APP . '/Core/middlewares.php';

use App\Controllers\ContactController;
use App\Controllers\HomeController;
use App\Controllers\LoginController;
use App\Controllers\PermissionController;
use App\Controllers\RoleController;
use App\Controllers\UserController;

Route::get('/', [HomeController::class, 'index']);

Route::post('/auth/login', [LoginController::class, 'login']);
Route::post('/auth/register', [LoginController::class, 'register']);
Route::get('/auth/logout', [LoginController::class, 'logout']);

Route::get('/auth/dashboard', [LoginController::class, 'dashboard'], [$authMiddleware]);

Route::get('/contacts', [ContactController::class, 'index']);
Route::get('/contacts/create', [ContactController::class, 'create']);
Route::post('/contacts', [ContactController::class, 'store']);
Route::get('/contacts/:id', [ContactController::class, 'show']);
Route::get('/contacts/:id/edit', [ContactController::class, 'edit']);
Route::post('/contacts/:id', [ContactController::class, 'update']);
Route::post('/contacts/:id/delete', [ContactController::class, 'destroy']);

Route::get('/showLoginForm', [LoginController::class, 'showLoginForm']);
Route::get('/showregisterForm', [LoginController::class, 'showregisterForm']);

Route::get('/roles', [RoleController::class, 'index'], [$authMiddleware]);
Route::get('/roles/create', [RoleController::class, 'create'], [$authMiddleware]);
Route::post('/roles', [RoleController::class, 'store'], [$authMiddleware]);
Route::get('/roles/:id/edit', [RoleController::class, 'edit'], [$authMiddleware]);
Route::get('/roles/:id/permissions', [RoleController::class, 'permissions']);
Route::post('/roles/:id/permissions', [RoleController::class, 'updatePermissions']);
Route::post('/roles/:id', [RoleController::class, 'update']);

Route::get('/permissions', [PermissionController::class, 'index'], [$authMiddleware]);
Route::get('/permissions/create', [PermissionController::class, 'create'], [$authMiddleware]);
Route::post('/permissions', [PermissionController::class, 'store'], [$authMiddleware]);
Route::get('/permissions/:id/edit', [PermissionController::class, 'edit']);
Route::post('/permissions/:id', [PermissionController::class, 'update']);

Route::get('/users', [UserController::class, 'index'], [$authMiddleware]);
Route::get('/users/create', [UserController::class, 'create'], [$authMiddleware]);
Route::post('/users', [UserController::class, 'store'], [$authMiddleware]);


Route::dispatch();
