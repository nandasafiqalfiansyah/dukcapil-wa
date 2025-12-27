<?php

use App\Models\ChatSession;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('can create a chat demo session', function () {
    $response = $this->postJson(route('chat-demo.sessions.create'));
    
    $response->assertStatus(200)
        ->assertJson([
            'success' => true,
        ])
        ->assertJsonStructure([
            'success',
            'session' => [
                'id',
                'title',
                'created_at',
                'updated_at',
            ],
        ]);
    
    // Verify session was created in database
    $this->assertDatabaseHas('chat_sessions', [
        'id' => $response->json('session.id'),
        'user_id' => null, // Guest session
    ]);
});

test('can send a message in chat demo', function () {
    // First create a session
    $sessionResponse = $this->postJson(route('chat-demo.sessions.create'));
    $sessionId = $sessionResponse->json('session.id');
    
    // Send a message
    $response = $this->postJson(route('chat-demo.messages.send'), [
        'session_id' => $sessionId,
        'message' => 'Halo, saya ingin tanya tentang KTP',
    ]);
    
    $response->assertStatus(200)
        ->assertJson([
            'success' => true,
        ])
        ->assertJsonStructure([
            'success',
            'user_message' => [
                'id',
                'message',
                'role',
            ],
            'bot_message' => [
                'id',
                'message',
                'role',
            ],
            'intent',
            'confidence',
        ]);
    
    // Verify messages were saved
    $this->assertDatabaseHas('chat_messages', [
        'chat_session_id' => $sessionId,
        'role' => 'user',
        'message' => 'Halo, saya ingin tanya tentang KTP',
    ]);
    
    $this->assertDatabaseHas('chat_messages', [
        'chat_session_id' => $sessionId,
        'role' => 'bot',
    ]);
});

test('returns validation error for missing session_id', function () {
    $response = $this->postJson(route('chat-demo.messages.send'), [
        'message' => 'Test message',
    ]);
    
    $response->assertStatus(422)
        ->assertJsonValidationErrors(['session_id']);
});

test('returns validation error for empty message', function () {
    // Create a session first
    $sessionResponse = $this->postJson(route('chat-demo.sessions.create'));
    $sessionId = $sessionResponse->json('session.id');
    
    $response = $this->postJson(route('chat-demo.messages.send'), [
        'session_id' => $sessionId,
        'message' => '',
    ]);
    
    $response->assertStatus(422)
        ->assertJsonValidationErrors(['message']);
});

test('returns error for non-existent session', function () {
    $response = $this->postJson(route('chat-demo.messages.send'), [
        'session_id' => 99999,
        'message' => 'Test message',
    ]);
    
    $response->assertStatus(422)
        ->assertJsonValidationErrors(['session_id']);
});
