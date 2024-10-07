<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Cart') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gray-900 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-gray-900 border-b border-gray-700">

                    <!-- Exibir aviso de débito, se houver -->
                    @if($user->balance < 0)
                        <div class="bg-red-600 text-white p-4 rounded mb-6">
                            {{ __('This user has pending debts of: ') }} <strong>{{ abs($user->balance) }}</strong>.
                        </div>
                    @endif

                    <!-- Exibir aviso de crédito, se houver -->
                    @if($user->balance > 0)
                        <div class="bg-blue-600 text-white p-4 rounded mb-6">
                            {{ __('This user has a credit of: ') }} <strong>{{ $user->balance }}</strong>.
                        </div>
                    @endif

                    <!-- Exibição dos Produtos em Formato de Tabela -->
                    <h3 class="text-lg font-semibold text-white mb-4">{{ __('Products in Cart') }}</h3>
                    <table class="min-w-full mb-6">
                        <thead>
                        <tr>
                            <th class="text-left text-white p-2">{{ __('Product Name') }}</th>
                            <th class="text-left text-white p-2">{{ __('Quantity') }}</th>
                            <th class="text-left text-white p-2">{{ __('Unit Price') }}</th>
                            <th class="text-left text-white p-2">{{ __('Total Price') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($cart->products as $product)
                            @php
                                $productModel = \App\Models\Product::find($product['id']);
                                $totalPrice = $product['quantity'] * $product['price'];
                            @endphp
                            <tr class="bg-gray-800 hover:bg-gray-700 text-white">
                                <td class="p-2">{{ $productModel->name }}</td>
                                <td class="p-2">{{ $product['quantity'] }}</td>
                                <td class="p-2">{{ $product['price'] }}</td>
                                <td class="p-2">{{ $totalPrice }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                    <!-- Exibição do Valor Total do Carrinho -->
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-white">
                            {{ __('Total Cart Value: ') }} <span class="text-green-400">{{ $cart->total_value }}</span>
                        </h3>
                    </div>

                    <form method="POST" action="{{ route('carts.update', $cart) }}">
                        @csrf
                        @method('PATCH')

                        <!-- Campo para Status do Carrinho -->
                        <div class="mb-4">
                            <x-input-label for="status" :value="__('Status')" class="text-gray-400" />
                            <select id="status" name="status" class="mt-1 block w-full bg-gray-700 text-gray-300 border border-gray-600 rounded">
                                <option value="open" {{ $cart->status == 'open' ? 'selected' : '' }}>Open</option>
                                <option value="done" {{ $cart->status == 'done' ? 'selected' : '' }}>Done</option>
                                <option value="cancelled" {{ $cart->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                            <x-input-error :messages="$errors->get('status')" class="mt-2 text-red-500" />
                        </div>

                        <!-- Campo para Valor Pago (apenas quando o status for "done") -->
                        <div id="amount_paid_section" class="mb-4 hidden">
                            <x-input-label for="amount_paid" :value="__('Amount Paid')" class="text-gray-400" />
                            <x-text-input id="amount_paid" name="amount_paid" type="number" step="0.01" class="mt-1 block w-full bg-gray-700 text-gray-300 border border-gray-600 rounded" />
                            <x-input-error :messages="$errors->get('amount_paid')" class="mt-2 text-red-500" />
                        </div>

                        <!-- Campo para Uso de Crédito (se houver) -->
                        @if($user->balance > 0)
                            <div id="use_credit_section" class="mb-4">
                                <x-input-label for="use_credit" :value="__('Use Credit')" class="text-gray-400" />
                                <x-text-input id="use_credit" name="use_credit" type="number" step="0.01" max="{{ $user->balance }}" class="mt-1 block w-full bg-gray-700 text-gray-300 border border-gray-600 rounded" />
                                <x-input-error :messages="$errors->get('use_credit')" class="mt-2 text-red-500" />
                            </div>
                        @endif

                        <!-- Campo para Método de Pagamento -->
                        <div class="mb-4">
                            <x-input-label for="payment_method" :value="__('Payment Method')" class="text-gray-400" />
                            <select id="payment_method" name="payment_method" class="mt-1 block w-full bg-gray-700 text-gray-300 border border-gray-600 rounded">
                                <option value="Pix">Pix</option>
                                <option value="Cash">Cash</option>
                                <option value="Debit">Debit</option>
                                <option value="Credit">Credit</option>
                            </select>
                            <x-input-error :messages="$errors->get('payment_method')" class="mt-2 text-red-500" />
                        </div>

                        <!-- Campo para Parcelas (apenas se for "Credit") -->
                        <div id="installments_section" class="mb-4 hidden">
                            <x-input-label for="installments" :value="__('Installments')" class="text-gray-400" />
                            <select id="installments" name="installments" class="mt-1 block w-full bg-gray-700 text-gray-300 border border-gray-600 rounded">
                                @foreach ($installmentOptions as $option)
                                    <option value="{{ $option }}">{{ $option }}x</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('installments')" class="mt-2 text-red-500" />
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <x-primary-button class="ml-4 bg-green-600 hover:bg-green-700 text-white">
                                {{ __('Update Cart') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Script para controlar exibição de campos -->
    <script>
        document.getElementById('status').addEventListener('change', function() {
            const amountPaidSection = document.getElementById('amount_paid_section');

            if (this.value === 'done') {
                amountPaidSection.classList.remove('hidden');
            } else {
                amountPaidSection.classList.add('hidden');
            }
        });

        document.getElementById('payment_method').addEventListener('change', function() {
            const installmentsSection = document.getElementById('installments_section');

            if (this.value === 'Credit') {
                installmentsSection.classList.remove('hidden');
            } else {
                installmentsSection.classList.add('hidden');
            }
        });
    </script>
</x-app-layout>
