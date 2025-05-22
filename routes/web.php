<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DownloadController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\TokenController;
use App\Http\Controllers\AdminController;

// Public routes
Route::get('/', function () {
    return view('welcome');
});

// Auth routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('auth/{provider}', [App\Http\Controllers\Auth\OAuthController::class, 'redirectToProvider']);
Route::get('auth/{provider}/callback', [App\Http\Controllers\Auth\OAuthController::class, 'handleProviderCallback']);

// Protected routes
Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Downloads
    Route::resource('downloads', DownloadController::class);
    Route::get('/downloads/{download}/download', [DownloadController::class, 'downloadFile'])->name('downloads.download');

    // Schedules
    Route::resource('schedules', ScheduleController::class);

    // Tokens
    Route::get('/tokens', [TokenController::class, 'index'])->name('tokens.index');

    // Admin routes
    Route::middleware(['admin'])->prefix('admin')->name('admin.')->group(function () {
        // Dashboard
        Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');

        // User management
        Route::get('/users', [AdminController::class, 'users'])->name('users');
        Route::get('/users/{user}/downloads', [AdminController::class, 'userDownloads'])->name('users.downloads');
        Route::get('/users/{user}/schedules', [AdminController::class, 'userSchedules'])->name('users.schedules');
        Route::post('/users/{user}/toggle', [AdminController::class, 'toggleUserStatus'])->name('users.toggle');

        // Token management
        Route::get('/tokens', [AdminController::class, 'tokenManagement'])->name('tokens');
        Route::post('/tokens/{user}', [AdminController::class, 'updateTokens'])->name('tokens.update');
    });
});


