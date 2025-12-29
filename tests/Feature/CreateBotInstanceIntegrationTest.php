<?php

use App\Models\BotInstance;
use App\Models\User;
use Illuminate\Support\Facades\Http;

describe('Bot Instance Creation - Integration', function () {
    
    beforeEach(function () {
        config([
            'services.fonnte.api_url' => 'https://md.fonnte.com',
            'services.fonnte.token' => 'test_fonnte_token',
        ]);

        // Create admin user - set role directly on model
        $this->user = User::create([
            'name' => 'Admin User',
            'email' => 'admin@test.com',
            'password' => bcrypt('password'),
            'email_verified_at' => now(),
            'role' => 'admin',
            'is_active' => true,
        ]);
    });

    test('show create bot form', function () {
        $response = $this->actingAs($this->user)
            ->get(route('admin.bots.create'));

        $response->assertOk();
        $response->assertViewIs('admin.bots.create');
    });

    test('store bot instance with valid data', function () {
        Http::fake([
            'https://md.fonnte.com/device' => Http::response([
                'device' => '628123456789',
                'status' => 'connected',
            ], 200),
        ]);

        $response = $this->actingAs($this->user)
            ->post(route('admin.bots.store'), [
                'name' => 'Test Bot',
                'bot_id' => 'test-bot-001',
                'fonnte_token' => 'test_token',
            ]);

        $bot = BotInstance::where('bot_id', 'test-bot-001')->first();
        
        expect($bot)->not->toBeNull();
        $response->assertRedirect(route('admin.bots.show', $bot));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('bot_instances', [
            'bot_id' => 'test-bot-001',
            'name' => 'Test Bot',
        ]);
    });

    test('store bot fails with invalid token', function () {
        Http::fake([
            'https://md.fonnte.com/device' => Http::response([
                'reason' => 'Invalid token',
            ], 401),
        ]);

        $response = $this->actingAs($this->user)
            ->post(route('admin.bots.store'), [
                'name' => 'Test Bot',
                'bot_id' => 'test-bot-002',
                'fonnte_token' => 'invalid_token',
            ]);

        $response->assertSessionHasErrors('error');
        $response->assertRedirect();
        
        // Ensure bot was not created
        expect(BotInstance::where('bot_id', 'test-bot-002')->exists())->toBeFalse();
    });

    test('store bot fails with duplicate bot_id', function () {
        BotInstance::create([
            'bot_id' => 'existing-bot',
            'name' => 'Existing Bot',
            'status' => 'disconnected',
            'is_active' => true,
        ]);

        $response = $this->actingAs($this->user)
            ->post(route('admin.bots.store'), [
                'name' => 'Another Bot',
                'bot_id' => 'existing-bot',
                'fonnte_token' => 'test_token',
            ]);

        $response->assertSessionHasErrors('bot_id');
        $response->assertRedirect();
    });

    test('store bot fails with missing required fields', function () {
        $response = $this->actingAs($this->user)
            ->post(route('admin.bots.store'), [
                'name' => 'Test Bot',
                // missing bot_id
            ]);

        $response->assertSessionHasErrors('bot_id');
        $response->assertRedirect();
    });

});
