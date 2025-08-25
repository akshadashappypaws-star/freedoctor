<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Http\Request;
use App\Events\MessageReceived;

use Illuminate\Foundation\Auth\EmailVerificationRequest;
use App\Http\Controllers\Admin\Pageview\PageController;
use App\Http\Controllers\Admin\Auth\LoginController as AdminLoginController;
use App\Http\Controllers\Admin\DoctorController;
use App\Http\Controllers\Doctor\Auth\LoginController as DoctorLoginController;
use App\Http\Controllers\User\Auth\LoginController as UserLoginController;
use App\Http\Controllers\CampaignController;
use App\Http\Controllers\PatientRegistrationController;
use App\Http\Controllers\CampaignSponsorController;
use App\Http\Controllers\DoctorPaymentController;
use App\Http\Controllers\BusinessOrganizationController;
use App\Http\Controllers\User\UserPaymentController;
use App\Http\Controllers\User\DashboardController as UserDashboardController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Doctor\DashboardController as DoctorDashboardController;
use App\Http\Controllers\User\PasswordController as UserPasswordController;
use App\Http\Controllers\Doctor\PasswordController as DoctorPasswordController;
use App\Http\Controllers\Admin\PasswordController as AdminPasswordController;

use App\Http\Controllers\User\Auth\RegisterController as UserRegisterController; // ✅ Make sure this exists!
use App\Http\Controllers\Doctor\Auth\RegisterController as DocterRegisterController; 
use App\Http\Controllers\User\WithdrawalController; 
use App\Http\Controllers\OrganicLeadController; 

use App\Http\Controllers\Doctor\DoctorEmailVerificationController ;
use App\Http\Controllers\User\UserEmailVerificationController;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\User\UserMessageController;
use App\Http\Controllers\ReferralController;
use App\Http\Controllers\ContactController;


// Google OAuth routes moved to user route group

// Broadcasting Authentication Route
Route::post('/broadcasting/auth', function (Request $request) {
    if (auth()->guard('admin')->check()) {
        return response()->json(['auth' => 'success']);
    }
    return response()->json(['error' => 'Unauthorized'], 401);
});

// Redirect base URL to user home page
Route::get('/', function () {
    return redirect()->route('user.home');
});

// Contact Us Routes (accessible to all)
Route::get('/contact', [ContactController::class, 'index'])->name('contact');
Route::post('/contact', [ContactController::class, 'submit'])->name('contact.submit');
Route::get('/contact/stats', [ContactController::class, 'getStatistics'])->name('contact.stats');


// WhatsApp Cloud API Webhook (public, no auth)
Route::match(['get', 'post'], '/webhook/whatsapp', [\App\Http\Controllers\Api\WhatsappWebhookController::class, 'handle']);

Route::get('/test/referral/simulate/{userId}', function ($userId) {
    $user = \App\Models\User::find($userId);
    if (!$user) {
        return response()->json(['error' => 'User not found']);
    }
    
    $campaignId = 60; // Use campaign ID from your URL
    $referralId = $user->your_referral_id;
    
    return response()->json([
        'user_id' => $user->id,
        'username' => $user->username,
        'referral_id' => $referralId,
        'campaign_id' => $campaignId,
        'referral_url' => "http://127.0.0.1:8000/user/campaigns/{$campaignId}/view?ref={$referralId}",
        'registration_url_with_ref' => "http://127.0.0.1:8000/user/register?ref={$referralId}",
        'note' => 'Visit the referral_url first, then register. The referred_by field should be automatically populated.'
    ]);
})->name('test.referral.simulate');

Route::get('/test/check-referral-session', function () {
    $sessionData = session('referral_data');
    return response()->json([
        'has_session_data' => $sessionData ? true : false,
        'session_data' => $sessionData,
        'url_ref_param' => request('ref'),
        'current_url' => request()->fullUrl()
    ]);
})->name('test.check.referral.session');

// Test route to check recent user registrations and referral data
Route::get('/test/recent-users', function () {
    $recentUsers = \App\Models\User::orderBy('created_at', 'desc')
        ->take(10)
        ->get()
        ->map(function($user) {
            return [
                'id' => $user->id,
                'username' => $user->username,
                'email' => $user->email,
                'your_referral_id' => $user->your_referral_id,
                'referred_by' => $user->referred_by,
                'referral_completed_at' => $user->referral_completed_at ? $user->referral_completed_at->format('Y-m-d H:i:s') : null,
                'created_at' => $user->created_at->format('Y-m-d H:i:s'),
                'total_earnings' => $user->total_earnings ?? 0,
                'available_balance' => $user->available_balance ?? 0
            ];
        });
    
    return response()->json([
        'recent_users_count' => $recentUsers->count(),
        'users_with_referrals' => $recentUsers->where('referred_by', '!=', null)->count(),
        'recent_users' => $recentUsers
    ]);
})->name('test.recent.users');

// Test route to verify specific referral scenario
Route::get('/test/verify-referral/{userEmail}', function ($userEmail) {
    $user = \App\Models\User::where('email', $userEmail)->first();
    
    if (!$user) {
        return response()->json(['error' => 'User not found']);
    }
    
    $referrer = null;
    if ($user->referred_by) {
        $referrer = \App\Models\User::where('your_referral_id', $user->referred_by)->first();
    }
    
    return response()->json([
        'user' => [
            'id' => $user->id,
            'username' => $user->username,
            'email' => $user->email,
            'your_referral_id' => $user->your_referral_id,
            'referred_by' => $user->referred_by,
            'referral_completed_at' => $user->referral_completed_at ? $user->referral_completed_at->format('Y-m-d H:i:s') : null,
            'created_at' => $user->created_at->format('Y-m-d H:i:s')
        ],
        'referrer' => $referrer ? [
            'id' => $referrer->id,
            'username' => $referrer->username,
            'email' => $referrer->email,
            'your_referral_id' => $referrer->your_referral_id,
            'total_earnings' => $referrer->total_earnings ?? 0,
            'available_balance' => $referrer->available_balance ?? 0
        ] : null,
        'referral_valid' => $referrer ? true : false
    ]);
})->name('test.verify.referral');

// ------------------------ ADMIN ROUTES ------------------------

