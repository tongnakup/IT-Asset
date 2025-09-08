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

    <div class="max-w-xs">
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
