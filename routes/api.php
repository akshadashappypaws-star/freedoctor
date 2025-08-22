<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReferralController;
use App\Http\Controllers\WebhookController;
use App\Http\Controllers\WorkflowController;
use App\Services\WhatsappCloudApiService;
use App\Services\WhatsappTemplateValidator;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Broadcasting authentication for admin
Route::middleware(['auth:admin'])->post('/broadcasting/auth', function (Request $request) {
    return response()->json(['auth' => 'success']);
});

// WhatsApp Webhook Routes (no authentication needed for WhatsApp webhooks)
Route::prefix('webhook')->group(function () {
    Route::get('/whatsapp', [WebhookController::class, 'verify']);
    Route::post('/whatsapp', [WebhookController::class, 'handle']);
    Route::post('/whatsapp/test', [WebhookController::class, 'test']);
});

// Workflow Management Routes (Admin only)
Route::middleware(['auth:admin'])->prefix('workflows')->group(function () {
    Route::get('/', [WorkflowController::class, 'index']);
    Route::get('/statistics', [WorkflowController::class, 'statistics']);
    Route::get('/performance', [WorkflowController::class, 'performance']);
    Route::post('/search', [WorkflowController::class, 'search']);
    
    Route::get('/{workflow}', [WorkflowController::class, 'show']);
    Route::get('/{workflow}/status', [WorkflowController::class, 'status']);
    Route::get('/{workflow}/logs', [WorkflowController::class, 'logs']);
    Route::get('/{workflow}/errors', [WorkflowController::class, 'errors']);
    Route::get('/{workflow}/conversation', [WorkflowController::class, 'conversation']);
    Route::post('/{workflow}/retry', [WorkflowController::class, 'retry']);
    Route::delete('/{workflow}', [WorkflowController::class, 'destroy']);
});

// Referral API Routes
Route::middleware(['auth:user'])->group(function () {
    Route::post('/generate-referral-link', [ReferralController::class, 'generateReferralLink']);
    Route::post('/process-referral-from-localstorage', [ReferralController::class, 'processReferralFromLocalStorage']);
});

Route::post('/track-referral-click', [ReferralController::class, 'trackReferralClick']);
Route::post('/track-referral-conversion', [ReferralController::class, 'trackReferralConversion']);

// WhatsApp Test Routes
Route::post('/whatsapp/test-template', function (Request $request) {
    try {
        $templateName = $request->input('template');
        $phoneNumber = $request->input('phone');
        $parameters = $request->input('parameters', []);
        
        if (!$templateName || !$phoneNumber) {
            return response()->json([
                'success' => false,
                'error' => 'Template name and phone number are required'
            ], 400);
        }
        
        // Validate template
        $validation = WhatsappTemplateValidator::validateTemplate($templateName, $parameters);
        
        if (!$validation['valid']) {
            return response()->json([
                'success' => false,
                'error' => $validation['error'],
                'required_params' => $validation['required_params'] ?? []
            ], 400);
        }
        
        // Send message
        $whatsappService = new WhatsappCloudApiService();
        
        if (empty($parameters)) {
            // Template without parameters
            $result = $whatsappService->sendTemplate($phoneNumber, $templateName);
        } else {
            // Template with parameters
            $result = $whatsappService->sendMessage($phoneNumber, null, $templateName, $parameters);
        }
        
        return response()->json($result);
        
    } catch (Exception $e) {
        return response()->json([
            'success' => false,
            'error' => $e->getMessage()
        ], 500);
    }
});

Route::get('/whatsapp/templates', function () {
    try {
        $templates = WhatsappTemplateValidator::getAllTemplatesWithParams();
        return response()->json([
            'success' => true,
            'templates' => $templates
        ]);
    } catch (Exception $e) {
        return response()->json([
            'success' => false,
            'error' => $e->getMessage()
        ], 500);
    }
});