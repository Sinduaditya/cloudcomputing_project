<?php
use Illuminate\Support\Facades\Route;

// Auth Controllers
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\OAuthController;

// User Controllers
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DownloadController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\TokenController;
use App\Http\Controllers\InstanceController;

// Admin Controllers
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ActivityController;
use App\Http\Controllers\Admin\TokenManagementController;
use App\Http\Controllers\Admin\SystemController;
use App\Models\Download;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Public routes
Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/features', function () {
    return view('features');
})->name('features');

Route::get('/pricing', function () {
    return view('pricing');
})->name('pricing');

Route::get('/about', function () {
    return view('about');
})->name('about');

Route::post('/api/url/check', [DownloadController::class, 'checkUrl'])->name('api.url.check');

// Auth routes
Route::middleware(['guest'])->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// Logout (doesn't need guest middleware)
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// OAuth routes
Route::get('auth/{provider}', [OAuthController::class, 'redirectToProvider'])->name('oauth.redirect');
Route::get('auth/{provider}/callback', [OAuthController::class, 'handleProviderCallback'])->name('oauth.callback');

// Protected user routes
Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/activity', [DashboardController::class, 'activity'])->name('dashboard.activity');
    Route::get('/dashboard/stats', [DashboardController::class, 'stats'])->name('dashboard.stats');

    // Downloads
    Route::get('/downloads', [DownloadController::class, 'index'])->name('downloads.index');
    Route::get('/downloads/create', [DownloadController::class, 'create'])->name('downloads.create');
    Route::post('/downloads', [DownloadController::class, 'store'])
        ->name('downloads.store')
        ->middleware('tokens');
    Route::get('/downloads/{download}', [DownloadController::class, 'show'])->name('downloads.show');
    Route::get('/downloads/{download}/status', [DownloadController::class, 'status'])->name('downloads.status');
    Route::get('/downloads/{download}/url', [DownloadController::class, 'getDownloadUrl'])->name('downloads.url');
    Route::get('/downloads/file/{download}', [DownloadController::class, 'downloadFile'])->name('downloads.file');
    Route::post('/downloads/{download}/retry', [DownloadController::class, 'retry'])->name('downloads.retry');
    Route::post('/downloads/{download}/cancel', [DownloadController::class, 'cancel'])->name('downloads.cancel');

    // Scheduled Downloads
    Route::get('/schedules', [ScheduleController::class, 'index'])->name('schedules.index');
    Route::get('/schedules/create', [ScheduleController::class, 'create'])->name('schedules.create');
    Route::post('/schedules', [ScheduleController::class, 'store'])
        ->name('schedules.store')
        ->middleware('tokens');
    Route::get('/schedules/{schedule}', [ScheduleController::class, 'show'])->name('schedules.show');
    Route::get('/schedules/{schedule}/edit', [ScheduleController::class, 'edit'])->name('schedules.edit');
    Route::put('/schedules/{schedule}', [ScheduleController::class, 'update'])->name('schedules.update');
    Route::delete('/schedules/{schedule}', [ScheduleController::class, 'destroy'])->name('schedules.destroy');

    // Media Instances (for media player, sharing, etc.)
    Route::get('/instance/{download}', [InstanceController::class, 'show'])->name('instance.show');
    Route::get('/instance/{download}/url', [InstanceController::class, 'getDownloadUrl'])->name('instance.url');
    Route::post('/instance/{download}/delete', [InstanceController::class, 'requestDeletion'])->name('instance.delete');
    Route::get('/instance/{download}/share-info', [InstanceController::class, 'getShareInfo'])->name('instance.share');

    // Token management for users
    Route::get('/tokens', [TokenController::class, 'index'])->name('tokens.index');
    Route::get('/tokens/balance', [TokenController::class, 'balance'])->name('tokens.balance');
    Route::get('/tokens/purchase', [TokenController::class, 'purchase'])->name('tokens.purchase');
    Route::post('/tokens/process-purchase', [TokenController::class, 'processPurchase'])->name('tokens.process-purchase');

    // Account settings
    Route::get('/account', [DashboardController::class, 'account'])->name('account');
    Route::put('/account/update', [DashboardController::class, 'updateAccount'])->name('account.update');
    Route::put('/account/password', [DashboardController::class, 'updatePassword'])->name('account.password');

    Route::get('/downloads/file/{id}', [DownloadController::class, 'downloadFile'])->name('downloads.file');

    // Fix downloads that are stuck in "storing" status
    Route::get('/downloads/fix-status', function() {
        $fixed = 0;
        $downloads = Download::where('status', 'storing')
            ->whereNotNull('storage_url')
            ->get();

        foreach ($downloads as $download) {
            $download->status = 'completed';
            $download->completed_at = $download->completed_at ?? now();
            $download->save();
            $fixed++;
        }

        return redirect()->back()->with('success', "Fixed $fixed downloads");
    })->name('downloads.fix-status');

});

