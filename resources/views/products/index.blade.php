<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Products') }}
            </h2>
            <a href="{{ route('products.create') }}"
               class="inline-block bg-gray-100 dark:bg-gray-300 text-gray-800 dark:text-gray-900 font-bold py-2 px-4 rounded shadow-md transition duration-300 ease-in-out hover:bg-gray-200 dark:hover:bg-gray-400">
                {{ __('Create New Product') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                    @if ($products->isEmpty())
                        <p class="text-gray-600 dark:text-gray-300">{{ __('No products available.') }}</p>
                    @else
                        <div class="overflow-x-auto">
                            <table id="products-table" class="min-w-full table-auto">
                                <thead class="bg-gray-700 dark:bg-gray-900">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 dark:text-gray-400 uppercase tracking-wider">Name</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-300 dark:text-gray-400 uppercase tracking-wider">Quantity</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-300 dark:text-gray-400 uppercase tracking-wider">Price</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 dark:text-gray-400 uppercase tracking-wider">Categories</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-300 dark:text-gray-400 uppercase tracking-wider">Actions</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($products as $product)
                                    <tr class="bg-gray-800 dark:bg-gray-800 border-b border-gray-700">
                                        <td class="px-6 py-4 whitespace-nowrap text-gray-200 dark:text-gray-300">{{ $product->name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-gray-200 dark:text-gray-300">{{ $product->quantity }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-gray-200 dark:text-gray-300">{{ $product->sale_value }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-gray-200 dark:text-gray-300">
                                            @foreach ($product->categories as $category)
                                                <span class="inline-block bg-gray-600 dark:bg-gray-700 text-gray-200 dark:text-gray-300 text-xs font-semibold mr-2 px-2.5 py-0.5 rounded">{{ $category->name }}</span>
                                            @endforeach
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <a href="{{ route('products.edit', $product) }}" class="text-indigo-500 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-500">Edit</a>
                                            <form action="{{ route('products.destroy', $product) }}" method="POST" class="inline-block">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-500 dark:text-red-400 hover:text-red-900 dark:hover:text-red-500 ml-4">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
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
        #products-table_wrapper {
            color: #e5e7eb;
        }
        #products-table thead {
            background-color: #1f2937; /* Darker gray for table headers */
        }
        #products-table tbody tr {
            background-color: #374151; /* Dark gray for table rows */
        }
        #products-table tbody tr:nth-child(even) {
            background-color: #4b5563; /* Alternate row color */
        }
        #products-table tbody tr:hover {
            background-color: #6b7280; /* Hover effect */
        }
        #products-table_wrapper .dataTables_paginate .paginate_button {
            background-color: #374151;
            color: #e5e7eb !important;
        }
        #products-table_wrapper .dataTables_paginate .paginate_button:hover {
            background-color: #4b5563;
            color: #fff !important;
        }
        #products-table_wrapper .dataTables_filter input {
            background-color: #374151;
            border: none;
            color: #e5e7eb;
        }
        #products-table_wrapper .dataTables_length select {
            background-color: #374151;
            border: none;
            color: #e5e7eb;
        }

        /* Align numeric columns to the right */
        #products-table th:nth-child(2),
        #products-table th:nth-child(3),
        #products-table td:nth-child(2),
        #products-table td:nth-child(3),
        #products-table th:nth-child(4),
        #products-table td:nth-child(4),
        #products-table th:nth-child(5),
        #products-table td:nth-child(5)
        {
            text-align: right;
        }
    </style>

    <!-- Initialize DataTables -->
    <script>
        $(document).ready(function() {
            $('#products-table').DataTable({
                "paging": true,
                "searching": true,
                "info": true,
                "responsive": true,
                "order": [[ 0, "asc" ]],
                "language": {
                    "search": "Search:",
                    "lengthMenu": "Show _MENU_ entries",
                    "info": "Showing _START_ to _END_ of _TOTAL_ entries",
                    "paginate": {
                        "first": "First",
                        "last": "Last",
                        "next": "Next",
                        "previous": "Previous"
                    }
                }
            });
        });
    </script>
</x-app-layout>
