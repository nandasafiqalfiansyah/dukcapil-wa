<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('NLP Diagnostics') }}
            </h2>
            <a href="{{ route('admin.nlp-config.index') }}" 
               class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                Kembali ke Konfigurasi
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="text-sm text-gray-500">Total Training Data</div>
                        <div class="text-3xl font-bold text-gray-900">{{ $trainingDataCount }}</div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="text-sm text-gray-500">Active Training Data</div>
                        <div class="text-3xl font-bold text-green-600">{{ $activeTrainingData }}</div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="text-sm text-gray-500">Total Processed</div>
                        <div class="text-3xl font-bold text-blue-600">{{ $totalProcessed }}</div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="text-sm text-gray-500">Avg Confidence</div>
                        <div class="text-3xl font-bold text-purple-600">{{ number_format($avgConfidence * 100, 1) }}%</div>
                    </div>
                </div>
            </div>

            <!-- Intent Distribution -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold mb-4">Intent Distribution (Top 10)</h3>
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Intent
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Count
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Avg Confidence
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Distribution
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($intentDistribution as $intent)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            {{ $intent->intent }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $intent->count }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                {{ $intent->avg_confidence >= 0.7 ? 'bg-green-100 text-green-800' : ($intent->avg_confidence >= 0.5 ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                                {{ number_format($intent->avg_confidence * 100, 1) }}%
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <div class="w-full bg-gray-200 rounded-full h-2.5">
                                                <div class="bg-blue-600 h-2.5 rounded-full" 
                                                     style="width: {{ ($intent->count / $totalProcessed) * 100 }}%"></div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- System Info -->
            <div class="mt-6 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold mb-4">System Information</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <h4 class="font-medium text-gray-700 mb-2">Current Configuration</h4>
                            <ul class="text-sm space-y-1">
                                <li><span class="text-gray-600">Confidence Threshold:</span> <span class="font-mono">{{ config('nlp.confidence_threshold') }}</span></li>
                                <li><span class="text-gray-600">Detailed Logging:</span> <span class="font-mono">{{ config('nlp.enable_detailed_logging') ? 'Enabled' : 'Disabled' }}</span></li>
                                <li><span class="text-gray-600">Cache Training Data:</span> <span class="font-mono">{{ config('nlp.cache_training_data') ? 'Enabled' : 'Disabled' }}</span></li>
                            </ul>
                        </div>
                        
                        <div>
                            <h4 class="font-medium text-gray-700 mb-2">Algorithm Weights</h4>
                            <ul class="text-sm space-y-1">
                                <li><span class="text-gray-600">Exact Match:</span> <span class="font-mono">{{ config('nlp.algorithm.exact_match_weight') }}</span></li>
                                <li><span class="text-gray-600">Partial Match:</span> <span class="font-mono">{{ config('nlp.algorithm.partial_match_weight') }}</span></li>
                                <li><span class="text-gray-600">Keyword Match:</span> <span class="font-mono">{{ config('nlp.algorithm.keyword_match_weight') }}</span></li>
                                <li><span class="text-gray-600">Word Similarity:</span> <span class="font-mono">{{ config('nlp.algorithm.word_similarity_weight') }}</span></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
