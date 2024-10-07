<!-- resources/views/components/dashboard/low-stock-products.blade.php -->
<div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
    <h3 class="font-semibold text-lg text-gray-800 dark:text-gray-200" style="border-bottom: 2px solid white;">Low Stock Products</h3>
    <br>

        @foreach ($lowStockProducts as $product)
            <div class="bg-orange-100 border-l-4 border-orange-500 text-orange-700 p-4" role="alert" style="background-color: coral">
                <p class="font-bold"><b>{{$product->name}}</b></p>
                <p>{{ $product->quantity }} units left</p>
            </div>
            <br>
        @endforeach
</div>
