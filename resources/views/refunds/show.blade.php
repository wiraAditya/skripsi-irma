<x-layouts.app :title="'Refund Details'">
    <div class="max-w-4xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-semibold text-gray-900">Refund Details #{{ $refund->id }}</h1>
            <a href="{{ route('refunds.index') }}" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                Back to List
            </a>
        </div>

        <div class="bg-white shadow-sm sm:rounded-lg p-6 space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <h3 class="text-lg font-medium text-gray-900">Order Information</h3>
                    <div class="mt-2 space-y-1 text-sm text-gray-700">
                        <p><span class="font-medium">Order ID:</span> #{{ $refund->order_id }}</p>
                        <p><span class="font-medium">Customer:</span> {{ $refund->order->nama ?? 'N/A' }}</p>
                        <p><span class="font-medium">Date:</span> {{ $refund->created_at->format('d M Y H:i') }}</p>
                    </div>
                </div>
                <div>
                    <h3 class="text-lg font-medium text-gray-900">Refund Information</h3>
                    <div class="mt-2 space-y-1 text-sm text-gray-700">
                        <p><span class="font-medium">Amount:</span> Rp {{ number_format($refund->refund_amount, 0, ',', '.') }}</p>
                        <p><span class="font-medium">Method:</span> <span class="capitalize">{{ $refund->refund_method }}</span></p>
                        <p><span class="font-medium">Status:</span> <span class="capitalize">{{ $refund->status }}</span></p>
                        <p><span class="font-medium">Processed By:</span> {{ $refund->processedBy->name ?? 'System' }}</p>
                    </div>
                </div>
            </div>

            <div>
                <h3 class="text-lg font-medium text-gray-900">Reason</h3>
                <p class="mt-1 text-sm text-gray-700">{{ $refund->reason }}</p>
            </div>

            <div>
                <h3 class="text-lg font-medium text-gray-900">Refunded Items</h3>
                <div class="mt-4 overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Item
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Price
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Qty
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Subtotal
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Notes
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($refund->items as $item)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $item->orderDetail->menu->nama ?? 'Menu Item' }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        Rp {{ number_format($item->orderDetail->harga, 0, ',', '.') }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        {{ $item->quantity }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">
                                        Rp {{ number_format($item->refund_amount, 0, ',', '.') }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        {{ $item->reason }}
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>