<x-layouts.app :title="'Create Meja'">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-semibold text-gray-900">Create Meja</h1>
    </div>

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 bg-white border-b border-gray-200">
            <form action="{{ route('meja.store') }}" method="POST">
                @csrf
                
                <div class="mb-4">
                    <flux:input
                        name="nama"
                        id="nama"
                        :value="old('nama')"
                        label="Nama Meja"
                        type="text"
                        required
                        autofocus
                    />
                    @error('nama')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <div class="flex items-center">
                        <input 
                            type="checkbox" 
                            name="is_active" 
                            id="is_active" 
                            value="1" 
                            {{ old('is_active', true) ? 'checked' : '' }}
                            class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                        >
                        <label for="is_active" class="ml-2 block text-sm text-gray-900">
                            Aktif
                        </label>
                    </div>
                    @error('is_active')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-end gap-1">
                    <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Simpan
                    </button>
                    <a href="{{ route('meja.index') }}" class="px-4 py-2 border-1 border-gray-600  text-gray-600 rounded-md hover:bg-gray-700 hover:text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>