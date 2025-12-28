<?php

use App\Models\BotInstance;

test('needsQrScan returns true when status is qr_generated', function () {
    $bot = new BotInstance(['status' => 'qr_generated']);
    
    expect($bot->needsQrScan())->toBeTrue();
});

test('needsQrScan returns false when status is connected', function () {
    $bot = new BotInstance(['status' => 'connected']);
    
    expect($bot->needsQrScan())->toBeFalse();
});

test('needsQrScan returns false when status is not_initialized', function () {
    $bot = new BotInstance(['status' => 'not_initialized']);
    
    expect($bot->needsQrScan())->toBeFalse();
});

test('needsQrScan returns false when status is disconnected', function () {
    $bot = new BotInstance(['status' => 'disconnected']);
    
    expect($bot->needsQrScan())->toBeFalse();
});

test('needsQrScan returns false when status is authenticated', function () {
    $bot = new BotInstance(['status' => 'authenticated']);
    
    expect($bot->needsQrScan())->toBeFalse();
});

test('isConnected returns true when status is connected and is_active is true', function () {
    $bot = new BotInstance(['status' => 'connected', 'is_active' => true]);
    
    expect($bot->isConnected())->toBeTrue();
});

test('isConnected returns false when status is connected but is_active is false', function () {
    $bot = new BotInstance(['status' => 'connected', 'is_active' => false]);
    
    expect($bot->isConnected())->toBeFalse();
});

test('needsConfiguration returns true when status is not_initialized', function () {
    $bot = new BotInstance(['status' => 'not_initialized']);
    
    expect($bot->needsConfiguration())->toBeTrue();
});

test('needsConfiguration returns true when status is disconnected', function () {
    $bot = new BotInstance(['status' => 'disconnected']);
    
    expect($bot->needsConfiguration())->toBeTrue();
});

test('needsConfiguration returns false when status is connected', function () {
    $bot = new BotInstance(['status' => 'connected']);
    
    expect($bot->needsConfiguration())->toBeFalse();
});
