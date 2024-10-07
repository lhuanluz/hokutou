<div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 w-full">
    <h3 class="font-semibold text-lg text-gray-800 dark:text-gray-200 mb-4" style="border-bottom: 2px solid white;">
        Monthly Income & Expenses Forecast
    </h3>

    <!-- Botões para alternar entre visualizações -->
    <div class="flex justify-end mb-4">
        <button id="dailyViewBtn" class="bg-blue text-white px-4 py-2 rounded mr-2">Daily View</button>
        <button id="monthlyViewBtn" class="bg-blue text-white px-4 py-2 rounded">Monthly View</button>
    </div>

    <canvas id="incomeChart" width="400" height="200"></canvas>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        var dailyIncomeData = {!! json_encode(array_values($completeIncomeData)) !!};
        var dailyExpenseData = {!! json_encode(array_values($completeExpenseData)) !!};
        var dailyLabels = {!! json_encode(array_keys($completeIncomeData)) !!};

        var monthlyIncomeData = {!! json_encode(array_values($monthlyIncomeData)) !!}; // Considerando que você calculou isso no controller
        var monthlyExpenseData = {!! json_encode(array_values($monthlyExpenseData)) !!}; // Considerando que você calculou isso no controller
        var monthlyLabels = {!! json_encode(array_keys($monthlyIncomeData)) !!}; // Considerando que você calculou isso no controller

        var ctx = document.getElementById('incomeChart').getContext('2d');
        var incomeChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: dailyLabels,
                datasets: [
                    {
                        label: 'Income',
                        data: dailyIncomeData,
                        backgroundColor: 'springgreen',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1
                    },
                    {
                        label: 'Expenses',
                        data: dailyExpenseData,
                        backgroundColor: 'tomato',
                        borderColor: 'rgba(255, 99, 132, 1)',
                        borderWidth: 1
                    }
                ]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // Função para atualizar o gráfico
        function updateChart(labels, incomeData, expenseData) {
            incomeChart.data.labels = labels;
            incomeChart.data.datasets[0].data = incomeData;
            incomeChart.data.datasets[1].data = expenseData;
            incomeChart.update();
        }

        // Event Listeners para os botões
        document.getElementById('dailyViewBtn').addEventListener('click', function() {
            updateChart(dailyLabels, dailyIncomeData, dailyExpenseData);
        });

        document.getElementById('monthlyViewBtn').addEventListener('click', function() {
            updateChart(monthlyLabels, monthlyIncomeData, monthlyExpenseData);
        });
    </script>
</div>
