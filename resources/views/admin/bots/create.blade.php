<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Create New Bot Instance') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
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
                                   class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" 
                                   placeholder="e.g., DUKCAPIL Bot 1"
                                   required>
                            @error('name')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
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
                                   class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" 
                                   placeholder="e.g., bot-1"
                                   pattern="[a-z0-9-]+"
                                   required>
                            @error('bot_id')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                                A unique identifier (lowercase letters, numbers, and hyphens only)
                            </p>
                        </div>

                        <div class="mb-6">
                            <label for="fonnte_token" class="block mb-2 text-sm font-medium text-gray-900">
                                Fonnte Token <span class="text-blue-500">(Optional)</span>
                            </label>
                            <input type="text" 
                                   id="fonnte_token" 
                                   name="fonnte_token" 
                                   value="{{ old('fonnte_token') }}"
                                   class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white font-mono" 
                                   placeholder="Your Fonnte API token">
                            @error('fonnte_token')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                                Enter your Fonnte token to connect this bot. If empty, the system will use the token from .env file.
                            </p>
                        </div>

                        <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4 mb-6">
                            <h3 class="text-sm font-semibold text-blue-800 dark:text-blue-300 mb-2">
                                📱 How to get Fonnte Token?
                            </h3>
                            <ol class="text-sm text-blue-700 dark:text-blue-400 list-decimal list-inside space-y-2">
                                <li>Visit <a href="https://fonnte.com" target="_blank" class="underline font-semibold">fonnte.com</a></li>
                                <li>Create an account or login</li>
                                <li>Connect your WhatsApp number</li>
                                <li>Copy your API token from the dashboard</li>
                                <li>Paste the token above</li>
                            </ol>
                            <p class="mt-3 text-sm text-blue-700 dark:text-blue-400">
                                💡 Your token will be validated automatically when creating the bot.
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
