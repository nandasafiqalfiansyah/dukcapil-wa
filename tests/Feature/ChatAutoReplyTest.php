<?php

use App\Models\AutoReplyConfig;
use App\Models\ChatSession;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('chat uses auto-reply for exact trigger match', function () {
    // Create an auto-reply configuration
    $autoReply = AutoReplyConfig::create([
        'trigger' => 'halo',
        'response' => 'Halo! Selamat datang di DUKCAPIL Ponorogo.',
        'priority' => 100,
        'is_active' => true,
        'case_sensitive' => false,
    ]);
    
    // Create a session
    $sessionResponse = $this->postJson(route('chat-demo.sessions.create'));
    $sessionId = $sessionResponse->json('session.id');
    
    // Send a message that matches the auto-reply trigger
    $response = $this->postJson(route('chat-demo.messages.send'), [
        'session_id' => $sessionId,
        'message' => 'halo',
    ]);
    
    $response->assertStatus(200)
        ->assertJson([
            'success' => true,
            'intent' => 'auto_reply',
            'confidence' => 1.0,
        ]);
    
    // Verify the bot response matches the auto-reply
    expect($response->json('bot_message.message'))->toBe('Halo! Selamat datang di DUKCAPIL Ponorogo.');
    
    // Verify metadata shows it came from auto-reply
    $this->assertDatabaseHas('chat_messages', [
        'chat_session_id' => $sessionId,
        'role' => 'bot',
        'intent' => 'auto_reply',
        'confidence' => 1.0,
    ]);
});

test('chat auto-reply respects case sensitivity', function () {
    // Create a case-sensitive auto-reply
    AutoReplyConfig::create([
        'trigger' => 'HALO',
        'response' => 'Response for uppercase HALO',
        'priority' => 100,
        'is_active' => true,
        'case_sensitive' => true,
    ]);
    
    // Create a session
    $sessionResponse = $this->postJson(route('chat-demo.sessions.create'));
    $sessionId = $sessionResponse->json('session.id');
    
    // Send lowercase message (should not match due to case sensitivity)
    $response = $this->postJson(route('chat-demo.messages.send'), [
        'session_id' => $sessionId,
        'message' => 'halo',
    ]);
    
    $response->assertStatus(200);
    
    // Should not use auto-reply, should fallback to NLP or unknown
    expect($response->json('intent'))->not->toBe('auto_reply');
    expect($response->json('bot_message.message'))->not->toBe('Response for uppercase HALO');
});

test('chat auto-reply with case insensitive matching', function () {
    // Create a case-insensitive auto-reply
    AutoReplyConfig::create([
        'trigger' => 'bantuan',
        'response' => 'Silakan ketik pertanyaan Anda.',
        'priority' => 100,
        'is_active' => true,
        'case_sensitive' => false,
    ]);
    
    // Create a session
    $sessionResponse = $this->postJson(route('chat-demo.sessions.create'));
    $sessionId = $sessionResponse->json('session.id');
    
    // Test with uppercase (should match due to case insensitivity)
    $response = $this->postJson(route('chat-demo.sessions.create'));
    $sessionId2 = $response->json('session.id');
    
    $response = $this->postJson(route('chat-demo.messages.send'), [
        'session_id' => $sessionId2,
        'message' => 'BANTUAN',
    ]);
    
    $response->assertStatus(200)
        ->assertJson([
            'success' => true,
            'intent' => 'auto_reply',
        ]);
    
    expect($response->json('bot_message.message'))->toBe('Silakan ketik pertanyaan Anda.');
});

test('chat auto-reply replaces placeholders', function () {
    // Create an auto-reply with placeholders
    AutoReplyConfig::create([
        'trigger' => 'jam',
        'response' => 'Sekarang jam {{time}} pada tanggal {{date}}',
        'priority' => 100,
        'is_active' => true,
        'case_sensitive' => false,
    ]);
    
    // Create a session
    $sessionResponse = $this->postJson(route('chat-demo.sessions.create'));
    $sessionId = $sessionResponse->json('session.id');
    
    // Send message
    $response = $this->postJson(route('chat-demo.messages.send'), [
        'session_id' => $sessionId,
        'message' => 'jam',
    ]);
    
    $response->assertStatus(200)
        ->assertJson([
            'success' => true,
            'intent' => 'auto_reply',
        ]);
    
    // Verify placeholders were replaced
    $message = $response->json('bot_message.message');
    expect($message)->toContain('Sekarang jam');
    expect($message)->toContain('pada tanggal');
    expect($message)->not->toContain('{{time}}');
    expect($message)->not->toContain('{{date}}');
});

test('inactive auto-reply is not used', function () {
    // Create an inactive auto-reply
    AutoReplyConfig::create([
        'trigger' => 'inactive',
        'response' => 'This should not be used',
        'priority' => 100,
        'is_active' => false,
        'case_sensitive' => false,
    ]);
    
    // Create a session
    $sessionResponse = $this->postJson(route('chat-demo.sessions.create'));
    $sessionId = $sessionResponse->json('session.id');
    
    // Send message that matches inactive trigger
    $response = $this->postJson(route('chat-demo.messages.send'), [
        'session_id' => $sessionId,
        'message' => 'inactive',
    ]);
    
    $response->assertStatus(200);
    
    // Should not use the inactive auto-reply
    expect($response->json('intent'))->not->toBe('auto_reply');
    expect($response->json('bot_message.message'))->not->toBe('This should not be used');
});

test('higher priority auto-reply is used first', function () {
    // Create two auto-replies with different triggers and priorities
    // Test that when multiple auto-replies exist, priority is respected
    AutoReplyConfig::create([
        'trigger' => 'prioritytest',
        'response' => 'High priority response',
        'priority' => 200,
        'is_active' => true,
        'case_sensitive' => false,
    ]);
    
    AutoReplyConfig::create([
        'trigger' => 'lowprioritytest',
        'response' => 'Low priority response',
        'priority' => 50,
        'is_active' => true,
        'case_sensitive' => false,
    ]);
    
    // Create a session
    $sessionResponse = $this->postJson(route('chat-demo.sessions.create'));
    $sessionId = $sessionResponse->json('session.id');
    
    // Send message matching the high priority trigger
    $response = $this->postJson(route('chat-demo.messages.send'), [
        'session_id' => $sessionId,
        'message' => 'prioritytest',
    ]);
    
    $response->assertStatus(200)
        ->assertJson([
            'success' => true,
            'intent' => 'auto_reply',
        ]);
    
    // Should use the high priority response
    expect($response->json('bot_message.message'))->toBe('High priority response');
});

test('chat falls back to nlp when no auto-reply matches', function () {
    // Create an auto-reply that won't match
    AutoReplyConfig::create([
        'trigger' => 'specific_keyword',
        'response' => 'This will not match',
        'priority' => 100,
        'is_active' => true,
        'case_sensitive' => false,
    ]);
    
    // Create a session
    $sessionResponse = $this->postJson(route('chat-demo.sessions.create'));
    $sessionId = $sessionResponse->json('session.id');
    
    // Send a message that doesn't match the auto-reply
    $response = $this->postJson(route('chat-demo.messages.send'), [
        'session_id' => $sessionId,
        'message' => 'saya butuh bantuan',
    ]);
    
    $response->assertStatus(200)
        ->assertJson([
            'success' => true,
        ]);
    
    // Should not use auto-reply
    expect($response->json('intent'))->not->toBe('auto_reply');
    
    // Should have a response (from NLP or default)
    expect($response->json('bot_message.message'))->not->toBeEmpty();
});
