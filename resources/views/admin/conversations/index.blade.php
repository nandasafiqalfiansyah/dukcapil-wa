<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-white leading-tight">
            {{ __('📬 Incoming Messages') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Statistics -->
            <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-gray-600 text-sm font-medium">Total Messages</div>
                    <div class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['total_messages'] ?? 0 }}</div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-blue-600 text-sm font-medium">📥 Incoming</div>
                    <div class="text-3xl font-bold text-blue-600 mt-2">{{ $stats['incoming_messages'] ?? 0 }}</div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-green-600 text-sm font-medium">📤 Outgoing</div>
                    <div class="text-3xl font-bold text-green-600 mt-2">{{ $stats['outgoing_messages'] ?? 0 }}</div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-purple-600 text-sm font-medium">👥 Total Users</div>
                    <div class="text-3xl font-bold text-purple-600 mt-2">{{ $stats['total_users'] ?? 0 }}</div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-orange-600 text-sm font-medium">📅 Today</div>
                    <div class="text-3xl font-bold text-orange-600 mt-2">{{ $stats['today_messages'] ?? 0 }}</div>
                </div>
            </div>

            <!-- Filters -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4">🔍 Filter & Search</h3>
                    <form method="GET" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Direction</label>
                            <select name="direction" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                <option value="">All</option>
                                <option value="incoming" {{ request('direction') === 'incoming' ? 'selected' : '' }}>📥 Incoming</option>
                                <option value="outgoing" {{ request('direction') === 'outgoing' ? 'selected' : '' }}>📤 Outgoing</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">User</label>
                            <select name="user_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                <option value="">All Users</option>
                                @foreach($whatsappUsers as $user)
                                    <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                        {{ $user->phone_number }} ({{ $user->name ?? 'Unknown' }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
                            <input type="text" name="phone" value="{{ request('phone') }}" placeholder="628xxx..." 
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Message Type</label>
                            <select name="message_type" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                <option value="">All Types</option>
                                <option value="text" {{ request('message_type') === 'text' ? 'selected' : '' }}>📝 Text</option>
                                <option value="image" {{ request('message_type') === 'image' ? 'selected' : '' }}>🖼️ Image</option>
                                <option value="document" {{ request('message_type') === 'document' ? 'selected' : '' }}>📄 Document</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">From Date</label>
                            <input type="date" name="date_from" value="{{ request('date_from') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">To Date</label>
                            <input type="date" name="date_to" value="{{ request('date_to') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                            <select name="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                <option value="">All Status</option>
                                <option value="delivered" {{ request('status') === 'delivered' ? 'selected' : '' }}>✅ Delivered</option>
                                <option value="sent" {{ request('status') === 'sent' ? 'selected' : '' }}>📤 Sent</option>
                                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>⏳ Pending</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search message..." 
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        </div>
                        <div class="flex items-end">
                            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                🔎 Apply Filters
                            </button>
                        </div>
                        <div class="flex items-end">
                            <a href="{{ route('admin.conversations.index') }}" class="w-full bg-gray-400 hover:bg-gray-500 text-white font-bold py-2 px-4 rounded text-center">
                                Clear
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Conversations Table -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4">Messages List</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">ID</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Direction</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Sender</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Name</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Message</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Type</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Timestamp</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Action</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($conversations as $msg)
                                    <tr class="hover:bg-gray-50 transition">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $msg->id }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            @if($msg->direction === 'incoming')
                                                <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-medium">📥 Incoming</span>
                                            @else
                                                <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-xs font-medium">📤 Outgoing</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-mono">{{ $msg->whatsappUser->phone_number }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $msg->whatsappUser->name ?? '—' }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-700 max-w-xs truncate">{{ $msg->message_content }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            <span class="px-2 py-1 bg-gray-100 text-gray-700 rounded text-xs">{{ $msg->message_type }}</span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $msg->created_at->format('d M Y H:i') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            @if($msg->status === 'delivered')
                                                <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs font-medium">✅ Delivered</span>
                                            @elseif($msg->status === 'sent')
                                                <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-medium">📤 Sent</span>
                                            @else
                                                <span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs font-medium">⏳ {{ $msg->status }}</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            <a href="{{ route('admin.conversations.show', $msg->id) }}" class="text-blue-600 hover:text-blue-900 font-medium">
                                                View →
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="px-6 py-8 text-center text-gray-500">
                                            <div class="text-lg">📭 No messages found</div>
                                            <p class="text-sm mt-2">Try adjusting your filters</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        {{ $conversations->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
