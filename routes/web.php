<?php

use App\Http\Controllers\Auth\WebAuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AgencyController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    // If user is not authenticated, redirect to login
    if (!auth()->check()) {
        return redirect()->route('login.show');
    }

    // If user is authenticated, redirect based on role
    $user = auth()->user();
    if ($user->role === 'super_admin') {
        return redirect()->route('super.dashboard');
    }

    if ($user->role === 'agency_admin') {
        return redirect()->route('agency.dashboard');
    }

    // Regular users (shouldn't reach here if middleware works)
    return redirect()->route('login.show');
})->name('home');

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [WebAuthController::class, 'show'])->name('login.show');
    Route::post('/login', [WebAuthController::class, 'login'])->name('login.perform');
});

Route::post('/logout', [WebAuthController::class, 'logout'])->middleware('auth')->name('logout');

// Super Admin Routes
Route::middleware(['auth', 'ensure.admin'])->prefix('/super')->name('super.')->group(function () {
    Route::get('/dashboard', function () {
        return view('super.dashboard');
    })->name('dashboard');
    
    // Agencies Routes
    Route::resource('agencies', AgencyController::class);
    
    // Categories Routes
    Route::resource('categories', CategoryController::class);
});

// Agency Admin Routes
Route::middleware(['auth', 'ensure.admin'])->prefix('/agency')->name('agency.')->group(function () {
    Route::get('/dashboard', function () {
        return view('agency.dashboard');
    })->name('dashboard');
    
    // Agency Reports Routes
    Route::get('/reports', [ReportController::class, 'agencyIndex'])->name('reports.index');
    Route::get('/reports/{report}', [ReportController::class, 'show'])->name('reports.show');
    Route::put('/reports/{report}/status', [ReportController::class, 'updateStatus'])->name('reports.updateStatus');
});

// User Management Routes (Super Admin Only)
Route::middleware(['auth', 'ensure.admin'])->prefix('/users')->name('users.')->group(function () {
    Route::get('/', [UserController::class, 'index'])->name('index');
    Route::get('/create', [UserController::class, 'create'])->name('create');
    Route::post('/', [UserController::class, 'store'])->name('store');
    Route::get('/{user}/edit', [UserController::class, 'edit'])->name('edit');
    Route::put('/{user}', [UserController::class, 'update'])->name('update');
    Route::delete('/{user}', [UserController::class, 'destroy'])->name('destroy');
});
