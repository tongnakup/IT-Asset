<div>
    @if ($showModal)
        <div class="fixed inset-0 bg-gray-600 bg-opacity-75 overflow-y-auto h-full w-full flex items-center justify-center z-50"
            x-data @click.away="$wire.closeModal()">
            <div class="relative mx-auto p-6 border w-full max-w-2xl shadow-lg rounded-md bg-white">

                {{-- Modal Header --}}
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl leading-6 font-bold text-gray-900">Edit IT Asset</h3>
                    <button wire:click="closeModal" class="text-gray-400 hover:text-gray-600">&times;</button>
                </div>

                {{-- Modal Body (Your Form) --}}
                <form wire:submit.prevent="save">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="category" class="block text-sm font-medium text-gray-700">Category</label>
                            <select id="category" name="asset_category_id" wire:model.live="selectedCategory"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                                <option value="">-- Please Select a Category --</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="type" class="block text-sm font-medium text-gray-700">Type</label>
                            <select id="type" name="type_id" wire:model.live="selectedType"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                                @if ($types->isEmpty())
                                    <option value="">-- Select a Category First --</option>
                                @else
                                    <option value="">-- Please Select a Type --</option>
                                    @foreach ($types as $type)
                                        <option value="{{ $type->id }}">{{ $type->name }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>

                        <div class="md:col-span-2">
                            <label for="brand" class="block text-sm font-medium text-gray-700">Brand</label>
                            <select id="brand" name="brand_id" wire:model="selectedBrand"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                                @if ($brands->isEmpty())
                                    <option value="">-- Select a Type First --</option>
                                @else
                                    <option value="">-- Please Select a Brand --</option>
                                    @foreach ($brands as $brand)
                                        <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    </div>

                    {{-- Modal Footer (Buttons) --}}
                    <div class="pt-6 mt-6 border-t flex justify-end space-x-3">
                        <button type="button" wire:click="closeModal"
                            class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50">Cancel</button>
                        <button type="submit"
                            class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                            Save Changes
                        </button>
                    </div>
                </form>

            </div>
        </div>
    @endif
</div>
