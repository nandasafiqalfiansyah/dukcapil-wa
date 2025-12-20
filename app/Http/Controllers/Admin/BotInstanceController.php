<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BotInstance;
use App\Services\WhatsAppService;
use Illuminate\Http\Request;

class BotInstanceController extends Controller
{
    protected WhatsAppService $whatsappService;

    public function __construct(WhatsAppService $whatsappService)
    {
        $this->whatsappService = $whatsappService;
    }

    /**
     * Display a listing of bot instances.
     */
    public function index()
    {
        $bots = BotInstance::orderBy('created_at', 'desc')->paginate(10);

        return view('admin.bots.index', compact('bots'));
    }

    /**
     * Show the form for creating a new bot instance.
     */
    public function create()
    {
        return view('admin.bots.create');
    }

    /**
     * Store a newly created bot instance.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'bot_id' => 'required|string|unique:bot_instances,bot_id|max:255',
        ]);

        $result = $this->whatsappService->initializeBot(
            $validated['bot_id'],
            $validated['name']
        );

        if ($result['success']) {
            return redirect()->route('admin.bots.index')
                ->with('success', 'Bot instance created and initializing. Please scan the QR code.');
        }

        return back()->withErrors(['error' => $result['error'] ?? 'Failed to initialize bot'])
            ->withInput();
    }

    /**
     * Display the specified bot instance with QR code if available.
     */
    public function show(BotInstance $bot)
    {
        // Get latest status from bot server
        $statusResult = $this->whatsappService->getBotStatus($bot->bot_id);

        if ($statusResult['success']) {
            $serverStatus = $statusResult['data'];
            
            // Update local status if different
            if (isset($serverStatus['status']) && $serverStatus['status'] !== $bot->status) {
                $bot->update([
                    'status' => $serverStatus['status'],
                    'qr_code' => $serverStatus['qr_code'] ?? null,
                ]);
                $bot->refresh();
            }
        }

        return view('admin.bots.show', compact('bot'));
    }

    /**
     * Update the specified bot instance.
     */
    public function update(Request $request, BotInstance $bot)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'is_active' => 'boolean',
        ]);

        $bot->update($validated);

        return redirect()->route('admin.bots.show', $bot)
            ->with('success', 'Bot instance updated successfully.');
    }

    /**
     * Disconnect the bot instance.
     */
    public function disconnect(BotInstance $bot)
    {
        $result = $this->whatsappService->disconnectBot($bot->bot_id);

        if ($result['success']) {
            return back()->with('success', 'Bot disconnected successfully.');
        }

        return back()->withErrors(['error' => $result['error'] ?? 'Failed to disconnect bot']);
    }

    /**
     * Logout the bot instance (removes session).
     */
    public function logout(BotInstance $bot)
    {
        $result = $this->whatsappService->logoutBot($bot->bot_id);

        if ($result['success']) {
            return redirect()->route('admin.bots.index')
                ->with('success', 'Bot logged out successfully. You can initialize it again.');
        }

        return back()->withErrors(['error' => $result['error'] ?? 'Failed to logout bot']);
    }

    /**
     * Reinitialize the bot instance.
     */
    public function reinitialize(BotInstance $bot)
    {
        $result = $this->whatsappService->initializeBot($bot->bot_id, $bot->name);

        if ($result['success']) {
            return redirect()->route('admin.bots.show', $bot)
                ->with('success', 'Bot reinitialized. Please scan the QR code.');
        }

        return back()->withErrors(['error' => $result['error'] ?? 'Failed to reinitialize bot']);
    }

    /**
     * Get bot status (for AJAX polling).
     */
    public function status(BotInstance $bot)
    {
        $result = $this->whatsappService->getBotStatus($bot->bot_id);

        if ($result['success']) {
            $serverStatus = $result['data'];
            
            // Update local database
            $bot->update([
                'status' => $serverStatus['status'] ?? $bot->status,
                'qr_code' => $serverStatus['qr_code'] ?? null,
            ]);
            
            $bot->refresh();

            return response()->json([
                'success' => true,
                'bot' => [
                    'id' => $bot->id,
                    'bot_id' => $bot->bot_id,
                    'name' => $bot->name,
                    'status' => $bot->status,
                    'qr_code' => $bot->qr_code,
                    'phone_number' => $bot->phone_number,
                    'connected' => $bot->isConnected(),
                ],
            ]);
        }

        return response()->json([
            'success' => false,
            'error' => $result['error'] ?? 'Failed to get bot status',
        ], 500);
    }

    /**
     * Remove the specified bot instance.
     */
    public function destroy(BotInstance $bot)
    {
        // Disconnect first if connected
        if ($bot->isConnected()) {
            $this->whatsappService->disconnectBot($bot->bot_id);
        }

        $bot->delete();

        return redirect()->route('admin.bots.index')
            ->with('success', 'Bot instance deleted successfully.');
    }
}

