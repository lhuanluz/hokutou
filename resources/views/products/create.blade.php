<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Create Product') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                    <form method="POST" action="{{ route('products.store') }}" enctype="multipart/form-data">
                        @csrf

                        <div>
                            <x-input-label for="name" :value="__('Name')" />
                            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full dark:bg-gray-700 dark:text-gray-200" :value="old('name')" required autofocus />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="quantity" :value="__('Quantity')" />
                            <x-text-input id="quantity" name="quantity" type="number" class="mt-1 block w-full dark:bg-gray-700 dark:text-gray-200" :value="old('quantity')" required />
                            <x-input-error :messages="$errors->get('quantity')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="cost_acquisition" :value="__('Acquisition Cost')" />
                            <x-text-input id="cost_acquisition" name="cost_acquisition" type="number" step="0.01" class="mt-1 block w-full dark:bg-gray-700 dark:text-gray-200" :value="old('cost_acquisition')" required />
                            <x-input-error :messages="$errors->get('cost_acquisition')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="sale_value" :value="__('Sale Price')" />
                            <x-text-input id="sale_value" name="sale_value" type="number" step="0.01" class="mt-1 block w-full dark:bg-gray-700 dark:text-gray-200" :value="old('sale_value')" required />
                            <x-input-error :messages="$errors->get('sale_value')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="minimum_quantity" :value="__('Minimum Quantity')" />
                            <x-text-input id="minimum_quantity" name="minimum_quantity" type="number" class="mt-1 block w-full dark:bg-gray-700 dark:text-gray-200" :value="old('minimum_quantity')" required />
                            <x-input-error :messages="$errors->get('minimum_quantity')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="barcode" :value="__('Barcode')" />
                            <x-text-input id="barcode" name="barcode" type="text" class="mt-1 block w-full dark:bg-gray-700 dark:text-gray-200" :value="old('barcode')" required />
                            <x-input-error :messages="$errors->get('barcode')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="product_image" :value="__('Product Photo')" />
                            <x-text-input id="product_image" name="product_image" type="file" class="mt-1 block w-full dark:bg-gray-700 dark:text-gray-200" />
                            <x-input-error :messages="$errors->get('product_image')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="categories" :value="__('Categories')" />
                            <select id="categories" name="categories[]" class="mt-1 block w-full dark:bg-gray-700 dark:text-gray-200" multiple>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('categories')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <x-primary-button class="ml-4">
                                {{ __('Create') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
