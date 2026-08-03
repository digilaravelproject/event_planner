<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\VendorController;
use App\Http\Controllers\Admin\SystemMasterController;
use App\Http\Controllers\Admin\SubscriptionController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\AiSettingController;
use App\Http\Controllers\Admin\AreaController;
use App\Http\Controllers\Vendor\VendorAuthController;
use App\Http\Controllers\Vendor\VendorDashboardController;
use App\Models\State;
use App\Models\City;
use App\Models\Area;
use App\Models\Subarea;

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
        
        // Vendor Management (Admin auditing / list view)
        Route::post('/vendors/{vendor}/toggle-status', [VendorController::class, 'toggleStatus'])->name('admin.vendors.toggle-status');
        Route::resource('/vendors', VendorController::class)->names('admin.vendors');

        // System Master Parameters
        Route::get('/system-masters', [SystemMasterController::class, 'index'])->name('admin.system-masters.index');
        Route::post('/system-masters', [SystemMasterController::class, 'store'])->name('admin.system-masters.store');
        Route::delete('/system-masters/{systemMaster}', [SystemMasterController::class, 'destroy'])->name('admin.system-masters.destroy');

        // Master Registry CRUD
        Route::post('/master-registries', [SystemMasterController::class, 'storeRegistry'])->name('admin.master-registries.store');
        Route::put('/master-registries/{id}', [SystemMasterController::class, 'updateRegistry'])->name('admin.master-registries.update');
        Route::delete('/master-registries/{id}', [SystemMasterController::class, 'destroyRegistry'])->name('admin.master-registries.destroy');

        // Subscription Manager
        Route::resource('/subscriptions', SubscriptionController::class)->except(['create', 'show', 'edit'])->names('admin.subscriptions');

        // Profile Settings
        Route::get('/profile', [ProfileController::class, 'edit'])->name('admin.profile.edit');
        Route::put('/profile', [ProfileController::class, 'update'])->name('admin.profile.update');

        // OpenAI Settings
        Route::get('/ai-settings', [AiSettingController::class, 'index'])->name('admin.ai.manage');
        Route::post('/ai-settings', [AiSettingController::class, 'store'])->name('admin.ai.manage.save');

        // Geographic Area Management (States, Cities, Areas, Subareas)
        Route::get('/areas', [AreaController::class, 'index'])->name('admin.areas.index');
        Route::post('/areas/states', [AreaController::class, 'storeState'])->name('admin.areas.storeState');
        Route::put('/areas/states/{state}', [AreaController::class, 'updateState'])->name('admin.areas.updateState');
        Route::delete('/areas/states/{state}', [AreaController::class, 'destroyState'])->name('admin.areas.destroyState');
        
        Route::post('/areas/cities', [AreaController::class, 'storeCity'])->name('admin.areas.storeCity');
        Route::put('/areas/cities/{city}', [AreaController::class, 'updateCity'])->name('admin.areas.updateCity');
        Route::delete('/areas/cities/{city}', [AreaController::class, 'destroyCity'])->name('admin.areas.destroyCity');

        Route::post('/areas/areas', [AreaController::class, 'storeArea'])->name('admin.areas.storeArea');
        Route::put('/areas/areas/{area}', [AreaController::class, 'updateArea'])->name('admin.areas.updateArea');
        Route::delete('/areas/areas/{area}', [AreaController::class, 'destroyArea'])->name('admin.areas.destroyArea');

        Route::post('/areas/subareas', [AreaController::class, 'storeSubarea'])->name('admin.areas.storeSubarea');
        Route::put('/areas/subareas/{subarea}', [AreaController::class, 'updateSubarea'])->name('admin.areas.updateSubarea');
        Route::delete('/areas/subareas/{subarea}', [AreaController::class, 'destroySubarea'])->name('admin.areas.destroySubarea');

        // Manage Distributions
        Route::resource('/distributions', \App\Http\Controllers\Admin\DistributionController::class)->only(['index', 'show', 'update', 'destroy'])->names('admin.distributions');

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

// Redirect /vendor to /vendor/dashboard
Route::redirect('/vendor', '/vendor/dashboard');

// Vendor Portal Routes
// Route::prefix('vendor')->group(function () {
//     // Guest Vendor Routes
//     Route::middleware('guest:vendor')->group(function () {
//         Route::get('/register', [VendorAuthController::class, 'showRegister'])->name('vendor.register');
//         Route::post('/register', [VendorAuthController::class, 'register'])->name('vendor.register.submit');
//         Route::get('/login', [VendorAuthController::class, 'showLogin'])->name('vendor.login');
//         Route::post('/login', [VendorAuthController::class, 'login'])->name('vendor.login.submit');
//         Route::get('/check-email', [VendorAuthController::class, 'checkEmail'])->name('vendor.check-email');
//     });

//     // Authenticated Vendor Routes
//     Route::middleware('auth:vendor')->group(function () {
//         Route::post('/logout', [VendorAuthController::class, 'logout'])->name('vendor.logout');
        
//         Route::get('/dashboard', [VendorDashboardController::class, 'index'])->name('vendor.dashboard');
//         Route::get('/business', [VendorDashboardController::class, 'editBusiness'])->name('vendor.business.edit');
//         Route::post('/business', [VendorDashboardController::class, 'updateBusiness'])->name('vendor.business.update');

//         // Registries & Budget Distribution
//         Route::get('/budget', [VendorDashboardController::class, 'editBudget'])->name('vendor.budget.edit');
//         Route::post('/budget', [VendorDashboardController::class, 'updateBudget'])->name('vendor.budget.update');

//         // Quote Requests
//         Route::get('/quote-requests', [\App\Http\Controllers\Vendor\QuoteRequestController::class, 'index'])->name('vendor.quote-requests.index');
//         Route::delete('/quote-requests/{id}', [\App\Http\Controllers\Vendor\QuoteRequestController::class, 'destroy'])->name('vendor.quote-requests.destroy');
//     });
// });

use App\Http\Controllers\User\UserAuthController;
use App\Http\Controllers\User\UserSubscriptionController;
use App\Http\Controllers\User\UserWizardController;
use App\Http\Controllers\User\UserDashboardController;

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

        // Quote Requests
        Route::post('/quote-requests', [\App\Http\Controllers\User\QuoteRequestController::class, 'store'])->name('user.quote-requests.store');

        // Wizard & Event Planner & Dashboard (Subscribed users only)
        Route::middleware('subscribed')->group(function () {
            Route::get('/dashboard', [UserDashboardController::class, 'dashboard'])->name('user.dashboard');
            Route::get('/plans', [UserDashboardController::class, 'plans'])->name('user.plans');
            Route::post('/plans/{id}/duplicate', [UserDashboardController::class, 'duplicatePlan'])->name('user.plans.duplicate');
            Route::delete('/plans/{id}', [UserDashboardController::class, 'deletePlan'])->name('user.plans.delete');
            Route::get('/profile', [UserDashboardController::class, 'profile'])->name('user.profile');
            Route::put('/profile/update', [UserDashboardController::class, 'updateProfile'])->name('user.profile.update');
            Route::put('/profile/password', [UserDashboardController::class, 'updatePassword'])->name('user.password.update');
            
            Route::get('/wizard', [UserWizardController::class, 'index'])->name('user.wizard');
            Route::post('/wizard/generate', [UserWizardController::class, 'generatePlan'])->name('user.wizard.generate');
            Route::get('/summary/{id}', [UserWizardController::class, 'showSummary'])->name('user.summary');
        });
    });
});

Route::get('/', function () {
    return view('web.index');
})->name('home');
