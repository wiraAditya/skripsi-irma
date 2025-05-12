<x-layouts.app :title="'Create User'">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-semibold text-gray-900">Create User</h1>
    </div>

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 bg-white border-b border-gray-200">
            <form action="{{ route('users.store') }}" method="POST">
                @csrf
                
                <div class="mb-4">
                <flux:input
                        name="name"
                        id="name"
                        :value="old('name')"
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
                        :value="old('email')"
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
                        :label="'Password'"
                        type="password"
                        required
                        autocomplete="new-password"
                    />
                    @error('password')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="flex justify-end gap-1">
                    <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Simpan
                    </button>
                    <a href="{{ route('users.index') }}" class="px-4 py-2 border-1 border-gray-600  text-gray-600 rounded-md hover:bg-gray-700 hover:text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>