<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\DocumentValidation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class DocumentValidationController extends Controller
{
    public function index(Request $request): View
    {
        $query = DocumentValidation::with(['serviceRequest.whatsappUser', 'validator'])
            ->latest();

        if ($request->filled('validation_status')) {
            $query->where('validation_status', $request->validation_status);
        }

        if ($request->filled('document_type')) {
            $query->where('document_type', $request->document_type);
        }

        $documents = $query->paginate(20);

        return view('admin.documents.index', compact('documents'));
    }

    public function show(DocumentValidation $document): View
    {
        $document->load(['serviceRequest.whatsappUser', 'validator']);

        return view('admin.documents.show', compact('document'));
    }

    public function validate(Request $request, DocumentValidation $document): RedirectResponse
    {
        $validated = $request->validate([
            'validation_status' => 'required|in:valid,invalid,needs_review',
            'validation_notes' => 'nullable|string',
            'validation_results' => 'nullable|array',
        ]);

        $oldStatus = $document->validation_status;

        $document->update([
            ...$validated,
            'validated_by' => auth()->id(),
            'validated_at' => now(),
        ]);

        AuditLog::log('document.validated', $document, [
            'old_status' => $oldStatus,
        ], [
            'new_status' => $document->validation_status,
        ]);

        return redirect()->back()->with('success', 'Dokumen berhasil divalidasi.');
    }

    public function download(DocumentValidation $document): \Symfony\Component\HttpFoundation\StreamedResponse|RedirectResponse
    {
        if (! $document->file_path || ! Storage::exists($document->file_path)) {
            return redirect()->back()->with('error', 'File tidak ditemukan.');
        }

        AuditLog::log('document.downloaded', $document);

        return Storage::download($document->file_path, $document->original_filename);
    }
}
