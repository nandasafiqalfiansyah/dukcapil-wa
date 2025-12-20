<?php

use App\Http\Controllers\Admin\AutoReplyConfigController;
use App\Http\Controllers\Admin\BotInstanceController;
use App\Http\Controllers\Admin\ConversationLogController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DocumentValidationController;
use App\Http\Controllers\Admin\ServiceRequestController;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\Admin\WhatsAppUserController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
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

        // Auto-reply configuration routes
        Route::resource('auto-replies', AutoReplyConfigController::class);
        Route::post('auto-replies/{autoReply}/toggle-active', [AutoReplyConfigController::class, 'toggleActive'])->name('auto-replies.toggle-active');
    });
});

require __DIR__.'/auth.php';
