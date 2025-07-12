<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\EventController;
use App\Http\Controllers\API\AdminController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Public routes
Route::prefix('v1')->group(function () {
    // Authentication routes
    Route::prefix('auth')->group(function () {
        Route::post('/register', [AuthController::class, 'register']);
        Route::post('/login', [AuthController::class, 'login']);
        Route::post('/admin/login', [AuthController::class, 'adminLogin']);
    });

    // Public event routes
    Route::prefix('events')->group(function () {
        Route::get('/', [EventController::class, 'index']);
        Route::get('/{id}', [EventController::class, 'show']);
        Route::post('/propose', [EventController::class, 'propose']);
    });
});

// Protected User routes
Route::prefix('v1')->middleware('auth:sanctum')->group(function () {
    // User authentication
    Route::prefix('auth')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/user', [AuthController::class, 'user']);
    });

    // User event actions
    Route::prefix('events')->group(function () {
        Route::post('/{id}/save', [EventController::class, 'saveEvent']);
        Route::delete('/{id}/unsave', [EventController::class, 'unsaveEvent']);
        Route::get('/saved', [EventController::class, 'savedEvents']);
    });
});

// Protected Admin routes
Route::prefix('v1/admin')->middleware(['auth:sanctum', 'admin'])->group(function () {
    // Event requests management
    Route::prefix('event-requests')->group(function () {
        Route::get('/', [AdminController::class, 'getPendingRequests']);
        Route::get('/{id}', [AdminController::class, 'getEventRequest']);
        Route::put('/{id}', [AdminController::class, 'updateEventRequest']);
        Route::patch('/{id}/approve', [AdminController::class, 'approveEventRequest']);
        Route::delete('/{id}/reject', [AdminController::class, 'rejectEventRequest']);
    });

    // Event management
    Route::prefix('events')->group(function () {
        Route::get('/', [AdminController::class, 'getEvents']);
        Route::post('/', [AdminController::class, 'createEvent']);
        Route::get('/{id}', [AdminController::class, 'getEvent']);
        Route::put('/{id}', [AdminController::class, 'updateEvent']);
        Route::delete('/{id}', [AdminController::class, 'deleteEvent']);
    });

    // Dashboard stats
    Route::get('/stats', [AdminController::class, 'getStats']);
});