<?php

require_once __DIR__ . '/vendor/autoload.php';

class MigrationFiller
{
    public function fillAllMigrations()
    {
        echo "🔄 Filling migration files with table schemas...\n";
        
        // Fill each migration with appropriate schema
        $this->fillUsersTable();
        $this->fillPasswordResetTokensTable();
        $this->fillFailedJobsTable();
        $this->fillPersonalAccessTokensTable();
        $this->fillSessionsTable();
        $this->fillCacheTable();
        $this->fillJobsTable();
        $this->fillNotificationsTable();
        $this->fillAdminsTable();
        $this->fillAdminMessagesTable();
        $this->fillAdminEarningsTable();
        $this->fillAdminSettingsTable();
        $this->fillDoctorsTable();
        $this->fillSpecialtiesTable();
        $this->fillDoctorMessagesTable();
        $this->fillDoctorPaymentsTable();
        $this->fillDoctorProposalsTable();
        $this->fillDoctorWithdrawalsTable();
        $this->fillCategoriesTable();
        $this->fillCampaignsTable();
        $this->fillCampaignReferralsTable();
        $this->fillCampaignSponsorsTable();
        $this->fillPatientRegistrationsTable();
        $this->fillPatientPaymentsTable();
        $this->fillBusinessRequestsTable();
        $this->fillBusinessOrganizationRequestsTable();
        $this->fillOrganicLeadsTable();
        $this->fillUserMessagesTable();
        $this->fillWhatsAppConversationsTable();
        $this->fillWhatsAppMessagesTable();
        $this->fillWhatsAppAutoRepliesTable();
        $this->fillWhatsAppTemplatesTable();
        $this->fillWhatsAppBulkMessagesTable();
        $this->fillWhatsAppMediaTable();
        $this->fillWhatsAppAutomationRulesTable();
        $this->fillWhatsAppAiAnalysisTable();
        $this->fillWhatsAppChatgptPromptsTable();
        $this->fillWhatsAppChatgptContextsTable();
        $this->fillWhatsAppDefaultResponsesTable();
        $this->fillWhatsAppLeadScoresTable();
        $this->fillWhatsAppMessageFlowsTable();
        $this->fillWhatsAppSystemHealthsTable();
        $this->fillWhatsAppTemplateCampaignsTable();
        $this->fillWhatsAppTemplateTableLinksTable();
        $this->fillWhatsAppUserBehaviorsTable();
        $this->fillWhatsAppWeeklyReportsTable();
        $this->fillWorkflowsTable();
        $this->fillWorkflowLogsTable();
        $this->fillWorkflowErrorsTable();
        $this->fillWorkflowConversationHistoriesTable();
        $this->fillWorkflowMachineConfigsTable();
        $this->fillWorkflowPerformanceMetricsTable();
        
        echo "✅ All migrations filled successfully!\n";
    }
    
