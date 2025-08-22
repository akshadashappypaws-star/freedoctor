A WhatsApp workflow system for FreeDoctor CRM (no bot for doctor module)

A WhatsApp-based workflow system integrated with your FreeDoctor Laravel CRM. Handles patient inquiries, doctor searches, health camp bookings, and more. The doctor module does not include a bot.

## 🏗️ Architecture Overview

The system consists of **5 interconnected machines** that work together to process WhatsApp messages:

```
WhatsApp Message → WorkflowEngine → Machine Orchestration → Response
                                  ↓
                    [AI] → [Function] → [DataTable] → [Template] → [Visualization]
```

### 🤖 Machine Types

1. **AiMachine**: Intent analysis, workflow planning, response generation using OpenAI
2. **FunctionMachine**: Business logic execution (search doctors, camps, payments)
3. **DataTableMachine**: Data formatting and processing for WhatsApp display
4. **TemplateMachine**: WhatsApp message sending via templates or plain text
5. **VisualizationMachine**: Real-time monitoring, dashboards, and notifications

## 📊 Database Schema

The system extends your existing CRM with these new tables:

### Core Workflow Tables
- `workflows` - Main workflow tracking
- `workflow_logs` - Step-by-step execution logs
- `workflow_errors` - Error tracking and recovery
- `workflow_conversation_history` - WhatsApp message history
- `workflow_machine_configs` - Machine configuration settings
- `workflow_performance_metrics` - Performance analytics

## 🚀 Installation & Setup

### Step 1: Run the Setup Command

```bash
cd /c/xampp/htdocs/freedoctor-web
php artisan workflow:setup
```

This will:
- Create all workflow tables
- Seed machine configurations
- Verify the installation

### Step 2: Configure Environment Variables

Add these to your `.env` file:

```env
# WhatsApp Business API
WHATSAPP_ACCESS_TOKEN=your_access_token
WHATSAPP_PHONE_NUMBER_ID=your_phone_number_id
WHATSAPP_BUSINESS_ACCOUNT_ID=your_business_account_id
WHATSAPP_VERIFY_TOKEN=your_custom_verify_token
WHATSAPP_APP_SECRET=your_app_secret

# OpenAI API
OPENAI_API_KEY=your_openai_api_key
```

### Step 3: Set Up WhatsApp Webhook

1. Go to Meta Business → WhatsApp → Configuration
2. Set webhook URL: `https://yourdomain.com/api/webhook/whatsapp`
3. Set verify token: Same as `WHATSAPP_VERIFY_TOKEN`
4. Subscribe to: `messages`, `message_deliveries`, `message_reads`

## 🎯 Usage Examples

### Sample WhatsApp Conversations

**User**: "Find cardiologist near Delhi"
```
System Flow:
1. AI analyzes intent: "find_doctor"
2. Function searches doctors in Delhi
3. DataTable formats results for WhatsApp
4. Template sends formatted doctor list
5. Visualization updates dashboard
```

**User**: "Show health camps tomorrow"
```
System Flow:
1. AI analyzes intent: "find_health_camp"
2. Function searches upcoming camps
3. DataTable formats camp information
4. Template sends camp details with registration links
5. Visualization tracks user engagement
```

**User**: "Book appointment with Dr. Smith"
```
System Flow:
1. AI analyzes intent: "book_appointment"
2. Function checks doctor availability
3. DataTable formats available slots
4. Template sends booking options
5. Visualization monitors booking process
```

## 🔧 API Endpoints

### Workflow Management (Admin Only)
```
GET    /api/workflows              - List all workflows
GET    /api/workflows/statistics   - Get workflow statistics
GET    /api/workflows/{id}         - Get workflow details
POST   /api/workflows/{id}/retry   - Retry failed workflow
DELETE /api/workflows/{id}         - Delete workflow
```

### Webhook Endpoints
```
GET    /api/webhook/whatsapp       - WhatsApp verification
POST   /api/webhook/whatsapp       - WhatsApp message handler
POST   /api/webhook/whatsapp/test  - Test endpoint
```

## 🎨 Real-Time Dashboard Features

### Admin Dashboard
- Live workflow execution monitoring
- Step-by-step progress visualization
- Error tracking and recovery suggestions
- Performance metrics and analytics
- User conversation history

### Visualizations
- Workflow flowchart with real-time updates
- Success/failure rates
- Response time analytics
- Popular intents and trends
- Error distribution charts

## 🔀 Workflow Examples