// ------------------------ ADMIN ROUTES ------------------------
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AdminLoginController::class, 'showLoginForm'])->name('login')->middleware('check.cross.auth:admin');
    Route::post('/login', [AdminLoginController::class, 'login']);
    Route::post('/logout', [AdminLoginController::class, 'logout'])->name('logout');



        Route::middleware(['auth:admin', 'verified'])->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        Route::get('/dashboard-data', [AdminDashboardController::class, 'getDashboardData'])->name('dashboard.data');
        Route::get('/chart-data', [AdminDashboardController::class, 'getChartData'])->name('chart.data');
        Route::get('/recent-activities', [AdminDashboardController::class, 'getRecentActivities'])->name('recent.activities');
        
        // Admin notification routes
        Route::get('/notifications/check-new', [AdminDashboardController::class, 'checkNewNotifications'])->name('notifications.check.admin');
        Route::post('/notifications/mark-read', [AdminDashboardController::class, 'markNotificationsAsRead'])->name('notifications.mark-read.admin');
        
     
         
        // WhatsApp Bot Routes
        Route::prefix('whatsapp')->name('whatsapp.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\WhatsappBotController::class, 'index'])->name('dashboard');
            
            // Enhanced UI Demo Route
            Route::get('/ui-demo', function () {
                return view('admin.pages.whatsapp.ui-demo');
            })->name('ui-demo');
            
            // Scenario Workflow Management
            Route::get('/workflows', [\App\Http\Controllers\Admin\WorkflowController::class, 'index'])->name('workflows');
            Route::get('/workflows/create', [\App\Http\Controllers\Admin\WorkflowController::class, 'create'])->name('workflows.create');
            Route::post('/workflows', [\App\Http\Controllers\Admin\WorkflowController::class, 'store'])->name('workflows.store');
            Route::get('/workflows/{workflow}', [\App\Http\Controllers\Admin\WorkflowController::class, 'show'])->name('workflows.show');
            Route::get('/workflows/{workflow}/edit', [\App\Http\Controllers\Admin\WorkflowController::class, 'edit'])->name('workflows.edit');
            Route::put('/workflows/{workflow}', [\App\Http\Controllers\Admin\WorkflowController::class, 'update'])->name('workflows.update');
            Route::delete('/workflows/{workflow}', [\App\Http\Controllers\Admin\WorkflowController::class, 'destroy'])->name('workflows.destroy');
            Route::post('/workflows/{workflow}/retry', [\App\Http\Controllers\Admin\WorkflowController::class, 'retry'])->name('workflows.retry');
            Route::get('/workflows/{workflow}/live-monitor', [\App\Http\Controllers\Admin\WorkflowController::class, 'liveMonitor'])->name('workflows.live-monitor');
            
            // Machine Configuration Management
            Route::get('/machines', [\App\Http\Controllers\Admin\WorkflowMachineController::class, 'index'])->name('machines');
            Route::get('/machines/{machine}/config', [\App\Http\Controllers\Admin\WorkflowMachineController::class, 'showConfig'])->name('machines.config');
            Route::put('/machines/{machine}/config', [\App\Http\Controllers\Admin\WorkflowMachineController::class, 'updateConfig'])->name('machines.config.update');
            Route::post('/machines/{machine}/test', [\App\Http\Controllers\Admin\WorkflowMachineController::class, 'testMachine'])->name('machines.test');
            
            // Live Conversations Management
            Route::get('/conversations', [\App\Http\Controllers\Admin\WhatsappConversationController::class, 'index'])->name('conversations');
            Route::get('/conversations/{phone}', [\App\Http\Controllers\Admin\WhatsappConversationController::class, 'show'])->name('conversations.show');
            Route::post('/conversations/{phone}/intervene', [\App\Http\Controllers\Admin\WhatsappConversationController::class, 'intervene'])->name('conversations.intervene');
            Route::post('/conversations/{phone}/handover', [\App\Http\Controllers\Admin\WhatsappConversationController::class, 'handover'])->name('conversations.handover');
            
            // Analytics & Reports
            Route::get('/analytics', [\App\Http\Controllers\Admin\WhatsappAnalyticsController::class, 'index'])->name('analytics');
            Route::get('/analytics/performance', [\App\Http\Controllers\Admin\WhatsappAnalyticsController::class, 'performance'])->name('analytics.performance');
            Route::get('/analytics/workflows', [\App\Http\Controllers\Admin\WhatsappAnalyticsController::class, 'workflows'])->name('analytics.workflows');
            Route::get('/analytics/export', [\App\Http\Controllers\Admin\WhatsappAnalyticsController::class, 'export'])->name('analytics.export');
            
            // Automation Rules
            Route::get('/automation', [\App\Http\Controllers\Admin\WhatsappAutomationController::class, 'index'])->name('automation');
            Route::get('/automation/workflow', [\App\Http\Controllers\Admin\WhatsappAutomationController::class, 'workflow'])->name('automation.workflow');
            Route::get('/automation/rules', [\App\Http\Controllers\Admin\WhatsappAutomationController::class, 'rules'])->name('automation.rules');
            Route::get('/automation/analytics', [\App\Http\Controllers\Admin\WhatsappAutomationController::class, 'analytics'])->name('automation.analytics');
            Route::get('/automation/machines', [\App\Http\Controllers\Admin\WhatsappAutomationController::class, 'machines'])->name('automation.machines');
            Route::get('/automation/stats', [\App\Http\Controllers\Admin\WhatsappAutomationController::class, 'getStats'])->name('automation.stats');
            Route::post('/automation/rules', [\App\Http\Controllers\Admin\WhatsappAutomationController::class, 'storeRule'])->name('automation.rules.store');
            Route::put('/automation/rules/{rule}', [\App\Http\Controllers\Admin\WhatsappAutomationController::class, 'updateRule'])->name('automation.rules.update');
            Route::delete('/automation/rules/{rule}', [\App\Http\Controllers\Admin\WhatsappAutomationController::class, 'deleteRule'])->name('automation.rules.delete');
            Route::post('/automation/rules/{rule}/toggle', [\App\Http\Controllers\Admin\WhatsappAutomationController::class, 'toggleRule'])->name('automation.rules.toggle');
            Route::post('/automation/rules/{rule}/test', [\App\Http\Controllers\Admin\WhatsappAutomationController::class, 'testRule'])->name('automation.rules.test');
            
            // Workflow Management Routes
            Route::get('/automation/workflows/{workflow}/preview', [\App\Http\Controllers\Admin\WhatsappAutomationController::class, 'previewWorkflow'])->name('automation.workflows.preview');
            Route::post('/automation/workflows/{workflow}/activate', [\App\Http\Controllers\Admin\WhatsappAutomationController::class, 'activateWorkflow'])->name('automation.workflows.activate');
            Route::post('/automation/workflows/{workflow}/duplicate', [\App\Http\Controllers\Admin\WhatsappAutomationController::class, 'duplicateWorkflow'])->name('automation.workflows.duplicate');
            Route::get('/automation/workflows/{workflow}/visualization', [\App\Http\Controllers\Admin\WhatsappAutomationController::class, 'getWorkflowVisualization'])->name('automation.workflows.visualization');
            Route::get('/automation/workflows/{workflow}', [\App\Http\Controllers\Admin\WhatsappAutomationController::class, 'getWorkflowDetails'])->name('automation.workflows.details');
            Route::post('/automation/workflows/{workflow}/pause', [\App\Http\Controllers\Admin\WhatsappAutomationController::class, 'pauseWorkflow'])->name('automation.workflows.pause');
            Route::post('/automation/workflows/{workflow}/stop', [\App\Http\Controllers\Admin\WhatsappAutomationController::class, 'stopWorkflow'])->name('automation.workflows.stop');
            
            // Live Data Routes
            Route::get('/automation/live-stats', [\App\Http\Controllers\Admin\WhatsappAutomationController::class, 'getLiveStats'])->name('automation.live-stats');
            Route::get('/automation/active-workflows', [\App\Http\Controllers\Admin\WhatsappAutomationController::class, 'getActiveWorkflows'])->name('automation.active-workflows');
            
            // Workflow Builder Routes
            Route::post('/automation/workflows/save', [\App\Http\Controllers\Admin\WhatsappAutomationController::class, 'saveWorkflow'])->name('automation.workflows.save');
            Route::post('/automation/workflows/test', [\App\Http\Controllers\Admin\WhatsappAutomationController::class, 'testWorkflowBuilder'])->name('automation.workflows.test');
            
            // Machine Management
            Route::get('/machines', [\App\Http\Controllers\Admin\WhatsappMachineController::class, 'index'])->name('machines');
            Route::get('/machines/{machine}/config', [\App\Http\Controllers\Admin\WhatsappMachineController::class, 'config'])->name('machines.config');
            Route::post('/machines/{machine}/config', [\App\Http\Controllers\Admin\WhatsappMachineController::class, 'updateConfig'])->name('whatsapp.machines.config.update');
            Route::post('/machines/{machine}/test', [\App\Http\Controllers\Admin\WhatsappMachineController::class, 'testMachine'])->name('machines.test');
            Route::get('/machines/{machine}/analytics', [\App\Http\Controllers\Admin\WhatsappMachineController::class, 'analytics'])->name('machines.analytics');
            Route::post('/machines/{machine}/toggle/{config}', [\App\Http\Controllers\Admin\WhatsappMachineController::class, 'toggleStatus'])->name('machines.toggle');
            Route::delete('/machines/{machine}/config/{config}', [\App\Http\Controllers\Admin\WhatsappMachineController::class, 'deleteConfig'])->name('machines.config.delete');
            Route::get('/machines/{machine}/export', [\App\Http\Controllers\Admin\WhatsappMachineController::class, 'exportConfigs'])->name('machines.export');
            
            // Enhanced Conversations
            Route::post('/conversations/{conversation}/status', [\App\Http\Controllers\Admin\WhatsappConversationController::class, 'updateStatus'])->name('conversations.status');
            Route::post('/conversations/{conversation}/send', [\App\Http\Controllers\Admin\WhatsappConversationController::class, 'sendMessage'])->name('conversations.send');
            Route::get('/conversations/analytics', [\App\Http\Controllers\Admin\WhatsappConversationController::class, 'analytics'])->name('conversations.analytics');
            Route::post('/conversations/bulk', [\App\Http\Controllers\Admin\WhatsappConversationController::class, 'bulkUpdate'])->name('conversations.bulk');
            Route::get('/conversations/export', [\App\Http\Controllers\Admin\WhatsappConversationController::class, 'export'])->name('conversations.export');
            
            // Bot Settings
            Route::get('/settings', [\App\Http\Controllers\Admin\WhatsappSettingsController::class, 'index'])->name('settings');
            Route::put('/settings', [\App\Http\Controllers\Admin\WhatsappSettingsController::class, 'update'])->name('settings.update');
            Route::post('/settings/test-connection', [\App\Http\Controllers\Admin\WhatsappSettingsController::class, 'testConnection'])->name('settings.test-connection');
            Route::post('/settings/sync-templates', [\App\Http\Controllers\Admin\WhatsappSettingsController::class, 'syncTemplates'])->name('settings.sync-templates');
            
            // Legacy routes (keeping for backward compatibility)
            Route::get('/conversation-history', [\App\Http\Controllers\Admin\WhatsappBotController::class, 'conversationHistory'])->name('conversation-history');
            Route::get('/flows', [\App\Http\Controllers\Admin\WhatsappBotController::class, 'showMessageFlows'])->name('flows');
            Route::get('/flow-data', [\App\Http\Controllers\Admin\WhatsappBotController::class, 'showMessageFlows'])->name('flow-data');
            Route::post('/flow-data', [\App\Http\Controllers\Admin\WhatsappBotController::class, 'storeMessageFlow'])->name('flow-data.store');
            Route::put('/flow-data/{id}', [\App\Http\Controllers\Admin\WhatsappBotController::class, 'updateMessageFlow'])->name('flow-data.update');
            Route::delete('/flow-data/{id}', [\App\Http\Controllers\Admin\WhatsappBotController::class, 'deleteMessageFlow'])->name('flow-data.delete');
            
            // Bulk Message Management - Specific routes first, then parameterized routes
            Route::get('/bulk-messages', [\App\Http\Controllers\Admin\WhatsappBotController::class, 'showBulkMessages'])->name('bulk-messages');
            Route::post('/bulk-messages', [\App\Http\Controllers\Admin\WhatsappBotController::class, 'storeBulkMessage'])->name('bulk-messages.store');
            Route::get('/bulk-messages/export', [\App\Http\Controllers\Admin\WhatsappBotController::class, 'exportBulkMessages'])->name('bulk-messages.export');
            Route::get('/bulk-messages/analytics', [\App\Http\Controllers\Admin\WhatsappBotController::class, 'getBulkMessageAnalytics'])->name('bulk-messages.analytics');
            Route::get('/bulk-messages/status-check', [\App\Http\Controllers\Admin\WhatsappBotController::class, 'getMessageStatusForConsole'])->name('bulk-messages.status-check');
            Route::get('/bulk-messages/realtime-stats', [\App\Http\Controllers\Admin\WhatsappBotController::class, 'getRealTimeStats'])->name('bulk-messages.realtime-stats');
            Route::post('/bulk-messages/send', [\App\Http\Controllers\Admin\WhatsappBotController::class, 'sendBulkMessage'])->name('bulk-messages.send');
            Route::post('/bulk-messages/recipients', [\App\Http\Controllers\Admin\WhatsappBotController::class, 'getTargetedRecipients'])->name('bulk-messages.recipients');
            Route::get('/bulk-messages/{id}', [\App\Http\Controllers\Admin\WhatsappBotController::class, 'showBulkMessage'])->name('bulk-messages.show');
            Route::get('/bulk-messages/{id}/export', [\App\Http\Controllers\Admin\WhatsappBotController::class, 'exportBulkMessage'])->name('bulk-messages.export-single');
            Route::post('/bulk-messages/{id}/cancel', [\App\Http\Controllers\Admin\WhatsappBotController::class, 'cancelBulkMessage'])->name('bulk-messages.cancel');
            Route::delete('/bulk-messages/{id}', [\App\Http\Controllers\Admin\WhatsappBotController::class, 'deleteBulkMessage'])->name('bulk-messages.delete');
            
            // Templates Management
            Route::get('/templates', [\App\Http\Controllers\Admin\WhatsappBotController::class, 'showTemplates'])->name('templates');
            Route::post('/templates', [\App\Http\Controllers\Admin\WhatsappBotController::class, 'storeTemplate'])->name('templates.store');
            Route::get('/templates/{id}', [\App\Http\Controllers\Admin\WhatsappBotController::class, 'showTemplate'])->name('templates.show');
            Route::put('/templates/{id}', [\App\Http\Controllers\Admin\WhatsappBotController::class, 'updateTemplate'])->name('templates.update');
            Route::delete('/templates/{id}', [\App\Http\Controllers\Admin\WhatsappBotController::class, 'deleteTemplate'])->name('templates.delete');
            Route::post('/templates/sync', [\App\Http\Controllers\Admin\WhatsappBotController::class, 'syncTemplates'])->name('templates.sync');
            Route::post('/templates/test', [\App\Http\Controllers\Admin\WhatsappBotController::class, 'testTemplate'])->name('templates.test');
            Route::post('/templates/send', [\App\Http\Controllers\Admin\WhatsappBotController::class, 'sendTemplateMessage'])->name('templates.send');
            Route::post('/templates/table-data', [\App\Http\Controllers\Admin\WhatsappBotController::class, 'getTableData'])->name('templates.table-data');
            Route::post('/templates/multiple-tables', [\App\Http\Controllers\Admin\WhatsappBotController::class, 'getMultipleTablesData'])->name('templates.multiple-tables');
            Route::post('/templates/table-preview', [\App\Http\Controllers\Admin\WhatsappBotController::class, 'getTablePreview'])->name('templates.table-preview');
            Route::post('/templates/test-table-link', [\App\Http\Controllers\Admin\WhatsappBotController::class, 'testTableLink'])->name('templates.test-table-link');
            Route::post('/templates/save-table-link', [\App\Http\Controllers\Admin\WhatsappBotController::class, 'saveTableLink'])->name('templates.save-table-link');
            
            // Template-Campaign Linking
            Route::post('/refresh-templates', [\App\Http\Controllers\Admin\WhatsappBotController::class, 'refreshTemplates'])->name('refresh-templates');
            Route::post('/link-template-campaign', [\App\Http\Controllers\Admin\WhatsappBotController::class, 'linkTemplateToCampaign'])->name('link-template-campaign');
            Route::delete('/unlink-template-campaign/{id}', [\App\Http\Controllers\Admin\WhatsappBotController::class, 'unlinkTemplateCampaign'])->name('unlink-template-campaign');
            Route::post('/send-campaign-template', [\App\Http\Controllers\Admin\WhatsappBotController::class, 'sendCampaignTemplate'])->name('send-campaign-template');
            Route::post('/template-campaign-preview', [\App\Http\Controllers\Admin\WhatsappBotController::class, 'getTemplateCampaignPreview'])->name('template-campaign-preview');
            Route::get('/test-connection', [\App\Http\Controllers\Admin\WhatsappBotController::class, 'testConnection'])->name('test-connection');
            Route::get('/debug-api', function() {
                $apiKey = config('services.whatsapp.token');
                $businessAccountId = config('services.whatsapp.business_account_id');
                $url = "https://graph.facebook.com/v18.0/{$businessAccountId}/message_templates?limit=100";
                
                try {
                    $response = \Illuminate\Support\Facades\Http::withToken($apiKey)
                        ->timeout(30)
                        ->get($url);
                    
                    return response()->json([
                        'success' => $response->successful(),
                        'status' => $response->status(),
                        'data' => $response->json(),
                        'config' => [
                            'api_key_set' => !empty($apiKey),
                            'business_id_set' => !empty($businessAccountId),
                            'url' => $url
                        ]
                    ]);
                } catch (\Exception $e) {
                    return response()->json([
                        'success' => false,
                        'error' => $e->getMessage(),
                        'config' => [
                            'api_key_set' => !empty($apiKey),
                            'business_id_set' => !empty($businessAccountId),
                            'url' => $url
                        ]
                    ]);
                }
            })->name('debug-api');
            
            // Auto Replies Management
            Route::get('/auto-replies', [\App\Http\Controllers\Admin\WhatsappBotController::class, 'showAutoReplies'])->name('auto-replies');
            Route::post('/auto-replies', [\App\Http\Controllers\Admin\WhatsappBotController::class, 'storeAutoReply'])->name('auto-replies.store');
            Route::get('/auto-replies/{id}', [\App\Http\Controllers\Admin\WhatsappBotController::class, 'showAutoReply'])->name('auto-replies.show');
            Route::put('/auto-replies/{id}', [\App\Http\Controllers\Admin\WhatsappBotController::class, 'updateAutoReply'])->name('auto-replies.update');
            Route::delete('/auto-replies/{id}', [\App\Http\Controllers\Admin\WhatsappBotController::class, 'deleteAutoReply'])->name('auto-replies.delete');
            
            // ChatGPT Integration
            Route::get('/chatgpt', [\App\Http\Controllers\Admin\WhatsappBotController::class, 'showChatGPT'])->name('chatgpt');
            Route::post('/chatgpt/config', [\App\Http\Controllers\Admin\WhatsappBotController::class, 'saveChatGPTConfig'])->name('chatgpt.config');
            Route::post('/chatgpt/prompts', [\App\Http\Controllers\Admin\WhatsappBotController::class, 'storeChatGPTPrompt'])->name('chatgpt.prompts.store');
            Route::get('/chatgpt/prompts/{id}', [\App\Http\Controllers\Admin\WhatsappBotController::class, 'showChatGPTPrompt'])->name('chatgpt.prompts.show');
            Route::put('/chatgpt/prompts/{id}', [\App\Http\Controllers\Admin\WhatsappBotController::class, 'updateChatGPTPrompt'])->name('chatgpt.prompts.update');
            Route::delete('/chatgpt/prompts/{id}', [\App\Http\Controllers\Admin\WhatsappBotController::class, 'deleteChatGPTPrompt'])->name('chatgpt.prompts.delete');

            // Settings Management (Legacy)
            Route::get('/settings-legacy', [\App\Http\Controllers\Admin\WhatsappBotController::class, 'showSettings'])->name('settings.legacy');
            Route::post('/settings-legacy', [\App\Http\Controllers\Admin\WhatsappBotController::class, 'updateSettings'])->name('settings.legacy.update');
        });
