<!-- resources/views/components/dashboard/top-customer.blade.php -->
<div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
    <h3 class="font-semibold text-lg text-gray-800 dark:text-gray-200">Top Customer</h3>
    <p>{{ $topCustomer->name }} - ${{ number_format($topCustomer->total_spent, 2) }} spent</p>
</div>
