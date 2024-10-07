<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

            @if(auth()->user()->admin_level >= 1)
                <x-dashboard.low-stock-products :lowStockProducts="$lowStockProducts" />
                <x-dashboard.today-income :todayIncome="$todayIncome" />
                <x-dashboard.top-products-today :topProductsToday="$topProductsToday" />
                <x-dashboard.top-categories :topCategories="$topCategories" />
                <x-dashboard.top-sales :topSales="$topSales" />
                <x-dashboard.top-customer :topCustomer="$topCustomer" />
            @endif
        </div>

        <!-- Nova linha para o Income Chart -->
        @if(auth()->user()->admin_level >= 1)
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 grid grid-cols-1 gap-6 mt-6">
                <x-dashboard.income :completeIncomeData="$completeIncomeData"
                                    :completeExpenseData="$completeExpenseData"
                                    :monthlyIncomeData="$monthlyIncomeData"
                                    :monthlyExpenseData="$monthlyExpenseData"/>
            </div>
        @endif
    </div>
</x-app-layout>
