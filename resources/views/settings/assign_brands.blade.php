<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Assign Brands to Asset Types') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-4 bg-green-100 border-l-4 border-green-500 text-green-700 p-4" role="alert">
                    <p>{{ session('success') }}</p>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 md:p-8 text-gray-900" x-data="{ selectedType: '' }">

                    <form action="{{ route('settings.assign_brands.store') }}" method="POST">
                        @csrf

                        <!-- Asset Type Selection -->
                        <div class="mb-6">
                            <label for="asset_type_id" class="block text-sm font-medium text-gray-700">Select Asset Type to manage its brands:</label>
                            <select id="asset_type_id" name="asset_type_id" 
                                    x-model="selectedType" 
                                    @change="$store.brandAssignment.updateCurrentBrands(selectedType)" 
                                    class="mt-1 block w-full md:w-1/2 rounded-md border-gray-300 shadow-sm" required>
                                <option value="">-- Please Select an Asset Type --</option>
                                @foreach ($assetTypes as $type)
                                    <option value="{{ $type->id }}">{{ $type->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Brands Checkboxes (shown when a type is selected) -->
                        <div x-show="selectedType" x-transition class="border-t pt-6">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4">Available Brands</h3>
                            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
                                @foreach ($brands as $brand)
                                    <label class="flex items-center space-x-3 p-2 rounded-md hover:bg-gray-50">
                                        <input 
                                            type="checkbox" 
                                            name="brands[]" 
                                            value="{{ $brand->id }}"
                                            class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                                            :checked="$store.brandAssignment.isChecked({{ $brand->id }})"
                                        >
                                        <span>{{ $brand->name }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div x-show="selectedType" class="mt-8 flex justify-end space-x-4 pt-4 border-t">
                            <a href="{{ route('settings.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50">Cancel</a>
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700">Save Assignments</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.store('brandAssignment', {
                // This holds the initial mapping of type ID to an array of brand IDs.
                typeBrandMap: @json($assetTypes->mapWithKeys(function ($type) {
                    return [$type->id => $type->brands->pluck('id')];
                })),

                // This will hold the currently selected brands for the active type.
                currentBrands: [],

                // This is called when the dropdown changes.
                updateCurrentBrands(typeId) {
                    if (typeId && this.typeBrandMap[typeId]) {
                        // We make a copy to avoid modifying the original map.
                        this.currentBrands = [...this.typeBrandMap[typeId]];
                    } else {
                        this.currentBrands = [];
                    }
                },

                // This checks if a brand checkbox should be checked.
                isChecked(brandId) {
                    // Alpine's reactivity will automatically update checkboxes when currentBrands changes.
                    return this.currentBrands.includes(brandId);
                }
            });
        });
    </script>
    @endpush
</x-app-layout>
