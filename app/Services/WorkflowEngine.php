<?php

namespace App\Services;

use App\Models\Workflow;
use App\Models\WorkflowLog;
use App\Models\WorkflowError;
use App\Models\WorkflowConversationHistory;
use App\Models\WorkflowPerformanceMetric;
use App\Machines\AiMachine;
use App\Machines\FunctionMachine;
use App\Machines\DataTableMachine;
use App\Machines\TemplateMachine;
use App\Machines\VisualizationMachine;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class WorkflowEngine
{
    private array $machines = [];
    private ?Workflow $currentWorkflow = null;

    public function __construct()
    {
        // Initialize available machines
        $this->machines = [
            'ai' => AiMachine::class,
            'function' => FunctionMachine::class,
            'datatable' => DataTableMachine::class,
            'template' => TemplateMachine::class,
            'visualization' => VisualizationMachine::class
        ];
    }

    /**
     * Process incoming WhatsApp message and execute workflow
     */
    public function processMessage(
        string $whatsappNumber,
        string $messageContent,
        ?int $userId = null,
        array $context = []
    ): array {
        
        try {
            // Log incoming message
            $conversationHistory = WorkflowConversationHistory::createIncoming(
                $whatsappNumber,
                $messageContent,
                null,
                $userId
            );

            // Create or get existing workflow
            $workflow = $this->createWorkflow($whatsappNumber, $messageContent, $userId, $context);
            $this->currentWorkflow = $workflow;

            // Update conversation with workflow ID
            $conversationHistory->update(['workflow_id' => $workflow->id]);

            // Start workflow execution
            $workflow->markAsStarted();

            // Log workflow started event
            $this->logVisualizationEvent('started', [
                'workflow_id' => $workflow->id,
                'whatsapp_number' => $whatsappNumber,
                'initial_message' => $messageContent
            ]);

            // Execute workflow steps
            $result = $this->executeWorkflow($workflow, $messageContent, $context);

            // Mark workflow as completed
            $workflow->markAsCompleted();

            // Log workflow completed event
            $this->logVisualizationEvent('completed', [
                'workflow_id' => $workflow->id,
                'total_steps' => $workflow->total_steps,
                'execution_time' => $workflow->execution_time_ms
            ]);

            // Record performance metrics
            WorkflowPerformanceMetric::recordResponseTime(
                $workflow->id,
                $workflow->execution_time_ms ?? 0
            );

            return [
                'success' => true,
                'workflow_id' => $workflow->id,
                'result' => $result,
                'execution_time_ms' => $workflow->execution_time_ms
            ];

        } catch (Exception $e) {
            if ($this->currentWorkflow) {
                $this->currentWorkflow->markAsFailed();
                
                // Log workflow failed event
                $this->logVisualizationEvent('error', [
                    'workflow_id' => $this->currentWorkflow->id,
                    'error_message' => $e->getMessage(),
                    'step_number' => $this->currentWorkflow->current_step
                ]);
            }

            Log::error('Workflow execution failed', [
                'whatsapp_number' => $whatsappNumber,
                'message' => $messageContent,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            // Send error response
            $this->sendErrorResponse($whatsappNumber, $e->getMessage());

            return [
                'success' => false,
                'error' => $e->getMessage(),
                'workflow_id' => $this->currentWorkflow?->id
            ];
        }
    }

    /**
     * Create new workflow
     */
    private function createWorkflow(
        string $whatsappNumber,
        string $messageContent,
        ?int $userId,
        array $context
    ): Workflow {
        
        return Workflow::create([
            'name' => 'WhatsApp Chat - ' . now()->format('Y-m-d H:i:s'),
            'status' => 'pending',
            'user_id' => $userId,
            'whatsapp_number' => $whatsappNumber,
            'context_data' => $context,
            'current_step' => 0,
            'total_steps' => 0 // Will be updated after AI planning
        ]);
    }

    /**
     * Execute workflow steps
     */
    private function executeWorkflow(Workflow $workflow, string $messageContent, array $context): array
    {
        // Step 1: AI Analysis and Planning
        $planningResult = $this->executeAiPlanning($workflow, $messageContent, $context);
        
        // Update workflow with plan
        $workflow->update([
            'intent' => $planningResult['intent'] ?? 'general_inquiry',
            'json_plan' => $planningResult['plan'] ?? [],
            'total_steps' => count($planningResult['plan']['steps'] ?? []) + 2 // +2 for AI and final template
        ]);

        $workflowPlan = $planningResult['plan'];
        $workflowSteps = $workflowPlan['steps'] ?? [];

        // Create visualization data
        $this->updateVisualizationData($workflow, $workflowPlan);

        $lastResult = $planningResult;

        // Execute each step in the plan
        foreach ($workflowSteps as $stepConfig) {
            $stepNumber = $stepConfig['step'];
            $machineType = $stepConfig['machine'];
            $action = $stepConfig['action'];
            $parameters = $stepConfig['parameters'] ?? [];

            // Merge previous result into parameters
            if (isset($lastResult['output'])) {
                $parameters = array_merge($parameters, ['previous_output' => $lastResult['output']]);
            }

            // Execute the step
            $stepResult = $this->executeStep($workflow, $stepNumber, $machineType, $action, $parameters);
            
            // Update workflow progress
            $workflow->incrementStep();

            // Broadcast step progress
            $this->broadcastStepProgress($workflow, $stepNumber, $machineType, 'completed', $stepResult);

            $lastResult = $stepResult;
        }

        // Final step: Send response via template machine
        $finalStep = count($workflowSteps) + 2;
        $responseResult = $this->executeFinalResponse($workflow, $finalStep, $lastResult);

        return [
            'workflow_result' => $responseResult,
            'total_steps_executed' => $workflow->current_step,
            'final_response' => $responseResult['response'] ?? 'Workflow completed successfully'
        ];
    }

    /**
     * Execute AI planning step
     */
    private function executeAiPlanning(Workflow $workflow, string $messageContent, array $context): array
    {
        $aiMachine = new AiMachine($workflow);

        // First, analyze intent
        $intentResult = $aiMachine->execute([
            'message' => $messageContent,
            'intent_type' => 'analyze_intent'
        ], 1);

        // Then generate workflow plan
        $planResult = $aiMachine->execute([
            'message' => $messageContent,
            'intent_type' => 'generate_plan',
            'context' => array_merge($context, ['intent_analysis' => $intentResult])
        ], 2);

        // Broadcast AI step completion
        $this->broadcastStepProgress($workflow, 1, 'ai', 'completed', $intentResult);
        $this->broadcastStepProgress($workflow, 2, 'ai', 'completed', $planResult);

        return [
            'intent' => $intentResult['intent'],
            'confidence' => $intentResult['confidence'],
            'plan' => $planResult['plan'],
            'output' => ['intent_result' => $intentResult, 'plan_result' => $planResult]
        ];
    }

    /**
     * Execute a single workflow step
     */
    private function executeStep(
        Workflow $workflow,
        int $stepNumber,
        string $machineType,
        string $action,
        array $parameters
    ): array {
        
        if (!isset($this->machines[$machineType])) {
            throw new Exception("Machine type '{$machineType}' not found");
        }

        $machineClass = $this->machines[$machineType];
        $machine = new $machineClass($workflow);

        // Broadcast step started
        $this->broadcastStepProgress($workflow, $stepNumber, $machineType, 'running');

        // Prepare input for the machine
        $input = array_merge($parameters, [
            'action' => $action,
            'workflow_id' => $workflow->id,
            'whatsapp_number' => $workflow->whatsapp_number
        ]);

        // Execute the machine
        $result = $machine->execute($input, $stepNumber);

        return [
            'step' => $stepNumber,
            'machine' => $machineType,
            'action' => $action,
            'output' => $result,
            'success' => true
        ];
    }

    /**
     * Execute final response step
     */
    private function executeFinalResponse(Workflow $workflow, int $stepNumber, array $lastResult): array
    {
        $templateMachine = new TemplateMachine($workflow);

        // Determine what type of response to send based on last result
        $responseAction = $this->determineResponseAction($lastResult);
        
        $input = [
            'action' => $responseAction,
            'data' => $lastResult['output'] ?? [],
            'options' => [
                'whatsapp_number' => $workflow->whatsapp_number,
                'workflow_id' => $workflow->id
            ]
        ];

        $result = $templateMachine->execute($input, $stepNumber);

        // Log outgoing message
        if (isset($result['message'])) {
            WorkflowConversationHistory::createOutgoing(
                $workflow->whatsapp_number,
                $result['message'],
                $workflow->id,
                $workflow->user_id,
                $result['message_type'] === 'template',
                $result['template_name'] ?? null
            );
        }

        return [
            'response_sent' => true,
            'response_type' => $responseAction,
            'response_data' => $result
        ];
    }

    /**
     * Determine appropriate response action based on workflow result
     */
    private function determineResponseAction(array $lastResult): string
    {
        $output = $lastResult['output'] ?? [];

        // Check what type of data we have
        if (isset($output['doctors'])) {
            return 'sendDoctorList';
        }
        
        if (isset($output['health_camps'])) {
            return 'sendHealthCampInfo';
        }
        
        if (isset($output['registration_id'])) {
            return 'sendRegistrationConfirmation';
        }
        
        if (isset($output['payment_id'])) {
            return 'sendPaymentReminder';
        }

        // Default to generic response
        return 'sendGenericResponse';
    }

    /**
     * Send error response to user
     */
    private function sendErrorResponse(string $whatsappNumber, string $errorMessage): void
    {
        try {
            $whatsappService = app(WhatsAppService::class);
            
            $errorType = $this->categorizeError($errorMessage);
            $userFriendlyMessage = $this->getUserFriendlyErrorMessage($errorType);
            
            $whatsappService->sendMessage($whatsappNumber, $userFriendlyMessage);

            // Log outgoing error message
            WorkflowConversationHistory::createOutgoing(
                $whatsappNumber,
                $userFriendlyMessage,
                null,
                null,
                false
            );

        } catch (Exception $e) {
            Log::error('Failed to send error response', [
                'whatsapp_number' => $whatsappNumber,
                'original_error' => $errorMessage,
                'send_error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Categorize error for user-friendly response
     */
    private function categorizeError(string $errorMessage): string
    {
        $errorMessage = strtolower($errorMessage);

        if (str_contains($errorMessage, 'location') || str_contains($errorMessage, 'address')) {
            return 'location_not_found';
        }
        
        if (str_contains($errorMessage, 'payment') || str_contains($errorMessage, 'razorpay')) {
            return 'payment_failed';
        }
        
        if (str_contains($errorMessage, 'full') || str_contains($errorMessage, 'booked')) {
            return 'registration_full';
        }
        
        if (str_contains($errorMessage, 'timeout') || str_contains($errorMessage, 'network')) {
            return 'system_error';
        }

        return 'general';
    }

    /**
     * Get user-friendly error message
     */
    private function getUserFriendlyErrorMessage(string $errorType): string
    {
        $messages = [
            'location_not_found' => "📍 Sorry, I couldn't find that location. Please provide a more specific address or try a nearby landmark.",
            'payment_failed' => "💳 Payment processing failed. Please check your payment details and try again, or contact support.",
            'registration_full' => "🎫 Sorry, this health camp is fully booked. Let me show you other available options.",
            'system_error' => "⚠️ We're experiencing technical difficulties. Please try again in a few minutes.",
            'general' => "😔 Something went wrong while processing your request. Please try again or type 'help' for assistance."
        ];

        return $messages[$errorType] ?? $messages['general'];
    }

    /**
     * Update visualization data
     */
    private function updateVisualizationData(Workflow $workflow, array $workflowPlan): void
    {
        try {
            $visualizationMachine = new VisualizationMachine($workflow);
            
            $visualizationMachine->execute([
                'action' => 'createVisualizationData',
                'data' => [
                    'workflow_id' => $workflow->id,
                    'workflow_plan' => $workflowPlan,
                    'current_step' => $workflow->current_step
                ]
            ], 0);

        } catch (Exception $e) {
            Log::warning('Failed to update visualization data', [
                'workflow_id' => $workflow->id,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Broadcast step progress
     */
    private function broadcastStepProgress(
        Workflow $workflow,
        int $stepNumber,
        string $machineType,
        string $status,
        array $stepData = null
    ): void {
        try {
            $visualizationMachine = new VisualizationMachine($workflow);
            
            $visualizationMachine->execute([
                'action' => 'broadcastStepProgress',
                'data' => [
                    'workflow_id' => $workflow->id,
                    'step_number' => $stepNumber,
                    'machine_type' => $machineType,
                    'step_status' => $status,
                    'step_data' => $stepData
                ]
            ], 0);

        } catch (Exception $e) {
            Log::warning('Failed to broadcast step progress', [
                'workflow_id' => $workflow->id,
                'step_number' => $stepNumber,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Log visualization event
     */
    private function logVisualizationEvent(string $eventType, array $eventData): void
    {
        try {
            if ($this->currentWorkflow) {
                $visualizationMachine = new VisualizationMachine($this->currentWorkflow);
                
                $visualizationMachine->execute([
                    'action' => 'logWorkflowEvent',
                    'data' => array_merge($eventData, [
                        'event_type' => $eventType
                    ])
                ], 0);
            }

        } catch (Exception $e) {
            Log::warning('Failed to log visualization event', [
                'event_type' => $eventType,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Get workflow status
     */
    public function getWorkflowStatus(int $workflowId): array
    {
        $workflow = Workflow::with(['logs', 'errors', 'conversationHistory'])->find($workflowId);
        
        if (!$workflow) {
            throw new Exception("Workflow not found with ID: {$workflowId}");
        }

        return [
            'workflow' => $workflow->toArray(),
            'progress_percentage' => $workflow->getProgressPercentage(),
            'current_step' => $workflow->current_step,
            'total_steps' => $workflow->total_steps,
            'status' => $workflow->status,
            'logs_count' => $workflow->logs->count(),
            'errors_count' => $workflow->errors->count(),
            'conversation_count' => $workflow->conversationHistory->count()
        ];
    }

    /**
     * Retry failed workflow
     */
    public function retryWorkflow(int $workflowId): array
    {
        $workflow = Workflow::find($workflowId);
        
        if (!$workflow) {
            throw new Exception("Workflow not found with ID: {$workflowId}");
        }

        if ($workflow->status !== 'failed') {
            throw new Exception("Can only retry failed workflows");
        }

        // Get the last user message
        $lastUserMessage = $workflow->conversationHistory()
            ->where('message_type', 'incoming')
            ->latest()
            ->first();

        if (!$lastUserMessage) {
            throw new Exception("No user message found to retry");
        }

        // Process the message again
        return $this->processMessage(
            $workflow->whatsapp_number,
            $lastUserMessage->message_content,
            $workflow->user_id,
            $workflow->context_data ?? []
        );
    }
}
