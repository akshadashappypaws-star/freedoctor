# WhatsApp Scheduled Messages Implementation

## Overview
The scheduled messaging system now allows you to schedule WhatsApp bulk messages to be sent at specific times. Messages are automatically processed and sent at the scheduled time.

## How It Works

### 1. Background Components
- **Job**: `ProcessScheduledWhatsappMessages` - Handles the actual sending of scheduled messages
- **Command**: `whatsapp:process-scheduled` - Checks for due scheduled messages every minute
- **Scheduler**: Runs the command automatically every minute

### 2. Message Flow
1. **Create Scheduled Message**: User creates a bulk message with a future timestamp
2. **Store in Database**: Message is saved with `status = 'pending'` and `is_scheduled = true`
3. **Background Processing**: Every minute, the scheduler checks for due messages
4. **Job Dispatch**: Due messages are dispatched to the queue for processing
5. **Message Sending**: The job sends messages to all recipients
6. **Status Update**: Message status is updated to 'completed' or 'failed'

### 3. Immediate vs Scheduled Messages
- **Immediate Messages**: If no schedule is set, messages are sent immediately
- **Scheduled Messages**: If a schedule is set, messages wait until the scheduled time

## Setup Instructions

### Option 1: Automatic Scheduler (Recommended)
The scheduler should already be running. If not:

```bash
php artisan schedule:work
```

Keep this running in the background for automatic processing.

### Option 2: Manual Processing
You can manually check and process scheduled messages:

```bash
php artisan whatsapp:process-scheduled
```

### Option 3: Windows Batch File
Use the provided batch file to start the scheduler:

```batch
start_scheduler.bat
```

## Usage

### Creating Scheduled Messages
1. Go to WhatsApp Bulk Messages page
2. Click "New Message"
3. Fill in the message details
4. Check "Schedule for later"
5. Set the date and time (in IST)
6. Click "Send Message"

### Monitoring Scheduled Messages
- **Pending**: Message is scheduled but not yet sent
- **Processing**: Message is currently being sent
- **Completed**: Message has been sent successfully
- **Failed**: Message sending failed

## Technical Details

### Database Fields
- `is_scheduled`: Boolean flag indicating if message is scheduled
- `scheduled_at`: Timestamp when message should be sent (stored in UTC)
- `status`: Current status of the message

### Timezone Handling
- Frontend displays times in IST (Asia/Kolkata)
- Backend stores times in UTC
- Conversion happens automatically

### Error Handling
- Template parameter mismatches are resolved
- Failed messages are logged with detailed error information
- Individual recipient failures don't stop the entire batch

## Troubleshooting

### If Scheduled Messages Don't Send
1. Check if the scheduler is running:
   ```bash
   php artisan schedule:work
   ```

2. Manually process scheduled messages:
   ```bash
   php artisan whatsapp:process-scheduled
   ```

3. Check Laravel logs for errors:
   ```bash
   tail -f storage/logs/laravel.log
   ```

### Common Issues
- **Parameter Mismatch**: Fixed automatically by checking template parameters
- **Rate Limiting**: 0.5 second delay between messages
- **Invalid Phone Numbers**: Individual failures are logged but don't stop the batch

## Benefits
- ✅ Real-time scheduled message processing
- ✅ Automatic error handling and recovery
- ✅ Individual recipient failure handling
- ✅ Timezone-aware scheduling (IST)
- ✅ Background processing without blocking UI
- ✅ Detailed logging and monitoring

## Status Indicators
- 📅 **Scheduled**: Message waiting to be sent
- ⏳ **Processing**: Message currently being sent
- ✅ **Completed**: All messages sent successfully
- ❌ **Failed**: Some or all messages failed to send
