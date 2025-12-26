<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-white leading-tight flex items-center">
                <svg class="h-6 w-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                {{ __('Chat Configuration') }}
            </h2>
            <a href="{{ route('admin.chat-config.create') }}" class="px-4 py-2 bg-whatsapp-600 hover:bg-whatsapp-700 text-white rounded-lg transition flex items-center">
                <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Add Training Data
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6">
                    <div class="mb-4 flex items-center justify-between">
                        <h3 class="text-lg font-bold text-gray-900">NLP Training Data</h3>
                        <div class="text-sm text-gray-600">
                            Total: {{ $trainingData->total() }} patterns
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Intent</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pattern</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Response Preview</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Priority</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($trainingData as $data)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                                {{ $data->intent }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-sm text-gray-900 max-w-xs truncate">{{ $data->pattern }}</div>
                                            @if($data->keywords && count($data->keywords) > 0)
                                                <div class="mt-1 flex flex-wrap gap-1">
                                                    @foreach(array_slice($data->keywords, 0, 3) as $keyword)
                                                        <span class="text-xs bg-gray-100 text-gray-600 px-2 py-0.5 rounded">{{ $keyword }}</span>
                                                    @endforeach
                                                    @if(count($data->keywords) > 3)
                                                        <span class="text-xs text-gray-500">+{{ count($data->keywords) - 3 }}</span>
                                                    @endif
                                                </div>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-sm text-gray-900 max-w-md truncate">{{ $data->response }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="text-sm font-medium text-gray-900">{{ $data->priority }}</span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <button 
                                                onclick="toggleActive({{ $data->id }}, {{ $data->is_active ? 'true' : 'false' }})"
                                                class="toggle-btn relative inline-flex h-6 w-11 items-center rounded-full transition-colors {{ $data->is_active ? 'bg-green-600' : 'bg-gray-200' }}"
                                                data-id="{{ $data->id }}"
                                            >
                                                <span class="toggle-slider inline-block h-4 w-4 transform rounded-full bg-white transition-transform {{ $data->is_active ? 'translate-x-6' : 'translate-x-1' }}"></span>
                                            </button>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <div class="flex space-x-2">
                                                <a href="{{ route('admin.chat-config.edit', $data) }}" class="text-blue-600 hover:text-blue-900">
                                                    Edit
                                                </a>
                                                <form action="{{ route('admin.chat-config.destroy', $data) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this training data?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-900">
                                                        Delete
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                                            No training data found. Create one to get started!
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $trainingData->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

        async function toggleActive(id, currentState) {
            try {
                const response = await fetch(`/admin/chat-config/${id}/toggle-active`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    }
                });
                
                const data = await response.json();
                
                if (data.success) {
                    const btn = document.querySelector(`button[data-id="${id}"]`);
                    const slider = btn.querySelector('.toggle-slider');
                    
                    if (data.is_active) {
                        btn.classList.remove('bg-gray-200');
                        btn.classList.add('bg-green-600');
                        slider.classList.remove('translate-x-1');
                        slider.classList.add('translate-x-6');
                    } else {
                        btn.classList.remove('bg-green-600');
                        btn.classList.add('bg-gray-200');
                        slider.classList.remove('translate-x-6');
                        slider.classList.add('translate-x-1');
                    }
                }
            } catch (error) {
                console.error('Error toggling active status:', error);
                alert('Failed to update status');
            }
        }
    </script>
    @endpush
</x-app-layout>