// Admin routes
Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        // Admin dashboard
        Route::get('/', [AdminController::class, 'index'])->name('index');
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

        // User management
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
        Route::post('/users', [UserController::class, 'store'])->name('users.store');
        Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show');
        Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
        Route::post('/users/{user}/toggle', [UserController::class, 'toggleStatus'])->name('users.toggle');
        Route::get('/users/{user}/downloads', [UserController::class, 'downloads'])->name('users.downloads');
        Route::get('/users/{user}/activities', [UserController::class, 'activities'])->name('users.activities');
        Route::get('/users/search', [UserController::class, 'search'])->name('users.search');

        // Activity monitoring
        Route::get('/activities', [ActivityController::class, 'index'])->name('activities.index');
        Route::get('/activities/{activity}', [ActivityController::class, 'show'])->name('activities.show');
        Route::delete('/activities/clear', [ActivityController::class, 'clear'])->name('activities.clear');
        Route::get('/activities/filter', [ActivityController::class, 'filter'])->name('activities.filter');

        // Token management
        Route::get('/tokens', [TokenManagementController::class, 'index'])->name('tokens.index');
        Route::get('/tokens/transactions', [TokenManagementController::class, 'transactions'])->name('tokens.transactions');
        Route::post('/tokens/adjust/{user}', [TokenManagementController::class, 'adjustTokens'])->name('tokens.adjust');
        Route::get('/tokens/pricing', [TokenManagementController::class, 'pricing'])->name('tokens.pricing');
        Route::post('/tokens/pricing', [TokenManagementController::class, 'updatePricing'])->name('tokens.update-pricing');

        // System management
        Route::get('/system/settings', [SystemController::class, 'settings'])->name('system.settings');
        Route::post('/system/settings', [SystemController::class, 'updateSettings'])->name('system.update-settings');
        Route::post('/system/cloudinary', [SystemController::class, 'updateCloudinary'])->name('system.update-cloudinary');
        Route::get('/system/maintenance', [SystemController::class, 'maintenance'])->name('system.maintenance');
        Route::post('/system/maintenance', [SystemController::class, 'runMaintenance'])->name('system.run-maintenance');
        Route::get('/system/info', [SystemController::class, 'info'])->name('system.info');
        Route::get('/system/logs', [SystemController::class, 'logs'])->name('system.logs');
        Route::get('/system/logs/{filename}', [SystemController::class, 'viewLog'])->name('system.view-log');
        Route::post('/system/logs/{filename}/clear', [SystemController::class, 'clearLog'])->name('system.clear-log');

        // Download & Schedule management
        Route::get('/downloads', [AdminController::class, 'downloads'])->name('downloads.index');
        Route::get('/downloads/{download}', [AdminController::class, 'showDownload'])->name('downloads.show');
        Route::delete('/downloads/{download}', [AdminController::class, 'deleteDownload'])->name('downloads.delete');
        Route::get('/schedules', [AdminController::class, 'schedules'])->name('schedules.index');
        Route::get('/schedules/{schedule}', [AdminController::class, 'showSchedule'])->name('schedules.show');
        Route::delete('/schedules/{schedule}', [AdminController::class, 'deleteSchedule'])->name('schedules.delete');
    });


