<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\WhatsappBotController;

/*
|--------------------------------------------------------------------------
| WhatsApp Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth:admin', 'whatsapp.status'])->prefix('admin/whatsapp')->name('admin.whatsapp.')->group(function () {
    // Bulk Messages
    Route::get('/bulk-messages', [WhatsappBotController::class, 'showBulkMessages'])->name('bulk-messages');
    Route::post('/bulk-messages', [WhatsappBotController::class, 'storeBulkMessage']);
    Route::post('/bulk-messages/recipients', [WhatsappBotController::class, 'getSmartRecipients']);
    Route::post('/bulk-messages/send', [WhatsappBotController::class, 'sendBulkMessage']);
    Route::get('/bulk-messages/export', [WhatsappBotController::class, 'exportBulkMessages']);
    Route::get('/bulk-messages/analytics', [WhatsappBotController::class, 'getBulkMessageAnalytics']);
    Route::get('/bulk-messages/{id}/export', [WhatsappBotController::class, 'exportBulkMessage']);
    Route::post('/bulk-messages/{id}/cancel', [WhatsappBotController::class, 'cancelBulkMessage']);
    Route::get('/bulk-messages/{id}', [WhatsappBotController::class, 'showBulkMessage']);

    // Templates
    Route::get('/templates', [WhatsappBotController::class, 'showTemplates'])->name('templates');
    Route::post('/templates/sync', [WhatsappBotController::class, 'syncTemplates']);
    Route::post('/templates/test', [WhatsappBotController::class, 'testTemplate']);
    Route::get('/templates/{id}', [WhatsappBotController::class, 'showTemplate']);
    Route::post('/templates', [WhatsappBotController::class, 'storeTemplate']);
    Route::put('/templates/{id}', [WhatsappBotController::class, 'updateTemplate']);
    Route::delete('/templates/{id}', [WhatsappBotController::class, 'deleteTemplate']);

    // Flow Data
    Route::get('/flows', [WhatsappBotController::class, 'showFlows'])->name('flows');
    Route::post('/flows', [WhatsappBotController::class, 'storeFlow']);
    Route::get('/flows/{id}', [WhatsappBotController::class, 'showFlow']);
    Route::put('/flows/{id}', [WhatsappBotController::class, 'updateFlow']);
    Route::delete('/flows/{id}', [WhatsappBotController::class, 'deleteFlow']);
    Route::post('/flows/{id}/activate', [WhatsappBotController::class, 'activateFlow']);
    Route::post('/flows/{id}/deactivate', [WhatsappBotController::class, 'deactivateFlow']);

    // Auto Replies
    Route::get('/auto-replies', [WhatsappBotController::class, 'showAutoReplies'])->name('auto-replies');
    Route::post('/auto-replies', [WhatsappBotController::class, 'storeAutoReply']);
    Route::get('/auto-replies/{id}', [WhatsappBotController::class, 'showAutoReply']);
    Route::put('/auto-replies/{id}', [WhatsappBotController::class, 'updateAutoReply']);
    Route::delete('/auto-replies/{id}', [WhatsappBotController::class, 'deleteAutoReply']);
    Route::post('/auto-replies/test', [WhatsappBotController::class, 'testAutoReply']);

    // Settings
    Route::get('/settings', [WhatsappBotController::class, 'showSettings'])->name('settings');
    Route::post('/settings', [WhatsappBotController::class, 'updateSettings']);
});
