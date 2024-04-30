<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LeaderboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ExperienceController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;

// Public routes accessible without authentication
Route::post('/register', [RegisteredUserController::class, 'store']);
Route::get('/leaderboard/{tenantId}', [LeaderboardController::class, 'index']); // Move this route outside the middleware group

// Authenticated routes with tenant scoping middleware
Route::middleware(['auth:sanctum', 'tenant'])->group(function () {
    // Protected routes accessible only with authentication and proper tenant scope
    Route::post('/users/award_xp', [ExperienceController::class, 'awardExperience']);
});
