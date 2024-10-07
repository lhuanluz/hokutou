<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Expenses') }}
            </h2>
            <a href="{{ route('expenses.create') }}"
               class="inline-block bg-gray-100 dark:bg-gray-300 text-gray-800 dark:text-gray-900 font-bold py-2 px-4 rounded shadow-md transition duration-300 ease-in-out hover:bg-gray-200 dark:hover:bg-gray-400">
                {{ __('Add expense') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                    <table class="min-w-full mt-4 table-auto">
                        <thead class="bg-gray-700 dark:bg-gray-900">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-white dark:text-white uppercase tracking-wider">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-white dark:text-white uppercase tracking-wider">Description</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-white dark:text-white uppercase tracking-wider">Amount</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-white dark:text-white uppercase tracking-wider">Payment Method</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-white dark:text-white uppercase tracking-wider">Categories</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-white dark:text-white uppercase tracking-wider">Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($expenses as $expense)
                            <tr class="bg-gray-800 dark:bg-gray-800 border-b border-gray-700">
                                <td class="px-6 py-4 text-white">{{ $expense->expense_date }}</td>
                                <td class="px-6 py-4 text-white">{{ $expense->description }}</td>
                                <td class="px-6 py-4 text-white">{{ $expense->amount }}</td>
                                <td class="px-6 py-4 text-white">{{ $expense->payment_method }}</td>
                                <td class="px-6 py-4 text-white">
                                    @foreach($expense->categories as $category)
                                        <span class="bg-gray-600 dark:bg-gray-700 text-gray-200 dark:text-gray-300 text-xs font-semibold mr-2 px-2.5 py-0.5 rounded">{{ $category->name }}</span>
                                    @endforeach
                                </td>
                                <td class="px-6 py-4 text-right text-sm font-medium">
                                    <a href="{{ route('expenses.edit', $expense) }}" class="text-indigo-500 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-500">Edit</a>
                                    <form action="{{ route('expenses.destroy', $expense) }}" method="POST" class="inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-500 dark:text-red-400 hover:text-red-900 dark:hover:text-red-500 ml-4">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                    <!-- Pagination -->
                    <div class="mt-4">
                        {{ $expenses->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
