<!-- resources/views/components/dashboard/top-sales.blade.php -->
<div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
    <h3 class="font-semibold text-lg text-gray-800 dark:text-gray-200">Top Sales</h3>
    <ul class="list-disc list-inside">
        @foreach ($topSales as $sale)
            <li>{{ $sale->product_name }} - ${{ number_format($sale->amount, 2) }}</li>
        @endforeach
    </ul>
</div>
