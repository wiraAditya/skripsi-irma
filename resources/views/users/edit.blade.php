<x-layouts.app :title="'Edit User'">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-semibold text-gray-900">Edit User</h1>
    </div>

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 bg-white border-b border-gray-200">
            <form action="{{ route('users.update', $user) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="mb-4">
                    <flux:input
                        name="name"
                        id="name"
                        :value="old('name', $user->name)"
                        label="Nama"
                        type="text"
                        required
                        autofocus
                    />
                    @error('name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <flux:input
                        name="email"
                        id="email"
                        :value="old('email', $user->email)"
                        label="Email"
                        type="email"
                        required
                        autocomplete="email"
                        placeholder="email@example.com"
                    />
                    @error('email')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <flux:input
                        name="password"
                        id="password"
                        label="Password"
                        type="password"
                        autocomplete="new-password"
                    />
                    <span class="text-xs text-gray-500 italic">Biarkan kosong jika tidak diubah</span>
                    @error('password')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-4">
                    <label for="role" class="block text-sm font-medium text-gray-700 mb-1">Role</label>
                    <select name="role" id="role" class="menu-select border border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm px-3 py-2" required>
                        <option value="">Pilih Role</option>
                        <option value="role_kasir" {{ old('role', $user->role) == 'role_kasir' ? 'selected' : '' }}>Kasir</option>
                        <option value="role_dapur" {{ old('role', $user->role) == 'role_dapur' ? 'selected' : '' }}>Dapur</option>
                    </select>
                    @error('role')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="flex justify-end gap-1">
                    <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Simpan
                    </button>
                    <a href="{{ route('users.index') }}" class="px-4 py-2 border-1 border-gray-600 text-gray-600 rounded-md hover:bg-gray-700 hover:text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>