<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PasswordResetController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\UserManagementController as UsersApi;
use App\Http\Controllers\Api\RoleController as RolesApi;
use App\Http\Controllers\Api\PermissionController as PermissionsApi;
// use App\Http\Controllers\Api\UserProfileController;
use App\Http\Controllers\Api\Customer\PagesController;
use App\Http\Controllers\Api\Customer\CartController;
use App\Http\Controllers\Api\Customer\OrderController;

// AUTH
Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login',    [AuthController::class, 'login']);

    Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);
});

Route::middleware('auth:sanctum')->get('/dashboard', [DashboardController::class, 'index']);

// PASSWORD RESET (rate limited)
Route::prefix('password')->group(function () {
    Route::post('/forgot', [PasswordResetController::class, 'sendResetLink'])->middleware('throttle:6,1');
    Route::post('/reset',  [PasswordResetController::class, 'resetPassword'])->middleware('throttle:6,1');
});

// PROFILE (protected)
Route::middleware('auth:sanctum')->prefix('profile')->group(function () {
    Route::get('/',         [ProfileController::class, 'show']);
    Route::put('/',         [ProfileController::class, 'update']);
    Route::put('/password', [ProfileController::class, 'updatePassword']);
});

// ADMIN (protected + role/permission)
Route::middleware(['auth:sanctum'])->prefix('admin')->group(function () {
    // Users
    Route::prefix('users')->group(function () {
        Route::get('/',                   [UsersApi::class, 'index'])->middleware('permission:users.index|users.view');
        Route::post('/',                  [UsersApi::class, 'store'])->middleware('permission:users.create');
        Route::get('/{user}',             [UsersApi::class, 'show'])->middleware('permission:users.view');
        Route::put('/{user}',             [UsersApi::class, 'updateProfile'])->middleware('permission:users.update');
        Route::put('/{user}/password',    [UsersApi::class, 'updatePassword'])->middleware('permission:users.update');
        Route::post('/{user}/sync-roles', [UsersApi::class, 'syncRoles'])->middleware('permission:users.assign-roles|users.update');
        Route::post('/{user}/sync-permissions', [UsersApi::class, 'syncPermissions'])->middleware('permission:users.grant-permissions|users.update');
    });

    // Roles
    Route::prefix('roles')->group(function () {
        Route::get('/',                      [RolesApi::class, 'index'])->middleware('permission:roles.index|roles.view');
        Route::post('/',                     [RolesApi::class, 'store'])->middleware('permission:roles.create');
        Route::get('/{role}',                [RolesApi::class, 'show'])->middleware('permission:roles.view');
        Route::put('/{role}',                [RolesApi::class, 'update'])->middleware('permission:roles.update');
        Route::delete('/{role}',             [RolesApi::class, 'destroy'])->middleware('permission:roles.delete');
        Route::post('/{role}/sync-permissions', [RolesApi::class, 'syncPermissions'])->middleware('permission:roles.sync-permissions|roles.update');
    });

    // Permissions
    Route::prefix('permissions')->group(function () {
        Route::get('/',                 [PermissionsApi::class, 'index']);  // permissions.index|permissions.view
        Route::post('/',                [PermissionsApi::class, 'store']);  // permissions.create
        Route::get('/{permission}',     [PermissionsApi::class, 'show']);   // permissions.view
        Route::put('/{permission}',     [PermissionsApi::class, 'update']); // permissions.update
        Route::delete('/{permission}',  [PermissionsApi::class, 'destroy']); // permissions.delete
    });

    // Profile
    Route::prefix('profile')->group(function () {
        Route::get('/',         [ProfileController::class, 'show']);
        Route::put('/',         [ProfileController::class, 'update']);
        Route::put('/password', [ProfileController::class, 'updatePassword']);
    });
});

// -------- CUSTOMER PAGES -----------
Route::get('/', [PagesController::class, 'index']);

Route::middleware('auth:sanctum')->group(function () {
    // Cart
    Route::prefix('cart')->group(function () {
        Route::get('/',         [CartController::class, 'index']);
        Route::post('/',         [CartController::class, 'store']);
        Route::put('/{id}', [CartController::class, 'update']);
        Route::delete('/{id}', [CartController::class, 'destroy']);
        Route::delete('/', [CartController::class, 'clear']);
        Route::get('/summary', [CartController::class, 'getSummary']);
    });

    // Orders
    Route::prefix('orders')->group(function () {
        Route::get('/', [OrderController::class, 'index']);
        Route::post('/', [OrderController::class, 'store']);
        Route::get('/{orderId}', [OrderController::class, 'show']);
        // Route::post('/callback', [OrderController::class, 'callback'])->withoutMiddleware('auth:sanctum');
    });
});

// PROFILE (protected)
// Route::middleware('auth:sanctum')->prefix('profile')->group(function () {
//     Route::get('/',         [UserProfileController::class, 'show']);
//     Route::put('/',         [UserProfileController::class, 'update']);
//     Route::put('/password', [UserProfileController::class, 'updatePassword']);
// });

Route::post('/midtrans/notification', [\App\Http\Controllers\Customer\OrderController::class, 'callback']);