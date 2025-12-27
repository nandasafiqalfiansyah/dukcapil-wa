<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight flex items-center">
            <svg class="h-6 w-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            {{ __('Add Training Data') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6">
                    <form action="{{ route('admin.chat-config.store') }}" method="POST">
                        @csrf

                        <div class="space-y-6">
                            <!-- Intent -->
                            <div>
                                <label for="intent" class="block text-sm font-medium text-gray-700 mb-2">
                                    Intent <span class="text-red-500">*</span>
                                </label>
                                <input 
                                    type="text" 
                                    name="intent" 
                                    id="intent" 
                                    value="{{ old('intent') }}"
                                    class="w-full rounded-lg border-gray-300 focus:border-whatsapp-500 focus:ring focus:ring-whatsapp-200 @error('intent') border-red-500 @enderror"
                                    placeholder="e.g., greeting, ktp_inquiry, birth_certificate"
                                    required
                                >
                                @error('intent')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                <p class="mt-1 text-sm text-gray-500">Unique identifier for this intent type</p>
                            </div>

                            <!-- Pattern -->
                            <div>
                                <label for="pattern" class="block text-sm font-medium text-gray-700 mb-2">
                                    Pattern <span class="text-red-500">*</span>
                                </label>
                                <input 
                                    type="text" 
                                    name="pattern" 
                                    id="pattern" 
                                    value="{{ old('pattern') }}"
                                    class="w-full rounded-lg border-gray-300 focus:border-whatsapp-500 focus:ring focus:ring-whatsapp-200 @error('pattern') border-red-500 @enderror"
                                    placeholder="e.g., cara mengurus ktp"
                                    required
                                >
                                @error('pattern')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                <p class="mt-1 text-sm text-gray-500">The pattern or phrase that triggers this intent</p>
                            </div>

                            <!-- Keywords -->
                            <div>
                                <label for="keywords" class="block text-sm font-medium text-gray-700 mb-2">
                                    Keywords
                                </label>
                                <input 
                                    type="text" 
                                    name="keywords" 
                                    id="keywords" 
                                    value="{{ old('keywords') }}"
                                    class="w-full rounded-lg border-gray-300 focus:border-whatsapp-500 focus:ring focus:ring-whatsapp-200 @error('keywords') border-red-500 @enderror"
                                    placeholder="e.g., ktp, kartu, identitas"
                                >
                                @error('keywords')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                <p class="mt-1 text-sm text-gray-500">Comma-separated keywords to help match this intent</p>
                            </div>

                            <!-- Response -->
                            <div>
                                <label for="response" class="block text-sm font-medium text-gray-700 mb-2">
                                    Response <span class="text-red-500">*</span>
                                </label>
                                <textarea 
                                    name="response" 
                                    id="response" 
                                    rows="5"
                                    class="w-full rounded-lg border-gray-300 focus:border-whatsapp-500 focus:ring focus:ring-whatsapp-200 @error('response') border-red-500 @enderror"
                                    placeholder="The bot's response when this intent is detected..."
                                    required
                                >{{ old('response') }}</textarea>
                                @error('response')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                <p class="mt-1 text-sm text-gray-500">
                                    Available placeholders: @{{ timestamp }}, @{{ date }}, @{{ time }}, @{{ day }}, @{{ user_message }}
                                </p>
                            </div>

                            <!-- Priority -->
                            <div>
                                <label for="priority" class="block text-sm font-medium text-gray-700 mb-2">
                                    Priority
                                </label>
                                <input 
                                    type="number" 
                                    name="priority" 
                                    id="priority" 
                                    min="0"
                                    max="100"
                                    value="{{ old('priority', 50) }}"
                                    class="w-full rounded-lg border-gray-300 focus:border-whatsapp-500 focus:ring focus:ring-whatsapp-200 @error('priority') border-red-500 @enderror"
                                >
                                @error('priority')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                <p class="mt-1 text-sm text-gray-500">Higher priority patterns are checked first (0-100, default: 50)</p>
                            </div>

                            <!-- Active Status -->
                            <div>
                                <label class="flex items-center">
                                    <input 
                                        type="checkbox" 
                                        name="is_active" 
                                        id="is_active"
                                        value="1"
                                        {{ old('is_active', true) ? 'checked' : '' }}
                                        class="rounded border-gray-300 text-whatsapp-600 focus:ring-whatsapp-500"
                                    >
                                    <span class="ml-2 text-sm text-gray-700">Active (Enable this training data)</span>
                                </label>
                            </div>

                            <!-- Buttons -->
                            <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200">
                                <a href="{{ route('admin.chat-config.index') }}" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition">
                                    Cancel
                                </a>
                                <button type="submit" class="px-4 py-2 bg-whatsapp-600 hover:bg-whatsapp-700 text-white rounded-lg transition">
                                    Create Training Data
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
