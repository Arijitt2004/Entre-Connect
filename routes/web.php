<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\ConnectionController;

// Welcome / Root redirect
Route::get('/', function () {
    return auth()->check() ? redirect()->route('dashboard') : redirect()->route('login');
});

// Diagnostic Route for Render deployment
Route::get('/test-db', function () {
    try {
        $connection = \Illuminate\Support\Facades\DB::connection('mongodb');
        $db = $connection->getMongoDB();
        return response()->json([
            'status' => 'success',
            'message' => 'Successfully connected to MongoDB Atlas from Render!',
            'database_name' => $db->getDatabaseName(),
            'collections' => iterator_to_array($db->listCollectionNames())
        ]);
    } catch (\Throwable $e) {
        return response()->json([
            'status' => 'error',
            'message' => 'Database Connection Failed on Render!',
            'error_class' => get_class($e),
            'error_details' => $e->getMessage()
        ]);
    }
});

// Guest Auth Routes
Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

// Authenticated Routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    // Discovery Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Connection Management
    Route::post('/connect/{userId}', [ConnectionController::class, 'connect'])->name('connect');
    Route::post('/connect/accept/{connectionId}', [ConnectionController::class, 'accept'])->name('connect.accept');
    Route::post('/connect/decline/{connectionId}', [ConnectionController::class, 'decline'])->name('connect.decline');
    
    // Direct Messaging
    Route::get('/messages', [MessageController::class, 'index'])->name('messages');
    Route::get('/messages/thread/{userId}', [MessageController::class, 'showThread'])->name('messages.thread');
    Route::post('/messages/send', [MessageController::class, 'send'])->name('messages.send');
    
    // Profile Management
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::post('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
});
