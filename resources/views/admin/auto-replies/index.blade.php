<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-white leading-tight">
                {{ __('Auto-Reply Configurations') }}
            </h2>
            <a href="{{ route('admin.auto-replies.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                {{ __('Add Auto-Reply') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <p class="mb-4 text-sm text-gray-600">
                        Konfigurasi balasan otomatis untuk kata kunci tertentu. Bot akan otomatis membalas ketika menerima pesan yang sesuai dengan trigger yang telah dikonfigurasi.
                    </p>

                    @if($autoReplies->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Trigger
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Response Preview
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Priority
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Status
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Actions
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($autoReplies as $autoReply)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <div class="text-sm font-medium text-gray-900">
                                                        <code class="bg-gray-100 px-2 py-1 rounded">{{ $autoReply->trigger }}</code>
                                                    </div>
                                                    @if($autoReply->case_sensitive)
                                                        <span class="ml-2 text-xs text-gray-500" title="Case sensitive">
                                                            Aa
                                                        </span>
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="text-sm text-gray-900 max-w-md truncate">
                                                    {{ Str::limit($autoReply->response, 100) }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                                    {{ $autoReply->priority }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <form action="{{ route('admin.auto-replies.toggle-active', $autoReply) }}" method="POST" class="inline">
                                                    @csrf
                                                    <button type="submit" class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $autoReply->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                                        {{ $autoReply->is_active ? 'Active' : 'Inactive' }}
                                                    </button>
                                                </form>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <a href="{{ route('admin.auto-replies.edit', $autoReply) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">
                                                    Edit
                                                </a>
                                                <form action="{{ route('admin.auto-replies.destroy', $autoReply) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this auto-reply?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-900">
                                                        Delete
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-4">
                            {{ $autoReplies->links() }}
                        </div>
                    @else
                        <div class="text-center py-8">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                            </svg>
                            <p class="text-gray-500 mt-2 mb-4">No auto-reply configurations found.</p>
                            <a href="{{ route('admin.auto-replies.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Add Your First Auto-Reply
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
                <h3 class="text-sm font-medium text-blue-900 mb-2">ℹ️ Tips:</h3>
                <ul class="text-sm text-blue-700 space-y-1 list-disc list-inside">
                    <li>Gunakan <code class="bg-blue-100 px-1 rounded">@{{ timestamp }}</code> untuk menampilkan waktu saat ini</li>
                    <li>Gunakan <code class="bg-blue-100 px-1 rounded">@{{ date }}</code> untuk tanggal saat ini</li>
                    <li>Gunakan <code class="bg-blue-100 px-1 rounded">@{{ time }}</code> untuk waktu saat ini</li>
                    <li>Priority lebih tinggi akan dicocokkan terlebih dahulu</li>
                    <li>Bot hanya akan membalas satu auto-reply per pesan (yang pertama cocok)</li>
                </ul>
            </div>
        </div>
    </div>
</x-app-layout>
