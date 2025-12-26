# Quick Reference - New Routes

## Public Routes (No Authentication)

### Chat Demo
| Method | URI | Name | Description |
|--------|-----|------|-------------|
| GET | `/chat-demo` | `chat-demo.index` | Display chat demo interface |
| POST | `/chat-demo/sessions` | `chat-demo.sessions.create` | Create new guest session |
| POST | `/chat-demo/messages` | `chat-demo.messages.send` | Send message and get bot response |
| GET | `/chat-demo/sessions/{sessionId}/messages` | `chat-demo.messages.get` | Get session messages |
| POST | `/chat-demo/reset` | `chat-demo.reset` | Reset guest session |

## Admin Routes (Admin Role Required)

### Chat Configuration
| Method | URI | Name | Description |
|--------|-----|------|-------------|
| GET | `/admin/chat-config` | `admin.chat-config.index` | List all training data |
| GET | `/admin/chat-config/create` | `admin.chat-config.create` | Show create form |
| POST | `/admin/chat-config` | `admin.chat-config.store` | Store new training data |
| GET | `/admin/chat-config/{id}/edit` | `admin.chat-config.edit` | Show edit form |
| PUT | `/admin/chat-config/{id}` | `admin.chat-config.update` | Update training data |
| DELETE | `/admin/chat-config/{id}` | `admin.chat-config.destroy` | Delete training data |
| POST | `/admin/chat-config/{id}/toggle-active` | `admin.chat-config.toggle-active` | Toggle active status |

### NLP Logs
| Method | URI | Name | Description |
|--------|-----|------|-------------|
| GET | `/admin/nlp-logs` | `admin.nlp-logs.index` | View NLP logs interface |
| GET | `/admin/nlp-logs/live` | `admin.nlp-logs.live` | Get live logs (AJAX) |
| GET | `/admin/nlp-logs/statistics` | `admin.nlp-logs.statistics` | Get statistics (AJAX) |

## Usage Examples

### Blade Templates
```blade
<!-- Link to Chat Demo (Public) -->
<a href="{{ route('chat-demo.index') }}">Try Demo</a>

<!-- Link to Chat Config (Admin) -->
<a href="{{ route('admin.chat-config.index') }}">Manage Training Data</a>

<!-- Link to NLP Logs (Admin) -->
<a href="{{ route('admin.nlp-logs.index') }}">View Logs</a>

<!-- Edit Training Data (Admin) -->
<a href="{{ route('admin.chat-config.edit', $trainingData) }}">Edit</a>

<!-- Delete Training Data Form (Admin) -->
<form action="{{ route('admin.chat-config.destroy', $trainingData) }}" method="POST">
    @csrf
    @method('DELETE')
    <button type="submit">Delete</button>
</form>
```

### JavaScript (AJAX)
```javascript
// Create guest session
fetch('{{ route("chat-demo.sessions.create") }}', {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': csrfToken
    }
})

// Send message
fetch('{{ route("chat-demo.messages.send") }}', {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': csrfToken
    },
    body: JSON.stringify({
        session_id: sessionId,
        message: userMessage
    })
})

// Get live NLP logs
fetch('{{ route("admin.nlp-logs.live") }}?since=' + timestamp)

// Get NLP statistics
fetch('{{ route("admin.nlp-logs.statistics") }}')

// Toggle training data active status
fetch(`/admin/chat-config/${id}/toggle-active`, {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': csrfToken
    }
})
```

### Controller Redirects
```php
// Redirect to chat config after save
return redirect()->route('admin.chat-config.index')
    ->with('success', 'Training data created successfully.');

// Redirect to NLP logs
return redirect()->route('admin.nlp-logs.index');
```

## Navigation Updates

### Main Navigation (resources/views/layouts/navigation.blade.php)
Added links for:
- Chat Demo (all authenticated users, opens in new tab)
- Chat Config (admin only)
- NLP Logs (admin only)

### Welcome Page (resources/views/welcome.blade.php)
Added "Try Chat Demo" button in CTA section linking to `chat-demo.index`

## API Response Formats

### Chat Demo - Send Message Response
```json
{
    "success": true,
    "user_message": {
        "id": 123,
        "role": "user",
        "message": "Hello",
        "created_at": "2025-12-26T10:00:00.000000Z"
    },
    "bot_message": {
        "id": 124,
        "role": "bot",
        "message": "Hi! How can I help?",
        "intent": "greeting",
        "confidence": 0.95,
        "metadata": {
            "matched_pattern": "hello"
        },
        "created_at": "2025-12-26T10:00:01.000000Z"
    },
    "intent": "greeting",
    "confidence": 0.95,
    "nlp_details": {
        "matched_pattern": "hello",
        "processing_time": 1000
    }
}
```

### NLP Logs - Live Response
```json
{
    "success": true,
    "logs": [
        {
            "id": 124,
            "message": "Hi! How can I help?",
            "intent": "greeting",
            "confidence": 0.95,
            "session_id": 10,
            "matched_pattern": "hello",
            "created_at": "2025-12-26T10:00:01.000000Z",
            "created_at_human": "10:00:01"
        }
    ],
    "latest_timestamp": "2025-12-26T10:00:01.000000Z"
}
```

### NLP Logs - Statistics Response
```json
{
    "success": true,
    "statistics": {
        "total_processed": 1523,
        "average_confidence": 78.45,
        "low_confidence_count": 42,
        "intent_distribution": [
            {
                "intent": "greeting",
                "count": 523
            },
            {
                "intent": "ktp_inquiry",
                "count": 342
            }
        ]
    }
}
```

## Access Middleware

| Route Group | Middleware | Allowed Roles |
|-------------|-----------|---------------|
| `/chat-demo` | None | Public (All) |
| `/admin/chatbot` | `auth`, `role` | admin, officer, viewer |
| `/admin/chat-config` | `auth`, `role` | admin only |
| `/admin/nlp-logs` | `auth`, `role` | admin only |

## Testing URLs

After deployment, test these URLs:

**Public:**
- `https://yourdomain.com/chat-demo` - Guest chat demo

**Admin (requires login as admin):**
- `https://yourdomain.com/admin/chat-config` - Training data management
- `https://yourdomain.com/admin/nlp-logs` - Live NLP monitoring
- `https://yourdomain.com/admin/chat-config/create` - Create new training data

## Common Tasks

### Add New Training Pattern
1. Navigate to `/admin/chat-config`
2. Click "Add Training Data"
3. Fill in: intent, pattern, keywords, response, priority
4. Check "Active" checkbox
5. Submit form

### Monitor NLP Performance
1. Navigate to `/admin/nlp-logs`
2. View real-time statistics
3. Watch live log stream
4. Apply filters if needed
5. Review historical logs

### Test Chatbot as Guest
1. Open `/chat-demo`
2. Type message in chat
3. Observe intent detection
4. Check confidence score
5. Reset session if needed
