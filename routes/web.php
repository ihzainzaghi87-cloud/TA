<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BannerController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\Customer\OrderController;
use App\Http\Controllers\Customer\CartController;
use App\Http\Controllers\Customer\PagesController;
use App\Http\Controllers\Customer\ShippingController;
use App\Http\Controllers\Customer\UserAddressController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserManagementController;
use App\Http\Controllers\AdminOrderController;
use App\Http\Controllers\UserPointController;
use App\Http\Controllers\PointTransactionController;
use Illuminate\Support\Facades\Route;

// ---------- Auth (web session) ----------
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'login'])->name('login.attempt')->middleware('guest');

Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register')->middleware('guest');
Route::post('/register', [AuthController::class, 'register'])->name('register.attempt')->middleware('guest');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// ---------- Dashboard ----------
Route::get('/admin', DashboardController::class)->name('dashboard')->middleware('auth', 'role:superadmin|admin|staff');

// ---------- Admin Page (web) ----------
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    // Profile
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');

    // Roles
    Route::get('/roles', [RoleController::class, 'index'])->name('roles.index');
    Route::get('/roles/create', [RoleController::class, 'create'])->name('roles.create');
    Route::post('/roles', [RoleController::class, 'store'])->name('roles.store');
    Route::get('/roles/{role}', [RoleController::class, 'show'])->name('roles.show');
    Route::get('/roles/{role}/edit', [RoleController::class, 'edit'])->name('roles.edit');
    Route::put('/roles/{role}', [RoleController::class, 'update'])->name('roles.update');
    Route::delete('/roles/{role}', [RoleController::class, 'destroy'])->name('roles.destroy');
    Route::post('/roles/{role}/sync-permissions', [RoleController::class, 'syncPermissions'])->name('roles.sync-permissions');

    // Permissions
    Route::get('/permissions', [PermissionController::class, 'index'])->name('permissions.index');
    Route::get('/permissions/create', [PermissionController::class, 'create'])->name('permissions.create');
    Route::post('/permissions', [PermissionController::class, 'store'])->name('permissions.store');
    Route::get('/permissions/{permission}/edit', [PermissionController::class, 'edit'])->name('permissions.edit');
    Route::put('/permissions/{permission}', [PermissionController::class, 'update'])->name('permissions.update');
    Route::delete('/permissions/{permission}', [PermissionController::class, 'destroy'])->name('permissions.destroy');

    // Users
    Route::get('/users', [UserManagementController::class, 'index'])->name('users.index');
    Route::get('/users/create', [UserManagementController::class, 'create'])->name('users.create');
    Route::post('/users', [UserManagementController::class, 'store'])->name('users.store');

    Route::get('/users/{user}', [UserManagementController::class, 'show'])->name('users.show');
    Route::get('/users/{user}/edit', [UserManagementController::class, 'edit'])->name('users.edit');

    // edit profil & password dari halaman edit user
    Route::put('/users/{user}', [UserManagementController::class, 'updateProfile'])->name('users.update');
    Route::put('/users/{user}/password', [UserManagementController::class, 'updatePassword'])->name('users.password.update');

    // sinkron role & permission
    Route::post('/users/{user}/sync-roles', [UserManagementController::class, 'syncRoles'])->name('users.sync-roles');
    Route::post('/users/{user}/sync-permissions', [UserManagementController::class, 'syncPermissions'])->name('users.sync-permissions');

    // Category
    Route::resource('categories', CategoryController::class);

    // Product
    Route::resource('products', ProductController::class);
    // Toggle product active status
    Route::patch('products/{product}/toggle-active', [ProductController::class, 'toggleActive'])
        ->name('products.toggle-active');
    // Update variation stock
    Route::patch('products/{product}/variations/{variation}/stock', [ProductController::class, 'updateVariationStock'])
        ->name('products.variations.update-stock');
    // Bulk update product status
    Route::post('products/bulk/update-status', [ProductController::class, 'bulkUpdateStatus'])
        ->name('products.bulk.update-status');
    // Delete image (jika ada method destroyImage di controller)
    Route::delete('products/images/{image}', [ProductController::class, 'destroyImage'])
        ->name('products.images.destroy');

    // Orders
    Route::resource('orders', AdminOrderController::class)->only(['index', 'show']);

    // User Points
    Route::resource('user-points', UserPointController::class)->only(['index', 'show']);
    Route::post('user-points/{userId}/reset', [UserPointController::class, 'reset'])->name('user-points.reset');
    Route::get('user-points-leaderboard', [UserPointController::class, 'leaderboard'])->name('user-points.leaderboard');
    
    // Point Transactions
    Route::resource('point-transactions', PointTransactionController::class)->only(['index', 'show']);
    Route::get('point-transactions/user/{userId}/earned', [PointTransactionController::class, 'earned'])->name('point-transactions.earned');
    Route::get('point-transactions/user/{userId}/redeemed', [PointTransactionController::class, 'redeemed'])->name('point-transactions.redeemed');
    Route::get('point-transactions/user/{userId}/statistics', [PointTransactionController::class, 'statistics'])->name('point-transactions.statistics');

    // Shipping update
    Route::get('orders/{order}/edit-shipping', [AdminOrderController::class, 'editShipping'])
         ->name('orders.edit-shipping');
    
    Route::put('orders/{order}/shipping', [AdminOrderController::class, 'updateShipping'])
         ->name('orders.update-shipping');
    
    Route::patch('orders/{order}/status', [AdminOrderController::class, 'updateStatus'])
         ->name('orders.update-status');

    // Banner
    Route::resource('banners', BannerController::class);
    Route::patch('banners/{banner}/toggle-status', [BannerController::class, 'toggleStatus'])
        ->name('banners.toggle-status');

    // Shipping
    Route::post('shipping/sync-provinces', [ShippingController::class, 'syncProvinces']);
    Route::post('shipping/sync-cities', [ShippingController::class, 'syncCities']);        
});

