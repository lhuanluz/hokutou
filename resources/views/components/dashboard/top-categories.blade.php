<!-- resources/views/components/dashboard/top-categories.blade.php -->
<div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
    <h3 class="font-semibold text-lg text-gray-800 dark:text-gray-200">Top Earning Categories</h3>
    <ul class="list-disc list-inside">
        @foreach ($topCategories as $category)
            <li>{{ $category['name'] }} - ${{ number_format($category['total_earned'] , 2) }} earned</li>
        @endforeach
    </ul>
</div>
