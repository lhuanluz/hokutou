<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Financial Transactions') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                    <table id="transactions-table" class="min-w-full table-auto">
                        <thead class="bg-gray-700 dark:bg-gray-900">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 dark:text-gray-400 uppercase tracking-wider">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 dark:text-gray-400 uppercase tracking-wider">Amount</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 dark:text-gray-400 uppercase tracking-wider">Type</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 dark:text-gray-400 uppercase tracking-wider">Payment Method</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 dark:text-gray-400 uppercase tracking-wider">Installments</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 dark:text-gray-400 uppercase tracking-wider">Payment Fee</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 dark:text-gray-400 uppercase tracking-wider">User</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 dark:text-gray-400 uppercase tracking-wider">Cart Summary</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 dark:text-gray-400 uppercase tracking-wider">Observations</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($transactions as $transaction)
                            <tr class="bg-gray-800 dark:bg-gray-800 border-b border-gray-700">
                                <td class="px-6 py-4 text-gray-200">{{ $transaction->transaction_date }}</td>
                                <td class="px-6 py-4 text-gray-200">{{ $transaction->amount }}</td>
                                <td class="px-6 py-4 text-gray-200">{{ ucfirst($transaction->transaction_type) }}</td>
                                <td class="px-6 py-4 text-gray-200">{{ $transaction->payment_method }}</td>
                                <td class="px-6 py-4 text-gray-200">{{ $transaction->installments }}</td>
                                <td class="px-6 py-4 text-gray-200">{{ $transaction->payment_fee }}%</td>

                                @if ($transaction->cart)
                                    @php
                                        $user = $transaction->cart->user;
                                    @endphp
                                    <td class="px-6 py-4 text-gray-200">{{ $user->name }} - {{ $user->email }}</td>
                                    <td class="px-6 py-4 text-gray-200">{!! $transaction->formatted_products !!}</td>
                                @else
                                    <td class="px-6 py-4 text-gray-200"></td>
                                    <td class="px-6 py-4 text-gray-200"></td>
                                @endif

                                <td class="px-6 py-4 text-gray-200">{{ $transaction->observations }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                    <!-- Paginação -->
                    <div class="mt-4">
                        {{ $transactions->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Include jQuery and DataTables -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">

    <!-- Custom styles for DataTables Dark Mode -->
    <style>
        /* DataTable Custom Dark Mode */
        #transactions-table_wrapper {
            color: #e5e7eb;
        }
        #transactions-table thead {
            background-color: #1f2937; /* Darker gray for table headers */
        }
        #transactions-table tbody tr {
            background-color: #374151; /* Dark gray for table rows */
        }
        #transactions-table tbody tr:nth-child(even) {
            background-color: #4b5563; /* Alternate row color */
        }
        #transactions-table tbody tr:hover {
            background-color: #6b7280; /* Hover effect */
        }
        #transactions-table_wrapper .dataTables_paginate .paginate_button {
            background-color: #374151;
            color: #e5e7eb !important;
        }
        #transactions-table_wrapper .dataTables_paginate .paginate_button:hover {
            background-color: #4b5563;
            color: #fff !important;
        }
        #transactions-table_wrapper .dataTables_filter input {
            background-color: #374151;
            border: none;
            color: #e5e7eb;
        }
        #transactions-table_wrapper .dataTables_length select {
            background-color: #374151;
            border: none;
            color: #e5e7eb;
        }
        /* Align numeric columns to the right */
        #transactions-table th:nth-child(2),
        #transactions-table th:nth-child(3),
        #transactions-table td:nth-child(2),
        #transactions-table td:nth-child(3),
        #transactions-table th:nth-child(4),
        #transactions-table td:nth-child(4),
        #transactions-table th:nth-child(5),
        #transactions-table td:nth-child(5),
        #transactions-table th:nth-child(6),
        #transactions-table td:nth-child(6),
        #transactions-table th:nth-child(7),
        #transactions-table td:nth-child(7)
        {
            text-align: right;
        }

    </style>
    <!-- Inicializar DataTables -->
    <script>
        $(document).ready(function() {
            $('#transactions-table').DataTable();
        });
    </script>
</x-app-layout>
