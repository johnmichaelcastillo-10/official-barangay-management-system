<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ResidentController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserManagementController;
use App\Http\Controllers\DocumentRequestController;

// Welcome page
Route::get('/', function () {
    return view('welcome');
})->name('welcome');

// Public routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');

//Public Document Request Form
Route::get('/barangay-document-request', [DocumentRequestController::class, 'create'])->name('document-requests.request');
Route::post('/barangay-document-request', [DocumentRequestController::class, 'store'])->name('document-request.store');

// NEW route for fetching resident ID by name and birthdate
Route::get('/residents/fetch-id-by-name-and-birthdate', [ResidentController::class, 'getIdByNameAndBirthdate'])->name('residents.get-id-by-name-and-birthdate');

// REMOVED: Old email-based fetch route
// Route::get('/residents/fetch-id', [ResidentController::class, 'getResidentIdByEmail'])->name('residents.get-id');


Route::get('/public-registration', [ResidentController::class, 'showPublicRegistrationForm'])->name('public.residents.register');
Route::post('/public-registration', [ResidentController::class, 'storePublic'])->name('public.residents.store');

// Public registration tracking routes (no auth required)
Route::get('/track-registration', [ResidentController::class, 'trackRegistration'])->name('residents.track');
Route::post('/track-registration', [ResidentController::class, 'trackRegistrationResult'])->name('residents.track.result');

// Public document tracking routes (no auth required)
Route::get('/track-document', [DocumentRequestController::class, 'track'])->name('document-requests.track');
Route::post('/track-document', [DocumentRequestController::class, 'trackResult'])->name('document-requests.track.result');

// Protected routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Dashboard routes
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/chairman', [DashboardController::class, 'chairman'])->name('dashboard.chairman');
    Route::get('/dashboard/secretary', [DashboardController::class, 'secretary'])->name('dashboard.secretary');
    Route::get('/dashboard/staff', [DashboardController::class, 'staff'])->name('dashboard.staff');

    // Resident registration routes (admin/staff version)
    Route::get('/residents/register', [ResidentController::class, 'showRegistrationForm'])->name('residents.register');
    Route::post('/residents/register', [ResidentController::class, 'store'])->name('residents.store');

    // Resident routes - ORDER IS IMPORTANT!
    Route::get('/residents', [ResidentController::class, 'index'])->name('residents.index');
    Route::get('/residents/pending', [ResidentController::class, 'pendingRegistrations'])->name('residents.pending');

    // NEW: Rejected registrations routes (Chairman and Secretary only)
    Route::get('/residents/rejected', [ResidentController::class, 'rejectedRegistrations'])->name('residents.rejected');
    Route::delete('/residents/rejected/{rejectedRegistration}', [ResidentController::class, 'destroyRejected'])->name('residents.rejected.destroy');
    Route::post('/residents/rejected/cleanup', [ResidentController::class, 'cleanupRejected'])->name('residents.rejected.cleanup');

    // Individual resident routes
    Route::get('/residents/{resident}', [ResidentController::class, 'show'])->name('residents.show');
    Route::get('/residents/{resident}/edit', [ResidentController::class, 'edit'])->name('residents.edit');
    Route::put('/residents/{resident}', [ResidentController::class, 'update'])->name('residents.update');
    Route::delete('/residents/{resident}', [ResidentController::class, 'destroy'])->name('residents.destroy');

    // Resident approval/rejection routes (Chairman and Secretary only)
    Route::patch('/residents/{resident}/approve', [ResidentController::class, 'approve'])->name('residents.approve');
    Route::patch('/residents/{resident}/reject', [ResidentController::class, 'reject'])->name('residents.reject');

    // Document Request routes (Chairman and Secretary only)
    Route::get('/document-requests', [DocumentRequestController::class, 'index'])->name('document-requests.index');
    Route::get('/document-requests/create', [DocumentRequestController::class, 'create'])->name('document-requests.create');
    Route::post('/document-requests', [DocumentRequestController::class, 'store'])->name('document-requests.store');
    Route::get('/document-requests/{documentRequest}', [DocumentRequestController::class, 'show'])->name('document-requests.show');
    Route::get('/document-requests/{documentRequest}/edit', [DocumentRequestController::class, 'edit'])->name('document-requests.edit');
    Route::put('/document-requests/{documentRequest}', [DocumentRequestController::class, 'update'])->name('document-requests.update');
    Route::delete('/document-requests/{documentRequest}', [DocumentRequestController::class, 'destroy'])->name('document-requests.destroy');

    // Document Request Status Update routes
    Route::patch('/document-requests/{documentRequest}/process', [DocumentRequestController::class, 'process'])->name('document-requests.process');
    Route::patch('/document-requests/{documentRequest}/reject', [DocumentRequestController::class, 'reject'])->name('document-requests.reject');
    Route::patch('/document-requests/{documentRequest}/ready', [DocumentRequestController::class, 'ready'])->name('document-requests.ready');
    Route::patch('/document-requests/{documentRequest}/release', [DocumentRequestController::class, 'release'])->name('document-requests.release');
    Route::get('/document-requests/{documentRequest}/download', [DocumentRequestController::class, 'download'])->name('document-requests.download');

    //Certificate Issuance
    Route::get('/certificate-issuance', [DocumentRequestController::class, 'certificateIndex'])->name('certificate-issuance.index');

    // User Management Routes (Secretary Only)
    // Using full class path instead of middleware alias
    Route::middleware([\App\Http\Middleware\SecretaryMiddleware::class])->group(function () {
        Route::get('/users', [UserManagementController::class, 'index'])->name('users.index');
        Route::get('/users/create', [UserManagementController::class, 'create'])->name('users.create');
        Route::post('/users', [UserManagementController::class, 'store'])->name('users.store');
        Route::get('/users/{user}/edit', [UserManagementController::class, 'edit'])->name('users.edit');
        Route::put('/users/{user}', [UserManagementController::class, 'update'])->name('users.update');
        Route::delete('/users/{user}', [UserManagementController::class, 'destroy'])->name('users.destroy');
    });
});
