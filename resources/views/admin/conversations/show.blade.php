@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header with Back Button -->
        <div class="mb-8 flex justify-between items-center">
            <div>
                <a href="{{ route('admin.conversations.index') }}" class="text-blue-600 hover:text-blue-900 mb-2 inline-block">← Back to Messages</a>
                <h1 class="text-3xl font-bold text-gray-900">💬 Message Detail</h1>
            </div>
            <div class="text-right">
                <div class="text-sm text-gray-600">ID: {{ $conversation->id }}</div>
            </div>
        </div>

        <!-- Main Message Card -->
        <div class="bg-white rounded-lg shadow p-8 mb-8">
            <!-- Message Header -->
            <div class="border-b border-gray-200 pb-6 mb-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <h3 class="text-sm font-medium text-gray-600 mb-1">From</h3>
                        <p class="text-lg font-semibold text-gray-900">{{ $conversation->whatsappUser?->name ?? 'Unknown' }}</p>
                        <p class="text-gray-600 font-mono">{{ $conversation->whatsappUser?->phone_number ?? 'No phone number' }}</p>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-600 mb-1">Direction</h3>
                        <div>
                            @if($conversation->direction === 'incoming')
                                <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm font-medium">📥 Incoming</span>
                            @else
                                <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm font-medium">📤 Outgoing</span>
                            @endif
                        </div>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-600 mb-1">Timestamp</h3>
                        @if($conversation->created_at)
                            <p class="text-lg font-semibold text-gray-900">{{ $conversation->created_at->format('d M Y') }}</p>
                            <p class="text-gray-600">{{ $conversation->created_at->format('H:i:s') }}</p>
                        @else
                            <p class="text-gray-600">No timestamp recorded</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Message Content -->
            <div class="mb-6">
                <h3 class="text-sm font-medium text-gray-600 mb-2">Message Content</h3>
                <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                    <p class="text-gray-900 whitespace-pre-wrap">{{ $conversation->message_content }}</p>
                </div>
            </div>

            <!-- Message Details -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 p-4 bg-gray-50 rounded-lg">
                <div>
                    <h4 class="text-xs font-semibold text-gray-600 uppercase mb-1">Type</h4>
                    <p class="text-sm text-gray-900">{{ $conversation->message_type }}</p>
                </div>
                <div>
                    <h4 class="text-xs font-semibold text-gray-600 uppercase mb-1">Status</h4>
                    <div class="flex items-center gap-2">
                        <p class="text-sm text-gray-900">
                            @if($conversation->status === 'delivered')
                                ✅ Delivered
                            @elseif($conversation->status === 'sent')
                                📤 Sent
                            @elseif($conversation->status === 'failed')
                                ❌ Failed
                            @else
                                {{ $conversation->status }}
                            @endif
                        </p>
                        @if($conversation->status === 'failed' && $conversation->error_message)
                            <button type="button" 
                                    class="px-2 py-1 bg-red-100 text-red-700 rounded text-xs font-semibold hover:bg-red-200"
                                    onclick="document.getElementById('errorDetails').style.display = 'block'">
                                Details
                            </button>
                        @endif
                    </div>
                </div>
                <div>
                    <h4 class="text-xs font-semibold text-gray-600 uppercase mb-1">Bot Instance</h4>
                    <p class="text-sm text-gray-900">{{ $conversation->botInstance?->name ?? '—' }}</p>
                </div>
                <div>
                    <h4 class="text-xs font-semibold text-gray-600 uppercase mb-1">Message ID</h4>
                    <p class="text-sm text-gray-900 font-mono text-xs">{{ $conversation->message_id }}</p>
                </div>
            </div>

            <!-- Metadata (if exists) -->
            @if(!empty($conversation->metadata))
                <div class="mt-6">
                    <h3 class="text-sm font-medium text-gray-600 mb-2">Metadata</h3>
                    <pre class="bg-gray-900 text-gray-100 p-4 rounded-lg text-xs overflow-auto">{{ json_encode($conversation->metadata, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</pre>
                </div>
            @endif

            @if($conversation->status === 'failed' && $conversation->error_message)
                <div id="errorDetails" class="mt-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                    <h3 class="text-sm font-semibold text-red-900 mb-2">❌ Error Details</h3>
                    <div class="bg-white p-3 rounded border border-red-200">
                        <pre class="text-sm text-gray-900 whitespace-pre-wrap break-words font-mono">{{ $conversation->error_message }}</pre>
                    </div>
                </div>
            @endif
        </div>

        <!-- Conversation Thread -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="bg-gray-50 border-b border-gray-200 px-6 py-4">
                <h2 class="text-lg font-semibold text-gray-900">💬 Conversation Thread</h2>
                <p class="text-sm text-gray-600 mt-1">All messages from {{ $conversation->whatsappUser?->name ?? 'this user' }}</p>
            </div>

            <div class="p-6 space-y-6">
                @forelse($relatedConversations as $msg)
                    <div class="flex gap-4 {{ $msg->direction === 'incoming' ? 'flex-row' : 'flex-row-reverse' }}">
                        <!-- Avatar -->
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 rounded-full {{ $msg->direction === 'incoming' ? 'bg-blue-100' : 'bg-green-100' }} flex items-center justify-center">
                                <span class="text-lg">{{ $msg->direction === 'incoming' ? '📥' : '📤' }}</span>
                            </div>
                        </div>

                        <!-- Message -->
                        <div class="flex-1">
                            <div class="flex items-center gap-2 mb-1">
                                <span class="font-semibold text-gray-900">
                                    {{ $msg->direction === 'incoming' ? $msg->whatsappUser?->name ?? 'User' : 'Bot' }}
                                </span>
                                <span class="text-xs text-gray-500">{{ $msg->created_at?->format('d M H:i') ?? 'Unknown time' }}</span>
                            </div>
                            <div class="bg-gray-50 rounded-lg p-3 border border-gray-200 {{ $msg->id === $conversation->id ? 'border-2 border-blue-500 bg-blue-50' : '' }}">
                                <p class="text-gray-900 text-sm">{{ $msg->message_content }}</p>
                            </div>
                            <div class="text-xs text-gray-500 mt-1">
                                {{ $msg->message_type }} • {{ $msg->status }}
                                @if($msg->id === $conversation->id)
                                    <span class="font-semibold text-blue-600"> (Current)</span>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8 text-gray-500">
                        <p>No related messages found</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
