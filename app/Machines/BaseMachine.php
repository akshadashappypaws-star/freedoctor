<?php

namespace App\Machines;

use App\Models\Workflow;
use App\Models\WorkflowLog;
use App\Models\WorkflowError;
use Exception;

abstract class BaseMachine
{
    protected string $machineType;
    protected Workflow $workflow;
    protected ?WorkflowLog $currentLog = null;

    public function __construct(Workflow $workflow)
    {
        $this->workflow = $workflow;
    }

    /**
     * Execute the machine with input data
     */
    abstract public function execute(array $input, int $stepNumber): array;

    /**
     * Get machine type
     */
    public function getMachineType(): string
    {
        return $this->machineType;
    }

    /**
     * Start logging for a step
     */
    protected function startLog(int $stepNumber, string $action = null, array $input = []): WorkflowLog
    {
        $this->currentLog = WorkflowLog::create([
            'workflow_id' => $this->workflow->id,
            'step_number' => $stepNumber,
            'machine_type' => $this->machineType,
            'machine_action' => $action,
            'input_json' => $input,
            'status' => 'running',
            'started_at' => now()
        ]);

        return $this->currentLog;
    }

    /**
     * Complete the current log
     */
    protected function completeLog(array $output = []): void
    {
        if ($this->currentLog) {
            $this->currentLog->markAsCompleted($output);
        }
    }

    /**
     * Fail the current log
     */
    protected function failLog(string $error): void
    {
        if ($this->currentLog) {
            $this->currentLog->markAsFailed($error);
        }
    }

    /**
     * Log an error
     */
    protected function logError(
        int $stepNumber,
        string $errorType,
        string $errorMessage,
        ?string $stackTrace = null,
        ?array $contextData = null,
        bool $isRecoverable = false,
        ?string $recoverySuggestion = null
    ): WorkflowError {
        return WorkflowError::createError(
            $this->workflow->id,
            $stepNumber,
            $this->machineType,
            $errorType,
            $errorMessage,
            $this->currentLog?->id,
            $stackTrace,
            $contextData,
            $isRecoverable,
            $recoverySuggestion
        );
    }

    /**
     * Safe execution wrapper
     */
    protected function safeExecute(callable $callback, int $stepNumber, string $action = null, array $input = []): array
    {
        $this->startLog($stepNumber, $action, $input);

        try {
            $result = $callback();
            $this->completeLog($result);
            return $result;
        } catch (Exception $e) {
            $this->failLog($e->getMessage());
            $this->logError(
                $stepNumber,
                get_class($e),
                $e->getMessage(),
                $e->getTraceAsString(),
                ['input' => $input],
                $this->isRecoverableError($e)
            );
            throw $e;
        }
    }

    /**
     * Determine if an error is recoverable
     */
    protected function isRecoverableError(Exception $e): bool
    {
        // Override in child classes for specific error handling
        return false;
    }

    /**
     * Validate input data
     */
    protected function validateInput(array $input, array $requiredFields): void
    {
        foreach ($requiredFields as $field) {
            if (!isset($input[$field])) {
                throw new Exception("Required field '{$field}' is missing from input");
            }
        }
    }

    /**
     * Get configuration for this machine
     */
    protected function getConfig(string $configName): ?array
    {
        return \App\Models\WorkflowMachineConfig::getConfig($this->machineType, $configName);
    }
}
