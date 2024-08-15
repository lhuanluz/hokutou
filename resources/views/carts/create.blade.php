<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Create Cart') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                    <form method="POST" action="{{ route('carts.store') }}">
                        @csrf

                        <div>
                            <x-input-label for="user_id" :value="__('User')" />
                            <select id="user_id" name="user_id" class="mt-1 block w-full dark:bg-gray-700 dark:text-gray-200">
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('user_id')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="products" :value="__('Products')" />
                            <select id="products" name="products[]" class="mt-1 block w-full dark:bg-gray-700 dark:text-gray-200" multiple>
                                @foreach ($products as $product)
                                    <option value="{{ json_encode(['id' => $product->id, 'quantity' => 1, 'price' => $product->price]) }}">{{ $product->name }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('products')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="total_value" :value="__('Total Value')" />
                            <x-text-input id="total_value" name="total_value" type="number" step="0.01" class="mt-1 block w-full dark:bg-gray-700 dark:text-gray-200" required />
                            <x-input-error :messages="$errors->get('total_value')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="discount_coupon" :value="__('Discount Coupon')" />
                            <x-text-input id="discount_coupon" name="discount_coupon" type="text" class="mt-1 block w-full dark:bg-gray-700 dark:text-gray-200" />
                            <x-input-error :messages="$errors->get('discount_coupon')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="discount_value" :value="__('Discount Value')" />
                            <x-text-input id="discount_value" name="discount_value" type="number" step="0.01" class="mt-1 block w-full dark:bg-gray-700 dark:text-gray-200" />
                            <x-input-error :messages="$errors->get('discount_value')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="status" :value="__('Status')" />
                            <select id="status" name="status" class="mt-1 block w-full dark:bg-gray-700 dark:text-gray-200">
                                <option value="open">Open</option>
                                <option value="done">Done</option>
                                <option value="cancelled">Cancelled</option>
                            </select>
                            <x-input-error :messages="$errors->get('status')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <x-primary-button class="ml-4">
                                {{ __('Create Cart') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
