<?php

use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AiSettingController;
use App\Http\Controllers\Admin\EventRequirementQuestionController;
use App\Http\Controllers\Admin\LandingContentController;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\StaffController;
use App\Http\Controllers\Admin\SubscriptionController;
use App\Http\Controllers\Admin\TransactionController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\UserPlanController;
use App\Http\Controllers\Admin\UserQueryController as AdminUserQueryController;
use App\Http\Controllers\Admin\VendorAnalyticsController;
use App\Http\Controllers\AiPlannerController;
use App\Http\Controllers\PublicQueryController;
use App\Http\Controllers\User\UserAuthController;
use App\Http\Controllers\User\UserDashboardController;
use App\Http\Controllers\User\UserNotificationController;
use App\Http\Controllers\User\UserQueryController;
use App\Http\Controllers\User\UserSubscriptionController;
use App\Http\Controllers\Vendor\VendorAuthController;
use App\Http\Controllers\Vendor\VendorPanelController;
use App\Models\EventRequirementQuestion;
use App\Models\LandingContent;
use App\Support\AdminMenu;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;

Route::get('/send-mail', function () {

    $to = 'darshankondekar01@gmail.com';

    Mail::raw('This is a sample test email from the Event Planner project.', function ($message) use ($to) {
        $message->to($to)
            ->subject('Test Email - Event Planner');
    });

    return 'Email sent successfully!';
});

Route::get('/admin', function () {
    $admin = auth('admin')->user();

    return $admin
        ? redirect(AdminMenu::firstRouteFor($admin))
        : redirect()->route('admin.login');
});

Route::prefix('admin')->group(function () {
    // Guest Admin Routes
    Route::middleware('guest:admin')->group(function () {
        Route::get('/login', [AdminAuthController::class, 'showLogin'])->name('admin.login');
        Route::post('/login', [AdminAuthController::class, 'login'])->name('admin.login.submit');
    });

    // Authenticated Admin Routes
    Route::middleware(['auth:admin', 'admin.active'])->group(function () {
        Route::post('/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');

        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->middleware('admin.permission:dashboard')->name('admin.dashboard');

        Route::post('/staff/{staff}/toggle', [StaffController::class, 'toggle'])->middleware('admin.permission:staff')->name('admin.staff.toggle');
        Route::resource('/staff', StaffController::class)->except('show')->middleware('admin.permission:staff')->names('admin.staff');

        // Subscription Manager
        Route::resource('/subscriptions', SubscriptionController::class)->except(['create', 'show', 'edit'])->middleware('admin.permission:subscriptions')->names('admin.subscriptions');
        Route::get('/transactions/export/pdf', [TransactionController::class, 'exportPdf'])->middleware('admin.permission:transactions')->name('admin.transactions.export.pdf');
        Route::get('/transactions/export/excel', [TransactionController::class, 'exportExcel'])->middleware('admin.permission:transactions')->name('admin.transactions.export.excel');
        Route::get('/transactions', [TransactionController::class, 'index'])->middleware('admin.permission:transactions')->name('admin.transactions.index');
        Route::get('/transactions/{transaction}', [TransactionController::class, 'show'])->middleware('admin.permission:transactions')->name('admin.transactions.show');

        // Profile Settings
        Route::get('/profile', [ProfileController::class, 'edit'])->name('admin.profile.edit');
        Route::put('/profile', [ProfileController::class, 'update'])->name('admin.profile.update');

        // OpenRouter Settings
        Route::get('/ai-settings', [AiSettingController::class, 'index'])->middleware('admin.permission:ai_settings')->name('admin.ai.manage');
        Route::post('/ai-settings', [AiSettingController::class, 'store'])->middleware('admin.permission:ai_settings')->name('admin.ai.manage.save');

        // Additive planning and communication modules
        Route::get('/vendor-analytics', [VendorAnalyticsController::class, 'index'])->middleware('admin.permission:vendor_analytics')->name('admin.vendor-analytics.index');
        Route::resource('/event-requirement-questions', EventRequirementQuestionController::class)
            ->parameters(['event-requirement-questions' => 'event_question'])
            ->middleware('admin.permission:event_questions')
            ->names('admin.event-questions');
        Route::post('/notifications/{notification}/send', [NotificationController::class, 'send'])->middleware('admin.permission:notifications')->name('admin.notifications.send');
        Route::resource('/notifications', NotificationController::class)->middleware('admin.permission:notifications')->names('admin.notifications');
        Route::resource('/pages', PageController::class)->middleware('admin.permission:pages')->names('admin.pages');
        Route::prefix('/landing-content/{type}')->whereIn('type', array_keys(LandingContent::TYPES))->middleware('admin.permission:pages')->group(function () {
            Route::get('/', [LandingContentController::class, 'index'])->name('admin.landing-content.index');
            Route::get('/create', [LandingContentController::class, 'create'])->name('admin.landing-content.create');
            Route::post('/', [LandingContentController::class, 'store'])->name('admin.landing-content.store');
            Route::get('/{landingContent}/edit', [LandingContentController::class, 'edit'])->name('admin.landing-content.edit');
            Route::put('/{landingContent}', [LandingContentController::class, 'update'])->name('admin.landing-content.update');
            Route::delete('/{landingContent}', [LandingContentController::class, 'destroy'])->name('admin.landing-content.destroy');
        });
        // User Management CRUD
        Route::get('/users/export/pdf', [UserController::class, 'exportPdf'])->middleware('admin.permission:users')->name('admin.users.export.pdf');
        Route::get('/users/export/excel', [UserController::class, 'exportExcel'])->middleware('admin.permission:users')->name('admin.users.export.excel');
        Route::post('/users/{id}/toggle-status', [UserController::class, 'toggleStatus'])->middleware('admin.permission:users')->name('admin.users.toggle-status');
        Route::get('/users/{user}/plans', [UserPlanController::class, 'index'])->middleware('admin.permission:users')->name('admin.users.plans.index');
        Route::get('/user-plans/{plan}', [UserPlanController::class, 'show'])->middleware('admin.permission:users')->name('admin.users.plans.show');
        Route::get('/user-plans/{plan}/download', [UserPlanController::class, 'download'])->middleware('admin.permission:users')->name('admin.users.plans.download');
        Route::resource('/users', UserController::class)->except(['create', 'show', 'store'])->middleware('admin.permission:users')->names('admin.users');
        Route::post('/user-queries/{query}/reply', [AdminUserQueryController::class, 'reply'])->middleware('admin.permission:user_queries')->name('admin.user-queries.reply');
        Route::resource('/user-queries', AdminUserQueryController::class)->only(['index', 'update', 'destroy'])->parameters(['user-queries' => 'query'])->middleware('admin.permission:user_queries')->names('admin.user-queries');
    });
});

