<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\BookAPIController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\DatabaseDiagnosticController;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Database Diagnostic API (chỉ khi debug mode)
if (config('app.debug')) {
    Route::prefix('db')->group(function () {
        Route::get('/config', [DatabaseDiagnosticController::class, 'checkConfig']);
        Route::get('/test-connection', [DatabaseDiagnosticController::class, 'testConnection']);
        Route::get('/data-stats', [DatabaseDiagnosticController::class, 'dataStats']);
        Route::get('/migrations', [DatabaseDiagnosticController::class, 'checkMigrations']);
        Route::get('/diagnostics', [DatabaseDiagnosticController::class, 'diagnostics']);
    });
}

// Public API routes với rate limiting
Route::middleware('throttle:60,1')->group(function () {
    // Books API
    Route::get('/books', [BookAPIController::class, 'index']);
    Route::get('/books/search', [BookAPIController::class, 'search']);
    Route::get('/books/{id}', [BookAPIController::class, 'show']);
    
    // Protected routes - yêu cầu authentication (có thể thêm sau)
    Route::post('/books', [BookAPIController::class, 'store']);
    Route::put('/books/{id}', [BookAPIController::class, 'update']);
    Route::delete('/books/{id}', [BookAPIController::class, 'destroy']);
    
    // AI Chatbot API
    Route::prefix('chat')->group(function () {
        Route::post('/message', [ChatController::class, 'sendMessage']);
        Route::get('/suggestions', [ChatController::class, 'getSuggestions']);
        Route::post('/clear-history', [ChatController::class, 'clearHistory']);
    });
});

// Endpoint Webhook Ngân Hàng (Nhận Auto Topup)
Route::post('/webhooks/bank', [\App\Http\Controllers\API\WebhookController::class, 'handleBankTransfer']);
