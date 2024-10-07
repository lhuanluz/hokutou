<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Add Expense') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                    <form action="{{ route('expenses.store') }}" method="POST">
                        @csrf
                        <div>
                            <x-input-label for="expense_date" :value="__('Expense Date')" />
                            <x-text-input id="expense_date" class="block mt-1 w-full" type="date" name="expense_date" value="{{ old('expense_date', now()->toDateString()) }}" required />
                            <x-input-error :messages="$errors->get('expense_date')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="description" :value="__('Description')" />
                            <x-text-input id="description" class="block mt-1 w-full" type="text" name="description" value="{{ old('description') }}" required />
                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="amount" :value="__('Amount')" />
                            <x-text-input id="amount" class="block mt-1 w-full" type="number" name="amount" value="{{ old('amount') }}" required step="0.01" />
                            <x-input-error :messages="$errors->get('amount')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="payment_method" :value="__('Payment Method')" />
                            <select id="payment_method" name="payment_method" class="block mt-1 w-full">
                                <option value="Pix">Pix</option>
                                <option value="Cash">Cash</option>
                                <option value="Debit">Debit</option>
                                <option value="Credit">Credit</option>
                            </select>
                            <x-input-error :messages="$errors->get('payment_method')" class="mt-2" />
                        </div>

                        <div id="installments_section" class="mt-4 hidden">
                            <x-input-label for="installments" :value="__('Installments')" />
                            <select id="installments" name="installments" class="block mt-1 w-full">
                                @for ($i = 1; $i <= 24; $i++)
                                    <option value="{{ $i }}">{{ $i }}x</option>
                                @endfor
                            </select>
                            <x-input-error :messages="$errors->get('installments')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="categories" :value="__('Categories')" />
                            <select id="categories" name="categories[]" class="block mt-1 w-full" multiple>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('categories')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <x-primary-button class="ml-4">
                                {{ __('Save Expense') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('payment_method').addEventListener('change', function () {
            var installmentsSection = document.getElementById('installments_section');
            if (this.value === 'Credit') {
                installmentsSection.classList.remove('hidden');
            } else {
                installmentsSection.classList.add('hidden');
            }
        });
    </script>
</x-app-layout>
