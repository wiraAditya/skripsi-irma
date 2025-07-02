<x-layouts.app :title="'Tutup Sesi Kasir'">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-semibold text-gray-900">Tutup Sesi Kasir</h1>
    </div>

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 bg-white border-b border-gray-200">
            @if ($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="mb-6">
                <p class="font-medium">Waktu Buka: {{ $session->start_time->format('d/m/Y H:i') }}</p>
                <p class="font-medium">Saldo Awal: Rp {{ number_format($session->starting_cash, 0, ',', '.') }}</p>
            </div>

            <form action="{{ route('admin.sessions.storeClose', $session->id) }}" method="POST">
                @csrf
                
                <div class="mb-4">
                    <flux:input
                        name="ending_cash"
                        id="ending_cash"
                        :value="old('ending_cash')"
                        label="Saldo Akhir"
                        type="number"
                        required
                        placeholder="Masukkan saldo akhir kas"
                    />
                    @error('ending_cash')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-end gap-1">
                    <a href="{{ route('order.index') }}" class="px-4 py-2 border-1 border-gray-600 text-gray-600 rounded-md hover:bg-gray-700 hover:text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                        Batal
                    </a>
                    <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                        Tutup Sesi
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>