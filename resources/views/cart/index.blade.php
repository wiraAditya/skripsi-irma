<x-layouts.app.clean :title="'Cart'">
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
            
            <!-- Navigation back -->
            <div class="bg-white/90 backdrop-blur-md px-4 py-3 border-b border-neutral-200 flex justify-between items-center">
                <a href="{{ url()->previous() }}" class="flex items-center text-teal-800 hover:text-teal-600 transition-colors duration-300">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    Back to Menu
                </a>
                <h2 class="text-xl font-medium text-teal-800">Your Order</h2>
                <div class="w-24"></div> <!-- Spacer for flex alignment -->
            </div>
        </header>

        <!-- Cart Content -->
        <div class="container mx-auto px-4 py-8 pb-32">
            @if(count($cart ?? []) > 0)
                <div class="bg-white rounded-xl shadow-md overflow-hidden mb-6">
                    <div class="divide-y divide-neutral-100">
                        @php $total = 0; @endphp
                        @foreach($cart as $id => $item)
                            @php $total += $item['subtotal']; @endphp
                            <div class="p-4 flex flex-col sm:flex-row gap-4 cart-item" data-id="{{ $id }}">
                                <!-- Item Image -->
                                <div class="sm:w-24 h-24">
                                    <img src="{{ $item['image'] }}" alt="{{ $item['name'] }}" class="w-full h-full object-cover rounded-lg">
                                </div>
                                
                                <!-- Item Details -->
                                <div class="flex-grow">
                                    <h3 class="font-medium text-lg text-teal-900">{{ $item['name'] }}</h3>
                                    <p class="text-sm text-neutral-500 mb-1">{{ 'Rp ' . number_format($item['price'], 0, ',', '.') }}</p>
                                    <div class="flex items-center text-sm text-neutral-600 mb-2">
                                        <span class="mr-2">Quantity: {{ $item['quantity'] }}</span>
                                        @if(!empty($item['catatan']))
                                            <span class="px-2 py-0.5 bg-neutral-100 rounded-full text-xs">Note: {{ $item['catatan'] }}</span>