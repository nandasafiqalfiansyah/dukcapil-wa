<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Konfigurasi NLP') }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('admin.nlp-config.diagnostics') }}" 
                   class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Diagnostics
                </a>
                <form action="{{ route('admin.nlp-config.clear-cache') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" 
                            class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded"
                            onclick="return confirm('Hapus cache NLP?')">
                        Clear Cache
                    </button>
                </form>
                <form action="{{ route('admin.nlp-config.reset') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" 
                            class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded"
                            onclick="return confirm('Reset semua konfigurasi ke default?')">
                        Reset Default
                    </button>
                </form>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('admin.nlp-config.update') }}" method="POST">
                        @csrf
                        @method('PUT')

                        @foreach ($configs as $group => $groupConfigs)
                            <div class="mb-8">
                                <h3 class="text-lg font-semibold mb-4 text-gray-700 border-b pb-2">
                                    {{ ucfirst(str_replace('_', ' ', $group)) }}
                                </h3>

                                <div class="grid grid-cols-1 gap-4">
                                    @foreach ($groupConfigs as $config)
                                        <div class="border rounded p-4 bg-gray-50">
                                            <div class="flex justify-between items-start">
                                                <div class="flex-1">
                                                    <label for="config_{{ $config->id }}" class="block text-sm font-medium text-gray-700">
                                                        {{ str_replace('nlp_', '', str_replace('_', ' ', $config->key)) }}
                                                    </label>
                                                    @if ($config->description)
                                                        <p class="text-xs text-gray-500 mt-1">{{ $config->description }}</p>
                                                    @endif
                                                </div>

                                                <div class="ml-4 w-48">
                                                    <input type="hidden" name="configs[{{ $config->id }}][key]" value="{{ $config->key }}">
                                                    
                                                    @if ($config->type === 'boolean')
                                                        <select name="configs[{{ $config->id }}][value]" 
                                                                id="config_{{ $config->id }}"
                                                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                                            <option value="1" {{ $config->value ? 'selected' : '' }}>Enabled</option>
                                                            <option value="0" {{ !$config->value ? 'selected' : '' }}>Disabled</option>
                                                        </select>
                                                    @elseif ($config->type === 'integer' || $config->type === 'float')
                                                        <input type="number" 
                                                               name="configs[{{ $config->id }}][value]" 
                                                               id="config_{{ $config->id }}"
                                                               value="{{ $config->value }}"
                                                               step="{{ $config->type === 'float' ? '0.01' : '1' }}"
                                                               min="0"
                                                               max="{{ $config->type === 'float' ? '1' : '10000' }}"
                                                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                                    @else
                                                        <input type="text" 
                                                               name="configs[{{ $config->id }}][value]" 
                                                               id="config_{{ $config->id }}"
                                                               value="{{ $config->value }}"
                                                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach

                        <div class="flex justify-end">
                            <button type="submit" 
                                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded">
                                Simpan Konfigurasi
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="mt-6 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold mb-4">Informasi</h3>
                    <div class="prose max-w-none">
                        <p class="text-sm text-gray-600">
                            Konfigurasi ini mengontrol bagaimana algoritma NLP (Natural Language Processing) bekerja dalam mendeteksi intent dan menghasilkan respons.
                        </p>
                        <ul class="text-sm text-gray-600 list-disc pl-5 mt-2">
                            <li><strong>Confidence Threshold:</strong> Nilai minimum (0-1) untuk menerima intent. Nilai lebih rendah = lebih permisif.</li>
                            <li><strong>Algorithm Weights:</strong> Bobot untuk setiap algoritma pencocokan (0-1). Total bisa lebih dari 1.</li>
                            <li><strong>Logging:</strong> Aktifkan untuk transparansi proses NLP di log file.</li>
                            <li><strong>Cache:</strong> Aktifkan untuk performa lebih baik (disable untuk real-time updates).</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
