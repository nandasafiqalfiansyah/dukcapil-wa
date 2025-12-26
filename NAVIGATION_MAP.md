# Navigation and Feature Map

## Public Access (No Login Required)

```
┌─────────────────────────────────────────────────────────┐
│                    Homepage (/)                          │
│  - WhatsApp Service Information                         │
│  - Features Overview                                     │
│  - "Try Chat Demo" Button ──────────────┐              │
│  - "Hubungi via WhatsApp" Button         │              │
└──────────────────────────────────────────┼──────────────┘
                                           │
                                           ▼
                        ┌──────────────────────────────────┐
                        │   Chat Demo (/chat-demo)         │
                        │  - No login required             │
                        │  - Real-time chatbot testing     │
                        │  - Intent detection display      │
                        │  - Confidence scoring            │
                        │  - Session management            │
                        │  - Reset functionality           │
                        └──────────────────────────────────┘
```

## Admin Access (Authentication Required)

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                         Admin Navigation Bar                                 │
│                                                                              │
│  Dashboard | Chat Bot | Chat Demo | Conversations | Requests                │
│                                                                              │
│  [Admin Only]:                                                               │
│  Chat Config | NLP Logs | Bots | Auto-Reply | Users                        │
└─────────────────────────────────────────────────────────────────────────────┘
         │           │           │              │               │
         │           │           │              │               │
         ▼           ▼           ▼              ▼               ▼
    ┌─────────┐ ┌─────────┐ ┌─────────┐  ┌─────────┐    ┌─────────┐
    │Dashboard│ │Chat Bot │ │Chat Demo│  │  Chat   │    │   NLP   │
    │         │ │Testing  │ │(Public) │  │ Config  │    │  Logs   │
    └─────────┘ └─────────┘ └─────────┘  └─────────┘    └─────────┘
                      │                         │              │
                      │                         │              │
                      ▼                         ▼              ▼
              ┌──────────────┐        ┌──────────────┐  ┌──────────────┐
              │Authenticated │        │   Training   │  │  Live Log    │
              │Chat Testing  │        │     Data     │  │  Streaming   │
              │              │        │  Management  │  │              │
              │- Sessions    │        │              │  │- Statistics  │
              │- Messages    │        │- Add/Edit    │  │- Filters     │
              │- WhatsApp    │        │- Delete      │  │- Real-time   │
              │  Integration │        │- Priority    │  │- Historical  │
              └──────────────┘        │- Keywords    │  └──────────────┘
                                      │- Active/Off  │
                                      └──────────────┘
```

## Feature Flow Diagram

### Chat Demo Flow (Guest Users)
```
┌──────────┐
│  Guest   │
│  Visits  │
│   Site   │
└─────┬────┘
      │
      ▼
┌─────────────────┐
│ Click "Try Demo"│
│  on Homepage    │
└─────┬───────────┘
      │
      ▼
┌─────────────────────────┐
│  Create Guest Session   │
│  (Auto via PHP Session) │
└─────┬───────────────────┘
      │
      ▼
┌─────────────────────────┐
│   Type Message          │
└─────┬───────────────────┘
      │
      ▼
┌─────────────────────────┐
│  NLP Processing         │
│  - Pattern Matching     │
│  - Intent Detection     │
│  - Confidence Scoring   │
└─────┬───────────────────┘
      │
      ▼
┌─────────────────────────┐
│  Display Bot Response   │
│  + Intent Badge         │
│  + Confidence %         │
└─────────────────────────┘
```

### NLP Monitoring Flow (Admins)
```
┌──────────┐
│  Admin   │
│  Login   │
└─────┬────┘
      │
      ▼
┌─────────────────┐
│ Navigate to     │
│  "NLP Logs"     │
└─────┬───────────┘
      │
      ▼
┌─────────────────────────┐
│  Statistics Dashboard   │
│  - Total Processed      │
│  - Avg Confidence       │
│  - Low Confidence Count │
│  - Live Update Counter  │
└─────┬───────────────────┘
      │
      ▼
