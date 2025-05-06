<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController;
use Illuminate\Database\Capsule\Manager;
use App\Http\Controllers\ErrorController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\FirstLoginController;
use App\Http\Controllers\ItemDemandController;
use App\Http\Controllers\Admin\StockController;
use App\Http\Controllers\Admin\DivisionController;
use App\Http\Controllers\Admin\StationeryController;
use App\Http\Controllers\Admin\PrintDemandController;
use App\Http\Controllers\Manager\DashboardController;
use App\Http\Controllers\Coo\PengajuanBarangController;
use Illuminate\Auth\Middleware\RedirectIfAuthenticated;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Coo\DashboardController as CooDashboardController;
use App\Http\Controllers\User\DashboardController as UserDashboardController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\User\ItemDemandController as UserItemDemandController;
use App\Http\Controllers\Admin\ItemDemandController as AdminItemDemandController;
use App\Http\Controllers\Manager\DashboardController as ManagerDashboardController;
use App\Http\Controllers\Manager\ItemDemandController as ManagerItemDemandController;

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
    // Route untuk User
    Route::prefix('user')->middleware('role:user')->group(function () {
        // Route::resource('item-demands', ItemDemandController::class);
        Route::get('/dashboard', [UserDashboardController::class, 'index'])->name('user.dashboard');
        Route::get('/get-stationery', [ApiController::class, 'getStationeryByJenis'])->name('getStationeryByJenis');
        // Route::resource('data-pengajuan', PengajuanBarangController::class);
        Route::resource('item-demand', UserItemDemandController::class);
    });

    // Route untuk Manager
    Route::prefix('manager')->middleware('role:manager')->group(function () {
        // Route::resource('item-demands', ItemDemandController::class);
        Route::get('/dashboard', [ManagerDashboardController::class, 'index'])->name('manager.dashboard');
        Route::get('/get-stationery', [ApiController::class, 'getStationeryByJenis'])->name('getStationeryByJenis');
        Route::resource('item_demands', ManagerItemDemandController::class);
    });

    // Route untuk COO
    Route::prefix('coo')->middleware('role:coo')->group(function () {
        // Route::resource('item-demands', ItemDemandController::class);
        Route::get('/dashboard', [CooDashboardController::class, 'index'])->name('coo.dashboard');
        Route::get('/get-stationery', [ApiController::class, 'getStationeryByJenis'])->name('getStationeryByJenis');
        Route::resource('user_demands', PengajuanBarangController::class);
    });

    // Route untuk Admin
    Route::prefix('admin')->middleware('role:admin')->group(function () {
        // Route::resource('item-demands', ItemDemandController::class);
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
        Route::resource('users-management', UserController::class);
        Route::resource('stationeries', StationeryController::class);
        Route::resource('demand', AdminItemDemandController::class);
        // Route::resource('list_demands', StockController::class);
        Route::resource('list_demands', PrintDemandController::class);
        Route::resource('divisions', DivisionController::class);
    });
});

// Route::middleware(['guest', 'redirect.authenticated'])->group(function () {
//     Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
//     Route::post('login', [AuthenticatedSessionController::class, 'store']);
//     // ...rute lain yang hanya bisa diakses oleh guest...
// });

// Admin Route
// Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {

//     // Route::get('/admin/users', [AdminUserController::class, 'index'])->name('admin.users');
//     // Route::get('/admin/reports', [AdminReportController::class, 'index'])->name('admin.reports');
// });

// // User Route
// Route::middleware(['auth', 'role:user'])->prefix('user')->group(function () {

//     // Route::get('/user/profile', [UserProfileController::class, 'index'])->name('user.profile');
//     // Route::get('/user/activities', [UserActivityController::class, 'index'])->name('user.activities');
// });
// // Vice Director Route
// Route::middleware(['auth', 'role:coo'])->prefix('coo')->group(function () {

// });

// Route::middleware(['auth', 'role:manager'])->prefix('manager')->group(function () {

// });


// Route::middleware('auth')->group(function () {
//     Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//     Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//     Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
// });

require __DIR__ . '/auth.php';
