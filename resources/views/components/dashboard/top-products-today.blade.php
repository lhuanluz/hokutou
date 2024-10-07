<!-- resources/views/components/dashboard/top-products-today.blade.php -->
<div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
    <h3 class="font-semibold text-lg text-gray-800 dark:text-gray-200" style="border-bottom: 2px solid white">Top Sold Products Today</h3>
    <br>
    <table class="list-disc list-inside" style="color:white">
        <thead class="bg-gray-700 dark:bg-gray-900">
        <tr>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 dark:text-gray-400 uppercase tracking-wider">Product</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 dark:text-gray-400 uppercase tracking-wider">Sold Quantity</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 dark:text-gray-400 uppercase tracking-wider">Total Earned</th>
        </tr>
        </thead>
        <tbody>
        @foreach($topProductsToday as $product)
            <tr class="bg-gray-800 dark:bg-gray-800 border-b border-gray-700">
                <td class="px-6 py-4 text-gray-200">{{ $product['name'] }}</td>
                <td class="px-6 py-4 text-gray-200">{{ $product['sold_quantity'] }}</td>
                <td class="px-6 py-4 text-gray-200">R$ {{ $product['total_earned'] }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
