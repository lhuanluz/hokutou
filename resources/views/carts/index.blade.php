<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Carts') }}
            </h2>
            <a href="{{ route('carts.addProductForm') }}"
               class="inline-block bg-gray-100 dark:bg-gray-300 text-gray-800 dark:text-gray-900 font-bold py-2 px-4 rounded shadow-md transition duration-300 ease-in-out hover:bg-gray-200 dark:hover:bg-gray-400">
                {{ __('Add Product') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                    @if ($carts->isEmpty())
                        <p class="text-gray-600 dark:text-gray-300">{{ __('No carts available.') }}</p>
                    @else
                        <div class="overflow-x-auto">
                            <table id="carts-table" class="min-w-full table-auto">
                                <thead class="bg-gray-700 dark:bg-gray-900">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 dark:text-gray-400 uppercase tracking-wider">User</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 dark:text-gray-400 uppercase tracking-wider">Products</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 dark:text-gray-400 uppercase tracking-wider">Total Value</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 dark:text-gray-400 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-300 dark:text-gray-400 uppercase tracking-wider">Actions</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($carts as $cart)
                                    <tr class="bg-gray-800 dark:bg-gray-800 border-b border-gray-700">
                                        <td class="px-6 py-4 whitespace-nowrap text-gray-200 dark:text-gray-300">{{ $cart->user->name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-gray-200 dark:text-gray-300">{!! $cart->formatted_products !!}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-gray-200 dark:text-gray-300">{{ $cart->total_value }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-gray-200 dark:text-gray-300">{{ ucfirst($cart->status) }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            @if ($cart->status !== 'done')
                                                <a href="{{ route('carts.edit', $cart) }}" class="text-indigo-500 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-500">Edit</a>
                                                <form action="{{ route('carts.destroy', $cart) }}" method="POST" class="inline-block">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-500 dark:text-red-400 hover:text-red-900 dark:hover:text-red-500 ml-4">Delete</button>
                                                </form>
                                            @else
                                                <span class="text-gray-500 dark:text-gray-400">No Actions Available</span>
                                            @endif
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
</x-app-layout>
