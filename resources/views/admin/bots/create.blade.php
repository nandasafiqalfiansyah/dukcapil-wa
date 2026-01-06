<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-white leading-tight">
            {{ __('Create New Bot Instance') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <!-- Error Summary -->
            @if ($errors->any())
                <div class="mb-6 bg-emerald-50 border-l-4 border-emerald-500 p-4 rounded">
                    <h3 class="font-semibold text-emerald-700 mb-2">⚠️ Validation Error(s)</h3>
                    <ul class="list-disc list-inside space-y-1 text-sm text-emerald-600">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="bg-emerald-50 dark:bg-emerald-950 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form action="{{ route('admin.bots.store') }}" method="POST">
                        @csrf

                        <div class="mb-6">
                            <label for="name" class="block mb-2 text-sm font-medium text-emerald-900 dark:text-emerald-100">
                                Bot Name <span class="text-emerald-600">*</span>
                            </label>
                            <input type="text" 
                                   id="name" 
                                   name="name" 
                                   value="{{ old('name') }}"
                                   class="bg-white border {{ $errors->has('name') ? 'border-emerald-500 focus:ring-emerald-500 focus:border-emerald-500' : 'border-emerald-300 focus:ring-emerald-500 focus:border-emerald-500' }} text-emerald-900 text-sm rounded-lg block w-full p-2.5 dark:bg-emerald-900/20 dark:border-emerald-700 dark:placeholder-emerald-400 dark:text-emerald-100 dark:focus:ring-emerald-500 dark:focus:border-emerald-500" 
                                   placeholder="e.g., DUKCAPIL Bot 1"
                                   required>
                            @error('name')
                                <p class="mt-2 text-sm text-emerald-600 font-medium">{{ $message }}</p>
                            @enderror
                            <p class="mt-2 text-sm text-emerald-600 dark:text-emerald-400">
                                A friendly name for this bot instance
                            </p>
                        </div>

                        <div class="mb-6">
                            <label for="bot_id" class="block mb-2 text-sm font-medium text-emerald-900 dark:text-emerald-100">
                                Bot ID <span class="text-emerald-600">*</span>
                            </label>
                            <input type="text" 
                                   id="bot_id" 
                                   name="bot_id" 
                                   value="{{ old('bot_id') }}"
                                   class="bg-white border {{ $errors->has('bot_id') ? 'border-emerald-500 focus:ring-emerald-500 focus:border-emerald-500' : 'border-emerald-300 focus:ring-emerald-500 focus:border-emerald-500' }} text-emerald-900 text-sm rounded-lg block w-full p-2.5 dark:bg-emerald-900/20 dark:border-emerald-700 dark:placeholder-emerald-400 dark:text-emerald-100 dark:focus:ring-emerald-500 dark:focus:border-emerald-500" 
                                   placeholder="e.g., 62881011983167 or bot-1"
                                   required>
                            @error('bot_id')
                                <p class="mt-2 text-sm text-emerald-600 font-medium">{{ $message }}</p>
                            @enderror
                            <p class="mt-2 text-sm text-emerald-600 dark:text-emerald-400">
                                A unique identifier for this bot instance. Must be unique.
                            </p>
                        </div>

                        <div class="mb-6">
                            <label for="fonnte_token" class="block mb-2 text-sm font-medium text-emerald-900 dark:text-emerald-100">
                                Fonnte Token <span class="text-emerald-600">*</span>
                            </label>
                            <input type="text" 
                                   id="fonnte_token" 
                                   name="fonnte_token" 
                                   value="{{ old('fonnte_token') }}"
                                   class="bg-white border {{ $errors->has('fonnte_token') ? 'border-emerald-500 focus:ring-emerald-500 focus:border-emerald-500' : 'border-emerald-300 focus:ring-emerald-500 focus:border-emerald-500' }} text-emerald-900 text-sm rounded-lg block w-full p-2.5 dark:bg-emerald-900/20 dark:border-emerald-700 dark:placeholder-emerald-400 dark:text-emerald-100 dark:focus:ring-emerald-500 dark:focus:border-emerald-500 font-mono" 
                                   placeholder="Your Fonnte API token (required)"
                                   required>
                            @error('fonnte_token')
                                <p class="mt-2 text-sm text-emerald-600 font-medium">{{ $message }}</p>
                            @enderror
                            <p class="mt-2 text-sm text-emerald-600 dark:text-emerald-400">
                                Enter your valid Fonnte API token. The token will be validated and stored securely for this bot instance.
                            </p>
                        </div>

                        <div class="mb-6">
                            <label for="api_url" class="block mb-2 text-sm font-medium text-emerald-900 dark:text-emerald-100">
                                API URL <span class="text-emerald-600">*</span>
                            </label>
                            <input type="url" 
                                   id="api_url" 
                                   name="api_url" 
                                   value="{{ old('api_url', 'https://api.fonnte.com') }}"
                                   class="bg-white border {{ $errors->has('api_url') ? 'border-emerald-500 focus:ring-emerald-500 focus:border-emerald-500' : 'border-emerald-300 focus:ring-emerald-500 focus:border-emerald-500' }} text-emerald-900 text-sm rounded-lg block w-full p-2.5 dark:bg-emerald-900/20 dark:border-emerald-700 dark:placeholder-emerald-400 dark:text-emerald-100 dark:focus:ring-emerald-500 dark:focus:border-emerald-500 font-mono" 
                                   placeholder="https://api.fonnte.com"
                                   required>
                            @error('api_url')
                                <p class="mt-2 text-sm text-emerald-600 font-medium">{{ $message }}</p>
                            @enderror
                            <p class="mt-2 text-sm text-emerald-600 dark:text-emerald-400">
                                Fonnte API base URL. Default: https://api.fonnte.com
                            </p>
                        </div>

                        <div class="mb-6">
                            <label for="webhook_url" class="block mb-2 text-sm font-medium text-emerald-900 dark:text-emerald-100">
                                Webhook URL <span class="text-gray-500">(Optional)</span>
                            </label>
                            <input type="url" 
                                   id="webhook_url" 
                                   name="webhook_url" 
                                   value="{{ old('webhook_url', url('/api/webhook/whatsapp')) }}"
                                   class="bg-white border border-emerald-300 focus:ring-emerald-500 focus:border-emerald-500 text-emerald-900 text-sm rounded-lg block w-full p-2.5 dark:bg-emerald-900/20 dark:border-emerald-700 dark:placeholder-emerald-400 dark:text-emerald-100 dark:focus:ring-emerald-500 dark:focus:border-emerald-500 font-mono" 
                                   placeholder="{{ url('/api/webhook/whatsapp') }}">
                            @error('webhook_url')
                                <p class="mt-2 text-sm text-emerald-600 font-medium">{{ $message }}</p>
                            @enderror
                            <p class="mt-2 text-sm text-emerald-600 dark:text-emerald-400">
                                Your webhook endpoint URL for receiving messages. Configure this in your Fonnte dashboard.
                            </p>
                        </div>

                        <div class="bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800 rounded-lg p-4 mb-4">
                            <h3 class="text-sm font-semibold text-emerald-800 dark:text-emerald-300 mb-2">
                                ⚠️ Token Validation Required
                            </h3>
                            <p class="text-sm text-emerald-700 dark:text-emerald-400 mb-2">
                                Your Fonnte token will be validated when you create this bot instance. Make sure:
                            </p>
                            <ul class="text-sm text-emerald-700 dark:text-emerald-400 list-disc list-inside space-y-1">
                                <li>Token is valid and not expired</li>
                                <li>Your Fonnte account is active</li>
                                <li>You have connected a WhatsApp device at fonnte.com</li>
                            </ul>
                        </div>

                        <div class="bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800 rounded-lg p-4 mb-6">
                            <h3 class="text-sm font-semibold text-emerald-800 dark:text-emerald-300 mb-2">
                                📱 How to get Your Fonnte Token?
                            </h3>
                            <ol class="text-sm text-emerald-700 dark:text-emerald-400 list-decimal list-inside space-y-2">
                                <li>Visit <a href="https://fonnte.com" target="_blank" class="underline font-semibold hover:text-emerald-900 dark:hover:text-emerald-200">fonnte.com</a> and register/login</li>
                                <li><strong>Connect your WhatsApp</strong> by scanning the QR code in your dashboard</li>
                                <li>Wait until status shows <span class="font-semibold">"Connected"</span></li>
                                <li>Go to <strong>Settings</strong> or <strong>Dashboard</strong> and copy your <strong>API Token</strong></li>
                                <li>Paste the token above - it will be validated and securely stored</li>
                            </ol>
                            <div class="mt-3 p-3 bg-emerald-100 dark:bg-emerald-800/30 rounded border border-emerald-300 dark:border-emerald-700">
                                <p class="text-sm text-emerald-800 dark:text-emerald-300 font-semibold mb-1">
                                    ℹ️ Per-Bot Configuration
                                </p>
                                <p class="text-sm text-emerald-700 dark:text-emerald-400">
                                    Each bot instance requires its own Fonnte token. This allows you to manage multiple WhatsApp numbers independently without relying on .env configuration.
                                </p>
                            </div>
                        </div>

                        <!-- Server Error Alert -->
                        @if ($errors->has('error'))
                            <div class="mb-6 bg-emerald-50 border-l-4 border-emerald-500 p-4 rounded">
                                <h3 class="font-semibold text-emerald-700 mb-2">⚠️ Error</h3>
                                <p class="text-sm text-emerald-600">{{ $errors->first('error') }}</p>
                            </div>
                        @endif

                        <div class="flex items-center justify-between gap-4">
                            <a href="{{ route('admin.bots.index') }}" 
                               class="text-emerald-600 hover:text-emerald-900 dark:text-emerald-400 dark:hover:text-emerald-200 font-medium">
                                ← Back to Bots
                            </a>
                            <button type="submit" 
                                    class="text-white bg-emerald-600 hover:bg-emerald-700 focus:ring-4 focus:outline-none focus:ring-emerald-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-emerald-600 dark:hover:bg-emerald-700 dark:focus:ring-emerald-800 transition duration-150">
                                Create Bot Instance
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
