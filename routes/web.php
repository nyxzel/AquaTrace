<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminPortController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\AdminVesselController;
use App\Http\Controllers\AdminReportController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\UserVesselRegisterController;
use App\Http\Controllers\AdminEventController;
use App\Http\Controllers\UserHomeController;
use App\Http\Controllers\UserReportController;
use App\Http\Controllers\UserNewsController;
use App\Http\Controllers\UserVesselController;
use App\Http\Controllers\NewsLandingController;
use App\Http\Controllers\AnalyticsLandingController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\UserContactController;





Route::get('/welcome', function () {
    return view('welcome');
});



// ===========================
// PUBLIC ROUTES
// ===========================

// Landing pages
Route::get('/', function () {
    return view('webLanding');
})->name('home');

Route::get('/vessel_landing', function () {
    return view('vesselLanding');
})->name('vessel.landing');

Route::get('/ports_landing', function () {
    return view('portsLanding');
})->name('ports.landing');

Route::get('/news', [NewsLandingController::class, 'index'])->name('news.landing');

Route::get('/about_landing', function () {
    return view('aboutLanding');
})->name('about.landing');

Route::get('/contact', [ContactController::class, 'index'])->name('contact.landing');
Route::post('/contact/send', [ContactController::class, 'send'])->name('contact.send');

Route::get('/analytics_landing', [AnalyticsLandingController::class, 'index'])->name('analytics.landing');





// ===========================
// AUTHENTICATION ROUTES
// ===========================

Route::get('/login', [AuthController::class, 'showLoginPage'])->name('login');
Route::post('/owner-login', [AuthController::class, 'ownerLogin'])->name('owner.login');
Route::post('/admin-login', [AuthController::class, 'adminLogin'])->name('admin.login');

// Registration routes
Route::get('/register', [RegistrationController::class, 'showInitialForm'])->name('register');
Route::get('/complete-registration', [RegistrationController::class, 'showCompleteForm'])->name('register.complete');
Route::post('/register-process', [RegistrationController::class, 'processRegistration'])->name('register.process');
Route::post('/store-registration-data', [RegistrationController::class, 'storeRegistrationData'])->name('register.store.data');
Route::post('/check-email', [RegistrationController::class, 'checkEmail'])->name('check.email');
Route::post('/generate-username', [RegistrationController::class, 'generateUsername'])->name('generate.username');

// Logout
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');



// ===========================
// ADMIN ROUTES
// ===========================

Route::prefix('admin')->middleware(['auth', 'admin'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [AdminDashboardController::class, 'dashboard'])->name('admin.dashboard.index');

    // Vessel Management
    Route::get('/vessel-positions', [AdminDashboardController::class, 'getVesselPositions'])->name('admin.vesselPositions');
    Route::post('/vessel/approve', [AdminDashboardController::class, 'approveAndSavePosition'])->name('admin.vessel.approve');
    Route::post('/vessel/reject', [AdminDashboardController::class, 'rejectVesselRegistration'])->name('admin.vessel.reject');
    Route::post('/save-position', [AdminDashboardController::class, 'savePosition'])->name('admin.savePosition');
    Route::post('/update-vessel-position', [AdminDashboardController::class, 'updateVesselPosition'])->name('admin.updateVesselPosition');
    Route::post('/delete-vessel', [AdminDashboardController::class, 'deleteVessel'])->name('admin.deleteVessel');
    Route::get('/vessel/{vessel_id}', [AdminDashboardController::class, 'getVesselDetails'])->name('admin.vesselDetails');

    // Ports
    Route::get('/ports', [AdminPortController::class, 'index'])->name('admin.ports.index');
    Route::post('/ports/add', [AdminPortController::class, 'store'])->name('admin.ports.store');
    Route::post('/ports/archive', [AdminPortController::class, 'archive'])->name('admin.ports.archive');

    // Users
    Route::get('/users', [AdminUserController::class, 'index'])->name('admin.users.index');
    Route::post('/users', [AdminUserController::class, 'store'])->name('admin.users.store');
    Route::put('/users/{owner}', [AdminUserController::class, 'update'])->name('admin.users.update');
    Route::delete('/users/{owner}', [AdminUserController::class, 'destroy'])->name('admin.users.destroy');

    // Vessels
    Route::get('/vessels', [AdminVesselController::class, 'index'])->name('admin.vessels.index');
    Route::post('/vessels', [AdminVesselController::class, 'store'])->name('admin.vessels.store');
    Route::put('/vessels/{vessel}', [AdminVesselController::class, 'update'])->name('admin.vessels.update');
    Route::delete('/vessels/{vessel}', [AdminVesselController::class, 'destroy'])->name('admin.vessels.destroy');

    // Reports
    Route::get('/reports', [AdminReportController::class, 'index'])->name('admin.reports.index');
    Route::post('/reports/add', [AdminReportController::class, 'store'])->name('admin.reports.store');
    Route::post('/reports/update', [AdminReportController::class, 'update'])->name('admin.reports.update');
    Route::post('/reports/archive', [AdminReportController::class, 'archive'])->name('admin.reports.archive');

    // Event/News
    Route::get('/news', [AdminEventController::class, 'index'])->name('admin.news.index');
    Route::post('/news/store', [AdminEventController::class, 'store'])->name('admin.news.store');
    Route::get('/news/fetch', [AdminEventController::class, 'fetch'])->name('admin.news.fetch');
    Route::delete('/news/{id}', [AdminEventController::class, 'destroy'])->name('admin.news.destroy');
});