### Doctor Search Workflow
```json
{
  "steps": [
    {
      "step": 1,
      "machine": "ai",
      "action": "analyze_intent",
      "expected_output": "intent_analysis"
    },
    {
      "step": 2,
      "machine": "function",
      "action": "searchNearbyDoctors",
      "parameters": {"specialty": "cardiologist", "location": "delhi"},
      "expected_output": "doctor_list"
    },
    {
      "step": 3,
      "machine": "datatable",
      "action": "formatDoctorList",
      "parameters": {"format": "whatsapp", "max_items": 5},
      "expected_output": "formatted_doctors"
    },
    {
      "step": 4,
      "machine": "template",
      "action": "sendDoctorList",
      "expected_output": "message_sent"
    }
  ]
}
```

### Health Camp Registration Workflow
```json
{
  "steps": [
    {
      "step": 1,
      "machine": "function",
      "action": "findHealthCamps",
      "parameters": {"location": "delhi", "category": "general"},
      "expected_output": "camp_list"
    },
    {
      "step": 2,
      "machine": "datatable",
      "action": "formatHealthCampList",
      "expected_output": "formatted_camps"
    },
    {
      "step": 3,
      "machine": "template",
      "action": "sendHealthCampInfo",
      "expected_output": "camps_sent"
    }
  ]
}
```

## 🛠️ Customization

### Adding New Machine Actions

1. **AI Machine**: Add new intent patterns in `WorkflowMachineConfig`
2. **Function Machine**: Add new methods in `FunctionMachine` class
3. **DataTable Machine**: Add new formatting actions
4. **Template Machine**: Create new message templates
5. **Visualization Machine**: Add new dashboard widgets

### Example: Adding Payment Processing

```php
// In FunctionMachine.php
private function processPayment(array $params): array
{
    $paymentService = app(RazorpayService::class);
    // Your payment logic here
    return $result;
}
```

### Example: Adding Custom Template

```php
// In TemplateMachine.php
private function sendPaymentConfirmation(array $data, array $options): array
{
    $template = $this->findTemplate('payment_confirmation');
    return $this->sendWhatsAppTemplate($template, $data['whatsapp_number'], [
        'amount' => $data['amount'],
        'transaction_id' => $data['transaction_id']
    ]);
}
```

## 📊 Monitoring & Analytics

### Performance Metrics Tracked
- Response time per machine
- Success rate by intent type
- User satisfaction scores
- Error frequency and types
- Peak usage hours
- Popular workflows

### Real-time Monitoring
- Live workflow execution
- Step-by-step progress
- Error alerts
- Performance dashboards
- User conversation flows

## 🐛 Troubleshooting

### Common Issues

1. **WhatsApp webhook not receiving messages**
   - Check webhook URL configuration
   - Verify SSL certificate
   - Check firewall settings

2. **AI responses not working**
   - Verify OpenAI API key
   - Check API quota limits
   - Review error logs

3. **Database connection issues**
   - Check migration status
   - Verify database credentials
   - Run `php artisan workflow:setup --force`

### Debug Commands

```bash
# Check workflow status
php artisan tinker
>>> App\Models\Workflow::latest()->first();

# Test WhatsApp service
>>> app(App\Services\WhatsAppService::class)->sendMessage('1234567890', 'Test message');

# Check machine configurations
>>> App\Models\WorkflowMachineConfig::where('machine_type', 'ai')->get();
```

## 🔒 Security Considerations

- Webhook signature validation
- Rate limiting on API endpoints
- Input sanitization and validation
- Secure storage of API keys
- User data privacy compliance

## 📈 Scaling Considerations

- Use Laravel Queues for heavy operations
- Implement Redis caching for frequent queries
- Set up horizontal scaling for high traffic
- Monitor database performance
- Implement CDN for media handling

## 🤝 Integration with Existing CRM

The workflow system seamlessly integrates with your existing:
- **User management** (patients, doctors, admins)
- **Campaign system** (health camps, events)
- **Payment processing** (Razorpay integration)
- **Notification system** (real-time alerts)
- **Location services** (distance calculations)

## 📞 Support

For technical support or feature requests:
1. Check the error logs in `storage/logs/laravel.log`
2. Review workflow errors in the admin dashboard
3. Test individual components using the provided API endpoints
4. Monitor real-time execution in the visualization dashboard

---

## 🎉 Success! Your WhatsApp Workflow System is Ready!

The system is now capable of handling:
- ✅ Intelligent conversation understanding
- ✅ Doctor and health camp searches (no bot in doctor module)  
- ✅ Appointment booking assistance
- ✅ Payment processing support
- ✅ Real-time monitoring and analytics
- ✅ Automatic error recovery
- ✅ Multi-language support potential
- ✅ Scalable architecture for growth

**Test it out by sending a WhatsApp message like**: *"Find cardiologist near me"* 🏥
