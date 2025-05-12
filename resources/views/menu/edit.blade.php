<x-layouts.app :title="'Edit Menu'">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-semibold text-gray-900">Edit Menu</h1>
    </div>

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 bg-white border-b border-gray-200">
            <form action="{{ route('menu.update', $menu) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="mb-4">
                    <label for="menu_category_id" class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
                    <select 
                        name="menu_category_id" 
                        id="menu_category_id" 
                        required
                        class="w-full px-3 py-2 text-gray-700 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-150"
                    >
                        <option value="">Pilih Kategori</option>
                        @foreach($kategoris as $kategori)
                            <option value="{{ $kategori->id }}" {{ old('menu_category_id', $menu->menu_category_id) == $kategori->id ? 'selected' : '' }}>
                                {{ $kategori->nama }}
                            </option>
                        @endforeach
                    </select>
                    @error('menu_category_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <flux:input
                        name="nama"
                        id="nama"
                        :value="old('nama', $menu->nama)"
                        label="Nama Menu"
                        type="text"
                        required
                        autofocus
                    />
                    @error('nama')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="deskripsi" class="block text-sm font-medium text-gray-700">Deskripsi</label>
                    <textarea name="deskripsi" id="deskripsi" rows="3"
                        class="px-3 py-2 mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('deskripsi', $menu->deskripsi) }}</textarea>
                    @error('deskripsi')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <flux:input
                        name="harga"
                        id="harga"
                        :value="old('harga', $menu->harga)"
                        label="Harga"
                        type="number"
                        required
                    />
                    @error('harga')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="gambar" class="block text-sm font-medium text-gray-700">Gambar</label>
                    @if($menu->gambar)
                        <div class="mb-2">
                            <img src="{{ asset('storage/' . $menu->gambar) }}" alt="Menu Image" class="h-20 w-20 object-cover rounded">
                        </div>
                    @endif
                    <input type="file" name="gambar" id="gambar"
                        class="mt-1 block w-full text-sm text-gray-500
                            file:mr-4 file:py-2 file:px-4
                            file:rounded-md file:border-0
                            file:text-sm file:font-semibold
                            file:bg-blue-50 file:text-blue-700
                            hover:file:bg-blue-100">
                    @error('gambar')
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
                            {{ old('is_active', $menu->is_active) ? 'checked' : '' }}
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
                    <a href="{{ route('menu.index') }}" class="px-4 py-2 border-1 border-gray-600 text-gray-600 rounded-md hover:bg-gray-700 hover:text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>