// ---------- Customer Page ----------
Route::get('/', [PagesController::class, 'index'])->name('home');

// Product Detail Page (Customer)
Route::get('/products/{slug}', [PagesController::class, 'customerShow'])->name('product.detail');

Route::prefix('shipping')->name('shipping.')->group(function () {
    // Get provinces & cities
    Route::get('/provinces', [ShippingController::class, 'getProvinces']);
    Route::get('/provinces/{provinceId}/cities', [ShippingController::class, 'getCitiesByProvince']);
    // Get available couriers
    Route::get('/couriers', [ShippingController::class, 'getCouriers']);
    // Get origin city info
    Route::get('/origin', [ShippingController::class, 'getOriginCity']);
    // Calculate shipping cost (general)
    Route::post('/calculate', [ShippingController::class, 'calculateShippingCost']);
});

// ---------- Cart Routes ----------
Route::middleware(['auth'])->group(function () {
    // User Addresses
    Route::prefix('addresses')->name('addresses.')->group(function () {
        Route::get('/', [UserAddressController::class, 'index'])->name('index');
        Route::get('/create', [UserAddressController::class, 'create'])->name('create');
        Route::post('/', [UserAddressController::class, 'store'])->name('store');
        Route::get('/{id}', [UserAddressController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [UserAddressController::class, 'edit'])->name('edit');
        Route::put('/{id}', [UserAddressController::class, 'update'])->name('update');
        Route::delete('/{id}', [UserAddressController::class, 'destroy'])->name('destroy');
        Route::post('/{id}/set-primary', [UserAddressController::class, 'setPrimary'])->name('setPrimary');
    });

    // Cart
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart', [CartController::class, 'store'])->name('cart.store');
    Route::put('/cart/{id}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/{id}', [CartController::class, 'destroy'])->name('cart.destroy');
    Route::delete('/cart', [CartController::class, 'clear'])->name('cart.clear');
    Route::get('/cart/summary', [CartController::class, 'getSummary'])->name('cart.summary');

    // Checkout
    Route::post('/checkout/select-products', [OrderController::class, 'selectProducts'])->name('checkout.select-products');
    Route::get('/checkout', [OrderController::class, 'checkout'])->name('checkout');
    Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
    Route::get('/orders/success/{orderNumber}', [OrderController::class, 'success'])->name('checkout.success');
    
    // My Orders
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{orderId}', [OrderController::class, 'show'])->name('orders.show');

    // Calculate shipping for user's cart
    Route::post('/calculate-cart', [ShippingController::class, 'calculateCartShipping']);

    // API Routes for AJAX
    Route::middleware(['auth'])->prefix('api')->group(function () {
        Route::get('/provinces/{provinceId}/cities', [UserAddressController::class, 'getCities']);
        Route::get('/user/addresses', [UserAddressController::class, 'getForCheckout']);
        Route::get('/user/addresses/{id}', [UserAddressController::class, 'getAddressDetail']);
    });
});

// Route::post('/midtrans/notification', [OrderController::class, 'callback'])->name('midtrans.callback');


// ---------- Password Reset ----------
Route::middleware('guest')->group(function () {
    // form minta link reset
    Route::get('/forgot-password', [PasswordResetController::class, 'showLinkRequestForm'])
        ->name('password.request');

    // kirim link reset via email
    Route::post('/forgot-password', [PasswordResetController::class, 'sendResetLink'])
        ->middleware('throttle:6,1')
        ->name('password.email');

    // form reset password (via link di email)
    Route::get('/reset-password/{token}', [PasswordResetController::class, 'showResetForm'])
        ->name('password.reset');

    // submit reset password
    Route::post('/reset-password', [PasswordResetController::class, 'resetPassword'])
        ->middleware('throttle:6,1')
        ->name('password.update');
});
