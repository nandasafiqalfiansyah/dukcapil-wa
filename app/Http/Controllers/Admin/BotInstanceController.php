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
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'bot_id' => 'required|string|unique:bot_instances,bot_id|max:255',
                'fonnte_token' => 'required|string|min:10',
                'api_url' => 'required|url',
                'webhook_url' => 'nullable|url',
            ], [
                'fonnte_token.required' => 'Fonnte token is required to create a bot instance',
                'fonnte_token.min' => 'Token appears to be invalid (too short)',
                'api_url.required' => 'API URL is required',
                'api_url.url' => 'API URL must be a valid URL',
            ]);

            $result = $this->whatsappService->initializeBot(
                $validated['bot_id'],
                $validated['name'],
                $validated['fonnte_token'] ?? null,
                $validated['api_url'] ?? 'https://api.fonnte.com',
                $validated['webhook_url'] ?? null
            );

            if ($result['success']) {
                $bot = BotInstance::where('bot_id', $validated['bot_id'])->first();

                if ($bot) {
                    return redirect()->route('admin.bots.show', $bot)
                        ->with('success', 'Bot instance created successfully. Your WhatsApp connection is ready to use.');
                }

                return redirect()->route('admin.bots.index')
                    ->with('success', 'Bot instance created successfully.');
            }

            // Handle API errors
            $errorMessage = $result['error'] ?? 'Failed to initialize bot';
            
            return back()
                ->withErrors(['error' => $errorMessage])
                ->withInput()
                ->with('alert_type', 'error');
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Re-throw validation exceptions (handled by Laravel)
            throw $e;
        } catch (\Exception $e) {
            \Log::error('Error creating bot instance', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return back()
                ->withErrors(['error' => 'An unexpected error occurred: ' . $e->getMessage()])
                ->withInput()
                ->with('alert_type', 'error');
        }
    }

    /**
     * Display the specified bot instance.
     */
    public function show(BotInstance $bot)
    {
        // Get latest status
        $statusResult = $this->whatsappService->getBotStatus($bot->bot_id);

        if ($statusResult['success']) {
            $serverStatus = $statusResult['data'];

            // Update local status if different
            if (isset($serverStatus['status']) && $serverStatus['status'] !== $bot->status) {
                $bot->update([
                    'status' => $serverStatus['status'],
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
                ->with('success', 'Bot reinitialized successfully.');
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
            ]);

            $bot->refresh();

            return response()->json([
                'success' => true,
                'bot' => [
                    'id' => $bot->id,
                    'bot_id' => $bot->bot_id,
                    'name' => $bot->name,
                    'status' => $bot->status,
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

    /**
     * Update phone number for the bot instance.
     */
    public function updatePhone(Request $request, BotInstance $bot)
    {
        try {
            $validated = $request->validate([
                'phone_number' => 'required|string|min:10|max:20|regex:/^[0-9]+$/',
            ], [
                'phone_number.required' => 'Nomor telepon harus diisi',
                'phone_number.regex' => 'Nomor telepon hanya boleh berisi angka',
                'phone_number.min' => 'Nomor telepon minimal 10 digit',
            ]);

            $bot->update([
                'phone_number' => $validated['phone_number'],
            ]);

            return redirect()->route('admin.bots.show', $bot)
                ->with('success', 'Nomor telepon berhasil diperbarui.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            throw $e;
        } catch (\Exception $e) {
            \Log::error('Error updating phone number', [
                'error' => $e->getMessage(),
                'bot_id' => $bot->id,
            ]);

            return back()
                ->withErrors(['error' => 'Gagal memperbarui nomor telepon: ' . $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Update WhatsApp link and message for the bot instance.
     */
    public function updateLink(Request $request, BotInstance $bot)
    {
        try {
            $validated = $request->validate([
                'wa_link' => 'required|url',
                'wa_message' => 'nullable|string|max:1000',
            ], [
                'wa_link.required' => 'Link WhatsApp harus diisi',
                'wa_link.url' => 'Link WhatsApp harus berupa URL yang valid',
                'wa_message.max' => 'Pesan maksimal 1000 karakter',
            ]);

            $metadata = $bot->metadata ?? [];
            $metadata['wa_link'] = $validated['wa_link'];
            $metadata['wa_message'] = $validated['wa_message'] ?? null;

            $bot->update([
                'metadata' => $metadata,
            ]);

            return redirect()->route('admin.bots.show', $bot)
                ->with('success', 'Link WhatsApp berhasil diperbarui.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            throw $e;
        } catch (\Exception $e) {
            \Log::error('Error updating WhatsApp link', [
                'error' => $e->getMessage(),
                'bot_id' => $bot->id,
            ]);

            return back()
                ->withErrors(['error' => 'Gagal memperbarui link WhatsApp: ' . $e->getMessage()])
                ->withInput();
        }
    }
}
