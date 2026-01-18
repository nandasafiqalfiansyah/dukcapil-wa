<?php

namespace App\Http\Controllers;

use App\Models\BotInstance;
use Illuminate\View\View;

class WelcomeController extends Controller
{
    /**
     * Show the welcome/landing page.
     */
    public function index(): View
    {
        // Get available WhatsApp links from connected bots
        $whatsappLinks = BotInstance::where('status', 'connected')
            ->where('is_active', true)
            ->whereNotNull('phone_number')
            ->orderBy('created_at', 'asc')
            ->get()
            ->map(function ($bot) {
                return [
                    'id' => $bot->id,
                    'name' => $bot->name,
                    'phone_number' => $bot->phone_number,
                    'link' => $bot->metadata['wa_link'] ?? 'https://wa.me/' . preg_replace('/[^0-9]/', '', $bot->phone_number),
                    'message' => $bot->metadata['wa_message'] ?? null,
                ];
            })
            ->values();

        return view('welcome', compact('whatsappLinks'));
    }
}
