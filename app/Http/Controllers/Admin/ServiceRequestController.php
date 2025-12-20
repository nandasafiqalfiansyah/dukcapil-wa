<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\ServiceRequest;
use App\Models\User;
use App\Services\WhatsAppService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ServiceRequestController extends Controller
{
    public function __construct(protected WhatsAppService $whatsappService) {}

    public function index(Request $request): View
    {
        $query = ServiceRequest::with(['whatsappUser', 'assignedOfficer'])
            ->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        if ($request->filled('service_type')) {
            $query->where('service_type', $request->service_type);
        }

        if ($request->filled('assigned_to')) {
            $query->where('assigned_to', $request->assigned_to);
        }

        if ($request->boolean('escalated')) {
            $query->escalated();
        }

        $serviceRequests = $query->paginate(20);
        $officers = User::whereIn('role', ['admin', 'officer'])->get();

        return view('admin.service-requests.index', compact('serviceRequests', 'officers'));
    }

    public function show(ServiceRequest $serviceRequest): View
    {
        $serviceRequest->load(['whatsappUser', 'assignedOfficer', 'documentValidations.validator', 'notifications']);

        return view('admin.service-requests.show', compact('serviceRequest'));
    }

    public function updateStatus(Request $request, ServiceRequest $serviceRequest): RedirectResponse
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,in_review,processing,approved,rejected,completed,cancelled',
            'notes' => 'nullable|string',
        ]);

        $oldStatus = $serviceRequest->status;
        $serviceRequest->update($validated);

        AuditLog::log('service_request.status_updated', $serviceRequest, [
            'old_status' => $oldStatus,
        ], [
            'new_status' => $serviceRequest->status,
        ]);

        $this->whatsappService->sendMessage(
            $serviceRequest->whatsappUser->phone_number,
            "Status permintaan Anda ({$serviceRequest->request_number}) telah diperbarui menjadi: {$serviceRequest->status}"
        );

        return redirect()->back()->with('success', 'Status permintaan berhasil diperbarui.');
    }

    public function assign(Request $request, ServiceRequest $serviceRequest): RedirectResponse
    {
        $validated = $request->validate([
            'assigned_to' => 'required|exists:users,id',
        ]);

        $oldAssignee = $serviceRequest->assigned_to;
        $serviceRequest->update($validated);

        AuditLog::log('service_request.assigned', $serviceRequest, [
            'old_assigned_to' => $oldAssignee,
        ], [
            'new_assigned_to' => $serviceRequest->assigned_to,
        ]);

        return redirect()->back()->with('success', 'Permintaan berhasil ditugaskan.');
    }

    public function escalate(ServiceRequest $serviceRequest): RedirectResponse
    {
        if ($serviceRequest->isEscalated()) {
            return redirect()->back()->with('error', 'Permintaan sudah dieskalasi sebelumnya.');
        }

        $serviceRequest->escalate();
        $serviceRequest->update(['priority' => 'urgent']);

        AuditLog::log('service_request.escalated', $serviceRequest);

        $this->whatsappService->sendMessage(
            $serviceRequest->whatsappUser->phone_number,
            "Permintaan Anda ({$serviceRequest->request_number}) telah dieskalasi ke petugas untuk penanganan lebih lanjut."
        );

        return redirect()->back()->with('success', 'Permintaan berhasil dieskalasi.');
    }

    public function addNote(Request $request, ServiceRequest $serviceRequest): RedirectResponse
    {
        $validated = $request->validate([
            'notes' => 'required|string',
        ]);

        $currentNotes = $serviceRequest->notes ?? '';
        $newNote = sprintf(
            "[%s - %s]\n%s\n\n",
            now()->format('Y-m-d H:i:s'),
            auth()->user()->name,
            $validated['notes']
        );

        $serviceRequest->update([
            'notes' => $newNote.$currentNotes,
        ]);

        AuditLog::log('service_request.note_added', $serviceRequest);

        return redirect()->back()->with('success', 'Catatan berhasil ditambahkan.');
    }
}
