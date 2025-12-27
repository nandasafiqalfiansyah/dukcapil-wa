# NLP (Natural Language Processing) Enhancement

## Overview

This document describes the enhanced NLP features implemented in the DUKCAPIL WhatsApp chatbot system. The implementation includes extensive training data, configurable algorithms, and transparent logging.

## Features

### 1. Extensive Training Data (41+ Patterns)

The system now includes comprehensive training data covering:

- **Greetings & Farewells** (7 patterns)
  - Halo, Selamat pagi/siang/sore/malam, Assalamualaikum
  - Sampai jumpa, Terima kasih

- **KTP Services** (8 patterns)
  - Cara buat KTP, KTP hilang, KTP rusak
  - E-KTP info, KTP pertama kali
  - KTP pindah alamat, Update data, Masa berlaku

- **Kartu Keluarga (KK)** (6 patterns)
  - Cara buat KK, Tambah/Kurang anggota
  - KK hilang, KK rusak, Pisah KK

- **Akta Kelahiran** (4 patterns)
  - Cara buat akta, Akta terlambat
  - Akta hilang, Akta bayi baru lahir

- **Other Services** (4 patterns)
  - Akta kematian, Akta perkawinan, Akta perceraian

- **Information** (12 patterns)
  - Jam operasional, Lokasi/Kontak
  - Biaya, Persyaratan, Status tracking
  - Layanan online, Info umum, Waktu proses
  - Bantuan, Keluhan

### 2. Configurable NLP Algorithm

