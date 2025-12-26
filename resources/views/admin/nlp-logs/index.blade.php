<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-white leading-tight flex items-center">
                <svg class="h-6 w-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                </svg>
                {{ __('NLP Live Logs') }}
            </h2>
            <div class="flex items-center space-x-3">
                <button id="toggleLive" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition flex items-center">
                    <span id="liveIndicator" class="w-3 h-3 bg-green-300 rounded-full mr-2 animate-pulse"></span>
                    <span id="liveText">Live</span>
                </button>
                <button id="clearLogs" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition">
                    Clear Display
                </button>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="rounded-md bg-blue-500 p-3">
                                <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <div class="text-sm font-medium text-gray-500">Total Processed</div>
                            <div class="text-2xl font-bold text-gray-900" id="statTotal">-</div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="rounded-md bg-green-500 p-3">
                                <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <div class="text-sm font-medium text-gray-500">Avg Confidence</div>
                            <div class="text-2xl font-bold text-gray-900" id="statConfidence">-</div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="rounded-md bg-yellow-500 p-3">
                                <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <div class="text-sm font-medium text-gray-500">Low Confidence</div>
                            <div class="text-2xl font-bold text-gray-900" id="statLowConf">-</div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="rounded-md bg-purple-500 p-3">
                                <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <div class="text-sm font-medium text-gray-500">Live Updates</div>
                            <div class="text-2xl font-bold text-gray-900" id="liveCount">0</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filters -->
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6">
                    <form id="filterForm" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Intent</label>
                            <select name="intent" class="w-full rounded-lg border-gray-300 focus:border-whatsapp-500 focus:ring focus:ring-whatsapp-200">
                                <option value="">All Intents</option>
                                @foreach($intents as $intent)
                                    <option value="{{ $intent }}" {{ request('intent') == $intent ? 'selected' : '' }}>
                                        {{ $intent }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Min Confidence %</label>
                            <input type="number" name="min_confidence" min="0" max="100" 
                                   class="w-full rounded-lg border-gray-300 focus:border-whatsapp-500 focus:ring focus:ring-whatsapp-200"
                                   value="{{ request('min_confidence') }}" placeholder="0">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Date From</label>
                            <input type="date" name="date_from" 
                                   class="w-full rounded-lg border-gray-300 focus:border-whatsapp-500 focus:ring focus:ring-whatsapp-200"
                                   value="{{ request('date_from') }}">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Date To</label>
                            <input type="date" name="date_to" 
                                   class="w-full rounded-lg border-gray-300 focus:border-whatsapp-500 focus:ring focus:ring-whatsapp-200"
                                   value="{{ request('date_to') }}">
                        </div>

                        <div class="md:col-span-4 flex justify-end space-x-2">
                            <button type="submit" class="px-4 py-2 bg-whatsapp-600 hover:bg-whatsapp-700 text-white rounded-lg transition">
                                Apply Filters
                            </button>
                            <a href="{{ route('admin.nlp-logs.index') }}" class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg transition">
                                Reset
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Live Logs Stream -->
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                        <svg class="h-5 w-5 mr-2 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                            <circle cx="10" cy="10" r="3" class="animate-ping"></circle>
                            <circle cx="10" cy="10" r="3"></circle>
                        </svg>
                        Live NLP Processing Stream
                    </h3>
                    <div id="liveLogsContainer" class="space-y-2 max-h-96 overflow-y-auto bg-gray-900 rounded-lg p-4 font-mono text-sm">
                        <div class="text-gray-400 text-center py-8">
                            Waiting for new NLP processing events...
                        </div>
                    </div>
                </div>
            </div>

            <!-- Historical Logs -->
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Historical Logs</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Time</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Message</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Intent</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Confidence</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pattern</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($logs as $log)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $log->created_at->format('Y-m-d H:i:s') }}
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-900">
                                            <div class="max-w-xs truncate">{{ $log->message }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                                {{ $log->intent }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @php
                                                $confidencePercent = round($log->confidence * 100);
                                                $colorClass = $confidencePercent >= 70 ? 'bg-green-100 text-green-800' : ($confidencePercent >= 50 ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800');
                                            @endphp
                                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $colorClass }}">
                                                {{ $confidencePercent }}%
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-500">
                                            <div class="max-w-xs truncate">
                                                {{ $log->metadata['matched_pattern'] ?? 'N/A' }}
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                                            No logs found
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $logs->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        let isLiveEnabled = true;
        let latestTimestamp = null;
        let liveUpdateCount = 0;

        // Load statistics
        async function loadStatistics() {
            try {
                const response = await fetch('{{ route("admin.nlp-logs.statistics") }}');
                const data = await response.json();
                
                if (data.success) {
                    document.getElementById('statTotal').textContent = data.statistics.total_processed;
                    document.getElementById('statConfidence').textContent = data.statistics.average_confidence + '%';
                    document.getElementById('statLowConf').textContent = data.statistics.low_confidence_count;
                }
            } catch (error) {
                console.error('Error loading statistics:', error);
            }
        }

        // Fetch live logs
        async function fetchLiveLogs() {
            if (!isLiveEnabled) return;

            try {
                const url = new URL('{{ route("admin.nlp-logs.live") }}');
                if (latestTimestamp) {
                    url.searchParams.append('since', latestTimestamp);
                }

                const response = await fetch(url);
                const data = await response.json();
                
                if (data.success && data.logs.length > 0) {
                    const container = document.getElementById('liveLogsContainer');
                    
                    // Remove placeholder if exists
                    if (container.querySelector('.text-center')) {
                        container.innerHTML = '';
                    }

                    // Add new logs at the top
                    data.logs.reverse().forEach(log => {
                        const logElement = createLogElement(log);
                        container.insertBefore(logElement, container.firstChild);
                        liveUpdateCount++;
                    });

                    // Update latest timestamp
                    if (data.latest_timestamp) {
                        latestTimestamp = data.latest_timestamp;
                    }

                    // Update live count
                    document.getElementById('liveCount').textContent = liveUpdateCount;

                    // Keep only last 50 logs
                    while (container.children.length > 50) {
                        container.removeChild(container.lastChild);
                    }
                }
            } catch (error) {
                console.error('Error fetching live logs:', error);
            }
        }

        function createLogElement(log) {
            const div = document.createElement('div');
            div.className = 'border-l-4 border-green-500 bg-gray-800 p-3 rounded animate-fade-in';
            
            const confidencePercent = Math.round(log.confidence * 100);
            const confidenceColor = confidencePercent >= 70 ? 'text-green-400' : (confidencePercent >= 50 ? 'text-yellow-400' : 'text-red-400');
            
            div.innerHTML = `
                <div class="flex justify-between items-start">
                    <div class="flex-1">
                        <span class="text-gray-400 text-xs">${log.created_at_human}</span>
                        <span class="ml-3 text-blue-400 text-xs font-semibold">${escapeHtml(log.intent)}</span>
                        <span class="ml-2 ${confidenceColor} text-xs">${confidencePercent}%</span>
                    </div>
                    <span class="text-gray-500 text-xs">#${log.session_id}</span>
                </div>
                <div class="text-gray-300 text-sm mt-1">${escapeHtml(log.message)}</div>
                ${log.matched_pattern ? `<div class="text-gray-500 text-xs mt-1">Pattern: ${escapeHtml(log.matched_pattern)}</div>` : ''}
            `;
            
            return div;
        }

        function escapeHtml(text) {
            if (!text) return '';
            const map = {
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                '"': '&quot;',
                "'": '&#039;'
            };
            return text.replace(/[&<>"']/g, m => map[m]);
        }

        // Toggle live updates
        document.getElementById('toggleLive').addEventListener('click', () => {
            isLiveEnabled = !isLiveEnabled;
            const btn = document.getElementById('toggleLive');
            const indicator = document.getElementById('liveIndicator');
            const text = document.getElementById('liveText');
            
            if (isLiveEnabled) {
                btn.className = 'px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition flex items-center';
                indicator.className = 'w-3 h-3 bg-green-300 rounded-full mr-2 animate-pulse';
                text.textContent = 'Live';
                fetchLiveLogs();
            } else {
                btn.className = 'px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg transition flex items-center';
                indicator.className = 'w-3 h-3 bg-gray-300 rounded-full mr-2';
                text.textContent = 'Paused';
            }
        });

        // Clear logs display
        document.getElementById('clearLogs').addEventListener('click', () => {
            document.getElementById('liveLogsContainer').innerHTML = '<div class="text-gray-400 text-center py-8">Waiting for new NLP processing events...</div>';
            liveUpdateCount = 0;
            document.getElementById('liveCount').textContent = '0';
        });

        // Initial load
        loadStatistics();
        fetchLiveLogs();

        // Poll for new logs every 3 seconds
        setInterval(fetchLiveLogs, 3000);

        // Reload statistics every 30 seconds
        setInterval(loadStatistics, 30000);
    </script>

    <style>
        @keyframes fade-in {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        .animate-fade-in {
            animation: fade-in 0.3s ease-out;
        }
    </style>
    @endpush
</x-app-layout>
