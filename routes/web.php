<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\SubscriptionController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\AiSettingController;
use App\Http\Controllers\Admin\EventRequirementQuestionController;
use App\Http\Controllers\Admin\FeedbackController;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\Admin\VendorAnalyticsController;
use App\Http\Controllers\User\UserAuthController;
use App\Http\Controllers\User\UserSubscriptionController;
use App\Http\Controllers\User\UserDashboardController;

// Redirect /admin to /admin/dashboard
Route::redirect('/admin', '/admin/dashboard');

Route::prefix('admin')->group(function () {
    // Guest Admin Routes
    Route::middleware('guest:admin')->group(function () {
        Route::get('/login', [AdminAuthController::class, 'showLogin'])->name('admin.login');
        Route::post('/login', [AdminAuthController::class, 'login'])->name('admin.login.submit');
    });

    // Authenticated Admin Routes
    Route::middleware('auth:admin')->group(function () {
        Route::post('/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');
        
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
        
        // Subscription Manager
        Route::resource('/subscriptions', SubscriptionController::class)->except(['create', 'show', 'edit'])->names('admin.subscriptions');

        // Profile Settings
        Route::get('/profile', [ProfileController::class, 'edit'])->name('admin.profile.edit');
        Route::put('/profile', [ProfileController::class, 'update'])->name('admin.profile.update');

        // OpenAI Settings
        Route::get('/ai-settings', [AiSettingController::class, 'index'])->name('admin.ai.manage');
        Route::post('/ai-settings', [AiSettingController::class, 'store'])->name('admin.ai.manage.save');

        // Additive planning and communication modules
        Route::get('/vendor-analytics', [VendorAnalyticsController::class, 'index'])->name('admin.vendor-analytics.index');
        Route::resource('/event-requirement-questions', EventRequirementQuestionController::class)
            ->parameters(['event-requirement-questions' => 'event_question'])
            ->names('admin.event-questions');
        Route::post('/notifications/{notification}/send', [NotificationController::class, 'send'])->name('admin.notifications.send');
        Route::resource('/notifications', NotificationController::class)->names('admin.notifications');
        Route::resource('/feedback', FeedbackController::class)->only(['index', 'show', 'update', 'destroy'])->names('admin.feedback');

        // User Management CRUD
        Route::post('/users/{id}/toggle-status', [\App\Http\Controllers\Admin\UserController::class, 'toggleStatus'])->name('admin.users.toggle-status');
        Route::resource('/users', \App\Http\Controllers\Admin\UserController::class)->except(['create', 'show', 'store'])->names('admin.users');
    });
});

// Location helper routes (Accessible publicly/by AJAX)
Route::get('/locations/states', function() {
    return response()->json(State::orderBy('name')->get());
})->name('locations.states');
Route::get('/locations/states/{state}/cities', function($stateId) {
    return response()->json(City::where('state_id', $stateId)->orderBy('name')->get());
})->name('locations.cities');
Route::get('/locations/cities/{city}/areas', function($cityId) {
    return response()->json(Area::where('city_id', $cityId)->orderBy('name')->get());
})->name('locations.areas');
Route::get('/locations/areas/{area}/subareas', function($areaId) {
    return response()->json(Subarea::where('area_id', $areaId)->orderBy('name')->get());
})->name('locations.subareas');

// User Portal Routes
Route::prefix('user')->group(function () {
    // Guest User Routes
    Route::middleware('guest:web')->group(function () {
        Route::get('/register', [UserAuthController::class, 'showRegister'])->name('user.register');
        Route::post('/register', [UserAuthController::class, 'register'])->name('user.register.submit');
        Route::get('/login', [UserAuthController::class, 'showLogin'])->name('user.login');
        Route::post('/login', [UserAuthController::class, 'login'])->name('user.login.submit');
    });

    // Authenticated User Routes
    Route::middleware('auth:web')->group(function () {
        Route::post('/logout', [UserAuthController::class, 'logout'])->name('user.logout');

        // Subscription tier choosing & payment verification
        Route::get('/subscription', [UserSubscriptionController::class, 'index'])->name('user.subscription');
        Route::post('/subscribe/verify', [UserSubscriptionController::class, 'verifyPayment'])->name('user.subscribe.verify');

        // Account pages for subscribed users
        Route::middleware('subscribed')->group(function () {
            Route::get('/profile', [UserDashboardController::class, 'profile'])->name('user.profile');
            Route::put('/profile/update', [UserDashboardController::class, 'updateProfile'])->name('user.profile.update');
            Route::put('/profile/password', [UserDashboardController::class, 'updatePassword'])->name('user.password.update');
        });
    });
});

Route::get('/', function () {
    return view('web.index');
})->name('home');
