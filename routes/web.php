<?php

use App\Http\Controllers\Admin\StationeryController;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\ErrorController;
use App\Http\Controllers\Manager\DashboardController;
use App\Http\Controllers\User\ItemDemandController as UserItemDemandController;
use App\Http\Controllers\Admin\ItemDemandController as AdminItemDemandController;
use App\Http\Controllers\Manager\ItemDemandController as ManagerItemDemandController;
use Illuminate\Database\Capsule\Manager;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\FirstLoginController;
use Illuminate\Auth\Middleware\RedirectIfAuthenticated;
use App\Http\Controllers\User\PengajuanBarangController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\User\DashboardController as UserDashboardController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Manager\DashboardController as ManagerDashboardController;

// Route::get('/', function () {
//     return view('welcome');
// });

// Route::middleware(['auth', 'password.change'])->group(function () {
//     Route::get('/change-password', [FirstLoginController::class, 'show'])->name('password.change');
//     Route::put('/change-password', [FirstLoginController::class, 'update'])->name('password.update');
// });

Route::middleware(['auth'])->group(function () {
    // Route::get('/change-password', [FirstLoginController::class, 'show'])->name('password.change');
    // Route::post('/change-password', [FirstLoginController::class, 'update'])->name('password.update');
    Route::resource('change-password', FirstLoginController::class);
    Route::get('/redirect-dashboard', [ErrorController::class, 'redirect'])->name('redirect.dashboard');
});

Route::middleware(['guest', 'redirect.authenticated'])->group(function () {
    Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('login', [AuthenticatedSessionController::class, 'store']);
    // ...rute lain yang hanya bisa diakses oleh guest...
});

// Admin Route
Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
    Route::resource('users-management', UserController::class);
    Route::resource('stationeries', StationeryController::class);
    Route::resource('demand', AdminItemDemandController::class);
    // Route::get('/admin/users', [AdminUserController::class, 'index'])->name('admin.users');
    // Route::get('/admin/reports', [AdminReportController::class, 'index'])->name('admin.reports');
});

// User Route
Route::middleware(['auth', 'role:user'])->prefix('user')->group(function () {
    Route::get('/dashboard', [UserDashboardController::class, 'index'])->name('user.dashboard');
    Route::get('/get-stationery', [ApiController::class, 'getStationeryByJenis'])->name('getStationeryByJenis');
    Route::resource('data-pengajuan', PengajuanBarangController::class);
    Route::resource('item-demand', UserItemDemandController::class);
    // Route::get('/user/profile', [UserProfileController::class, 'index'])->name('user.profile');
    // Route::get('/user/activities', [UserActivityController::class, 'index'])->name('user.activities');
});

Route::middleware(['auth', 'role:manager'])->prefix('manager')->group(function () {
    Route::get('/dashboard', [ManagerDashboardController::class, 'index'])->name('manager.dashboard');
    Route::resource('item_demands', ManagerItemDemandController::class);
});


// Route::middleware('auth')->group(function () {
//     Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//     Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//     Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
// });

require __DIR__ . '/auth.php';
