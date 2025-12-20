<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WhatsAppUser;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class WhatsAppUserController extends Controller
{
    public function index(Request $request): View
    {
        $query = WhatsAppUser::withCount(['conversationLogs', 'serviceRequests'])
            ->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('is_verified')) {
            $query->where('is_verified', $request->boolean('is_verified'));
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('phone_number', 'like', "%{$search}%")
                    ->orWhere('nik', 'like', "%{$search}%");
            });
        }

        $users = $query->paginate(20);

        return view('admin.whatsapp-users.index', compact('users'));
    }

    public function show(WhatsAppUser $whatsappUser): View
    {
        $whatsappUser->loadCount(['conversationLogs', 'serviceRequests']);
        $whatsappUser->load([
            'conversationLogs' => fn($q) => $q->latest()->take(20),
            'serviceRequests' => fn($q) => $q->latest()->take(10),
        ]);

        return view('admin.whatsapp-users.show', compact('whatsappUser'));
    }

    public function verify(WhatsAppUser $whatsappUser): RedirectResponse
    {
        if ($whatsappUser->is_verified) {
            return redirect()->back()->with('error', 'Pengguna sudah terverifikasi.');
        }

        $whatsappUser->update([
            'is_verified' => true,
            'verified_at' => now(),
            'status' => 'active',
        ]);

        AuditLog::log('whatsapp_user.verified', $whatsappUser);

        return redirect()->back()->with('success', 'Pengguna berhasil diverifikasi.');
    }

    public function updateStatus(Request $request, WhatsAppUser $whatsappUser): RedirectResponse
    {
        $validated = $request->validate([
            'status' => 'required|in:active,blocked,pending',
        ]);

        $oldStatus = $whatsappUser->status;
        $whatsappUser->update($validated);

        AuditLog::log('whatsapp_user.status_updated', $whatsappUser, [
            'old_status' => $oldStatus,
        ], [
            'new_status' => $whatsappUser->status,
        ]);

        return redirect()->back()->with('success', 'Status pengguna berhasil diperbarui.');
    }
}
