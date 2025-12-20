<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Detail Permintaan') }} - {{ $serviceRequest->request_number }}
            </h2>
            <a href="{{ route('admin.service-requests.index') }}" class="text-sm text-blue-600 hover:text-blue-800 dark:text-blue-400">
                ← Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <!-- Request Info -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Informasi Permintaan</h3>
                            <dl class="space-y-3">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Nomor Permintaan</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $serviceRequest->request_number }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Jenis Layanan</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ strtoupper($serviceRequest->service_type) }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Status</dt>
                                    <dd class="mt-1">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            @if($serviceRequest->status === 'pending') bg-yellow-100 text-yellow-800
                                            @elseif($serviceRequest->status === 'completed') bg-green-100 text-green-800
                                            @elseif($serviceRequest->status === 'rejected') bg-red-100 text-red-800
                                            @else bg-blue-100 text-blue-800
                                            @endif">
                                            {{ ucfirst($serviceRequest->status) }}
                                        </span>
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Prioritas</dt>
                                    <dd class="mt-1">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            @if($serviceRequest->priority === 'urgent') bg-red-100 text-red-800
                                            @elseif($serviceRequest->priority === 'high') bg-orange-100 text-orange-800
                                            @else bg-gray-100 text-gray-800
                                            @endif">
                                            {{ ucfirst($serviceRequest->priority) }}
                                        </span>
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Deskripsi</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $serviceRequest->description ?? '-' }}</dd>
                                </div>
                            </dl>
                        </div>

                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Informasi Pengguna</h3>
                            <dl class="space-y-3">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Nama</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $serviceRequest->whatsappUser->name ?? '-' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Nomor WhatsApp</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $serviceRequest->whatsappUser->phone_number }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">NIK</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $serviceRequest->whatsappUser->nik ?? '-' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Status Verifikasi</dt>
                                    <dd class="mt-1">
                                        @if($serviceRequest->whatsappUser->is_verified)
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                Terverifikasi
                                            </span>
                                        @else
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                Belum Terverifikasi
                                            </span>
                                        @endif
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Petugas yang Ditugaskan</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $serviceRequest->assignedOfficer->name ?? 'Belum ditugaskan' }}</dd>
                                </div>
                                @if($serviceRequest->isEscalated())
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Dieskalasi Pada</dt>
                                        <dd class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $serviceRequest->escalated_at->format('d M Y H:i') }}</dd>
                                    </div>
                                @endif
                            </dl>
                        </div>
                    </div>

                    @if($serviceRequest->notes)
                        <div class="mt-6">
                            <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Catatan</h4>
                            <div class="bg-gray-50 dark:bg-gray-900 rounded-lg p-4">
                                <pre class="text-sm text-gray-900 dark:text-gray-100 whitespace-pre-wrap">{{ $serviceRequest->notes }}</pre>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Actions -->
            @if(auth()->user()->hasAnyRole(['admin', 'officer']))
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Aksi</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Update Status -->
                            <form method="POST" action="{{ route('admin.service-requests.update-status', $serviceRequest) }}">
                                @csrf
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Update Status</label>
                                <div class="flex gap-2">
                                    <select name="status" class="flex-1 rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        <option value="pending" {{ $serviceRequest->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="in_review" {{ $serviceRequest->status === 'in_review' ? 'selected' : '' }}>In Review</option>
                                        <option value="processing" {{ $serviceRequest->status === 'processing' ? 'selected' : '' }}>Processing</option>
                                        <option value="approved" {{ $serviceRequest->status === 'approved' ? 'selected' : '' }}>Approved</option>
                                        <option value="rejected" {{ $serviceRequest->status === 'rejected' ? 'selected' : '' }}>Rejected</option>
                                        <option value="completed" {{ $serviceRequest->status === 'completed' ? 'selected' : '' }}>Completed</option>
                                    </select>
                                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                        Update
                                    </button>
                                </div>
                            </form>

                            <!-- Escalate -->
                            @if(!$serviceRequest->isEscalated())
                                <form method="POST" action="{{ route('admin.service-requests.escalate', $serviceRequest) }}" onsubmit="return confirm('Apakah Anda yakin ingin mengekskalasi permintaan ini?')">
                                    @csrf
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Eskalasi ke Petugas</label>
                                    <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                                        Eskalasi Permintaan
                                    </button>
                                </form>
                            @endif
                        </div>

                        <!-- Add Note -->
                        <form method="POST" action="{{ route('admin.service-requests.add-note', $serviceRequest) }}" class="mt-4">
                            @csrf
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Tambah Catatan</label>
                            <textarea name="notes" rows="3" class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Masukkan catatan..."></textarea>
                            <button type="submit" class="mt-2 bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                Simpan Catatan
                            </button>
                        </form>
                    </div>
                </div>
            @endif

            <!-- Document Validations -->
            @if($serviceRequest->documentValidations->count() > 0)
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Validasi Dokumen</h3>
                        <div class="space-y-3">
                            @foreach($serviceRequest->documentValidations as $document)
                                <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <p class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $document->document_type }}</p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $document->original_filename ?? 'No file' }}</p>
                                            <p class="mt-1">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                    @if($document->validation_status === 'valid') bg-green-100 text-green-800
                                                    @elseif($document->validation_status === 'invalid') bg-red-100 text-red-800
                                                    @else bg-yellow-100 text-yellow-800
                                                    @endif">
                                                    {{ ucfirst($document->validation_status) }}
                                                </span>
                                            </p>
                                        </div>
                                        @if($document->file_path)
                                            <a href="{{ route('admin.documents.download', $document) }}" class="text-sm text-blue-600 hover:text-blue-800 dark:text-blue-400">
                                                Download
                                            </a>
                                        @endif
                                    </div>
                                    @if($document->validation_notes)
                                        <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">{{ $document->validation_notes }}</p>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