    private function fillUsersTable()
    {
        $this->fillMigration('create_users_table', '
            $table->id();
            $table->string("name");
            $table->string("email")->unique();
            $table->timestamp("email_verified_at")->nullable();
            $table->string("password");
            $table->string("phone")->nullable();
            $table->string("address")->nullable();
            $table->rememberToken();
            $table->timestamps();
        ');
    }
    
    private function fillPasswordResetTokensTable()
    {
        $this->fillMigration('create_password_reset_tokens_table', '
            $table->string("email")->primary();
            $table->string("token");
            $table->timestamp("created_at")->nullable();
        ');
    }
    
    private function fillFailedJobsTable()
    {
        $this->fillMigration('create_failed_jobs_table', '
            $table->id();
            $table->string("uuid")->unique();
            $table->text("connection");
            $table->text("queue");
            $table->longText("payload");
            $table->longText("exception");
            $table->timestamp("failed_at")->useCurrent();
        ');
    }
    
    private function fillPersonalAccessTokensTable()
    {
        $this->fillMigration('create_personal_access_tokens_table', '
            $table->id();
            $table->morphs("tokenable");
            $table->string("name");
            $table->string("token", 64)->unique();
            $table->text("abilities")->nullable();
            $table->timestamp("last_used_at")->nullable();
            $table->timestamp("expires_at")->nullable();
            $table->timestamps();
        ');
    }
    
    private function fillSessionsTable()
    {
        $this->fillMigration('create_sessions_table', '
            $table->string("id")->primary();
            $table->foreignId("user_id")->nullable()->index();
            $table->string("ip_address", 45)->nullable();
            $table->text("user_agent")->nullable();
            $table->longText("payload");
            $table->integer("last_activity")->index();
        ');
    }
    
    private function fillCacheTable()
    {
        $this->fillMigration('create_cache_table', '
            $table->string("key")->primary();
            $table->mediumText("value");
            $table->integer("expiration");
        ');
    }
    
    private function fillJobsTable()
    {
        $this->fillMigration('create_jobs_table', '
            $table->id();
            $table->string("queue")->index();
            $table->longText("payload");
            $table->unsignedTinyInteger("attempts");
            $table->unsignedInteger("reserved_at")->nullable();
            $table->unsignedInteger("available_at");
            $table->unsignedInteger("created_at");
        ');
    }
    
    private function fillNotificationsTable()
    {
        $this->fillMigration('create_notifications_table', '
            $table->uuid("id")->primary();
            $table->string("type");
            $table->morphs("notifiable");
            $table->text("data");
            $table->timestamp("read_at")->nullable();
            $table->timestamps();
        ');
    }
    
    private function fillAdminsTable()
    {
        $this->fillMigration('create_admins_table', '
            $table->id();
            $table->string("name");
            $table->string("email")->unique();
            $table->timestamp("email_verified_at")->nullable();
            $table->string("password");
            $table->string("role")->default("admin");
            $table->rememberToken();
            $table->timestamps();
        ');
    }
    
    private function fillAdminMessagesTable()
    {
        $this->fillMigration('create_admin_messages_table', '
            $table->id();
            $table->unsignedBigInteger("admin_id");
            $table->string("to_number");
            $table->text("message");
            $table->string("type")->default("text");
            $table->string("status")->default("pending");
            $table->timestamps();
            $table->foreign("admin_id")->references("id")->on("admins")->onDelete("cascade");
        ');
    }
    
    private function fillAdminEarningsTable()
    {
        $this->fillMigration('create_admin_earnings_table', '
            $table->id();
            $table->unsignedBigInteger("admin_id");
            $table->decimal("amount", 10, 2);
            $table->string("source");
            $table->string("type")->default("commission");
            $table->text("description")->nullable();
            $table->timestamps();
            $table->foreign("admin_id")->references("id")->on("admins")->onDelete("cascade");
        ');
    }
    
    private function fillAdminSettingsTable()
    {
        $this->fillMigration('create_admin_settings_table', '
            $table->id();
            $table->string("key")->unique();
            $table->text("value")->nullable();
            $table->string("type")->default("string");
            $table->text("description")->nullable();
            $table->timestamps();
        ');
    }
    
    private function fillDoctorsTable()
    {
        $this->fillMigration('create_doctors_table', '
            $table->id();
            $table->string("name");
            $table->string("email")->unique();
            $table->string("password");
            $table->string("phone");
            $table->string("license_number")->unique();
            $table->string("specialization");
            $table->text("qualification");
            $table->integer("experience");
            $table->decimal("consultation_fee", 8, 2);
            $table->text("bio")->nullable();
            $table->string("profile_photo")->nullable();
            $table->string("status")->default("pending");
            $table->json("documents")->nullable();
            $table->decimal("latitude", 10, 8)->nullable();
            $table->decimal("longitude", 11, 8)->nullable();
            $table->string("address")->nullable();
            $table->timestamps();
        ');
    }
    
    private function fillSpecialtiesTable()
    {
        $this->fillMigration('create_specialties_table', '
            $table->id();
            $table->string("name")->unique();
            $table->text("description")->nullable();
            $table->string("icon")->nullable();
            $table->boolean("active")->default(true);
            $table->timestamps();
        ');
    }
    
    private function fillDoctorMessagesTable()
    {
        $this->fillMigration('create_doctor_messages_table', '
            $table->id();
            $table->unsignedBigInteger("doctor_id");
            $table->string("to_number");
            $table->text("message");
            $table->string("type")->default("text");
            $table->string("status")->default("pending");
            $table->timestamps();
            $table->foreign("doctor_id")->references("id")->on("doctors")->onDelete("cascade");
        ');
    }
    
    private function fillDoctorPaymentsTable()
    {
        $this->fillMigration('create_doctor_payments_table', '
            $table->id();
            $table->unsignedBigInteger("doctor_id");
            $table->decimal("amount", 10, 2);
            $table->string("transaction_id")->unique();
            $table->string("payment_method");
            $table->string("status")->default("pending");
            $table->string("type")->default("consultation");
            $table->text("description")->nullable();
            $table->json("payment_details")->nullable();
            $table->timestamps();
            $table->foreign("doctor_id")->references("id")->on("doctors")->onDelete("cascade");
        ');
    }
    
    private function fillDoctorProposalsTable()
    {
        $this->fillMigration('create_doctor_proposals_table', '
            $table->id();
            $table->unsignedBigInteger("doctor_id");
            $table->unsignedBigInteger("campaign_id");
            $table->text("proposal");
            $table->decimal("quoted_amount", 10, 2);
            $table->string("status")->default("pending");
            $table->text("admin_notes")->nullable();
            $table->timestamps();
            $table->foreign("doctor_id")->references("id")->on("doctors")->onDelete("cascade");
            $table->foreign("campaign_id")->references("id")->on("campaigns")->onDelete("cascade");
        ');
    }
    
    private function fillDoctorWithdrawalsTable()
    {
        $this->fillMigration('create_doctor_withdrawals_table', '
            $table->id();
            $table->unsignedBigInteger("doctor_id");
            $table->decimal("amount", 10, 2);
            $table->string("account_details");
            $table->string("status")->default("pending");
            $table->text("admin_notes")->nullable();
            $table->timestamp("processed_at")->nullable();
            $table->timestamps();
            $table->foreign("doctor_id")->references("id")->on("doctors")->onDelete("cascade");
        ');
    }
    
    private function fillCategoriesTable()
    {
        $this->fillMigration('create_categories_table', '
            $table->id();
            $table->string("name")->unique();
            $table->text("description")->nullable();
            $table->string("type")->default("campaign");
            $table->boolean("active")->default(true);
            $table->timestamps();
        ');
    }
    
    private function fillCampaignsTable()
    {
        $this->fillMigration('create_campaigns_table', '
            $table->id();
            $table->string("title");
            $table->text("description");
            $table->decimal("budget", 10, 2);
            $table->unsignedBigInteger("category_id");
            $table->string("target_audience");
            $table->json("requirements")->nullable();
            $table->string("status")->default("active");
            $table->date("start_date");
            $table->date("end_date");
            $table->string("thumbnail")->nullable();
            $table->decimal("latitude", 10, 8)->nullable();
            $table->decimal("longitude", 11, 8)->nullable();
            $table->string("location")->nullable();
            $table->timestamps();
            $table->foreign("category_id")->references("id")->on("categories")->onDelete("cascade");
        ');
    }
    
    private function fillCampaignReferralsTable()
    {
        $this->fillMigration('create_campaign_referrals_table', '
            $table->id();
            $table->unsignedBigInteger("campaign_id");
            $table->string("referrer_phone");
            $table->string("referee_phone");
            $table->decimal("commission", 8, 2)->default(0);
            $table->string("status")->default("pending");
            $table->timestamps();
            $table->foreign("campaign_id")->references("id")->on("campaigns")->onDelete("cascade");
        ');
    }
    
    private function fillCampaignSponsorsTable()
    {
        $this->fillMigration('create_campaign_sponsors_table', '
            $table->id();
            $table->unsignedBigInteger("campaign_id");
            $table->string("sponsor_name");
            $table->string("sponsor_contact");
            $table->decimal("sponsored_amount", 10, 2);
            $table->string("status")->default("active");
            $table->timestamps();
            $table->foreign("campaign_id")->references("id")->on("campaigns")->onDelete("cascade");
        ');
    }
    
    private function fillPatientRegistrationsTable()
    {
        $this->fillMigration('create_patient_registrations_table', '
            $table->id();
            $table->string("name");
            $table->string("phone")->unique();
            $table->string("email")->nullable();
            $table->integer("age");
            $table->string("gender");
            $table->text("medical_history")->nullable();
            $table->string("emergency_contact")->nullable();
            $table->string("address")->nullable();
            $table->string("status")->default("active");
            $table->timestamps();
        ');
    }
    
    private function fillPatientPaymentsTable()
    {
        $this->fillMigration('create_patient_payments_table', '
            $table->id();
            $table->string("patient_phone");
            $table->decimal("amount", 10, 2);
            $table->string("transaction_id")->unique();
            $table->string("payment_method");
            $table->string("status")->default("pending");
            $table->string("type")->default("consultation");
            $table->text("description")->nullable();
            $table->json("payment_details")->nullable();
            $table->timestamps();
        ');
    }
    
    private function fillBusinessRequestsTable()
    {
        $this->fillMigration('create_business_requests_table', '
            $table->id();
            $table->string("business_name");
            $table->string("contact_person");
            $table->string("phone");
            $table->string("email");
            $table->text("business_description");
            $table->string("business_type");
            $table->decimal("requested_budget", 10, 2)->nullable();
            $table->string("status")->default("pending");
            $table->text("admin_notes")->nullable();
            $table->timestamps();
        ');
    }
    
    private function fillBusinessOrganizationRequestsTable()
    {
        $this->fillMigration('create_business_organization_requests_table', '
            $table->id();
            $table->string("organization_name");
            $table->string("contact_person");
            $table->string("phone");
            $table->string("email");
            $table->text("organization_description");
            $table->string("organization_type");
            $table->json("services_required")->nullable();
            $table->decimal("budget_range", 10, 2)->nullable();
            $table->string("status")->default("pending");
            $table->text("admin_notes")->nullable();
            $table->timestamps();
        ');
    }
    
    private function fillOrganicLeadsTable()
    {
        $this->fillMigration('create_organic_leads_table', '
            $table->id();
            $table->string("phone")->unique();
            $table->string("name")->nullable();
            $table->string("email")->nullable();
            $table->string("source")->default("website");
            $table->string("lead_type")->default("inquiry");
            $table->text("message")->nullable();
            $table->string("status")->default("new");
            $table->json("metadata")->nullable();
            $table->timestamps();
        ');
    }
    
    private function fillUserMessagesTable()
    {
        $this->fillMigration('create_user_messages_table', '
            $table->id();
            $table->unsignedBigInteger("user_id")->nullable();
            $table->string("phone");
            $table->text("message");
            $table->string("type")->default("text");
            $table->string("status")->default("sent");
            $table->json("metadata")->nullable();
            $table->timestamps();
            $table->foreign("user_id")->references("id")->on("users")->onDelete("set null");
        ');
    }
    
    private function fillWhatsAppConversationsTable()
    {
        $this->fillMigration('create_whatsapp_conversations_table', '
            $table->id();
            $table->string("phone")->unique();
            $table->string("name")->nullable();
            $table->string("status")->default("active");
            $table->timestamp("last_message_at")->nullable();
            $table->json("metadata")->nullable();
            $table->timestamps();
        ');
    }
    
    private function fillWhatsAppMessagesTable()
    {
        $this->fillMigration('create_whatsapp_messages_table', '
            $table->id();
            $table->string("message_id")->unique();
            $table->string("phone");
            $table->text("message_body");
            $table->string("message_type")->default("text");
            $table->string("direction")->default("inbound"); // inbound/outbound
            $table->string("status")->default("received");
            $table->json("metadata")->nullable();
            $table->timestamp("sent_at")->nullable();
            $table->timestamps();
        ');
    }
    
    private function fillWhatsAppAutoRepliesTable()
    {
        $this->fillMigration('create_whatsapp_auto_replies_table', '
            $table->id();
            $table->string("trigger_keyword");
            $table->text("reply_message");
            $table->string("reply_type")->default("text");
            $table->boolean("active")->default(true);
            $table->integer("priority")->default(1);
            $table->json("conditions")->nullable();
            $table->timestamps();
        ');
    }
    
    private function fillWhatsAppTemplatesTable()
    {
        $this->fillMigration('create_whatsapp_templates_table', '
            $table->id();
            $table->string("name")->unique();
            $table->string("language")->default("en_US");
            $table->string("category");
            $table->json("components");
            $table->string("status")->default("PENDING");
            $table->string("template_id")->nullable();
            $table->timestamps();
        ');
    }
    
    private function fillWhatsAppBulkMessagesTable()
    {
        $this->fillMigration('create_whatsapp_bulk_messages_table', '
            $table->id();
            $table->string("campaign_name");
            $table->text("message_content");
            $table->json("recipients");
            $table->string("status")->default("pending");
            $table->integer("total_count")->default(0);
            $table->integer("sent_count")->default(0);
            $table->integer("failed_count")->default(0);
            $table->timestamp("scheduled_at")->nullable();
            $table->timestamps();
        ');
    }
    
    private function fillWhatsAppMediaTable()
    {
        $this->fillMigration('create_whatsapp_media_table', '
            $table->id();
            $table->string("media_id")->unique();
            $table->string("filename");
            $table->string("mime_type");
            $table->bigInteger("file_size");
            $table->string("media_type"); // image, document, audio, video
            $table->string("url")->nullable();
            $table->timestamps();
        ');
    }
    
    private function fillWhatsAppAutomationRulesTable()
    {
        $this->fillMigration('create_whatsapp_automation_rules_table', '
            $table->id();
            $table->string("rule_name");
            $table->json("conditions");
            $table->json("actions");
            $table->boolean("active")->default(true);
            $table->integer("priority")->default(1);
            $table->text("description")->nullable();
            $table->timestamps();
        ');
    }
    
    private function fillWhatsAppAiAnalysisTable()
    {
        $this->fillMigration('create_whatsapp_ai_analysis_table', '
            $table->id();
            $table->string("phone");
            $table->text("message_content");
            $table->string("sentiment")->nullable();
            $table->decimal("sentiment_score", 3, 2)->nullable();
            $table->string("intent")->nullable();
            $table->json("entities")->nullable();
            $table->json("ai_response")->nullable();
            $table->timestamps();
        ');
    }
    
    private function fillWhatsAppChatgptPromptsTable()
    {
        $this->fillMigration('create_whatsapp_chatgpt_prompts_table', '
            $table->id();
            $table->string("name");
            $table->text("prompt");
            $table->string("category")->default("general");
            $table->boolean("active")->default(true);
            $table->json("parameters")->nullable();
            $table->timestamps();
        ');
    }
    
    private function fillWhatsAppChatgptContextsTable()
    {
        $this->fillMigration('create_whatsapp_chatgpt_contexts_table', '
            $table->id();
            $table->string("phone");
            $table->json("conversation_history");
            $table->string("current_topic")->nullable();
            $table->json("user_preferences")->nullable();
            $table->timestamp("last_interaction");
            $table->timestamps();
        ');
    }
    
    private function fillWhatsAppDefaultResponsesTable()
    {
        $this->fillMigration('create_whatsapp_default_responses_table', '
            $table->id();
            $table->string("response_type");
            $table->text("message");
            $table->boolean("active")->default(true);
            $table->json("conditions")->nullable();
            $table->timestamps();
        ');
    }
    
    private function fillWhatsAppLeadScoresTable()
    {
        $this->fillMigration('create_whatsapp_lead_scores_table', '
            $table->id();
            $table->string("phone")->unique();
            $table->integer("engagement_score")->default(0);
            $table->integer("response_rate")->default(0);
            $table->integer("conversion_probability")->default(0);
            $table->json("interaction_history")->nullable();
            $table->timestamp("last_calculated");
            $table->timestamps();
        ');
    }
    
    private function fillWhatsAppMessageFlowsTable()
    {
        $this->fillMigration('create_whatsapp_message_flows_table', '
            $table->id();
            $table->string("flow_name");
            $table->json("flow_steps");
            $table->string("trigger_condition");
            $table->boolean("active")->default(true);
            $table->text("description")->nullable();
            $table->timestamps();
        ');
    }
    
    private function fillWhatsAppSystemHealthsTable()
    {
        $this->fillMigration('create_whatsapp_system_healths_table', '
            $table->id();
            $table->string("component");
            $table->string("status");
            $table->json("metrics");
            $table->text("error_message")->nullable();
            $table->timestamp("checked_at");
            $table->timestamps();
        ');
    }
    
    private function fillWhatsAppTemplateCampaignsTable()
    {
        $this->fillMigration('create_whatsapp_template_campaigns_table', '
            $table->id();
            $table->string("campaign_name");
            $table->unsignedBigInteger("template_id");
            $table->json("recipients");
            $table->json("template_parameters")->nullable();
            $table->string("status")->default("pending");
            $table->timestamp("scheduled_at")->nullable();
            $table->timestamps();
            $table->foreign("template_id")->references("id")->on("whatsapp_templates")->onDelete("cascade");
        ');
    }
    
    private function fillWhatsAppTemplateTableLinksTable()
    {
        $this->fillMigration('create_whatsapp_template_table_links_table', '
            $table->id();
            $table->unsignedBigInteger("template_id");
            $table->string("table_name");
            $table->string("field_mapping");
            $table->json("conditions")->nullable();
            $table->timestamps();
            $table->foreign("template_id")->references("id")->on("whatsapp_templates")->onDelete("cascade");
        ');
    }
    
    private function fillWhatsAppUserBehaviorsTable()
    {
        $this->fillMigration('create_whatsapp_user_behaviors_table', '
            $table->id();
            $table->string("phone");
            $table->string("behavior_type");
            $table->json("behavior_data");
            $table->timestamp("occurred_at");
            $table->timestamps();
        ');
    }
    
    private function fillWhatsAppWeeklyReportsTable()
    {
        $this->fillMigration('create_whatsapp_weekly_reports_table', '
            $table->id();
            $table->date("week_start");
            $table->date("week_end");
            $table->json("metrics");
            $table->json("top_conversations")->nullable();
            $table->json("performance_summary");
            $table->timestamps();
        ');
    }
    
    private function fillWorkflowsTable()
    {
        $this->fillMigration('create_workflows_table', '
            $table->id();
            $table->string("name");
            $table->text("description")->nullable();
            $table->json("workflow_steps");
            $table->string("trigger_type");
            $table->json("trigger_conditions")->nullable();
            $table->boolean("active")->default(true);
            $table->timestamps();
        ');
    }
    
    private function fillWorkflowLogsTable()
    {
        $this->fillMigration('create_workflow_logs_table', '
            $table->id();
            $table->unsignedBigInteger("workflow_id");
            $table->string("execution_id")->unique();
            $table->string("status");
            $table->json("execution_data")->nullable();
            $table->timestamp("started_at");
            $table->timestamp("completed_at")->nullable();
            $table->timestamps();
            $table->foreign("workflow_id")->references("id")->on("workflows")->onDelete("cascade");
        ');
    }
    
    private function fillWorkflowErrorsTable()
    {
        $this->fillMigration('create_workflow_errors_table', '
            $table->id();
            $table->unsignedBigInteger("workflow_log_id");
            $table->string("error_type");
            $table->text("error_message");
            $table->json("error_context")->nullable();
            $table->timestamp("occurred_at");
            $table->timestamps();
            $table->foreign("workflow_log_id")->references("id")->on("workflow_logs")->onDelete("cascade");
        ');
    }
    
    private function fillWorkflowConversationHistoriesTable()
    {
        $this->fillMigration('create_workflow_conversation_histories_table', '
            $table->id();
            $table->string("phone");
            $table->unsignedBigInteger("workflow_id");
            $table->string("current_step");
            $table->json("conversation_data")->nullable();
            $table->json("user_responses")->nullable();
            $table->timestamp("started_at");
            $table->timestamp("last_interaction");
            $table->timestamps();
            $table->foreign("workflow_id")->references("id")->on("workflows")->onDelete("cascade");
        ');
    }
    
    private function fillWorkflowMachineConfigsTable()
    {
        $this->fillMigration('create_workflow_machine_configs_table', '
            $table->id();
            $table->string("config_name")->unique();
            $table->json("states");
            $table->json("transitions");
            $table->json("initial_state");
            $table->boolean("active")->default(true);
            $table->timestamps();
        ');
    }
    
    private function fillWorkflowPerformanceMetricsTable()
    {
        $this->fillMigration('create_workflow_performance_metrics_table', '
            $table->id();
            $table->unsignedBigInteger("workflow_id");
            $table->date("metric_date");
            $table->integer("executions_count")->default(0);
            $table->integer("successful_executions")->default(0);
            $table->integer("failed_executions")->default(0);
            $table->decimal("average_execution_time", 8, 2)->default(0);
            $table->timestamps();
            $table->foreign("workflow_id")->references("id")->on("workflows")->onDelete("cascade");
            $table->unique(["workflow_id", "metric_date"]);
        ');
    }
    
    private function fillMigration($migrationName, $schema)
    {
        // Find the migration file
        $migrationFiles = glob("database/migrations/*_{$migrationName}.php");
        
        if (empty($migrationFiles)) {
            echo "❌ Migration file not found for: {$migrationName}\n";
            return;
        }
        
        $migrationFile = end($migrationFiles); // Get the latest one
        $content = file_get_contents($migrationFile);
        
        // Replace the default schema with the proper one
        $tableName = $this->getTableName($migrationName);
        $pattern = '/Schema::create\(\'' . $tableName . '\', function \(Blueprint \$table\) \{[\s\n]*\$table->id\(\);[\s\n]*\$table->timestamps\(\);[\s\n]*\}\);/';
        $replacement = "Schema::create('{$tableName}', function (Blueprint \$table) {
            {$schema}
        });";
        
        $newContent = preg_replace($pattern, $replacement, $content);
        
        if ($newContent === $content) {
            echo "❌ Could not replace content in: {$migrationName}\n";
            return;
        }
        
        file_put_contents($migrationFile, $newContent);
        echo "✅ Filled: {$migrationName}\n";
    }
    
    private function getTableName($migrationName)
    {
        return str_replace('create_', '', str_replace('_table', '', $migrationName));
    }
}

// Run the filler
$filler = new MigrationFiller();
$filler->fillAllMigrations();

echo "\n🎉 All migrations have been filled with proper schemas!\n";
echo "Next steps:\n";
echo "1. Run: php artisan migrate:fresh\n";
echo "2. Test locally\n";
echo "3. Deploy to VPS\n";
