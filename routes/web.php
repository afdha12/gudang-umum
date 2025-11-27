<?php

use App\Http\Controllers\ActionButtonController;
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
// Route::get('/', function () {
//     if (auth()->check()) {
//         return redirect()->route(auth()->user()->role . '.dashboard');
//     }

//     return redirect()->route('login');
// });
Route::get('/', function () {
    if (!Auth::check()) {
        return redirect()->route('login'); // arahkan ke halaman login
    }

    // Cek role dan arahkan ke dashboard sesuai role
    $role = Auth::user()->role;

    switch ($role) {
        case 'admin':
            return redirect()->route('admin.dashboard');
        case 'manager':
            return redirect()->route('manager.dashboard');
        case 'user':
        default:
            return redirect()->route('user.dashboard');
    }
});

Route::middleware(['auth', 'verified'])->group(function () {
    // Route::get('/change-password', [FirstLoginController::class, 'show'])->name('password.change');
    // Route::post('/change-password', [FirstLoginController::class, 'update'])->name('password.update');
    Route::resource('change-password', FirstLoginController::class);
    // Route::resource('act-btn', ActionButtonController::class);
    Route::get('/proxy/stationeries', function () {
        $token = env('GO_API_TOKEN');

        $response = Http::withToken($token)
            ->get('http://10.32.1.219:3000/api/stationeries');

        return response()->json($response->json(), $response->status());
    });

    // Route untuk User
    Route::prefix('user')->middleware(['role:user', 'password.change'])->group(function () {
        // Route::resource('item-demands', ItemDemandController::class);
        Route::get('/dashboard', [UserDashboardController::class, 'index'])->name('user.dashboard');
        // Route::resource('data-pengajuan', PengajuanBarangController::class);
        Route::resource('item-demand', UserItemDemandController::class);
        Route::get('request/stationeries', [ApiController::class, 'getStationeries'])->name('req.stationeries');
        Route::get('/item-demand/{user}/date/{date}/edit', [UserItemDemandController::class, 'editByDate'])
            ->name('item-demand.edit_by_date');
        Route::put('/item-demand/{user}/date/{date}', [UserItemDemandController::class, 'updateByDate'])
            ->name('item-demand.update_by_date');
    });

    // Route untuk Manager
    Route::prefix('manager')->middleware(['role:manager', 'password.change'])->group(function () {
        // Route::resource('item-demands', ItemDemandController::class);
        Route::get('/dashboard', [ManagerDashboardController::class, 'index'])->name('manager.dashboard');
        // Route::get('/get-stationery', [ApiController::class, 'getStationeryByJenis'])->name('getStationeryByJenis');
        Route::resource('item_demands', ManagerItemDemandController::class);
        Route::put('item_demands/{item}/reject', [ManagerItemDemandController::class, 'reject'])->name('item_demands.reject');
        Route::get('/item_demands/{user}/date/{date}', [ManagerItemDemandController::class, 'showByUserAndDate'])
            ->name('item_demands.show_by_user_and_date');
        // Route untuk edit semua item berdasarkan user dan tanggal permintaan
        Route::get('/item_demands/{user}/date/{date}/edit', [ManagerItemDemandController::class, 'editByDate'])
            ->name('item_demands.edit_by_date');
        Route::put('/item_demands/{user}/date/{date}', [ManagerItemDemandController::class, 'updateByDate'])
            ->name('item_demands.update_by_date');
    });

    // Route untuk COO
    Route::prefix('coo')->middleware(['role:coo', 'password.change'])->group(function () {
        // Route::resource('item-demands', ItemDemandController::class);
        Route::get('/dashboard', [CooDashboardController::class, 'index'])->name('coo.dashboard');
        // Route::get('/get-stationery', [ApiController::class, 'getStationeryByJenis'])->name('getStationeryByJenis');
        Route::resource('user_demands', PengajuanBarangController::class);
        Route::put('user_demands/{item}/reject', [PengajuanBarangController::class, 'reject'])->name('user_demands.reject');
        // Route untuk edit semua item berdasarkan user dan tanggal permintaan
        Route::get('/user_demands/{user}/date/{date}/edit', [PengajuanBarangController::class, 'editByDate'])
            ->name('user_demands.edit_by_date');
        Route::put('/user_demands/{user}/date/{date}', [PengajuanBarangController::class, 'updateByDate'])
            ->name('user_demands.update_by_date');
    });

    // Route untuk Admin
    Route::prefix('admin')->middleware(['role:admin', 'password.change'])->group(function () {
        // Route::resource('item-demands', ItemDemandController::class);
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
        Route::resource('users-management', UserController::class);
        Route::resource('stationeries', StationeryController::class);
        Route::get('/stationeries-export', [StationeryController::class, 'export'])->name('stationeries.export');
        Route::resource('demand', AdminItemDemandController::class);
        Route::put('demand/{item}/reject', [AdminItemDemandController::class, 'reject'])->name('demand.reject');
        Route::post('/demand/{id}/cancel', [AdminItemDemandController::class, 'cancelDemand'])->name('demand.cancel');

        // Route::resource('list_demands', StockController::class);
        Route::resource('list_demands', PrintDemandController::class);
        Route::resource('divisions', DivisionController::class);
        // Route untuk edit semua item berdasarkan user dan tanggal permintaan
        Route::get('/demand/{user}/date/{date}/edit', [AdminItemDemandController::class, 'editByDate'])
            ->name('demand.edit_by_date');
        Route::put('/demand/{user}/date/{date}', [AdminItemDemandController::class, 'updateByDate'])
            ->name('demand.update_by_date');
        Route::get('/export-bulanan', [PrintDemandController::class, 'exportExcel'])->name('export.bulanan');
    });
});


require __DIR__ . '/auth.php';
