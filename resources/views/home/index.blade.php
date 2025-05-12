<x-layouts.app.clean :title="'Home'">
    <div class="min-h-screen bg-gradient-to-b from-neutral-50 to-neutral-100">
        <!-- Header - Fixed at top -->
        <header class="sticky top-0 z-20 shadow-lg">
            <!-- Restaurant Name Banner -->
            <div class="bg-gradient-to-r from-emerald-900 to-teal-800 text-white py-5 px-6 text-center">
                <h1 class="text-3xl font-serif font-light tracking-widest mb-1 uppercase">Amedian Dapur</h1>
                <p class="text-lg font-light italic tracking-wide" data-table-name="{{ $table?->name }}">
                    {{ $table?->name ? 'Table: ' . $table->name : 'Fine Indonesian Cuisine' }}
                </p>
            </div>
            
            <!-- Category Navigation -->
            <div class="bg-white/90 backdrop-blur-md px-4 py-3 overflow-x-auto border-b border-neutral-200">
                  <div class="flex space-x-3 whitespace-nowrap min-w-max">
                    <a href="{{ request()->url() }}?mejaId={{ $table->id ?? '' }}" 
                       class="px-5 py-2 rounded-full {{ $currentCategoryId == 0 ? 'bg-teal-700 text-white' : 'bg-teal-50 text-teal-900' }} hover:bg-teal-600 hover:text-white transition-all duration-300 shadow-sm font-medium tracking-wide">
                        All Menu
                    </a>
                    
                    @foreach ($menuCategories as $category)
                        <a href="{{ request()->url() }}?category={{ $category->id }}&mejaId={{ $table->id ?? '' }}" 
                           class="px-5 py-2 rounded-full {{ $currentCategoryId == $category->id ? 'bg-teal-700 text-white' : 'bg-teal-50 text-teal-900' }} hover:bg-teal-600 hover:text-white transition-all duration-300 shadow-sm font-medium tracking-wide">
                            {{ $category->nama }}
                        </a>
                    @endforeach
                </div>
            </div>
        </header>

        <!-- Menu Items Grid -->
        <div class="container mx-auto px-4 py-8 pb-24">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @foreach ($menus as $menu)
                    <div class="bg-white rounded-xl overflow-hidden shadow-md hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 group menu-item" >
                        <div class="relative h-52 overflow-hidden">
                            <img 
                                src="{{ asset('/storage/' . $menu->gambar) }}" 
                                alt="{{ $menu->nama }}" 
                                class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110"
                                loading="lazy"
                            >
                            <div class="absolute inset-0 bg-gradient-to-t from-black/50 via-black/10 to-transparent opacity-60"></div>
                            <div class="absolute top-3 right-3 bg-teal-700/90 text-white px-3 py-1 rounded-full text-sm font-medium shadow-md">
                                {{ 'Rp ' . number_format($menu->harga, 0, ',', '.') }}
                            </div>
                        </div>
                        <div class="p-5">
                            <h3 class="font-medium text-xl text-teal-900 mb-2 group-hover:text-teal-700 transition-colors duration-300">{{ $menu->nama }}</h3>
                            <div class="min-h-[60px] mb-4">
                                <p class="text-neutral-600 text-sm">{{ $menu->deskripsi }}</p>
                            </div>
                            <button 
                                data-id="{{$menu->id}}"
                                data-name="{{$menu->nama}}"
                                data-details="{{$menu->deskripsi}}"
                                data-price="{{$menu->harga}}"
                                data-icon="fa-utensils"
                                data-image="{{asset('/storage/' . $menu->gambar)}}"
                                aria-label="Tambahkan {{$menu->nama}} ke pesanan"
                                class="add-to-cart-btn add-to-order-btn w-full py-2.5 bg-teal-600 hover:bg-teal-800 text-white rounded-lg transition-colors duration-300 flex items-center justify-center shadow-sm group-hover:shadow-md"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                                Add to Order
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Cart Button - Fixed at bottom right -->
        <div class="fixed bottom-6 right-6 z-10">
            <a href="{{ route('cart.index', ['mejaId' => $table->id ?? '']) }}" class="flex items-center justify-center w-16 h-16 bg-teal-700 text-white rounded-full hover:bg-teal-800 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:scale-105 relative">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                <div class="absolute -top-2 -right-2 bg-red-500 text-white w-6 h-6 rounded-full flex items-center justify-center text-sm font-bold shadow-sm" id="cart-count">{{ session('cart') ? count(session('cart')) : 0 }}</div>
            </a>
        </div>

        <!-- Include the modal component -->
        <x-order-modal />
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Open modal when "Tambahkan" is clicked
            document.querySelectorAll('.add-to-cart-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const modal = document.getElementById('addToCartModal');
                    
                    console.log('desc', this.dataset)
                    // Set form values
                    document.getElementById('modalMenuId').value = this.dataset.id;
                    document.getElementById('modalMenuName').textContent = this.dataset.name;
                    document.getElementById('modalMenuDetails').textContent = this.dataset.details;

                    document.getElementById('modalMenuNameInput').value = this.dataset.name;
                    document.getElementById('modalMenuPrice').value = this.dataset.price;
                    document.getElementById('modalMenuImageInput').value = this.dataset.image;
                    
                    // Set image display
                    const imgElement = document.getElementById('modalMenuImage');
                    const imgContainer = document.getElementById('modalMenuImage').parentElement;
                    const iconElement = imgContainer.querySelector('i');
                    
                    if (iconElement) {
                        iconElement.remove();
                    }

                    if (this.dataset.image && this.dataset.image !== '-') {
                        imgElement.src = this.dataset.image;
                        imgElement.alt = this.dataset.name;
                        imgElement.classList.remove('hidden');
                    } else {
                        imgElement.classList.add('hidden');
                        imgContainer.insertAdjacentHTML('beforeend',
                            `<i class="fas ${this.dataset.icon} text-2xl text-gray-400"></i>`);
                    }
                    
                    // Reset quantity and notes
                    document.getElementById('quantity').value = 1;
                    document.getElementById('notes').value = '';
                    
                    modal.classList.remove('hidden');
                });
            });
        });
    </script>

</x-layouts.app.clean>