<?php

use App\Models\ServiceRequest;
use App\Models\User;
use App\Models\WhatsAppUser;

test('admin can access dashboard', function () {
    $admin = User::factory()->create([
        'role' => 'admin',
        'is_active' => true,
    ]);

    $response = $this->actingAs($admin)->get('/admin');

    $response->assertStatus(200);
});

test('officer can access dashboard', function () {
    $officer = User::factory()->create([
        'role' => 'officer',
        'is_active' => true,
    ]);

    $response = $this->actingAs($officer)->get('/admin');

    $response->assertStatus(200);
});

test('viewer can access dashboard', function () {
    $viewer = User::factory()->create([
        'role' => 'viewer',
        'is_active' => true,
    ]);

    $response = $this->actingAs($viewer)->get('/admin');

    $response->assertStatus(200);
});

test('inactive user cannot access dashboard', function () {
    $user = User::factory()->create([
        'role' => 'admin',
        'is_active' => false,
    ]);

    $response = $this->actingAs($user)->get('/admin');

    $response->assertRedirect(route('login'));
});

test('guest cannot access dashboard', function () {
    $response = $this->get('/admin');

    $response->assertRedirect(route('login'));
});

test('admin can view service requests', function () {
    $admin = User::factory()->create([
        'role' => 'admin',
        'is_active' => true,
    ]);

    $response = $this->actingAs($admin)->get(route('admin.service-requests.index'));

    $response->assertStatus(200);
});

test('admin can escalate service request', function () {
    $admin = User::factory()->create([
        'role' => 'admin',
        'is_active' => true,
    ]);

    $whatsappUser = WhatsAppUser::factory()->create();
    $serviceRequest = ServiceRequest::factory()->create([
        'whatsapp_user_id' => $whatsappUser->id,
        'escalated_at' => null,
    ]);

    $response = $this->actingAs($admin)->post(route('admin.service-requests.escalate', $serviceRequest));

    $response->assertRedirect();
    $serviceRequest->refresh();
    expect($serviceRequest->escalated_at)->not->toBeNull();
    expect($serviceRequest->priority)->toBe('urgent');
});

test('viewer cannot escalate service request', function () {
    $viewer = User::factory()->create([
        'role' => 'viewer',
        'is_active' => true,
    ]);

    $whatsappUser = WhatsAppUser::factory()->create();
    $serviceRequest = ServiceRequest::factory()->create([
        'whatsapp_user_id' => $whatsappUser->id,
    ]);

    $response = $this->actingAs($viewer)->post(route('admin.service-requests.escalate', $serviceRequest));

    $response->assertStatus(403);
});

test('viewer cannot create users', function () {
    $viewer = User::factory()->create([
        'role' => 'viewer',
        'is_active' => true,
    ]);

    $response = $this->actingAs($viewer)->get(route('admin.users.create'));

    $response->assertStatus(403);
});

test('admin can create users', function () {
    $admin = User::factory()->create([
        'role' => 'admin',
        'is_active' => true,
    ]);

    $response = $this->actingAs($admin)->post(route('admin.users.store'), [
        'name' => 'New Officer',
        'email' => 'newofficer@example.com',
        'password' => 'password123',
        'password_confirmation' => 'password123',
        'role' => 'officer',
        'is_active' => true,
    ]);

    $response->assertRedirect(route('admin.users.index'));
    $this->assertDatabaseHas('users', [
        'email' => 'newofficer@example.com',
        'role' => 'officer',
    ]);
});