// Location helper routes (Accessible publicly/by AJAX)
Route::get('/locations/states', function () {
    return response()->json(State::orderBy('name')->get());
})->name('locations.states');
Route::get('/locations/states/{state}/cities', function ($stateId) {
    return response()->json(City::where('state_id', $stateId)->orderBy('name')->get());
})->name('locations.cities');
Route::get('/locations/cities/{city}/areas', function ($cityId) {
    return response()->json(Area::where('city_id', $cityId)->orderBy('name')->get());
})->name('locations.areas');
Route::get('/locations/areas/{area}/subareas', function ($areaId) {
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
        Route::post('/subscribe/order', [UserSubscriptionController::class, 'createOrder'])->name('user.subscribe.order');
        Route::post('/subscribe/verify', [UserSubscriptionController::class, 'verifyPayment'])->name('user.subscribe.verify');
        Route::get('/profile', [UserDashboardController::class, 'profile'])->name('user.profile');
        Route::put('/profile/update', [UserDashboardController::class, 'updateProfile'])->name('user.profile.update');
        Route::put('/profile/password', [UserDashboardController::class, 'updatePassword'])->name('user.password.update');

        Route::middleware('subscribed')->group(function () {
            Route::get('/dashboard', [UserDashboardController::class, 'dashboard'])->name('user.dashboard');
            Route::get('/plans', [AiPlannerController::class, 'history'])->name('user.plans.index');
            Route::get('/plans/{plan}/edit', [AiPlannerController::class, 'edit'])->name('user.plans.edit');
            Route::put('/plans/{plan}', [AiPlannerController::class, 'update'])->name('user.plans.update');
            Route::post('/plans/{plan}/share', [AiPlannerController::class, 'share'])->name('user.plans.share');
            Route::post('/plans/{plan}/suggestions', [AiPlannerController::class, 'refreshSuggestions'])->name('user.plans.suggestions.refresh');
            Route::get('/plans/{plan}', [AiPlannerController::class, 'show'])->name('user.plans.show');
            Route::get('/plans/{plan}/download', [AiPlannerController::class, 'download'])->name('user.plans.download');
            Route::get('/planner/resume', [AiPlannerController::class, 'resume'])->name('ai-planner.resume');
            Route::get('/notifications', [UserNotificationController::class, 'index'])->name('user.notifications.index');
            Route::patch('/notifications/read-all', [UserNotificationController::class, 'readAll'])->name('user.notifications.read-all');
            Route::patch('/notifications/{notification}/read', [UserNotificationController::class, 'read'])->name('user.notifications.read');
            Route::get('/queries', [UserQueryController::class, 'index'])->name('user.queries.index');
            Route::post('/queries', [UserQueryController::class, 'store'])->name('user.queries.store');
            Route::put('/queries/{query}', [UserQueryController::class, 'update'])->name('user.queries.update');
            Route::delete('/queries/{query}', [UserQueryController::class, 'destroy'])->name('user.queries.destroy');
        });
    });
});

