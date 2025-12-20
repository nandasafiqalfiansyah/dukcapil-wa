<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Create Auto-Reply Configuration') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form action="{{ route('admin.auto-replies.store') }}" method="POST">
                        @csrf

                        <div class="mb-4">
                            <label for="trigger" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Trigger (Kata Kunci) *
                            </label>
                            <input type="text" name="trigger" id="trigger" value="{{ old('trigger') }}" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            @error('trigger')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-sm text-gray-500">Kata kunci yang akan memicu balasan otomatis (contoh: ping, test, halo)</p>
                        </div>

                        <div class="mb-4">
                            <label for="response" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Response (Balasan) *
                            </label>
                            <textarea name="response" id="response" rows="6" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">{{ old('response') }}</textarea>
                            @error('response')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-sm text-gray-500">
                                Pesan yang akan dikirim sebagai balasan. Gunakan placeholder: 
                                <code class="bg-gray-100 dark:bg-gray-700 px-1 rounded">{{'{{'}}timestamp{{'}}'}}</code>, 
                                <code class="bg-gray-100 dark:bg-gray-700 px-1 rounded">{{'{{'}}date{{'}}'}}</code>, 
                                <code class="bg-gray-100 dark:bg-gray-700 px-1 rounded">{{'{{'}}time{{'}}'}}</code>
                            </p>
                        </div>

                        <div class="mb-4">
                            <label for="priority" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Priority *
                            </label>
                            <input type="number" name="priority" id="priority" value="{{ old('priority', 50) }}" min="0" max="1000" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            @error('priority')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-sm text-gray-500">Priority lebih tinggi akan dicocokkan terlebih dahulu (0-1000)</p>
                        </div>

                        <div class="mb-4">
                            <label class="flex items-center">
                                <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}
                                    class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600">
                                <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Active</span>
                            </label>
                            <p class="mt-1 text-sm text-gray-500">Centang untuk mengaktifkan auto-reply ini</p>
                        </div>

                        <div class="mb-4">
                            <label class="flex items-center">
                                <input type="checkbox" name="case_sensitive" value="1" {{ old('case_sensitive') ? 'checked' : '' }}
                                    class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600">
                                <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Case Sensitive</span>
                            </label>
                            <p class="mt-1 text-sm text-gray-500">Centang jika trigger harus sama persis dengan huruf besar/kecil</p>
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('admin.auto-replies.index') }}" class="mr-4 text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-100">
                                Cancel
                            </a>
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Create Auto-Reply
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