// ===========================
// USER ROUTES
// ===========================

Route::prefix('user')->middleware(['auth', 'owner'])->group(function () {

    // Reports
    Route::get('/reports', [UserReportController::class, 'index'])->name('user.reports');
    Route::post('/reports/store', [UserReportController::class, 'store'])->name('user.reports.store');
    Route::get('/reports/{id}', [UserReportController::class, 'show'])->name('user.reports.show');
    Route::delete('/reports/{id}', [UserReportController::class, 'destroy'])->name('user.reports.destroy');

    // Vessel Registration (Pending Vessels)
    Route::get('/register-vessel', [UserVesselRegisterController::class, 'showRegistrationForm'])->name('user.register.boat');
    Route::post('/vessel/register', [UserVesselRegisterController::class, 'submitRegistration'])->name('user.vessel.register');
    Route::get('/vessels', [UserVesselRegisterController::class, 'getUserVessels'])->name('user.vessels.list');
    Route::get('/vessel/registration/{request_id}/status', [UserVesselRegisterController::class, 'getRegistrationStatus'])->name('user.registrationStatus');
    Route::put('/vessel/registration/{request_id}', [UserVesselRegisterController::class, 'updateRegistration'])->name('user.vessel.update');
    Route::delete('/vessel/registration/{request_id}', [UserVesselRegisterController::class, 'cancelRegistration'])->name('user.vessel.delete');

    // VESSELS PAGE - View APPROVED vessels with tracking

    Route::get('/vessels-tracking', [UserVesselController::class, 'index'])->name('user.vessels');
    Route::post('/vessels/{vesselId}/update-location', [UserVesselController::class, 'updateLocation'])->name('user.vessels.update-location');
    Route::get('/vessels/{vesselId}/history', [UserVesselController::class, 'getHistory'])->name('user.vessels.history');
    Route::get('/vessels/map-data', [UserVesselController::class, 'getMapData'])->name('user.vessels.map-data');


    // News
    Route::get('/news', [UserNewsController::class, 'index'])->name('user.news');

    // Other user pages
    Route::get('/ports', function () {
        return view('user_ports');
    })->name('user.ports');
    Route::get('/about', function () {
        return view('user_about');
    })->name('user.about');
    Route::get('/contact', function () {
        return view('user_contact');
    })->name('user.contact');

    // Home - PUT THIS LAST as it's the most generic
    Route::get('/home', [UserHomeController::class, 'index'])->name('user.home');
});


// User registration route (duplicate - keeping for compatibility)
Route::post('/vessel/register', [UserVesselRegisterController::class, 'submitRegistration'])
    ->middleware('auth')
    ->name('user.vessel.register');

// Admin approval route (duplicate - keeping for compatibility)
Route::post('/vessel/approve', [AdminDashboardController::class, 'approveAndSavePosition'])
    ->middleware('auth')
    ->name('admin.vessel.approve');

// Admin rejection route (duplicate - keeping for compatibility)
Route::post('/vessel/reject', [AdminDashboardController::class, 'rejectVesselRegistration'])
    ->middleware('auth')
    ->name('admin.vessel.reject');