// Vendor Portal Routes
Route::prefix('vendor')->name('vendor.')->group(function () {
    Route::middleware('guest:vendor')->group(function () {
        Route::get('/register', [VendorAuthController::class, 'showRegister'])->name('register');
        Route::post('/register', [VendorAuthController::class, 'register'])->name('register.submit');
        Route::get('/login', [VendorAuthController::class, 'showLogin'])->name('login');
        Route::post('/login', [VendorAuthController::class, 'login'])->name('login.submit');
    });

    Route::middleware('auth:vendor')->group(function () {
        Route::post('/logout', [VendorAuthController::class, 'logout'])->name('logout');
        Route::get('/dashboard', [VendorPanelController::class, 'dashboard'])->name('dashboard');
        Route::get('/profile', [VendorPanelController::class, 'profile'])->name('profile');
        Route::put('/profile', [VendorPanelController::class, 'updateProfile'])->name('profile.update');
        Route::put('/password', [VendorPanelController::class, 'updatePassword'])->name('password.update');
        Route::get('/businesses/attribute-sheet/sample', [VendorPanelController::class, 'downloadAttributeSample'])->name('vendors.attribute-sheet.sample');
        Route::post('/businesses/attribute-sheet/import', [VendorPanelController::class, 'importAttributes'])->name('vendors.attribute-sheet.import');
        Route::resource('/businesses', VendorPanelController::class)
            ->parameters(['businesses' => 'dynamic_vendor'])
            ->names('vendors')
            ->except(['dashboard', 'profile']);
    });
});

Route::get('/', function () {
    $content = fn (string $type) => Schema::hasTable('landing_contents')
        ? LandingContent::where('type', $type)->published()->get()
        : collect();

    $guestQuestion = Schema::hasTable('event_requirement_questions')
        ? EventRequirementQuestion::enabled()->where('question_code', 'guest_capacity')->first()
        : null;
    $guestOptions = collect($guestQuestion?->options ?: ['50', '150', '300', '600'])
        ->mapWithKeys(function ($value): array {
            $label = trim((string) $value);
            preg_match_all('/\d+/', $label, $matches);
            $number = (int) (end($matches[0]) ?: 0);
            $label = preg_match('/guest/i', $label) ? $label : $label.' Guests';

            return [(string) $number => $label];
        })->filter(fn ($label, $value) => (int) $value > 0);
    $categoryQuestion = Schema::hasTable('event_requirement_questions')
        ? EventRequirementQuestion::enabled()->where('question_code', 'event_category')->first()
        : null;
    $eventCategoryOptions = collect($categoryQuestion?->options ?: ['Grand Wedding & Sangeet'])
        ->map(fn ($value): string => trim((string) $value))
        ->filter()
        ->mapWithKeys(fn (string $value): array => [$value => $value]);

    return view('web.index', [
        'howItWorks' => $content('how-it-works'),
        'comparisons' => $content('comparisons'),
        'testimonials' => $content('testimonials'),
        'guestOptions' => $guestOptions,
        'eventCategoryOptions' => $eventCategoryOptions,
    ]);
})->name('home');

Route::get('/ai-planner', [AiPlannerController::class, 'index'])->name('ai-planner');
Route::post('/ai-planner/generate', [AiPlannerController::class, 'generate'])->name('ai-planner.generate');
Route::post('/queries', [PublicQueryController::class, 'store'])->name('queries.store');