// ...existing code...
        
        // Broadcasting routes
        Route::post('/trigger-broadcast', [AdminDashboardController::class, 'broadcastDashboardUpdate'])->name('trigger.broadcast');
        Route::post('/trigger-chart-broadcast', [AdminDashboardController::class, 'broadcastChartUpdate'])->name('trigger.chart.broadcast');
        Route::post('/trigger-activities-broadcast', [AdminDashboardController::class, 'broadcastActivitiesUpdate'])->name('trigger.activities.broadcast');

    });

    // Password Reset
    Route::get('password/reset', [AdminPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('password/email', [AdminPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('password/reset/{token}', [AdminPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('password/reset', [AdminPasswordController::class, 'reset'])->name('password.update');
});

// ------------------------ DOCTORuser.profile ROUTES ------------------------
Route::prefix('doctor')->name('doctor.')->group(function () {
    // Auth Routes
    Route::get('/login', [DoctorLoginController::class, 'showLoginForm'])->name('login')->middleware('check.cross.auth:doctor');
    Route::post('/login', [DoctorLoginController::class, 'login']);
    Route::post('/logout', [DoctorLoginController::class, 'logout'])->name('logout');

    // Registration Routes
    Route::get('/registration', [DocterRegisterController::class, 'showRegistrationForm'])->name('registerform');
    Route::post('/register', [DocterRegisterController::class, 'register'])->name('register');

    // Payment Routes for Registration
    Route::get('/payment/{doctor}', [DoctorPaymentController::class, 'showPaymentForm'])->name('payment.form');
    Route::post('/payment/{doctor}/create', [DoctorPaymentController::class, 'createPayment'])->name('payment.create');
    Route::post('/payment/{payment}/success', [DoctorPaymentController::class, 'paymentSuccess'])->name('payment.success');
    Route::get('/payment/{payment}/failure', [DoctorPaymentController::class, 'paymentFailure'])->name('payment.failure');

    // Email Verification Routes
    Route::middleware('auth:doctor')->group(function () {
        Route::get('/email/verify', [DoctorEmailVerificationController::class, 'notice'])->name('doctor.verification.notice');
        Route::get('/email/verify/{id}/{hash}', [DoctorEmailVerificationController::class, 'verify'])->middleware('signed')->name('doctor.verification.verify');
        Route::post('/email/verification-notification', [DoctorEmailVerificationController::class, 'send'])->middleware('throttle:6,1')->name('doctor.verification.send');
    });

    // Protected Dashboard Routes
    Route::middleware(['auth:doctor', 'verified.doctor'])->group(function () {
        // Main Dashboard
        Route::get('/dashboard', [DoctorDashboardController::class, 'index'])->name('dashboard');
        Route::get('/dashboard-data', [DoctorDashboardController::class, 'getDashboardData'])->name('dashboard.data');
        
        // Core Feature Routes
        Route::get('/campaigns', [DoctorDashboardController::class, 'campaigns'])->name('campaigns');
        Route::get('/patients', [DoctorDashboardController::class, 'patients'])->name('patients');
        Route::get('/sponsors', [DoctorDashboardController::class, 'sponsors'])->name('sponsors');
        Route::get('/business-reach-out', [DoctorDashboardController::class, 'businessReachOut'])->name('business-reach-out');
        Route::get('/profit', [DoctorDashboardController::class, 'profit'])->name('profit');
        Route::get('/wallet', [DoctorDashboardController::class, 'wallet'])->name('wallet');
        Route::post('/wallet/withdraw', [DoctorDashboardController::class, 'processWithdrawal'])->name('wallet.withdraw');
        Route::post('/wallet/account-details', [DoctorDashboardController::class, 'updateAccountDetails'])->name('wallet.account-details');
        Route::get('/profile', [DoctorDashboardController::class, 'profile'])->name('profile');
        Route::get('/notifications', [DoctorDashboardController::class, 'notifications'])->name('notifications');
        
        // Campaign Management
        Route::post('/campaigns', [DoctorDashboardController::class, 'storeCampaign'])->name('campaigns.store');
        Route::get('/campaigns/{id}', [DoctorDashboardController::class, 'showCampaign'])->name('campaigns.show');
        Route::get('/campaigns/{id}/edit', [DoctorDashboardController::class, 'editCampaign'])->name('campaigns.edit');
        Route::get('/campaigns/{id}/view', [DoctorDashboardController::class, 'viewCampaign'])->name('campaigns.view');
        Route::put('/campaigns/{id}', [DoctorDashboardController::class, 'updateCampaign'])->name('campaigns.update');
        Route::delete('/campaigns/{id}', [DoctorDashboardController::class, 'destroyCampaign'])->name('campaigns.destroy');
        
        // Profile Management
        Route::put('/profile', [DoctorDashboardController::class, 'updateProfile'])->name('profile.update');
        Route::put('/profile/password', [DoctorDashboardController::class, 'updatePassword'])->name('profile.password.update');
        
        // Notification Management
        Route::get('/notifications/check-new', [DoctorDashboardController::class, 'checkNewNotifications'])->name('notifications.check');
        Route::post('/notifications/mark-read', [DoctorDashboardController::class, 'markNotificationsAsRead'])->name('notifications.mark-read');
        
        // Proposal Management
        Route::post('/proposals', [\App\Http\Controllers\Doctor\ProposalController::class, 'store'])->name('proposals.store');
        Route::get('/proposals/{proposal}', [\App\Http\Controllers\Doctor\ProposalController::class, 'show'])->name('proposals.show');
        
        // Export Routes
        Route::get('/patients/export', [DoctorDashboardController::class, 'exportPatients'])->name('patients.export');
        Route::get('/campaigns/export', [DoctorDashboardController::class, 'exportCampaigns'])->name('campaigns.export');
        
        // Legacy Compatibility Routes
        Route::get('/dashboard/{page}', function($page) {
            $validPages = ['campaigns', 'patients', 'sponsors', 'business-reach-out', 'profile', 'notifications'];
            
            if (in_array($page, $validPages)) {
                return redirect()->route("doctor.{$page}");
            }
            
            abort(404);
        })->name('dashboard.page');
    });

    // Password Reset Routes
    Route::get('password/reset', [DoctorPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('password/email', [DoctorPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('password/reset/{token}', [DoctorPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('password/reset', [DoctorPasswordController::class, 'reset'])->name('password.update');
});
Route::middleware(['auth:doctor'])->group(function () {
    Route::get('/doctor/notifications', [DoctorDashboardController::class, 'notifications'])->name('doctor.notifications');
    Route::post('/doctor/notifications/mark-all-read', [DoctorDashboardController::class, 'markAllNotificationsRead'])->name('doctor.notifications.mark-all-read');
    Route::post('/doctor/notifications/{id}/mark-read', [DoctorDashboardController::class, 'markNotificationRead'])->name('doctor.notifications.mark-read');
    Route::post('/doctor/notifications/mark-read', [DoctorDashboardController::class, 'markNotificationsAsRead'])->name('doctor.notifications.mark-read-bulk');
    Route::delete('/doctor/notifications/{id}', [DoctorDashboardController::class, 'deleteNotification'])->name('doctor.notifications.delete');
    Route::get('/doctor/notifications/check-new', [DoctorDashboardController::class, 'checkNewNotifications'])->name('doctor.notifications.check-new');
    
    // Comprehensive Notification Testing Routes
    Route::get('/test/notifications/business-request', function() {
        // Test business request notifications to doctors
        $doctors = \App\Models\Doctor::where('status', true)
                                   ->where('approved_by_admin', true)
                                   ->take(5)->get(); // Get any approved doctors for testing
        
        if ($doctors->isEmpty()) {
            // Try alternative approach if no doctors found
            $doctors = \App\Models\Doctor::where('approved_by_admin', true)
                                       ->take(5)->get();
        }
        
        foreach ($doctors as $doctor) {
            $message = \App\Models\DoctorMessage::create([
                'doctor_id' => $doctor->id,
                'message' => 'Test: New Business Opportunity. Organization needs medical camp assistance. Check your dashboard for details!',
                'type' => 'business_request',
                'is_read' => false,
            ]);
            
            // Trigger real-time event
            event(new \App\Events\DoctorMessageSent($message));
        }
        
        return response()->json([
            'success' => true, 
            'message' => 'Test business request notifications sent to ' . $doctors->count() . ' doctors',
            'doctors' => $doctors->pluck('doctor_name'),
            'doctor_ids' => $doctors->pluck('id')
        ]);
    });
    
    Route::get('/test/notifications/proposal-approved', function() {
        // Test proposal approval notification to user
        $user = \App\Models\User::first();
        if (!$user) {
            return response()->json(['error' => 'No users found']);
        }
        
        $message = \App\Models\UserMessage::create([
            'user_id' => $user->id,
            'message' => 'Test: Your camp proposal has been approved! The doctor will contact you soon.',
            'type' => 'camp_proposal_approved',
            'is_read' => false,
        ]);
        
        // Trigger real-time event (if UserMessageSent event exists)
        // event(new \App\Events\UserMessageSent($message));
        
        return response()->json([
            'success' => true, 
            'message' => 'Test proposal approval notification sent to user: ' . $user->name
        ]);
    });
    
    Route::get('/test/notifications/admin-proposal', function() {
        // Test admin notification when doctor submits proposal
        $admin = \App\Models\Admin::first();
        if (!$admin) {
            return response()->json(['error' => 'No admin found']);
        }
        
        $message = \App\Models\AdminMessage::create([
            'admin_id' => $admin->id,
            'message' => 'Test: New camp proposal submitted by Dr. Test Doctor for approval.',
            'type' => 'new_proposal',
            'is_read' => false,
        ]);
        
        // Trigger real-time event
        event(new \App\Events\AdminMessageSent($message));
        
        return response()->json([
            'success' => true, 
            'message' => 'Test proposal notification sent to admin: ' . $admin->name
        ]);
    });
    
    Route::get('/test/notifications/check-all', function() {
        return response()->json([
            'doctor_messages_count' => \App\Models\DoctorMessage::where('created_at', '>=', now()->subMinutes(5))->count(),
            'user_messages_count' => \App\Models\UserMessage::where('created_at', '>=', now()->subMinutes(5))->count(),
            'admin_messages_count' => \App\Models\AdminMessage::where('created_at', '>=', now()->subMinutes(5))->count(),
            'recent_doctor_messages' => \App\Models\DoctorMessage::where('created_at', '>=', now()->subMinutes(5))->with('doctor:id,name')->latest()->take(5)->get(),
            'recent_user_messages' => \App\Models\UserMessage::where('created_at', '>=', now()->subMinutes(5))->with('user:id,name')->latest()->take(5)->get(),
            'recent_admin_messages' => \App\Models\AdminMessage::where('created_at', '>=', now()->subMinutes(5))->with('admin:id,name')->latest()->take(5)->get(),
        ]);
    });
    
    // Test polling endpoint directly (works only if you're logged in as a doctor)
    Route::get('/test/doctor-polling', function() {
        if (!auth('doctor')->check()) {
            return response()->json(['error' => 'Not logged in as doctor']);
        }
        
        $doctor = auth('doctor')->user();
        
        // Get recent notifications
        $notifications = \App\Models\DoctorMessage::where('doctor_id', $doctor->id)
            ->where('is_read', false)
            ->where('created_at', '>=', now()->subMinutes(10)) // Last 10 minutes
            ->orderBy('created_at', 'desc')
            ->get();
            
        return response()->json([
            'doctor_id' => $doctor->id,
            'doctor_name' => $doctor->doctor_name,
            'notifications_found' => $notifications->count(),
            'notifications' => $notifications->map(function($msg) {
                return [
                    'id' => $msg->id,
                    'type' => $msg->type,
                    'message' => $msg->message,
                    'created_at' => $msg->created_at->format('Y-m-d H:i:s'),
                    'minutes_ago' => $msg->created_at->diffInMinutes(now())
                ];
            }),
            'polling_url' => '/doctor/notifications/check-new'
        ]);
    });
    
    // Test route to simulate business request with real data
    Route::get('/test/business-request-simulation', function() {
        try {
            // Find a specialty to use
            $specialty = \App\Models\Specialty::first();
            if (!$specialty) {
                return response()->json(['error' => 'No specialties found']);
            }
            
            // Create a test business request
            $businessRequest = \App\Models\BusinessOrganizationRequest::create([
                'user_id' => 1,
                'organization_name' => 'Test Medical Organization',
                'email' => 'test@example.com',
                'phone_number' => '1234567890',
                'camp_request_type' => 'medical',
                'specialty_id' => $specialty->id,
                'date_from' => now()->addDays(7)->format('Y-m-d'),
                'date_to' => now()->addDays(9)->format('Y-m-d'),
                'number_of_people' => 50,
                'location' => 'Test City Medical Center',
                'description' => 'Test medical camp for community health checkup',
                'status' => 'pending'
            ]);
            
            // Find matching doctors
            $matchingDoctors = \App\Models\Doctor::where('specialty_id', $specialty->id)
                ->where('approved_by_admin', true)
                ->where('status', true)
                ->get();
                
            $notificationsCreated = [];
            
            foreach ($matchingDoctors as $doctor) {
                $doctorMessage = \App\Models\DoctorMessage::create([
                    'doctor_id' => $doctor->id,
                    'type' => 'business_request',
                    'message' => "🎯 NEW BUSINESS OPPORTUNITY! {$businessRequest->organization_name} needs {$specialty->name} doctors for a medical camp in {$businessRequest->location} from " . 
                               date('M d, Y', strtotime($businessRequest->date_from)) . " to " . date('M d, Y', strtotime($businessRequest->date_to)) . 
                               ". Expected participants: {$businessRequest->number_of_people}. Submit your proposal now!",
                    'is_read' => false
                ]);
                
                $notificationsCreated[] = [
                    'doctor_id' => $doctor->id,
                    'doctor_name' => $doctor->doctor_name,
                    'message_id' => $doctorMessage->id
                ];
                
                // Broadcast real-time notification
                event(new \App\Events\DoctorMessageSent($doctorMessage));
            }
            
            return response()->json([
                'success' => true,
                'business_request_id' => $businessRequest->id,
                'specialty_used' => $specialty->name,
                'doctors_notified' => $matchingDoctors->count(),
                'notifications_created' => $notificationsCreated,
                'message' => 'Business request simulation completed successfully! Check doctor dashboards for toast notifications.'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Simulation failed: ' . $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    });
    
    // Debug route to check recent doctor messages
    Route::get('/debug/doctor-messages', function() {
        $messages = \App\Models\DoctorMessage::with('doctor')
            ->orderBy('created_at', 'desc')
            ->take(20)
            ->get();
            
        return response()->json([
            'total_messages' => \App\Models\DoctorMessage::count(),
            'recent_messages_count' => $messages->count(),
            'recent_messages' => $messages->map(function($message) {
                return [
                    'id' => $message->id,
                    'doctor_id' => $message->doctor_id,
                    'doctor_name' => $message->doctor ? $message->doctor->doctor_name : 'Unknown',
                    'type' => $message->type,
                    'message' => $message->message,
                    'is_read' => $message->is_read,
                    'created_at' => $message->created_at->format('Y-m-d H:i:s'),
                    'created_minutes_ago' => $message->created_at->diffInMinutes(now())
                ];
            })
        ]);
    });
    
    // Debug route to check doctors in database
    Route::get('/debug/doctors', function() {
        $doctors = \App\Models\Doctor::with('specialty')->get();
        $specialties = \App\Models\Specialty::all();
        
        return response()->json([
            'total_doctors' => $doctors->count(),
            'approved_doctors' => $doctors->where('approved_by_admin', true)->count(),
            'active_doctors' => $doctors->where('status', true)->count(),
            'both_approved_and_active' => $doctors->where('approved_by_admin', true)->where('status', true)->count(),
            'specialties' => $specialties->pluck('name', 'id'),
            'doctors' => $doctors->map(function($doctor) {
                return [
                    'id' => $doctor->id,
                    'name' => $doctor->doctor_name,
                    'specialty_id' => $doctor->specialty_id,
                    'specialty_name' => $doctor->specialty ? $doctor->specialty->name : 'No specialty',
                    'approved_by_admin' => $doctor->approved_by_admin,
                    'status' => $doctor->status,
                    'eligible_for_notifications' => $doctor->approved_by_admin && $doctor->status
                ];
            })
        ]);
    });
    
    // Echo Debug Route
    Route::get('/debug/echo', function() {
        return view('debug-echo');
    });
});
// User Reset

Route::prefix('user')->name('user.')->group(function () {
    // Login/Logout
    Route::get('/login', [UserLoginController::class, 'showLoginForm'])->name('login')->middleware('check.cross.auth:user');
    Route::post('/login', [UserLoginController::class, 'login']);
    Route::post('/logout', [UserLoginController::class, 'logout'])->name('logout');

    // Register
    Route::get('/register', [UserRegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [UserRegisterController::class, 'register'])->name('register.submit');

    // Google Auth
    Route::get('/auth/google', [UserRegisterController::class, 'redirectToGoogle'])->name('auth.google');
    Route::get('/auth/google/callback', [UserRegisterController::class, 'handleGoogleCallback'])->name('auth.google.callback');

    // Password Reset
    Route::get('/password/reset', [UserPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('/password/email', [UserPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('/password/reset/{token}', [UserPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('/password/reset', [UserPasswordController::class, 'reset'])->name('password.update');

    // Email Verification
    Route::middleware('auth:user')->group(function () {
        Route::get('/email/verify', [UserEmailVerificationController::class, 'notice'])->name('verification.notice');
        Route::get('/email/verify/{id}/{hash}', [UserEmailVerificationController::class, 'verify'])->middleware('signed')->name('verification.verify');
        Route::post('/email/verification-notification', [UserEmailVerificationController::class, 'send'])->middleware('throttle:6,1')->name('verification.send');
    });

    // Public Dashboard and Pages (accessible without login)
    Route::get('/home', [UserDashboardController::class, 'home'])->name('home');
    Route::get('/dashboard', [UserDashboardController::class, 'index'])->name('dashboard');
    Route::get('/campaigns', [UserDashboardController::class, 'campaigns'])->name('campaigns');
    Route::post('/campaigns/search', [UserDashboardController::class, 'searchCampaigns'])->name('campaigns.search');
    Route::post('/search-campaigns', [UserDashboardController::class, 'searchCampaigns'])->name('campaigns.advanced-search');
    Route::post('/save-user-location', [UserDashboardController::class, 'saveUserLocation'])->name('user.save-location');
    
    // Campaign detail and form pages
    Route::get('/campaigns/{id}/view', [UserDashboardController::class, 'campaignDetails'])->name('campaigns.view');
    Route::get('/c/{id}', [UserDashboardController::class, 'campaignDetails'])->name('campaigns.short'); // Short URL for sharing
    Route::get('/campaigns/{id}/sponsor', [UserDashboardController::class, 'campaignSponsor'])->name('campaigns.sponsor');
    
    // Campaign sponsorship processing (can be done without login if user provides details)
    Route::post('/campaigns/{id}/sponsor', [UserDashboardController::class, 'campaignSponsorStore'])->name('campaigns.sponsor.store');
    
    // Protected campaign registration (requires login)
    Route::middleware(['auth:user'])->group(function () {
        Route::get('/campaigns/{id}/register', [UserDashboardController::class, 'campaignRegister'])->name('campaigns.register');
        Route::post('/campaigns/{id}/register', [UserDashboardController::class, 'campaignRegisterStore'])->name('campaigns.register.store');
    });
    
    // Category page routes
    Route::get('/category', [UserDashboardController::class, 'categoryPage'])->name('category');
    Route::get('/api/campaigns/category', [UserDashboardController::class, 'getCategoryCampaigns'])->name('api.campaigns.category');
    
    Route::get('/organization-camp-request', [UserDashboardController::class, 'organizationCampRequest'])->name('organization-camp-request');
    Route::post('/organization-camp-request', [UserDashboardController::class, 'storeOrganizationCampRequest'])->name('organization-camp-request.store');
    Route::get('/sponsors', [UserDashboardController::class, 'sponsors'])->name('sponsors');

    // Legal Pages
    Route::get('/terms-and-conditions', function () {
        return view('user.pages.terms-and-conditions');
    })->name('terms-and-conditions');
    
    Route::get('/privacy-policy', function () {
        return view('user.pages.privacy-policy');
    })->name('privacy-policy');
    
    Route::get('/refund-policy', function () {
        return view('user.pages.refund-policy');
    })->name('refund-policy');
    
    Route::get('/disclaimer', function () {
        return view('user.pages.disclaimer');
    })->name('disclaimer');

    // Business Proposal Page (accessible without login)
    Route::get('/our-business-proposal', function () {
        return view('user.pages.our-business-proposal');
    })->name('our-business-proposal');

    // Campaign registration (can be done without login but email is required)
    
    // Organic Lead Notification Route
    Route::post('/notify-me', [OrganicLeadController::class, 'store'])->name('notify-me.store');

    // User notification routes (moved inside the user prefix group)
    Route::get('/notifications/check-new', [UserDashboardController::class, 'checkNewNotifications'])->name('notifications.check-new');
    Route::post('/notifications/mark-read', [UserDashboardController::class, 'markNotificationsAsRead'])->name('notifications.mark-read');
    
    // Test route for user notifications (remove in production)
    Route::get('/user/api/test-notification', function() {
        $userId = request('user_id', auth('web')->id());
        
        \App\Models\UserMessage::create([
            'user_id' => $userId,
            'type' => 'camp_proposal_approved',
            'message' => 'Test: Your camp proposal has been approved! You can now proceed with the next steps.',
            'data' => json_encode([
                'camp_id' => 123,
                'camp_name' => 'Test Camp',
                'status' => 'approved'
            ]),
            'read' => false
        ]);
        
        return response()->json(['message' => 'Test user notification created', 'user_id' => $userId]);
    });

    // Protected Pages (require authentication)
    Route::middleware(['auth:user', 'verified.user'])->prefix('user')->group(function () {
        Route::get('/profile', [UserDashboardController::class, 'profile'])->name('profile');
        Route::get('/settings', [UserDashboardController::class, 'settings'])->name('settings');
        Route::get('/my-registrations', [UserDashboardController::class, 'myRegistrations'])->name('my-registrations');
        Route::get('/doctor-details/{doctor}', [UserDashboardController::class, 'getDoctorDetails'])->name('doctor-details');
        Route::get('/notifications', [UserDashboardController::class, 'notifications'])->name('notifications');
        Route::post('/notifications/{id}/mark-read', [UserDashboardController::class, 'markNotificationRead'])->name('notifications.mark-single');
        Route::post('/update-location', [UserDashboardController::class, 'updateUserLocation'])->name('update-location');
        
        // Referral routes
        Route::get('/referral-dashboard', [ReferralController::class, 'dashboard'])->name('referral-dashboard');
        Route::post('/referral/generate-link', [ReferralController::class, 'generateReferralLink'])->name('referral.generate-link');
        Route::post('/referral/sync-earnings', [ReferralController::class, 'syncReferralEarnings'])->name('referral.sync-earnings');
        Route::get('/referral/earnings-summary', [ReferralController::class, 'getEarningsSummary'])->name('referral.earnings-summary');
        
        // Withdrawal routes
        Route::post('/withdrawal/process', [WithdrawalController::class, 'processWithdrawal'])->name('withdrawal.process');
        Route::post('/withdrawal/account-details', [WithdrawalController::class, 'updateAccountDetails'])->name('withdrawal.account-details');
        
        // Test route for withdrawal system
        Route::get('/withdrawal/test', function () {
            $user = auth('user')->user();
            if (!$user) {
                return response()->json(['error' => 'Please login first']);
            }
            
            return response()->json([
                'user_id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'total_earnings' => $user->total_earnings ?? 0,
                'available_balance' => $user->available_balance ?? 0,
                'withdrawn_amount' => $user->withdrawn_amount ?? 0,
                'bank_account' => $user->bank_account_number ? 'Set' : 'Not Set',
                'razorpay_key' => config('services.razorpay.key'),
                'razorpay_configured' => config('services.razorpay.key') ? 'Yes' : 'No'
            ]);
        })->name('withdrawal.test');
        
        // Campaign routes
        Route::get('/campaigns/{id}', [UserDashboardController::class, 'campaignDetails'])->name('campaign.details');
        
        // Profile update routes
        Route::put('/profile', [UserDashboardController::class, 'updateProfile'])->name('profile.update');
        Route::put('/profile/picture', [UserDashboardController::class, 'updateProfilePicture'])->name('profile.picture.update');
        Route::put('/password', [UserDashboardController::class, 'updatePassword'])->name('password.update');
        Route::put('/preferences', [UserDashboardController::class, 'updatePreferences'])->name('preferences.update');
        Route::delete('/account', [UserDashboardController::class, 'deleteAccount'])->name('account.delete');
    });
    
    // Referral link handling (public route - no auth required)
    Route::get('/campaign/{campaignId}/ref', [ReferralController::class, 'handleReferralClick'])->name('referral.click');
// Payment Routes for User Registration

    // Email Verification moved to user route group

    Route::middleware(['auth:user'])->group(function () {
        // Campaign routes
   
        // Registration routes
        Route::post('/campaigns/register', [UserPaymentController::class, 'register'])->name('patient.campaigns.register');
        Route::post('/campaigns/register/paid', [UserPaymentController::class, 'registerPaid'])->name('campaigns.register.paid');
        
        // /sponsor Payment routes


         Route::post('/campaigns/sponsor/payment/create', [UserPaymentController::class, 'sponserPayment'])->name('campaigns.sponsors.payment');
        Route::post('/campaigns/payment/create', [UserPaymentController::class, 'createPayment'])->name('campaigns.payment.create');
        Route::post('/campaigns/payment/verify', [UserPaymentController::class, 'verifyPayment'])->name('campaigns.payment.verify');
    });
});

// ------------------------------------------page view admin-----------------------------



Route::prefix('admin')->middleware(['auth:admin'])->group(function () {
    Route::get('/specification', [PageController::class, 'specification'])->name('admin.specification');
    Route::get('/whatsapp-bot', [PageController::class, 'whatsappBot'])->name('admin.whatsapp-bot');
    Route::get('/doctors', [PageController::class, 'doctors'])->name('admin.doctors');
    Route::post('/doctors', [DoctorController::class, 'store'])->name('admin.doctors.store');
    Route::get('/doctors/{id}', [DoctorController::class, 'show'])->name('admin.doctors.show');
    Route::put('/doctors/{id}/update', [DoctorController::class, 'update'])->name('admin.doctors.update');
    Route::delete('/doctors/{id}', [DoctorController::class, 'destroy'])->name('admin.doctors.destroy');
    Route::get('/doctors/{id}/detail', [DoctorController::class, 'detail'])->name('admin.doctors.detail');
    Route::post('/doctors/{id}/approve', [DoctorController::class, 'approve'])->name('admin.doctors.approve');
    Route::post('/doctors/{id}/toggle-status', [DoctorController::class, 'toggleStatus'])->name('admin.doctors.toggleStatus');
    Route::get('/campaigns', [CampaignController::class, 'index'])->name('admin.campaigns');
    Route::get('/campaign-sponsors', [CampaignSponsorController::class, 'index'])->name('admin.campaignSponsors');
    Route::get('/patients', [PatientRegistrationController::class, 'index'])->name('admin.patients');
    Route::get('/doctor-payments', [DoctorPaymentController::class, 'adminIndex'])->name('admin.doctorPayments');
    Route::get('/patient-payouts', [PageController::class, 'patientPayouts'])->name('admin.patientPayouts');
    Route::post('/patient-payouts/{payment}/process', [PageController::class, 'processPatientPayout'])->name('admin.patientPayouts.process');
    Route::get('/patient-payouts/{payment}/status', [PageController::class, 'checkPatientPayoutStatus'])->name('admin.patientPayouts.status');
    Route::get('/patient-payouts/{payment}/details', [PageController::class, 'patientPayoutDetails'])->name('admin.patientPayouts.details');
    Route::get('/doctor-payouts', [PageController::class, 'doctorPayouts'])->name('admin.doctorPayouts');
    Route::post('/doctor-payouts/{payment}/process', [PageController::class, 'processDoctorPayout'])->name('admin.doctorPayouts.process');
    Route::get('/doctor-payouts/{payment}/status', [PageController::class, 'checkDoctorPayoutStatus'])->name('admin.doctorPayouts.status');
    Route::get('/doctor-payouts/{payment}/details', [PageController::class, 'doctorPayoutDetails'])->name('admin.doctorPayouts.details');
    Route::get('/doctor-payouts/{payment}/receipt', [PageController::class, 'doctorPayoutReceipt'])->name('admin.doctorPayouts.receipt');
    Route::get('/business-organization', [BusinessOrganizationController::class, 'index'])->name('admin.businessOrganization');
    Route::get('/doctor-business-requests', [BusinessOrganizationController::class, 'doctorRequests'])->name('admin.doctorBusinessRequests');
    Route::get('/doctor-proposals', [\App\Http\Controllers\Admin\DoctorProposalController::class, 'index'])->name('admin.doctor-proposals.index');
    Route::get('/doctor-verification', [PageController::class, 'doctorVerification'])->name('admin.doctorVerification');
    Route::get('/settings', [PageController::class, 'settings'])->name('admin.settings');
    Route::get('/leads', [PageController::class, 'leads'])->name('admin.leads');
    Route::get('/profit', [PageController::class, 'profit'])->name('admin.profit');
    Route::get('/notifications', [PageController::class, 'notifications'])->name('admin.notifications');
    Route::get('/subscriptions', [PageController::class, 'subscriptions'])->name('admin.subscriptions');
    Route::get('/profile', [PageController::class, 'profile'])->name('admin.profile');

    // Admin notification routes
    Route::post('/notifications/{id}/mark-read', [\App\Http\Controllers\Admin\AdminNotificationController::class, 'markAsRead'])->name('notifications.mark-read.admin');
    Route::post('/notifications/mark-all-read', [\App\Http\Controllers\Admin\AdminNotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-read.admin');
    Route::delete('/notifications/{id}', [\App\Http\Controllers\Admin\AdminNotificationController::class, 'destroy'])->name('notifications.destroy.admin');
    Route::get('/notifications/count', [\App\Http\Controllers\Admin\AdminNotificationController::class, 'getUnreadCount'])->name('notifications.count.admin');
    
    // Test route to create sample notifications (remove in production)
    Route::get('/test-notifications', function() {
        \App\Models\AdminMessage::create([
            'type' => 'proposal',
            'message' => 'Test: New business proposal submitted by Dr. John Doe. Please review and take action.',
            'data' => [
                'doctor_id' => 1,
                'doctor_name' => 'Dr. John Doe',
                'doctor_email' => 'john@example.com'
            ],
            'read' => false
        ]);
        
        return response()->json(['message' => 'Test notification created']);
    });
    
    // Test route to create business request and notify doctors (remove in production)
    Route::get('/test-business-request', function() {
        // Create a test business request
        $businessRequest = \App\Models\BusinessOrganizationRequest::create([
            'user_id' => null,
            'organization_name' => 'Test Medical Center',
            'email' => 'test@medical.com',
            'phone_number' => '1234567890',
            'camp_request_type' => 'medical',
            'specialty_id' => 1, // Assuming cardiology has ID 1
            'date_from' => now()->addDays(30),
            'date_to' => now()->addDays(32),
            'number_of_people' => 50,
            'location' => 'Test City',
            'description' => 'Test medical camp for demonstration',
            'status' => 'pending'
        ]);
        
        // Send notifications to doctors with matching specialty
        $matchingDoctors = \App\Models\Doctor::where('specialty_id', 1)
            ->where('approved_by_admin', true)
            ->get();
            
        foreach ($matchingDoctors as $doctor) {
            $doctorMessage = \App\Models\DoctorMessage::create([
                'doctor_id' => $doctor->id,
                'type' => 'business_request',
                'message' => "New business opportunity! {$businessRequest->organization_name} is looking for doctors for a {$businessRequest->camp_request_type} camp in {$businessRequest->location}. Submit your proposal now!",
                'read' => false
            ]);
            
            // Note: Broadcasting event would fire here but since BROADCAST_DRIVER=log, 
            // it won't actually broadcast. The polling system will pick up the new notifications.
            try {
                event(new \App\Events\DoctorMessageSent($doctorMessage));
            } catch (\Exception $e) {
                // Broadcasting may fail if not configured, but notification is still saved
            }
        }
        
        return response()->json([
            'message' => 'Test business request created and notifications sent to ' . $matchingDoctors->count() . ' doctors',
            'notifications_created' => $matchingDoctors->count(),
            'business_request_id' => $businessRequest->id,
            'note' => 'Since broadcasting is set to log, real-time notifications will be delivered via polling every 15 seconds'
        ]);
    });

    // Simple test route to create a direct doctor notification
    Route::get('/test-doctor-notification/{doctorId}', function($doctorId) {
        $doctor = \App\Models\Doctor::find($doctorId);
        if (!$doctor) {
            return response()->json(['error' => 'Doctor not found']);
        }
        
        $doctorMessage = \App\Models\DoctorMessage::create([
            'doctor_id' => $doctor->id,
            'type' => 'business_request',
            'message' => "Test notification: This is a test business opportunity for Dr. {$doctor->doctor_name}. Please check your notifications!",
            'read' => false
        ]);
        
        return response()->json([
            'message' => 'Test notification created for ' . $doctor->doctor_name,
            'notification_id' => $doctorMessage->id
        ]);
    });
  
    // Campaign CRUD routes
    Route::post('/campaigns', [CampaignController::class, 'store'])->name('campaigns.store');
    Route::get('/campaigns/{id}/show', [CampaignController::class, 'show'])->name('campaigns.show');
    Route::get('/campaigns/{id}', [CampaignController::class, 'show'])->name('campaigns.detail');
    Route::get('/campaigns/{id}/details', [CampaignController::class, 'getDetails'])->name('campaigns.details');
    Route::put('/campaigns/{id}', [CampaignController::class, 'update'])->name('campaigns.update');
    Route::delete('/campaigns/{id}', [CampaignController::class, 'destroy'])->name('campaigns.destroy');
    
    // Campaign approval routes
    Route::post('/campaigns/{id}/approve', [CampaignController::class, 'Campaignapprove'])->name('campaigns.approve');
    Route::post('/campaigns/{id}/reject', [CampaignController::class, 'Campaignreject'])->name('campaigns.reject');
    
    // Settings routes
    Route::post('/settings/update', [PageController::class, 'updateSettings'])->name('settings.update');
    
    // Profit export route
    Route::get('/profit/export', [PageController::class, 'exportProfits'])->name('profit.export');
    
    // Patient Registration routes
    Route::post('/patient-registrations', [PatientRegistrationController::class, 'store'])->name('patient-registrations.store');
    Route::get('/patient-registrations/{id}', [PatientRegistrationController::class, 'show'])->name('patient-registrations.show');
    Route::put('/patient-registrations/{id}', [PatientRegistrationController::class, 'update'])->name('patient-registrations.update');
    Route::delete('/patient-registrations/{id}', [PatientRegistrationController::class, 'destroy'])->name('patient-registrations.destroy');
    Route::get('/patient-registrations/export', [PatientRegistrationController::class, 'export'])->name('patient-registrations.export');
    
    // Sponsor Management routes
    Route::get('/sponsors/{id}', [CampaignSponsorController::class, 'show'])->name('admin.sponsors.show');
    Route::put('/sponsors/{id}', [CampaignSponsorController::class, 'update'])->name('admin.sponsors.update');
    Route::delete('/sponsors/{id}', [CampaignSponsorController::class, 'destroy'])->name('admin.sponsors.destroy');
    Route::get('/sponsors/export', [CampaignSponsorController::class, 'export'])->name('admin.sponsors.export');
    
    // Doctor Payment Management routes
    Route::post('/doctor-payments/{payment}/approve', [DoctorPaymentController::class, 'adminApprove'])->name('doctor-payments.approve');
    
    // Business Organization routes
    Route::post('/business-organization', [BusinessOrganizationController::class, 'store'])->name('admin.business-organization.store');
    Route::post('/doctor-business-requests/{businessRequest}/approve', [BusinessOrganizationController::class, 'approveDoctor'])->name('admin.doctor-business-requests.approve');
    
    // Doctor Proposal Management routes
    Route::get('/doctor-proposals/{proposal}', [\App\Http\Controllers\Admin\DoctorProposalController::class, 'show'])->name('admin.doctor-proposals.show');
    Route::post('/doctor-proposals/{proposal}/approve', [\App\Http\Controllers\Admin\DoctorProposalController::class, 'approve'])->name('admin.doctor-proposals.approve');
    Route::post('/doctor-proposals/{proposal}/reject', [\App\Http\Controllers\Admin\DoctorProposalController::class, 'reject'])->name('admin.doctor-proposals.reject');
    Route::get('/doctor-proposals/export', [\App\Http\Controllers\Admin\DoctorProposalController::class, 'export'])->name('admin.doctor-proposals.export');
});


//---------------------------------------------end of admin page view

Route::prefix('admin')->name('admin.')->group(function () {
    Route::resource('specialties', PageController::class)->only(['index', 'store', 'destroy']);
});




Route::get('/test-broadcast/{id}', function ($id) {
    event(new MessageReceived('🔔 Hello User ID ' . $id, $id));
    return 'Message sent to user ' . $id;
});

Route::post('/user/messages', [UserMessageController::class, 'store']);

// Debug route for business requests
Route::get('/debug/business-requests', function () {
    $requests = \App\Models\BusinessOrganizationRequest::latest()
        ->with(['specialty', 'hiredDoctor'])
        ->take(10)
        ->get();
    
    return response()->json([
        'total_requests' => $requests->count(),
        'recent_requests' => $requests->map(function($request) {
            return [
                'id' => $request->id,
                'organization_name' => $request->organization_name,
                'camp_type' => $request->camp_request_type,
                'specialty' => $request->specialty->name ?? 'No specialty',
                'participants' => $request->number_of_people,
                'location' => $request->location,
                'status' => $request->status,
                'hired_doctor' => $request->hiredDoctor->doctor_name ?? 'None',
                'created_at' => $request->created_at->format('Y-m-d H:i:s')
            ];
        })
    ]);
});

// Test doctor proposal form status
Route::get('/debug/doctor-proposal-form', function () {
    if (!auth()->guard('doctor')->check()) {
        return response()->json(['error' => 'Please login as doctor first']);
    }
    
    $doctor = auth()->guard('doctor')->user();
    
    // Check if business requests exist for this doctor's specialty
    $businessOrgRequests = \App\Models\BusinessOrganizationRequest::with(['specialty', 'hiredDoctor'])
        ->where('specialty_id', $doctor->specialty_id)
        ->orderBy('created_at', 'desc')
        ->get();
    
    // Check doctor's existing proposals
    $doctorProposals = \App\Models\DoctorProposal::where('doctor_id', $doctor->id)
        ->with('businessOrganizationRequest')
        ->orderBy('created_at', 'desc')
        ->get();
    
    return response()->json([
        'doctor_id' => $doctor->id,
        'doctor_name' => $doctor->doctor_name,
        'specialty_id' => $doctor->specialty_id,
        'specialty_name' => $doctor->specialty->name ?? 'No specialty',
        'available_business_requests' => $businessOrgRequests->count(),
        'business_requests' => $businessOrgRequests->map(function($request) {
            return [
                'id' => $request->id,
                'organization_name' => $request->organization_name,
                'camp_type' => $request->camp_request_type,
                'status' => $request->status,
                'created_at' => $request->created_at->format('Y-m-d H:i:s')
            ];
        }),
        'existing_proposals' => $doctorProposals->count(),
        'proposals' => $doctorProposals->map(function($proposal) {
            return [
                'id' => $proposal->id,
                'business_request' => $proposal->businessOrganizationRequest ? 
                    $proposal->businessOrganizationRequest->organization_name : 'General Proposal',
                'status' => $proposal->status,
                'message' => substr($proposal->message, 0, 100) . '...',
                'created_at' => $proposal->created_at->format('Y-m-d H:i:s')
            ];
        }),
        'form_action_route' => route('doctor.proposals.store'),
        'page_accessible' => true
    ]);
});

// Test route for referral notifications
Route::get('/test/referral-notification/{userId}', function ($userId) {
    $user = \App\Models\User::find($userId);
    if (!$user) {
        return response()->json(['error' => 'User not found'], 404);
    }
    
    // Create a test referral earning notification
    $testMessage = "🎉 Test notification: You earned ₹50 for referring a user to 'Test Campaign'. Your total earnings: ₹" . number_format(($user->total_earnings ?? 0) + 50, 2);
    
    // Create user message
    \App\Models\UserMessage::create([
        'user_id' => $userId,
        'message' => $testMessage,
        'type' => 'referral_earning',
        'is_read' => false,
    ]);
    
    // Broadcast real-time notification
    event(new \App\Events\MessageReceived($testMessage, $userId));
    
    return response()->json([
        'success' => true,
        'message' => 'Test referral notification sent to user ID: ' . $userId,
        'notification_content' => $testMessage
    ]);
})->name('test.referral.notification');

// Test routes for notification system
use App\Http\Controllers\Test\NotificationTestController;

Route::get('/test/notification/user/{userId}', [NotificationTestController::class, 'testUserNotification'])->name('test.notification.user');
Route::get('/test/notification/doctor/{doctorId}', [NotificationTestController::class, 'testDoctorNotification'])->name('test.notification.doctor');
Route::get('/test/notification/admin/{adminId}', [NotificationTestController::class, 'testAdminNotification'])->name('test.notification.admin');
Route::get('/test/notification/all', [NotificationTestController::class, 'testAllNotifications'])->name('test.notification.all');

// Database structure test route
Route::get('/test-db-structure', function() {
    try {
        // Check if patient_payments table exists
        $tables = DB::select("SHOW TABLES LIKE 'patient_payments'");
        
        if (empty($tables)) {
            return response()->json([
                'status' => 'error',
                'message' => 'patient_payments table does not exist'
            ]);
        }
        
        // Get table structure
        $columns = DB::select("DESCRIBE patient_payments");
        
        $columnNames = array_map(function($col) {
            return $col->Field;
        }, $columns);
        
        // Check for required columns
        $requiredColumns = ['id', 'user_id', 'type', 'amount', 'payment_status', 'razorpay_payout_id', 'bank_details', 'processed_at'];
        $missingColumns = array_diff($requiredColumns, $columnNames);
        
        return response()->json([
            'status' => 'success',
            'existing_columns' => $columnNames,
            'missing_columns' => $missingColumns,
            'table_structure' => $columns
        ]);
        
    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => $e->getMessage()
        ]);
    }
});

// Create missing tables
Route::get('/create-tables', function() {
    try {
        // Create doctor_payments table
        if (!Schema::hasTable('doctor_payments')) {
            Schema::create('doctor_payments', function ($table) {
                $table->id();
                $table->unsignedBigInteger('doctor_id');
                $table->decimal('amount', 10, 2);
                $table->string('payment_id')->nullable();
                $table->string('order_id')->nullable();
                $table->enum('payment_status', ['pending', 'processing', 'completed', 'success', 'failed', 'cancelled'])->default('pending');
                $table->json('payment_details')->nullable();
                $table->timestamp('payment_date')->nullable();
                $table->string('receipt_number')->nullable();
                $table->text('description')->nullable();
                $table->timestamps();
                
                $table->index('doctor_id');
                $table->index('payment_status');
            });
            
            $doctorTableCreated = true;
        } else {
            $doctorTableCreated = false;
        }
        
        // Create patient_payments table if it doesn't exist
        if (!Schema::hasTable('patient_payments')) {
            Schema::create('patient_payments', function ($table) {
                $table->id();
                $table->unsignedBigInteger('user_id');
                $table->enum('type', ['deposit', 'withdrawal'])->default('deposit');
                $table->decimal('amount', 10, 2);
                $table->enum('payment_status', ['pending', 'processing', 'completed', 'success', 'failed', 'cancelled'])->default('pending');
                $table->string('payment_method')->nullable();
                $table->string('razorpay_payment_id')->nullable();
                $table->string('razorpay_payout_id')->nullable();
                $table->json('bank_details')->nullable();
                $table->text('notes')->nullable();
                $table->timestamp('processed_at')->nullable();
                $table->timestamps();
                
                $table->index('user_id');
                $table->index('payment_status');
                $table->index('type');
            });
            
            $patientTableCreated = true;
        } else {
            $patientTableCreated = false;
        }
        
        // Insert test data
        if ($doctorTableCreated) {
            DB::table('doctor_payments')->insert([
                'doctor_id' => 1,
                'amount' => 1000.00,
                'payment_status' => 'pending',
                'description' => 'Test doctor payout',
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
        
        if ($patientTableCreated) {
            DB::table('patient_payments')->insert([
                'user_id' => 1,
                'type' => 'withdrawal',
                'amount' => 500.00,
                'payment_status' => 'pending',
                'notes' => 'Test patient withdrawal',
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
        
        return response()->json([
            'status' => 'success',
            'doctor_payments_created' => $doctorTableCreated,
            'patient_payments_created' => $patientTableCreated,
            'message' => 'Tables checked and created if needed'
        ]);
        
    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
    }
});

// Basic model test
Route::get('/test-models', function() {
    try {
        // Test if we can create instances
        $doctorPayment = new \App\Models\DoctorPayment();
        $patientPayment = new \App\Models\PatientPayment();
        
        // Check table existence
        $doctorTable = DB::select("SHOW TABLES LIKE 'doctor_payments'");
        $patientTable = DB::select("SHOW TABLES LIKE 'patient_payments'");
        
        return response()->json([
            'status' => 'success',
            'doctor_payment_model' => 'OK',
            'patient_payment_model' => 'OK',
            'doctor_payments_table' => !empty($doctorTable) ? 'EXISTS' : 'MISSING',
            'patient_payments_table' => !empty($patientTable) ? 'EXISTS' : 'MISSING',
            'doctor_table_columns' => !empty($doctorTable) ? array_map(function($col) { return $col->Field; }, DB::select("DESCRIBE doctor_payments")) : [],
            'patient_table_columns' => !empty($patientTable) ? array_map(function($col) { return $col->Field; }, DB::select("DESCRIBE patient_payments")) : []
        ]);
        
    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
    }
});

// Test doctor payment processing
Route::get('/test-doctor-payment/{id}', function($id) {
    try {
        // First, check if the table exists
        $tables = DB::select("SHOW TABLES LIKE 'doctor_payments'");
        if (empty($tables)) {
            return response()->json([
                'status' => 'error',
                'message' => 'doctor_payments table does not exist'
            ]);
        }
        
        // Get table structure
        $columns = DB::select("DESCRIBE doctor_payments");
        $columnNames = array_map(function($col) { return $col->Field; }, $columns);
        
        // Try to find a payment record
        $payment = \App\Models\DoctorPayment::find($id);
        
        return response()->json([
            'status' => 'success',
            'table_columns' => $columnNames,
            'payment_found' => $payment ? true : false,
            'payment_data' => $payment,
            'payment_details_type' => $payment ? gettype($payment->payment_details) : 'N/A'
        ]);
        
    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
    }
});

// Test the actual controller method
Route::get('/test-doctor-payout-process/{id}', function($id) {
    try {
        $controller = new \App\Http\Controllers\Admin\Pageview\PageController();
        $result = $controller->processDoctorPayout($id);
        return $result;
        
    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
    }
});

// Temporary test route for debugging WhatsApp conversations (remove in production)
Route::get('/test-conversation/{phone}', function($phone) {
    $whatsappNumber = urldecode($phone);
    
    // Get conversation history for this phone number
    $messages = \App\Models\WhatsappConversation::where('phone', $whatsappNumber)
        ->orderBy('created_at', 'asc')
        ->get();

    // Get conversation statistics
    $stats = [
        'total_messages' => \App\Models\WhatsappConversation::where('phone', $whatsappNumber)->count(),
        'replied_messages' => \App\Models\WhatsappConversation::where('phone', $whatsappNumber)
            ->where('is_responded', 1)->count(),
        'pending_messages' => \App\Models\WhatsappConversation::where('phone', $whatsappNumber)
            ->where('is_responded', 0)->count(),
        'lead_status' => \App\Models\WhatsappConversation::where('phone', $whatsappNumber)
            ->orderBy('created_at', 'desc')->first()?->lead_status ?? 'new',
    ];

    // Get user information
    $userInfo = [
        'phone' => $whatsappNumber,
        'name' => 'Test User (' . substr($whatsappNumber, -4) . ')',
        'type' => 'guest'
    ];

    return view('test-conversation', compact(
        'whatsappNumber',
        'messages',
        'stats',
        'userInfo'
    ));
});

// Webhook Monitor Routes (Admin)
Route::prefix('admin')->middleware(['auth:admin'])->group(function () {
    Route::get('/webhook/monitor', [App\Http\Controllers\Admin\WebhookMonitorController::class, 'index'])->name('admin.webhook.monitor');
    Route::get('/webhook/logs', [App\Http\Controllers\Admin\WebhookMonitorController::class, 'getLogs'])->name('admin.webhook.logs');
    Route::get('/webhook/received-messages', [App\Http\Controllers\Admin\WebhookMonitorController::class, 'getReceivedMessagesAjax'])->name('admin.webhook.received-messages');
    Route::post('/webhook/clear', [App\Http\Controllers\Admin\WebhookMonitorController::class, 'clearLogs'])->name('admin.webhook.clear');
    Route::post('/webhook/test', [App\Http\Controllers\Admin\WebhookMonitorController::class, 'testWebhook'])->name('admin.webhook.test');
    Route::post('/webhook/send-test-message', [App\Http\Controllers\Admin\WebhookMonitorController::class, 'sendTestMessage'])->name('admin.webhook.send-test');
    Route::get('/webhook/debug', [App\Http\Controllers\Admin\WebhookMonitorController::class, 'debug'])->name('admin.webhook.debug');
    Route::get('/webhook/live-logs', [App\Http\Controllers\Admin\WebhookMonitorController::class, 'getLiveLogs'])->name('admin.webhook.live-logs');
});

// Add these routes to your web.php file
