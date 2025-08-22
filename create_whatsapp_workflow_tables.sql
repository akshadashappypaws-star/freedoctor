-- WhatsApp Workflow System Database Tables
-- Run this SQL script in your freedoctor database

-- 1. Workflows Table
CREATE TABLE IF NOT EXISTS `workflows` (
    `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
    `name` varchar(255) NOT NULL,
    `status` enum('pending','running','completed','failed','paused') NOT NULL DEFAULT 'pending',
    `created_by` bigint(20) unsigned DEFAULT NULL,
    `user_id` bigint(20) unsigned DEFAULT NULL,
    `whatsapp_number` varchar(20) DEFAULT NULL,
    `intent` varchar(255) DEFAULT NULL,
    `json_plan` json DEFAULT NULL,
    `context_data` json DEFAULT NULL,
    `current_step` int(11) NOT NULL DEFAULT 0,
    `total_steps` int(11) NOT NULL DEFAULT 0,
    `started_at` timestamp NULL DEFAULT NULL,
    `completed_at` timestamp NULL DEFAULT NULL,
    `execution_time_ms` bigint(20) DEFAULT NULL,
    `created_at` timestamp NULL DEFAULT NULL,
    `updated_at` timestamp NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `workflows_created_by_foreign` (`created_by`),
    KEY `workflows_user_id_foreign` (`user_id`),
    KEY `workflows_status_index` (`status`),
    KEY `workflows_whatsapp_number_index` (`whatsapp_number`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2. Workflow Logs Table
CREATE TABLE IF NOT EXISTS `workflow_logs` (
    `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
    `workflow_id` bigint(20) unsigned NOT NULL,
    `step_name` varchar(255) NOT NULL,
    `step_number` int(11) NOT NULL,
    `machine_type` enum('ai','function','datatable','template','visualization') NOT NULL,
    `input_data` json DEFAULT NULL,
    `output_data` json DEFAULT NULL,
    `execution_time_ms` bigint(20) DEFAULT NULL,
    `status` enum('pending','running','completed','failed') NOT NULL DEFAULT 'pending',
    `error_message` text DEFAULT NULL,
    `metadata` json DEFAULT NULL,
    `created_at` timestamp NULL DEFAULT NULL,
    `updated_at` timestamp NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `workflow_logs_workflow_id_foreign` (`workflow_id`),
    KEY `workflow_logs_status_index` (`status`),
    KEY `workflow_logs_machine_type_index` (`machine_type`),
    CONSTRAINT `workflow_logs_workflow_id_foreign` FOREIGN KEY (`workflow_id`) REFERENCES `workflows` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 3. Workflow Errors Table
CREATE TABLE IF NOT EXISTS `workflow_errors` (
    `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
    `workflow_id` bigint(20) unsigned NOT NULL,
    `step_name` varchar(255) NOT NULL,
    `error_code` varchar(100) DEFAULT NULL,
    `error_message` text NOT NULL,
    `error_type` enum('validation','execution','timeout','api_error','system_error') NOT NULL DEFAULT 'execution',
    `stack_trace` text DEFAULT NULL,
    `input_data` json DEFAULT NULL,
    `context_data` json DEFAULT NULL,
    `retry_count` int(11) NOT NULL DEFAULT 0,
    `resolved` tinyint(1) NOT NULL DEFAULT 0,
    `resolved_at` timestamp NULL DEFAULT NULL,
    `resolved_by` bigint(20) unsigned DEFAULT NULL,
    `created_at` timestamp NULL DEFAULT NULL,
    `updated_at` timestamp NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `workflow_errors_workflow_id_foreign` (`workflow_id`),
    KEY `workflow_errors_error_type_index` (`error_type`),
    KEY `workflow_errors_resolved_index` (`resolved`),
    CONSTRAINT `workflow_errors_workflow_id_foreign` FOREIGN KEY (`workflow_id`) REFERENCES `workflows` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 4. Workflow Performance Metrics Table
CREATE TABLE IF NOT EXISTS `workflow_performance_metrics` (
    `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
    `workflow_id` bigint(20) unsigned NOT NULL,
    `metric_name` varchar(255) NOT NULL,
    `metric_value` decimal(10,4) NOT NULL,
    `metric_type` enum('time','count','rate','percentage','bytes') NOT NULL DEFAULT 'count',
    `step_name` varchar(255) DEFAULT NULL,
    `machine_type` enum('ai','function','datatable','template','visualization') DEFAULT NULL,
    `recorded_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `metadata` json DEFAULT NULL,
    `created_at` timestamp NULL DEFAULT NULL,
    `updated_at` timestamp NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `workflow_performance_metrics_workflow_id_foreign` (`workflow_id`),
    KEY `workflow_performance_metrics_metric_name_index` (`metric_name`),
    KEY `workflow_performance_metrics_machine_type_index` (`machine_type`),
    CONSTRAINT `workflow_performance_metrics_workflow_id_foreign` FOREIGN KEY (`workflow_id`) REFERENCES `workflows` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 5. Workflow Conversation History Table
CREATE TABLE IF NOT EXISTS `workflow_conversation_history` (
    `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
    `workflow_id` bigint(20) unsigned NOT NULL,
    `message_id` varchar(255) DEFAULT NULL,
    `message_type` enum('incoming','outgoing','system') NOT NULL,
    `message_content` text NOT NULL,
    `message_format` enum('text','image','document','audio','video','interactive') NOT NULL DEFAULT 'text',
    `sender` varchar(255) DEFAULT NULL,
    `recipient` varchar(255) DEFAULT NULL,
    `template_name` varchar(255) DEFAULT NULL,
    `template_parameters` json DEFAULT NULL,
    `delivery_status` enum('pending','sent','delivered','read','failed') NOT NULL DEFAULT 'pending',
    `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `metadata` json DEFAULT NULL,
    `created_at` timestamp NULL DEFAULT NULL,
    `updated_at` timestamp NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `workflow_conversation_history_workflow_id_foreign` (`workflow_id`),
    KEY `workflow_conversation_history_message_type_index` (`message_type`),
    KEY `workflow_conversation_history_delivery_status_index` (`delivery_status`),
    KEY `workflow_conversation_history_timestamp_index` (`timestamp`),
    CONSTRAINT `workflow_conversation_history_workflow_id_foreign` FOREIGN KEY (`workflow_id`) REFERENCES `workflows` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 6. Workflow Machine Configs Table
CREATE TABLE IF NOT EXISTS `workflow_machine_configs` (
    `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
    `machine_type` enum('ai','function','datatable','template','visualization') NOT NULL,
    `config_name` varchar(255) NOT NULL,
    `config_data` json NOT NULL,
    `is_active` tinyint(1) NOT NULL DEFAULT 1,
    `created_by` bigint(20) unsigned DEFAULT NULL,
    `description` text DEFAULT NULL,
    `version` varchar(20) NOT NULL DEFAULT '1.0',
    `created_at` timestamp NULL DEFAULT NULL,
    `updated_at` timestamp NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `workflow_machine_configs_machine_config_unique` (`machine_type`,`config_name`),
    KEY `workflow_machine_configs_machine_type_index` (`machine_type`),
    KEY `workflow_machine_configs_is_active_index` (`is_active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert default machine configurations
INSERT INTO `workflow_machine_configs` (`machine_type`, `config_name`, `config_data`, `description`, `version`) VALUES
('ai', 'default_gpt4', '{"model":"gpt-4","max_tokens":1000,"temperature":0.7,"system_prompt":"You are a helpful WhatsApp assistant for a medical platform."}', 'Default GPT-4 configuration for AI responses', '1.0'),
('function', 'user_lookup', '{"function_name":"lookupUser","parameters":["phone_number"],"timeout":5000}', 'Function to lookup user information by phone number', '1.0'),
('datatable', 'user_format', '{"table_format":"json","columns":["name","phone","status"],"max_rows":100}', 'Standard user data table formatting', '1.0'),
('template', 'welcome_message', '{"template_name":"welcome","parameters":["user_name"],"language":"en"}', 'Welcome message template configuration', '1.0'),
('visualization', 'conversation_flow', '{"chart_type":"flowchart","data_source":"conversation_history","refresh_interval":30}', 'Real-time conversation flow visualization', '1.0');

-- Create indexes for performance
CREATE INDEX idx_workflows_status_created ON workflows(status, created_at);
CREATE INDEX idx_workflow_logs_workflow_step ON workflow_logs(workflow_id, step_number);
CREATE INDEX idx_workflow_errors_unresolved ON workflow_errors(workflow_id, resolved);
CREATE INDEX idx_workflow_conversation_timestamp ON workflow_conversation_history(workflow_id, timestamp);

-- Add comments for better documentation
ALTER TABLE workflows COMMENT = 'Main table for WhatsApp workflow scenarios and their execution state';
ALTER TABLE workflow_logs COMMENT = 'Detailed logs of each step execution in workflows';
ALTER TABLE workflow_errors COMMENT = 'Error tracking and debugging information for workflows';
ALTER TABLE workflow_performance_metrics COMMENT = 'Performance monitoring and analytics data';
ALTER TABLE workflow_conversation_history COMMENT = 'WhatsApp message history for each workflow session';
ALTER TABLE workflow_machine_configs COMMENT = 'Configuration settings for AI, Function, DataTable, Template, and Visualization machines';

-- Success message
SELECT 'WhatsApp Workflow System tables created successfully!' AS Status;
