<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('System Settings') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-4 bg-green-100 border-l-4 border-green-500 text-green-700 p-4" role="alert">
                    <p>{{ session('success') }}</p>
                </div>
            @endif
            @if ($errors->any())
                <div class="mb-4 bg-red-100 border-l-4 border-red-500 text-red-700 p-4" role="alert">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- GRID แถวที่ 1 (Categories, Types, Statuses) --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-8">

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-800 border-b pb-3 mb-4">Manage Asset Categories</h3>
                        <form action="{{ route('settings.categories.store') }}" method="POST"
                            class="flex items-center space-x-2">
                            @csrf
                            <input type="text" name="name" placeholder="Add new category..."
                                class="block w-full rounded-md border-gray-300 shadow-sm" required>
                            <button type="submit"
                                class="px-4 py-2 bg-gray-800 text-white rounded-md text-sm font-semibold hover:bg-gray-700 flex-shrink-0">Add</button>
                        </form>
                        <ul class="mt-4 space-y-2">
                            @forelse($assetCategories as $category)
                                <li class="flex justify-between items-center p-2 bg-gray-50 rounded-md">
                                    <span>{{ $category->name }}</span>
                                    <form action="{{ route('settings.categories.destroy', $category->id) }}"
                                        method="POST" onsubmit="return confirm('Are you sure?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="text-red-500 hover:text-red-700 text-sm font-semibold">Delete</button>
                                    </form>
                                </li>
                            @empty
                                <li class="text-gray-500 text-sm">No asset categories found.</li>
                            @endforelse
                        </ul>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-semibold border-b pb-3 mb-4">Manage Asset Types</h3>
                        <form action="{{ route('settings.types.store') }}" method="POST"
                            class="flex items-center space-x-2">
                            @csrf
                            <select name="asset_category_id" class="block w-full rounded-md border-gray-300 shadow-sm"
                                required>
                                <option value="">-- Select Category --</option>
                                @foreach ($assetCategories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                            <input type="text" name="name" placeholder="Add new type..."
                                class="block w-full rounded-md border-gray-300 shadow-sm" required>
                            <button type="submit"
                                class="px-4 py-2 bg-gray-800 text-white rounded-md text-sm font-semibold hover:bg-gray-700 flex-shrink-0">Add</button>
                        </form>
                        <div class="mt-6 border-t pt-4 space-y-2">
                            @forelse ($assetTypes as $type)
                                <li class="flex justify-between items-center p-2 bg-gray-50 rounded-md">
                                    <div>
                                        <span>{{ $type->name }}</span>
                                        <span
                                            class="text-xs text-gray-500 ml-2">({{ $type->assetCategory?->name ?? 'No Category' }})</span>
                                    </div>
                                    <form action="{{ route('settings.types.destroy', $type->id) }}" method="POST"
                                        onsubmit="return confirm('Are you sure?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="text-red-500 hover:text-red-700 text-sm font-semibold">Delete</button>
                                    </form>
                                </li>
                            @empty
                                <li class="text-gray-500 text-sm">No asset types found.</li>
                            @endforelse
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-semibold border-b pb-3 mb-4">Manage Asset Statuses</h3>
                        <form action="{{ route('settings.statuses.store') }}" method="POST"
                            class="flex items-center space-x-2">
                            @csrf
                            <input type="text" name="name" placeholder="New asset status name"
                                class="flex-grow rounded-md border-gray-300 shadow-sm" required>
                            <button type="submit"
                                class="px-4 py-2 bg-gray-800 text-white rounded-md text-sm font-semibold hover:bg-gray-700 flex-shrink-0">Add</button>
                        </form>
                        <div class="mt-4 space-y-2">
                            @forelse ($assetStatuses as $status)
                                <div class="flex justify-between items-center p-2 bg-gray-50 rounded-md">
                                    <span>{{ $status->name }}</span>
                                    <form action="{{ route('settings.statuses.destroy', $status->id) }}" method="POST"
                                        onsubmit="return confirm('Are you sure?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="text-red-500 hover:text-red-700 text-sm font-semibold">Delete</button>
                                    </form>
                                </div>
                            @empty
                                <p class="text-gray-500 text-sm">No asset statuses found.</p>
                            @endforelse
                        </div>
                    </div>
                </div>

            </div>

            {{-- GRID แถวที่ 2 (Positions, Departments, Locations, Brands) --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mt-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-800 border-b pb-3 mb-4">Manage Positions</h3>
                        <form action="{{ route('settings.positions.store') }}" method="POST"
                            class="flex items-center space-x-2">
                            @csrf
                            <input type="text" name="name" placeholder="Add new position..."
                                class="block w-full rounded-md border-gray-300 shadow-sm" required>
                            <button type="submit"
                                class="px-4 py-2 bg-gray-800 text-white rounded-md text-sm font-semibold hover:bg-gray-700 flex-shrink-0">Add</button>
                        </form>
                        <ul class="mt-4 space-y-2">
                            @forelse($positions as $item)
                                <li class="flex justify-between items-center p-2 bg-gray-50 rounded-md">
                                    <span>{{ $item->name }}</span>
                                    <form action="{{ route('settings.positions.destroy', $item->id) }}" method="POST"
                                        onsubmit="return confirm('Are you sure?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="text-red-500 hover:text-red-700 text-sm font-semibold">Delete</button>
                                    </form>
                                </li>
                            @empty
                                <li class="text-gray-500 text-sm">No positions found.</li>
                            @endforelse
                        </ul>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-800 border-b pb-3 mb-4">Manage Departments</h3>
                        <form action="{{ route('settings.departments.store') }}" method="POST"
                            class="flex items-center space-x-2">
                            @csrf
                            <input type="text" name="name" placeholder="Add new department..."
                                class="block w-full rounded-md border-gray-300 shadow-sm" required>
                            <button type="submit"
                                class="px-4 py-2 bg-gray-800 text-white rounded-md text-sm font-semibold hover:bg-gray-700 flex-shrink-0">Add</button>
                        </form>
                        <ul class="mt-4 space-y-2">
                            @forelse($departments as $item)
                                <li class="flex justify-between items-center p-2 bg-gray-50 rounded-md">
                                    <span>{{ $item->name }}</span>
                                    <form action="{{ route('settings.departments.destroy', $item->id) }}"
                                        method="POST" onsubmit="return confirm('Are you sure?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="text-red-500 hover:text-red-700 text-sm font-semibold">Delete</button>
                                    </form>
                                </li>
                            @empty
                                <li class="text-gray-500 text-sm">No departments found.</li>
                            @endforelse
                        </ul>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-800 border-b pb-3 mb-4">Manage Locations</h3>
                        <form action="{{ route('settings.locations.store') }}" method="POST"
                            class="flex items-center space-x-2">
                            @csrf
                            <input type="text" name="name" placeholder="Add new location..."
                                class="block w-full rounded-md border-gray-300 shadow-sm" required>
                            <button type="submit"
                                class="px-4 py-2 bg-gray-800 text-white rounded-md text-sm font-semibold hover:bg-gray-700 flex-shrink-0">Add</button>
                        </form>
                        <ul class="mt-4 space-y-2">
                            @forelse($locations as $item)
                                <li class="flex justify-between items-center p-2 bg-gray-50 rounded-md">
                                    <span>{{ $item->name }}</span>
                                    <form action="{{ route('settings.locations.destroy', $item->id) }}"
                                        method="POST" onsubmit="return confirm('Are you sure?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="text-red-500 hover:text-red-700 text-sm font-semibold">Delete</button>
                                    </form>
                                </li>
                            @empty
                                <li class="text-gray-500 text-sm">No locations found.</li>
                            @endforelse
                        </ul>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-800 border-b pb-3 mb-4">Manage Brands</h3>
                        <form action="{{ route('settings.brands.store') }}" method="POST"
                            class="flex items-center space-x-2">
                            @csrf
                            <input type="text" name="name" placeholder="Add new brand..."
                                class="block w-full rounded-md border-gray-300 shadow-sm" required>
                            <button type="submit"
                                class="px-4 py-2 bg-gray-800 text-white rounded-md text-sm font-semibold hover:bg-gray-700 flex-shrink-0">Add</button>
                        </form>
                        <ul class="mt-4 space-y-2">
                            @forelse($brands as $brand)
                                <li class="flex justify-between items-center p-2 bg-gray-50 rounded-md">
                                    <span>{{ $brand->name }}</span>
                                    <form action="{{ route('settings.brands.destroy', $brand->id) }}" method="POST"
                                        onsubmit="return confirm('Are you sure?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="text-red-500 hover:text-red-700 text-sm font-semibold">Delete</button>
                                    </form>
                                </li>
                            @empty
                                <li class="text-gray-500 text-sm">No brands found.</li>
                            @endforelse
                        </ul>
                    </div>
                </div>
            </div>

            {{-- ▼▼▼ [START] Card ใหม่สำหรับ Assign Brands ▼▼▼ --}}
            <div class="mt-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg" x-data="{ selectedType: '' }">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-semibold border-b pb-3 mb-4">Assign Brands to Asset Types</h3>

                        <form action="{{ route('settings.assign_brands.store') }}" method="POST">
                            @csrf

                            <!-- Asset Type Selection -->
                            <div class="mb-6">
                                <label for="asset_type_id" class="block text-sm font-medium text-gray-700">1. Select
                                    Asset Type:</label>
                                <select id="asset_type_id" name="asset_type_id" x-model="selectedType"
                                    @change="$store.brandAssignment.updateCurrentBrands(selectedType)"
                                    class="mt-1 block w-full md:w-1/2 rounded-md border-gray-300 shadow-sm" required>
                                    <option value="">-- Please Select an Asset Type --</option>
                                    @foreach ($assetTypes as $type)
                                        <option value="{{ $type->id }}">{{ $type->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Brands Checkboxes -->
                            <div x-show="selectedType" x-transition class="border-t pt-6">
                                <h3 class="text-base font-semibold text-gray-800 mb-4">2. Assign available Brands:</h3>
                                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
                                    @foreach ($brands as $brand)
                                        <label class="flex items-center space-x-3 p-2 rounded-md hover:bg-gray-50">
                                            <input type="checkbox" name="brands[]" value="{{ $brand->id }}"
                                                class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                                                :checked="$store.brandAssignment.isChecked({{ $brand->id }})">
                                            <span>{{ $brand->name }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div x-show="selectedType" class="mt-8 flex justify-end pt-4 border-t">
                                <button type="submit"
                                    class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700">
                                    Save Assignments
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            {{-- ▲▲▲ [END] Card ใหม่สำหรับ Assign Brands ▲▲▲ --}}

        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('alpine:init', () => {
                Alpine.store('brandAssignment', {
                    typeBrandMap: @json(
                        $assetTypes->mapWithKeys(function ($type) {
                            return [$type->id => $type->brands->pluck('id')];
                        })),
                    currentBrands: [],
                    updateCurrentBrands(typeId) {
                        this.currentBrands = typeId && this.typeBrandMap[typeId] ? [...this.typeBrandMap[
                            typeId]] : [];
                    },
                    isChecked(brandId) {
                        return this.currentBrands.includes(brandId);
                    }
                });
            });
        </script>
    @endpush
</x-app-layout>
