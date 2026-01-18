<?php

use App\Http\Controllers\Admin\AutoReplyConfigController;
use App\Http\Controllers\Admin\BotInstanceController;
use App\Http\Controllers\Admin\ChatBotController;
use App\Http\Controllers\Admin\ChatConfigController;
use App\Http\Controllers\Admin\ConversationLogController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DocumentValidationController;
use App\Http\Controllers\Admin\NlpConfigController;
use App\Http\Controllers\Admin\NlpLogController;
use App\Http\Controllers\Admin\ServiceRequestController;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\Admin\WhatsAppUserController;
use App\Http\Controllers\ChatDemoController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\WelcomeController;
use Illuminate\Support\Facades\Route;

Route::get('/', [WelcomeController::class, 'index'])->name('welcome');

// Public Chat Demo Routes (no authentication required)
Route::prefix('chat-demo')->name('chat-demo.')->group(function () {
    Route::get('/', [ChatDemoController::class, 'index'])->name('index');
    Route::post('/sessions', [ChatDemoController::class, 'createSession'])->name('sessions.create');
    Route::post('/messages', [ChatDemoController::class, 'sendMessage'])->name('messages.send');
    Route::get('/sessions/{sessionId}/messages', [ChatDemoController::class, 'getMessages'])->name('messages.get');
    Route::post('/reset', [ChatDemoController::class, 'resetSession'])->name('reset');
});

Route::get('/dashboard', function () {
    return redirect()->route('admin.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'role:admin,officer,viewer'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Chat Bot Testing routes
    Route::prefix('chatbot')->name('chatbot.')->group(function () {
        Route::get('/', [ChatBotController::class, 'index'])->name('index');
        Route::post('/sessions', [ChatBotController::class, 'createSession'])->name('sessions.create');
        Route::get('/sessions', [ChatBotController::class, 'getSessions'])->name('sessions.list');
        Route::post('/messages', [ChatBotController::class, 'sendMessage'])->name('messages.send');
        Route::get('/sessions/{sessionId}/messages', [ChatBotController::class, 'getMessages'])->name('messages.get');
        Route::put('/sessions/{sessionId}', [ChatBotController::class, 'updateSession'])->name('sessions.update');
        Route::delete('/sessions/{sessionId}', [ChatBotController::class, 'deleteSession'])->name('sessions.delete');
    });

    Route::resource('conversations', ConversationLogController::class)->only(['index', 'show']);

    Route::resource('service-requests', ServiceRequestController::class)->only(['index', 'show']);
    Route::middleware('role:admin,officer')->group(function () {
        Route::post('service-requests/{serviceRequest}/status', [ServiceRequestController::class, 'updateStatus'])->name('service-requests.update-status');
        Route::post('service-requests/{serviceRequest}/assign', [ServiceRequestController::class, 'assign'])->name('service-requests.assign');
        Route::post('service-requests/{serviceRequest}/escalate', [ServiceRequestController::class, 'escalate'])->name('service-requests.escalate');
        Route::post('service-requests/{serviceRequest}/note', [ServiceRequestController::class, 'addNote'])->name('service-requests.add-note');
    });

    Route::resource('documents', DocumentValidationController::class)->only(['index', 'show']);
    Route::middleware('role:admin,officer')->group(function () {
        Route::post('documents/{document}/validate', [DocumentValidationController::class, 'validate'])->name('documents.validate');
    });
    Route::get('documents/{document}/download', [DocumentValidationController::class, 'download'])->name('documents.download');

    Route::resource('whatsapp-users', WhatsAppUserController::class)->only(['index', 'show']);
    Route::middleware('role:admin,officer')->group(function () {
        Route::post('whatsapp-users/{whatsappUser}/verify', [WhatsAppUserController::class, 'verify'])->name('whatsapp-users.verify');
        Route::post('whatsapp-users/{whatsappUser}/status', [WhatsAppUserController::class, 'updateStatus'])->name('whatsapp-users.update-status');
    });

    Route::middleware('role:admin')->group(function () {
        Route::resource('users', UserManagementController::class);
        Route::post('users/{user}/toggle-active', [UserManagementController::class, 'toggleActive'])->name('users.toggle-active');

        // Bot management routes
        Route::resource('bots', BotInstanceController::class);
        Route::post('bots/{bot}/disconnect', [BotInstanceController::class, 'disconnect'])->name('bots.disconnect');
        Route::post('bots/{bot}/logout', [BotInstanceController::class, 'logout'])->name('bots.logout');
        Route::post('bots/{bot}/reinitialize', [BotInstanceController::class, 'reinitialize'])->name('bots.reinitialize');
        Route::get('bots/{bot}/status', [BotInstanceController::class, 'status'])->name('bots.status');
        Route::put('bots/{bot}/phone', [BotInstanceController::class, 'updatePhone'])->name('bots.updatePhone');
        Route::put('bots/{bot}/link', [BotInstanceController::class, 'updateLink'])->name('bots.updateLink');

        // Auto-reply configuration routes
        Route::resource('auto-replies', AutoReplyConfigController::class);
        Route::post('auto-replies/{autoReply}/toggle-active', [AutoReplyConfigController::class, 'toggleActive'])->name('auto-replies.toggle-active');

        // Chat configuration routes
        Route::resource('chat-config', ChatConfigController::class);
        Route::post('chat-config/{chatConfig}/toggle-active', [ChatConfigController::class, 'toggleActive'])->name('chat-config.toggle-active');

        // NLP logs routes
        Route::get('nlp-logs', [NlpLogController::class, 'index'])->name('nlp-logs.index');
        Route::get('nlp-logs/live', [NlpLogController::class, 'live'])->name('nlp-logs.live');
        Route::get('nlp-logs/statistics', [NlpLogController::class, 'statistics'])->name('nlp-logs.statistics');

        // NLP configuration routes
        Route::get('nlp-config', [NlpConfigController::class, 'index'])->name('nlp-config.index');
        Route::put('nlp-config', [NlpConfigController::class, 'update'])->name('nlp-config.update');
        Route::post('nlp-config/reset', [NlpConfigController::class, 'reset'])->name('nlp-config.reset');
        Route::get('nlp-config/diagnostics', [NlpConfigController::class, 'diagnostics'])->name('nlp-config.diagnostics');
        Route::post('nlp-config/clear-cache', [NlpConfigController::class, 'clearCache'])->name('nlp-config.clear-cache');
    });
});

require __DIR__.'/auth.php';
