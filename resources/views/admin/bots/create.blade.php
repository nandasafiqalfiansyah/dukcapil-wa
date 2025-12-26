<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Create New Bot Instance') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('admin.bots.store') }}" method="POST">
                        @csrf

                        <div class="mb-6">
                            <label for="name" class="block mb-2 text-sm font-medium text-gray-900">
                                Bot Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   id="name" 
                                   name="name" 
                                   value="{{ old('name') }}"
                                   class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" 
                                   placeholder="e.g., DUKCAPIL Bot 1"
                                   required>
                            @error('name')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-2 text-sm text-gray-600">
                                A friendly name for this bot instance
                            </p>
                        </div>

                        <div class="mb-6">
                            <label for="bot_id" class="block mb-2 text-sm font-medium text-gray-900">
                                Bot ID <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   id="bot_id" 
                                   name="bot_id" 
                                   value="{{ old('bot_id') }}"
                                   class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" 
                                   placeholder="e.g., bot-1"
                                   pattern="[a-z0-9-]+"
                                   required>
                            @error('bot_id')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-2 text-sm text-gray-600">
                                A unique identifier (lowercase letters, numbers, and hyphens only)
                            </p>
                        </div>

                        <div class="bg-white border border-blue-200 rounded-lg p-4 mb-6">
                            <h3 class="text-sm font-semibold text-blue-800 mb-2">📱 What happens next?</h3>
                            <p class="text-sm text-gray-700">
                                After creating the bot, you'll be shown a QR code. Scan it with WhatsApp on your phone to connect this bot to your account.
                            </p>
                        </div>

                        <div class="flex items-center justify-between">
                            <a href="{{ route('admin.bots.index') }}" 
                               class="text-gray-600 hover:text-gray-900">
                                ← Back to Bots
                            </a>
                            <button type="submit" 
                                    class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                                Create Bot
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
