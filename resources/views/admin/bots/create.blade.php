<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Create New Bot Instance') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <!-- Error Summary -->
            @if ($errors->any())
                <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded">
                    <h3 class="font-semibold text-red-700 mb-2">⚠️ Validation Error(s)</h3>
                    <ul class="list-disc list-inside space-y-1 text-sm text-red-600">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form action="{{ route('admin.bots.store') }}" method="POST">
                        @csrf

                        <div class="mb-6">
                            <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-100">
                                Bot Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   id="name" 
                                   name="name" 
                                   value="{{ old('name') }}"
                                   class="bg-white border {{ $errors->has('name') ? 'border-red-500 focus:ring-red-500 focus:border-red-500' : 'border-gray-300 focus:ring-blue-500 focus:border-blue-500' }} text-gray-900 text-sm rounded-lg block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" 
                                   placeholder="e.g., DUKCAPIL Bot 1"
                                   required>
                            @error('name')
                                <p class="mt-2 text-sm text-red-600 font-medium">{{ $message }}</p>
                            @enderror
                            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                                A friendly name for this bot instance
                            </p>
                        </div>

                        <div class="mb-6">
                            <label for="bot_id" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-100">
                                Bot ID <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   id="bot_id" 
                                   name="bot_id" 
                                   value="{{ old('bot_id') }}"
                                   class="bg-gray-50 border {{ $errors->has('bot_id') ? 'border-red-500 focus:ring-red-500 focus:border-red-500' : 'border-gray-300 focus:ring-blue-500 focus:border-blue-500' }} text-gray-900 text-sm rounded-lg block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" 
                                   placeholder="e.g., 62881011983167 or bot-1"
                                   required>
                            @error('bot_id')
                                <p class="mt-2 text-sm text-red-600 font-medium">{{ $message }}</p>
                            @enderror
                            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                                A unique identifier for this bot instance. Must be unique.
                            </p>
                        </div>

                        <div class="mb-6">
                            <label for="fonnte_token" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-100">
                                Fonnte Token <span class="text-blue-500">(Optional)</span>
                            </label>
                            <input type="text" 
                                   id="fonnte_token" 
                                   name="fonnte_token" 
                                   value="{{ old('fonnte_token') }}"
                                   class="bg-gray-50 border {{ $errors->has('fonnte_token') ? 'border-red-500 focus:ring-red-500 focus:border-red-500' : 'border-gray-300 focus:ring-blue-500 focus:border-blue-500' }} text-gray-900 text-sm rounded-lg block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white font-mono" 
                                   placeholder="Your Fonnte API token">
                            @error('fonnte_token')
                                <p class="mt-2 text-sm text-red-600 font-medium">{{ $message }}</p>
                            @enderror
                            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                                Enter your Fonnte token to connect this bot. If empty, the system will use the token from .env file.
                            </p>
                        </div>

                        <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg p-4 mb-4">
                            <h3 class="text-sm font-semibold text-yellow-800 dark:text-yellow-300 mb-2">
                                ⚠️ Token Validation Required
                            </h3>
                            <p class="text-sm text-yellow-700 dark:text-yellow-400 mb-2">
                                Your Fonnte token will be validated when you create this bot instance. Make sure:
                            </p>
                            <ul class="text-sm text-yellow-700 dark:text-yellow-400 list-disc list-inside space-y-1">
                                <li>Token is valid and not expired</li>
                                <li>Your Fonnte account is active</li>
                                <li>You have connected a WhatsApp device at fonnte.com</li>
                            </ul>
                        </div>

                        <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4 mb-6">
                            <h3 class="text-sm font-semibold text-blue-800 dark:text-blue-300 mb-2">
                                📱 How to get a Valid Fonnte Token?
                            </h3>
                            <ol class="text-sm text-blue-700 dark:text-blue-400 list-decimal list-inside space-y-2">
                                <li>Visit <a href="https://fonnte.com" target="_blank" class="underline font-semibold hover:text-blue-900">fonnte.com</a> and login/register</li>
                                <li><strong>Connect your WhatsApp</strong> by scanning the QR code</li>
                                <li>Wait until status shows <span class="font-semibold">"Connected"</span></li>
                                <li>Go to your dashboard and <strong>copy your API token</strong></li>
                                <li>Paste the token in the field above</li>
                            </ol>
                            <div class="mt-3 p-3 bg-blue-100 dark:bg-blue-800/30 rounded border border-blue-300 dark:border-blue-700">
                                <p class="text-sm text-blue-800 dark:text-blue-300 font-semibold mb-1">
                                    ⚠️ Common Issue:
                                </p>
                                <p class="text-sm text-blue-700 dark:text-blue-400">
                                    If you get "Failed to get device info" error, it means your token is <strong>invalid or expired</strong>. 
                                    Please get a new token from fonnte.com and make sure your WhatsApp is connected.
                                </p>
                            </div>
                        </div>

                        <!-- Server Error Alert -->
                        @if ($errors->has('error'))
                            <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded">
                                <h3 class="font-semibold text-red-700 mb-2">⚠️ Error</h3>
                                <p class="text-sm text-red-600">{{ $errors->first('error') }}</p>
                            </div>
                        @endif

                        <div class="flex items-center justify-between gap-4">
                            <a href="{{ route('admin.bots.index') }}" 
                               class="text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-200 font-medium">
                                ← Back to Bots
                            </a>
                            <button type="submit" 
                                    class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800 transition duration-150">
                                Create Bot Instance
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
