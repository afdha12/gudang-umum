<?php

use App\Http\Controllers\Admin\StationeryController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\FirstLoginController;
use Illuminate\Auth\Middleware\RedirectIfAuthenticated;
use App\Http\Controllers\User\PengajuanBarangController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\User\DashboardController as UserDashboardController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;

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
    // Route::get('/admin/users', [AdminUserController::class, 'index'])->name('admin.users');
    // Route::get('/admin/reports', [AdminReportController::class, 'index'])->name('admin.reports');
});

// User Route
Route::middleware(['auth', 'role:user'])->prefix('user')->group(function () {
    Route::get('/dashboard', [UserDashboardController::class, 'index'])->name('user.dashboard');
    Route::resource('data-pengajuan', PengajuanBarangController::class);
    // Route::get('/user/profile', [UserProfileController::class, 'index'])->name('user.profile');
    // Route::get('/user/activities', [UserActivityController::class, 'index'])->name('user.activities');
});


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
