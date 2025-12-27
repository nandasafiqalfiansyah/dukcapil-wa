# Auto-Reply Integration in Chat System

## Overview
The auto-reply feature has been successfully integrated into the chat system. Users can now set up simple keyword-based automatic responses that work alongside the NLP-based intent detection system.

## How It Works

### Priority System
1. **Auto-Reply Check (First Priority)**
   - System checks for exact keyword matches in AutoReplyConfig table
   - Matches are case-sensitive or case-insensitive based on configuration
   - Highest priority auto-replies are checked first

2. **NLP Fallback (Second Priority)**
   - If no auto-reply matches, system uses NLP intent detection
   - Uses CsTrainingData for pattern matching
   - Provides confidence scores and intent classification

### Configuration
Auto-replies are managed through the `AutoReplyConfig` model with the following fields:
- `trigger` - The keyword that triggers the response (must be exact match)
- `response` - The message to send back
- `priority` - Higher numbers are checked first (0-1000)
- `is_active` - Enable/disable the auto-reply
- `case_sensitive` - Whether to match case exactly

### Placeholders
The response can include dynamic placeholders:
- `{{timestamp}}` - Full date and time (e.g., 27/12/2025 14:41:20)
- `{{date}}` - Date only (e.g., 27/12/2025)
- `{{time}}` - Time only (e.g., 14:41:20)
- `{{day}}` - Day of week in Indonesian (e.g., Jumat)
- `{{user_message}}` - Echo back the user's message

### Example Auto-Replies
```php
// Simple greeting
AutoReplyConfig::create([
    'trigger' => 'halo',
    'response' => '👋 Halo! Selamat datang di DUKCAPIL Ponorogo.',
    'priority' => 80,
    'is_active' => true,
    'case_sensitive' => false,
]);

// With placeholders
AutoReplyConfig::create([
    'trigger' => 'ping',
    'response' => '🤖 Pong! Bot aktif pada {{timestamp}}',
    'priority' => 100,
    'is_active' => true,
    'case_sensitive' => false,
]);
```

## Usage

### In WhatsApp
Auto-replies work automatically when users send messages through WhatsApp Business API webhook.

### In Chat Interface
Auto-replies also work in the web-based chat interface (chat-demo).

### Testing
Run the test suite:
```bash
php artisan test --filter ChatAutoReplyTest
```

All 7 tests verify:
- Exact trigger matching
- Case sensitivity handling
- Placeholder replacement
- Priority ordering
- Active/inactive status
- Fallback to NLP

## Technical Details

### Modified Files
- `app/Services/ChatBotService.php` - Core logic
- `tests/Feature/ChatAutoReplyTest.php` - Test suite

### Key Methods
- `ChatBotService::checkAutoReply()` - Checks for auto-reply matches
- `ChatBotService::processMessage()` - Updated to check auto-replies first

### Database
- Table: `auto_reply_configs`
- Seeder: `AutoReplyConfigSeeder` (includes sample data)

## Benefits
1. **Fast Responses** - Instant replies for common queries
2. **Reduced Load** - Simple queries don't need NLP processing
3. **Easy Configuration** - Non-technical users can add keywords
4. **Priority Control** - Important triggers can take precedence
5. **Flexible Matching** - Case-sensitive or insensitive options
6. **Dynamic Content** - Placeholder support for timestamps

## Migration from Webhook-Only
Previously, auto-replies only worked for WhatsApp webhook messages. Now they also work in:
- Chat demo interface
- Any system using ChatBotService
- Both authenticated and guest chat sessions

## Future Enhancements
Potential improvements:
- Partial keyword matching (not just exact)
- Regular expression support
- Multiple trigger variations per response
- Time-based activation/deactivation
- User-specific auto-replies
- Analytics and usage tracking
