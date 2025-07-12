<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EventRequestController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\UserAuthController;
use App\Http\Controllers\Auth\AdminAuthController;


Route::get('/', [HomeController::class, 'home'])->name('home');
Route::get('/events/propose', [EventRequestController::class, 'proposeEvent'])->name('events.propose');
Route::post('/events/propose', [EventRequestController::class, 'storeProposedEvent'])->name('events.propose');

// User Auth
Route::get('/register', [UserAuthController::class, 'showRegister'])->name('register');
Route::post('/register', [UserAuthController::class, 'register']);
Route::get('/login', [UserAuthController::class, 'showLogin'])->name('login');
Route::post('/login', [UserAuthController::class, 'login']);
Route::post('/logout', [UserAuthController::class, 'logout'])->name('logout');

// Admin Auth
Route::get('/admin/login', [AdminAuthController::class, 'showLogin'])->name('admin.login');
Route::post('/admin/login', [AdminAuthController::class, 'login']);
Route::post('/admin/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');

Route::get('/test', function () {
    return view('test.test');
});

//Admin web routes
Route::middleware(['auth:admin'])->prefix('admin')->group(function () {
    // Dashboard and Event Request Management
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/dashboard/events/requests/{id}/edit', [AdminController::class, 'editProposeEvent'])->name('admin.dashboard.events.requests.edit');
    Route::put('/dashboard/events/requests/{id}/edit', [EventRequestController::class, 'updateProposedEvent'])->name('admin.dashboard.events.requests.edit');
    Route::patch('/dashboard/events/requests/{id}/approve', [EventRequestController::class, 'approve'])->name('admin.dashboard.events.requests.approve');
    Route::patch('/dashboard/events/requests/{id}/reject', [EventRequestController::class, 'reject'])->name('admin.dashboard.events.requests.reject');
    
    // Event Management (Published Events)
    Route::get('/events', [AdminController::class, 'events'])->name('admin.events');
    
    // NEW ROUTES: Direct Event Creation and Management by Admin
    Route::get('/events/create', [AdminController::class, 'createEvent'])->name('admin.events.create');
    Route::post('/events', [AdminController::class, 'storeEvent'])->name('admin.events.store');
    Route::get('/events/{id}/edit', [AdminController::class, 'editEvent'])->name('admin.events.edit');
    Route::put('/events/{id}', [AdminController::class, 'updateEvent'])->name('admin.events.update');
    Route::delete('/events/{id}', [AdminController::class, 'deleteEvent'])->name('admin.events.delete');
});

// User-specific routes
Route::middleware(['auth:web'])->group(function () {
    Route::get('/events/saved', [HomeController::class, 'savedEvents'])->name('events.saved');
    Route::post('/events/{id}/save', [EventRequestController::class, 'saveEvent'])->name('events.save');
});