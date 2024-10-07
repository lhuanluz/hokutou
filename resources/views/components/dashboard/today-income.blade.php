<!-- resources/views/components/dashboard/today-income.blade.php -->
<div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
    <h3 class="font-semibold text-lg text-gray-800 dark:text-gray-200" style="border-bottom: 2px solid white;">Today's Income</h3>
    <br>
    <div class="bg-orange-100 border-l-4 border-orange-500 text-orange-700 p-4" role="alert" style="background-color: springgreen">
        <p class="font-bold" style="font-size: 3.2rem">R$ {{number_format($todayIncome, 2)}}</p>
    </div>
</div>
