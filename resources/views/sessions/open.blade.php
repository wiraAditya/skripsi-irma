<x-layouts.app :title="'Buka Sesi Kasir'">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-semibold text-gray-900">Buka Sesi Kasir</h1>
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

            <form action="{{ route('admin.sessions.storeOpen') }}" method="POST">
                @csrf
                
                <div class="mb-4">
                    <flux:input
                        name="starting_cash"
                        id="starting_cash"
                        :value="old('starting_cash')"
                        label="Saldo Awal"
                        type="number"
                        required
                        placeholder="Masukkan saldo awal kas"
                    />
                    @error('starting_cash')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-end gap-1">
                    <a href="{{ route('order.index') }}" class="px-4 py-2 border-1 border-gray-600 text-gray-600 rounded-md hover:bg-gray-700 hover:text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                        Batal
                    </a>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Buka Sesi
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>