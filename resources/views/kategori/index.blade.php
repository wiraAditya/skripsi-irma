<x-layouts.app :title="'Kategori'">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-semibold text-gray-900">Kategori</h1>
        <a href="{{ route('kategori.create') }}" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
            Tambah
        </a>
    </div>
    
    @if(session('success'))
        <x-alert type="success" :message="session('success')" />
    @endif
    
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 bg-white border-b border-gray-200">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                No
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Kategori
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($kategoris as $index => $kategori)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="ml-4">
                                            <div class="text-sm text-gray-900">
                                              {{ ($kategoris->currentPage() - 1) * $kategoris->perPage() + $loop->iteration }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="ml-4">
                                            <div class="text-sm text-gray-900">
                                                {{ $kategori->nama }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $kategori->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $kategori->is_active ? 'Aktif' : 'Tidak Aktif' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-2">
                                        <a href="{{ route('kategori.edit', $kategori) }}" class="bg-yellow-500 hover:bg-yellow-600 text-black rounded-sm px-2.5 py-1">Edit</a>
                                        <button
                                            class="bg-red-700 hover:bg-red-900 text-white rounded-sm px-2.5 py-1"
                                            type="button"
                                            onclick="showModal('deleteModal', '{{ route('kategori.destroy', $kategori) }}')"
                                        >
                                            Delete
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                    No kategori found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                {{ $kategoris->links() }}
            </div>
        </div>
    </div>
    
    <x-delete-modal
        modalId="deleteModal"
        modalTitle="Hapus Kategori"
        message="Apakah Anda yakin ingin menghapus kategori ini? Data yang sudah dihapus tidak dapat dikembalikan."
        formId="deleteForm"
    >
    </x-delete-modal>
</x-layouts.app>