All NLP parameters are now configurable via:
- Database (nlp_configs table)
- Environment variables (.env)
- Admin UI (http://localhost/admin/nlp-config)

#### Configurable Parameters:

**Algorithm Weights** (0-1):
- `nlp_exact_match_weight`: Weight for exact pattern matching (default: 1.0)
- `nlp_partial_match_weight`: Weight for partial pattern matching (default: 0.6)
- `nlp_keyword_match_weight`: Weight for keyword matching (default: 0.4)
- `nlp_word_similarity_weight`: Weight for word similarity (default: 0.3)

**Algorithm Toggles**:
- `nlp_enable_exact_match`: Enable/disable exact matching
- `nlp_enable_partial_match`: Enable/disable partial matching
- `nlp_enable_keyword_match`: Enable/disable keyword matching
- `nlp_enable_word_similarity`: Enable/disable word similarity

**General Settings**:
- `nlp_confidence_threshold`: Minimum confidence to accept intent (default: 0.3)
- `nlp_enable_detailed_logging`: Enable transparent NLP logging
- `nlp_log_level`: Logging level (debug, info, warning, error)

**Performance**:
- `nlp_cache_training_data`: Enable caching for better performance
- `nlp_cache_ttl`: Cache time-to-live in seconds (default: 3600)

**Preprocessing**:
- `nlp_remove_punctuation`: Remove punctuation before processing
- `nlp_normalize_whitespace`: Normalize whitespace
- `nlp_convert_lowercase`: Convert to lowercase

### 3. Transparent NLP Logging

The system now logs detailed information about NLP processing:

- Intent detection process
- Confidence scores for all matches
- Matched patterns and keywords
- Processing time
- Algorithm details (partial match, keyword match, word similarity)

Logs can be viewed in:
- Laravel logs (`storage/logs/laravel.log`)
- NLP Logs page in admin panel

### 4. Admin Interface

**NLP Configuration Page** (`/admin/nlp-config`)
- View and edit all NLP parameters
- Grouped by category (Algorithm, Logging, Performance, Preprocessing)
- Real-time parameter updates
- Reset to defaults option
- Clear cache option

**NLP Diagnostics Page** (`/admin/nlp-config/diagnostics`)
- Statistics dashboard
  - Total training data count
  - Active training data
  - Total messages processed
  - Average confidence score
- Intent distribution chart
- Top 10 most used intents
- System configuration overview

**NLP Logs Page** (`/admin/nlp-logs`)
- View real-time NLP processing logs
- Filter by intent, confidence, date
- Live updates
- Statistics and analytics

## Installation & Setup

### 1. Run Migrations

```bash
php artisan migrate
```

This creates the `nlp_configs` table.

### 2. Seed NLP Configuration

```bash
php artisan db:seed --class=NlpConfigSeeder
```

This creates default NLP configuration entries.

### 3. Seed Training Data

```bash
php artisan db:seed --class=ExtendedNlpTrainingDataSeeder
```

This creates 41+ training data patterns.

### 4. Configure Environment

Add to your `.env` file:

```env
# NLP Configuration
NLP_CONFIDENCE_THRESHOLD=0.3
NLP_ENABLE_LOGGING=true
NLP_LOG_LEVEL=debug

# NLP Algorithm Weights
NLP_EXACT_MATCH_WEIGHT=1.0
NLP_PARTIAL_MATCH_WEIGHT=0.6
NLP_KEYWORD_MATCH_WEIGHT=0.4
NLP_WORD_SIMILARITY_WEIGHT=0.3

# NLP Algorithm Toggles
NLP_ENABLE_EXACT_MATCH=true
NLP_ENABLE_PARTIAL_MATCH=true
NLP_ENABLE_KEYWORD_MATCH=true
NLP_ENABLE_WORD_SIMILARITY=true

# NLP Performance
NLP_CACHE_TRAINING_DATA=false
NLP_CACHE_TTL=3600

# NLP Preprocessing
NLP_REMOVE_PUNCTUATION=true
NLP_NORMALIZE_WHITESPACE=true
NLP_CONVERT_LOWERCASE=true
```

## Usage

### Testing NLP

Run the test script:

```bash
php tests/nlp_test.php
```

This will display:
- Current NLP configuration
- Training data statistics
- Intent detection tests with various messages
- Confidence scores and processing times

### Accessing Admin Pages

1. **NLP Configuration**: `/admin/nlp-config`
2. **NLP Diagnostics**: `/admin/nlp-config/diagnostics`
3. **NLP Logs**: `/admin/nlp-logs`

### Monitoring NLP Performance

1. Check the diagnostics page for:
   - Average confidence scores
   - Intent distribution
   - Processing statistics

2. View detailed logs for:
   - Intent detection details
   - Confidence calculations
   - Algorithm performance

3. Adjust parameters based on:
   - Low confidence matches
   - Processing time
   - User feedback

## How It Works

### Intent Detection Process

1. **Preprocessing**
   - Convert to lowercase (if enabled)
   - Remove punctuation (if enabled)
   - Normalize whitespace (if enabled)

2. **Matching**
   For each training data entry:
   - Check exact match (weight: 1.0)
   - Check partial match (weight: 0.6)
   - Check keyword match (weight: 0.4)
   - Check word similarity (weight: 0.3)

3. **Confidence Calculation**
   - Sum all matching scores
   - Cap at 1.0 (100%)
   - Compare against threshold

4. **Intent Selection**
   - Select highest confidence match
   - Must be above threshold
   - Return "unknown" if below threshold

5. **Logging** (if enabled)
   - Log preprocessing details
   - Log all match attempts
   - Log confidence scores
   - Log processing time

### Response Generation

1. Use matched training data response
2. Replace placeholders:
   - `{{timestamp}}`: Current timestamp
   - `{{date}}`: Current date
   - `{{time}}`: Current time
   - `{{day}}`: Day of week
   - `{{user_message}}`: Original user message

## Performance Optimization

### Caching

Enable caching to improve performance:

```env
NLP_CACHE_TRAINING_DATA=true
NLP_CACHE_TTL=3600
```

This caches training data for 1 hour. Clear cache:
- Via admin UI: "Clear Cache" button
- Via code: `Cache::forget('nlp_training_data')`

### Algorithm Tuning

Adjust weights based on your needs:
- Higher exact match weight for precise matching
- Higher keyword match for flexible matching
- Lower threshold for more permissive matching

### Logging Level

Reduce logging in production:

```env
NLP_ENABLE_LOGGING=false
# or
NLP_LOG_LEVEL=error
```

## Troubleshooting

### Low Confidence Scores

1. Check training data patterns
2. Add more keywords to training data
3. Lower confidence threshold
4. Adjust algorithm weights

### Slow Performance

1. Enable caching
2. Reduce logging level
3. Optimize training data
4. Disable unused algorithms

### No Intent Matched

1. Check preprocessing settings
2. Review training data coverage
3. Lower confidence threshold
4. Add more training patterns

## Future Enhancements

Potential improvements:
- Machine learning integration
- Multi-language support
- Context-aware responses
- Sentiment analysis
- Entity extraction
- Conversation flow management

## API Reference

### NlpConfig Model

```php
// Get configuration value
$value = NlpConfig::getValue('nlp_confidence_threshold', 0.3);

// Set configuration value
NlpConfig::setValue('nlp_confidence_threshold', 0.5, 'float', 'algorithm', 'Description');
```

### ChatBotService

```php
$chatBotService = app(ChatBotService::class);

// Process message
$result = $chatBotService->processMessage($session, $userMessage);

// Returns:
// [
//     'user_message' => ChatMessage,
//     'bot_message' => ChatMessage,
//     'intent' => 'greeting',
//     'confidence' => 0.95,
// ]
```

## Contributing

To add new training data:

1. Edit `database/seeders/ExtendedNlpTrainingDataSeeder.php`
2. Add new training patterns
3. Run: `php artisan db:seed --class=ExtendedNlpTrainingDataSeeder`
4. Test in the chat interface

## License

This NLP enhancement is part of the DUKCAPIL WhatsApp chatbot system.