┌─────────────────────────┐     ┌──────────────────┐
│  Live Log Stream        │────▶│  Auto Refresh    │
│  (Updates every 3s)     │     │  (AJAX Polling)  │
└─────┬───────────────────┘     └──────────────────┘
      │
      ▼
┌─────────────────────────┐
│  Apply Filters          │
│  - Intent Type          │
│  - Confidence Level     │
│  - Date Range           │
└─────┬───────────────────┘
      │
      ▼
┌─────────────────────────┐
│  View Historical Logs   │
│  (Paginated)            │
└─────────────────────────┘
```

### Chat Configuration Flow (Admins)
```
┌──────────┐
│  Admin   │
│  Login   │
└─────┬────┘
      │
      ▼
┌─────────────────┐
│ Navigate to     │
│ "Chat Config"   │
└─────┬───────────┘
      │
      ▼
┌─────────────────────────┐
│  View Training Data     │
│  - Intent               │
│  - Pattern              │
│  - Keywords             │
│  - Priority             │
│  - Status (Active/Off)  │
└─────┬───────────────────┘
      │
      ├──▶ Add New ──────┐
      │                  │
      ├──▶ Edit ─────────┼──▶ ┌───────────────────┐
      │                  │    │  Form with:       │
      ├──▶ Delete ───────┘    │  - Intent         │
      │                       │  - Pattern        │
      └──▶ Toggle Active      │  - Response       │
                              │  - Keywords       │
                              │  - Priority       │
                              │  - Active Status  │
                              └───────────────────┘
                                        │
                                        ▼
                              ┌───────────────────┐
                              │  Validate & Save  │
                              └───────────────────┘
                                        │
                                        ▼
                              ┌───────────────────┐
                              │  Used by NLP      │
                              │  Engine for       │
                              │  Intent Detection │
                              └───────────────────┘
```

## Data Flow

```
┌──────────────┐
│  User Input  │
│   Message    │
└──────┬───────┘
       │
       ▼
┌──────────────────────┐
│  ChatBotService      │
│  - detectIntent()    │
│  - generateResponse()│
└──────┬───────────────┘
       │
       ├──▶ Query ──▶ ┌──────────────────┐
       │              │ cs_training_data │
       │              │ (Patterns)       │
       │              └──────────────────┘
       │
       ├──▶ Save ───▶ ┌──────────────────┐
       │              │ chat_messages    │
       │              │ (with intent &   │
       │              │  confidence)     │
       │              └──────────────────┘
       │
       └──▶ Return ──▶ Response + Metadata
                       (Intent, Confidence, Pattern)
```

## Access Control

```
┌─────────────────────────────────────────────────┐
│              Route Access Control                │
├─────────────────────────────────────────────────┤
│                                                  │
│  Public (No Auth):                              │
│  ✓ /                  - Homepage                │
│  ✓ /chat-demo         - Chat Demo               │
│                                                  │
│  Authenticated (All Roles):                     │
│  ✓ /dashboard         - Redirect to Admin       │
│  ✓ /admin             - Admin Dashboard         │
│  ✓ /admin/chatbot     - Chat Bot Testing        │
│  ✓ /admin/conversations - Conversation Logs     │
│  ✓ /admin/service-requests - Service Requests   │
│                                                  │
│  Admin Only:                                    │
│  ✓ /admin/chat-config - Training Data Mgmt      │
│  ✓ /admin/nlp-logs    - NLP Monitoring          │
│  ✓ /admin/bots        - Bot Management          │
│  ✓ /admin/auto-replies - Auto-Reply Config      │
│  ✓ /admin/users       - User Management         │
└─────────────────────────────────────────────────┘
```

## Mobile Responsive Design

All pages are fully responsive with:
- Hamburger menu for mobile navigation
- Touch-friendly buttons
- Optimized chat interface for mobile screens
- Scrollable log containers
- Adaptive table layouts
