<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Test Kirim Pesan WhatsApp') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <!-- Info Card -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                <div class="flex items-start">
                    <svg class="h-5 w-5 text-blue-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <div class="ml-3">
                        <h3 class="text-sm font-bold text-blue-900">Informasi</h3>
                        <p class="mt-1 text-sm text-blue-700">
                            Gunakan page ini untuk test kirim pesan WhatsApp langsung. Pastikan nomor WhatsApp dalam format Indonesia (08xxx atau 628xxx).
                        </p>
                    </div>
                </div>
            </div>

            <!-- Main Form Card -->
            <div class="bg-white overflow-hidden shadow-lg sm:rounded-xl">
                <div class="bg-gradient-to-r from-whatsapp-600 to-whatsapp-700 p-6">
                    <h3 class="text-xl font-bold text-white flex items-center">
                        <svg class="h-6 w-6 mr-2" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/>
                        </svg>
                        Kirim Pesan WhatsApp
                    </h3>
                </div>
                
                <form id="messageForm" class="p-6 space-y-6">
                    @csrf
                    
                    <!-- Bot Selection -->
                    <div>
                        <label for="bot_id" class="block text-sm font-bold text-gray-700 mb-2">
                            Pilih Bot / Device WhatsApp
                        </label>
                        <select id="bot_id" name="bot_id" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-whatsapp-500 focus:ring-whatsapp-500">
                            <option value="">-- Auto (Device yang tersedia) --</option>
                            @foreach($bots as $bot)
                                <option value="{{ $bot->id }}">
                                    {{ $bot->name }} ({{ $bot->phone_number }}) - {{ ucfirst($bot->status) }}
                                </option>
                            @endforeach
                        </select>
                        <p class="mt-1 text-xs text-gray-500">Biarkan kosong untuk auto-select device yang aktif</p>
                    </div>

                    <!-- Phone Number -->
                    <div>
                        <label for="phone_number" class="block text-sm font-bold text-gray-700 mb-2">
                            Nomor WhatsApp Tujuan <span class="text-red-600">*</span>
                        </label>
                        <input type="text" 
                               id="phone_number" 
                               name="phone_number" 
                               placeholder="08123456789 atau 628123456789" 
                               required
                               class="w-full rounded-lg border-gray-300 shadow-sm focus:border-whatsapp-500 focus:ring-whatsapp-500">
                        <p class="mt-1 text-xs text-gray-500">Format: 08xxx atau 628xxx (tanpa tanda + atau spasi)</p>
                    </div>

                    <!-- Message -->
                    <div>
                        <label for="message" class="block text-sm font-bold text-gray-700 mb-2">
                            Isi Pesan <span class="text-red-600">*</span>
                        </label>
                        <textarea id="message" 
                                  name="message" 
                                  rows="6" 
                                  placeholder="Tulis pesan yang ingin dikirim..."
                                  required
                                  class="w-full rounded-lg border-gray-300 shadow-sm focus:border-whatsapp-500 focus:ring-whatsapp-500"></textarea>
                        <p class="mt-1 text-xs text-gray-500">Maksimal 5000 karakter</p>
                    </div>

                    <!-- Submit Button -->
                    <div class="flex items-center justify-between">
                        <button type="button" 
                                onclick="fillSampleData()" 
                                class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition duration-150 text-sm font-medium">
                            📝 Isi Contoh
                        </button>
                        <button type="submit" 
                                id="submitBtn"
                                class="px-6 py-3 bg-whatsapp-500 hover:bg-whatsapp-600 text-white font-bold rounded-lg shadow-lg transition duration-150 flex items-center gap-2">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                            </svg>
                            Kirim Pesan
                        </button>
                    </div>
                </form>
            </div>

            <!-- Response Card -->
            <div id="responseCard" class="hidden mt-6 bg-white overflow-hidden shadow-lg sm:rounded-xl">
                <div id="responseHeader" class="p-4"></div>
                <div id="responseBody" class="p-6"></div>
            </div>

            <!-- Recent Messages -->
            <div class="mt-6 bg-white overflow-hidden shadow-lg sm:rounded-xl">
                <div class="bg-gray-100 p-4 border-b border-gray-200">
                    <h3 class="text-lg font-bold text-gray-900">📋 Log Pengiriman Terakhir</h3>
                </div>
                <div id="messageLog" class="p-6">
                    <p class="text-gray-500 text-center py-4">Belum ada pesan yang dikirim</p>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        const messageForm = document.getElementById('messageForm');
        const submitBtn = document.getElementById('submitBtn');
        const responseCard = document.getElementById('responseCard');
        const responseHeader = document.getElementById('responseHeader');
        const responseBody = document.getElementById('responseBody');
        const messageLog = document.getElementById('messageLog');
        
        let sentMessages = [];

        // Load messages from localStorage
        if (localStorage.getItem('sentMessages')) {
            sentMessages = JSON.parse(localStorage.getItem('sentMessages'));
            updateMessageLog();
        }

        function fillSampleData() {
            document.getElementById('phone_number').value = '628979213614';
            document.getElementById('message').value = 'Halo, ini adalah pesan test dari sistem DUKCAPIL.\n\nTerima kasih!';
        }

        messageForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            
            const formData = new FormData(messageForm);
            const data = Object.fromEntries(formData);
            
            // Disable button
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<svg class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Mengirim...';
            
            try {
                const response = await fetch('{{ route('admin.message-test.send') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify(data)
                });
                
                const result = await response.json();
                
                if (result.success) {
                    showSuccess(result);
                    addToLog(data, result);
                    messageForm.reset();
                } else {
                    showError(result);
                }
            } catch (error) {
                showError({ message: 'Terjadi kesalahan', error: error.message });
            } finally {
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" /></svg> Kirim Pesan';
            }
        });

        function showSuccess(result) {
            responseCard.classList.remove('hidden');
            responseHeader.className = 'bg-green-100 border-b border-green-200 p-4';
            responseHeader.innerHTML = '<div class="flex items-center"><svg class="h-6 w-6 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg><h3 class="text-lg font-bold text-green-900">✅ Pesan Berhasil Dikirim!</h3></div>';
            responseBody.innerHTML = `
                <div class="space-y-3">
                    <p class="text-gray-700">${result.message}</p>
                    ${result.data ? `<pre class="bg-gray-50 p-3 rounded text-xs overflow-auto">${JSON.stringify(result.data, null, 2)}</pre>` : ''}
                </div>
            `;
            window.scrollTo({ top: responseCard.offsetTop - 100, behavior: 'smooth' });
        }

        function showError(result) {
            responseCard.classList.remove('hidden');
            responseHeader.className = 'bg-red-100 border-b border-red-200 p-4';
            responseHeader.innerHTML = '<div class="flex items-center"><svg class="h-6 w-6 text-red-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg><h3 class="text-lg font-bold text-red-900">❌ Gagal Mengirim Pesan</h3></div>';
            responseBody.innerHTML = `
                <div class="space-y-3">
                    <p class="text-red-700 font-semibold">${result.message}</p>
                    ${result.error ? `<p class="text-red-600 text-sm">Error: ${result.error}</p>` : ''}
                </div>
            `;
            window.scrollTo({ top: responseCard.offsetTop - 100, behavior: 'smooth' });
        }

        function addToLog(data, result) {
            const logEntry = {
                timestamp: new Date().toLocaleString('id-ID'),
                phone: data.phone_number,
                message: data.message.substring(0, 50) + (data.message.length > 50 ? '...' : ''),
                status: 'success'
            };
            
            sentMessages.unshift(logEntry);
            if (sentMessages.length > 10) sentMessages.pop();
            
            localStorage.setItem('sentMessages', JSON.stringify(sentMessages));
            updateMessageLog();
        }

        function updateMessageLog() {
            if (sentMessages.length === 0) {
                messageLog.innerHTML = '<p class="text-gray-500 text-center py-4">Belum ada pesan yang dikirim</p>';
                return;
            }
            
            messageLog.innerHTML = `
                <div class="space-y-3">
                    ${sentMessages.map(msg => `
                        <div class="flex items-start gap-3 p-3 bg-gray-50 rounded-lg border border-gray-200">
                            <div class="flex-shrink-0 w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                                <svg class="h-5 w-5 text-green-600" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/>
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between mb-1">
                                    <span class="text-sm font-bold text-gray-900">${msg.phone}</span>
                                    <span class="text-xs text-gray-500">${msg.timestamp}</span>
                                </div>
                                <p class="text-sm text-gray-600">${msg.message}</p>
                                <span class="inline-block mt-1 px-2 py-0.5 bg-green-100 text-green-800 text-xs font-medium rounded">✓ Terkirim</span>
                            </div>
                        </div>
                    `).join('')}
                </div>
            `;
        }

        // Clear log button (optional)
        function clearLog() {
            if (confirm('Hapus semua log pengiriman?')) {
                sentMessages = [];
                localStorage.removeItem('sentMessages');
                updateMessageLog();
            }
        }
    </script>
    @endpush
</x-app-layout>
