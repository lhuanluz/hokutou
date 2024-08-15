<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Cart') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">

                    <!-- Exibição dos Produtos em Formato de Tabela -->
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200">{{ __('Products in Cart') }}</h3>
                    <table class="min-w-full mt-4">
                        <thead>
                        <tr>
                            <th class="text-left text-gray-400">{{ __('Product Name') }}</th>
                            <th class="text-left text-gray-400">{{ __('Quantity') }}</th>
                            <th class="text-left text-gray-400">{{ __('Unit Price') }}</th>
                            <th class="text-left text-gray-400">{{ __('Total Price') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($cart->products as $product)
                            @php
                                $productModel = \App\Models\Product::find($product['id']);
                                $totalPrice = $product['quantity'] * $product['price'];
                            @endphp
                            <tr>
                                <td class="text-gray-200">{{ $productModel->name }}</td>
                                <td class="text-gray-200">{{ $product['quantity'] }}</td>
                                <td class="text-gray-200">{{ $product['price'] }}</td>
                                <td class="text-gray-200">{{ $totalPrice }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                    <!-- Exibição do Valor Total do Carrinho -->
                    <div class="mt-4">
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200">
                            {{ __('Total Cart Value: ') }}{{ $cart->total_value }}
                        </h3>
                    </div>

                    <!-- Campo para Valor Pago -->
                    <form method="POST" action="{{ route('carts.update', $cart) }}">
                        @csrf
                        @method('PATCH')

                        <div class="mt-4">
                            <x-input-label for="amount_paid" :value="__('Amount Paid')" />
                            <x-text-input id="amount_paid" name="amount_paid" type="number" step="0.01" class="mt-1 block w-full dark:bg-gray-700 dark:text-gray-200" required />
                            <x-input-error :messages="$errors->get('amount_paid')" class="mt-2" />
                        </div>

                        <!-- Campo para Status do Carrinho -->
                        <div class="mt-4">
                            <x-input-label for="status" :value="__('Status')" />
                            <select id="status" name="status" class="mt-1 block w-full dark:bg-gray-700 dark:text-gray-200">
                                <option value="open" {{ $cart->status == 'open' ? 'selected' : '' }}>Open</option>
                                <option value="done" {{ $cart->status == 'done' ? 'selected' : '' }}>Done</option>
                                <option value="cancelled" {{ $cart->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                            <x-input-error :messages="$errors->get('status')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <x-primary-button class="ml-4">
                                {{ __('Update Cart') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
