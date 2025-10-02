<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('System Settings') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8" x-data="settingsManager()" @confirm-delete.window="confirmDelete()">

            @if (session('success'))
                <div class="mb-4 bg-green-100 border-l-4 border-green-500 text-green-700 p-4" role="alert">
                    <p>{{ session('success') }}</p>
                </div>
            @endif

            @if ($errors->any())
                <div x-show="!errorModal.show" class="mb-4 bg-red-100 border-l-4 border-red-500 text-red-700 p-4"
                    role="alert">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- GRID แถวที่ 1 (Categories, Types, Statuses) --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-8">

                {{-- Manage Asset Categories --}}
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-800 border-b pb-3 mb-4">Manage Asset Categories</h3>
                        <form action="{{ route('settings.categories.store') }}" method="POST"
                            class="flex items-center space-x-2">
                            @csrf
                            <input type="text" name="name" placeholder="Add new category..."
                                class="block w-full rounded-md border-gray-300 shadow-sm" required>
                            <button type="submit"
                                class="px-4 py-2 bg-green-600 text-white rounded-md text-sm font-semibold hover:bg-green-700 flex-shrink-0">Add</button>
                        </form>
                        <ul class="mt-4 space-y-2">
                            @forelse($assetCategories as $category)
                                <li class="flex justify-between items-center p-2 bg-gray-50 rounded-md">
                                    <span>{{ $category->name }}</span>
                                    <div>
                                        <form id="delete-category-{{ $category->id }}"
                                            action="{{ route('settings.categories.destroy', $category->id) }}"
                                            method="POST" class="hidden">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                        <button type="button"
                                            @click="openDeleteModal('delete-category-{{ $category->id }}')"
                                            class="text-red-600 hover:text-red-900" title="Delete">
                                            <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                                                    clip-rule="evenodd"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </li>
                            @empty
                                <li class="text-gray-500 text-sm">No asset categories found.</li>
                            @endforelse
                        </ul>
                    </div>
                </div>

                {{-- Manage Asset Types --}}
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
                                class="px-4 py-2 bg-green-600 text-white rounded-md text-sm font-semibold hover:bg-green-700 flex-shrink-0">Add</button>
                        </form>
                        <div class="mt-6 border-t pt-4 space-y-2">
                            @forelse ($assetTypes as $type)
                                <li class="flex justify-between items-center p-2 bg-gray-50 rounded-md">
                                    <div>
                                        <span>{{ $type->name }}</span>
                                        <span
                                            class="text-xs text-gray-500 ml-2">({{ $type->assetCategory?->name ?? 'No Category' }})</span>
                                    </div>
                                    <div>
                                        <form id="delete-type-{{ $type->id }}"
                                            action="{{ route('settings.types.destroy', $type->id) }}" method="POST"
                                            class="hidden">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                        <button type="button"
                                            @click="openDeleteModal('delete-type-{{ $type->id }}')"
                                            class="text-red-600 hover:text-red-900" title="Delete">
                                            <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                                                    clip-rule="evenodd"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </li>
                            @empty
                                <li class="text-gray-500 text-sm">No asset types found.</li>
                            @endforelse
                        </div>
                    </div>
                </div>

                {{-- Manage Asset Statuses --}}
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-semibold border-b pb-3 mb-4">Manage Asset Statuses</h3>
                        <form action="{{ route('settings.statuses.store') }}" method="POST"
                            class="flex items-center space-x-2">
                            @csrf
                            <input type="text" name="name" placeholder="New asset status name"
                                class="flex-grow rounded-md border-gray-300 shadow-sm" required>
                            <button type="submit"
                                class="px-4 py-2 bg-green-600 text-white rounded-md text-sm font-semibold hover:bg-green-700 flex-shrink-0">Add</button>
                        </form>
                        <div class="mt-4 space-y-2">
                            @forelse ($assetStatuses as $status)
                                <div class="flex justify-between items-center p-2 bg-gray-50 rounded-md">
                                    <span>{{ $status->name }}</span>
                                    <div>
                                        <form id="delete-status-{{ $status->id }}"
                                            action="{{ route('settings.statuses.destroy', $status->id) }}"
                                            method="POST" class="hidden">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                        <button type="button"
                                            @click="openDeleteModal('delete-status-{{ $status->id }}')"
                                            class="text-red-600 hover:text-red-900" title="Delete">
                                            <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                                                    clip-rule="evenodd"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            @empty
                                <p class="text-gray-500 text-sm">No asset statuses found.</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            {{-- GRID แถวที่ 2 --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mt-8">

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-800 border-b pb-3 mb-4">Manage Departments</h3>
                        <form action="{{ route('settings.departments.store') }}" method="POST"
                            class="flex items-center space-x-2">
                            @csrf
                            <input type="text" name="name" placeholder="Add new department..."
                                class="block w-full rounded-md border-gray-300 shadow-sm" required>
                            <button type="submit"
                                class="px-4 py-2 bg-green-600 text-white rounded-md text-sm font-semibold hover:bg-green-700 flex-shrink-0">Add</button>
                        </form>
                        <ul class="mt-4 space-y-2">
                            @forelse($departments as $item)
                                <li class="flex justify-between items-center p-2 bg-gray-50 rounded-md">
                                    <span>{{ $item->name }}</span>
                                    <div>
                                        <form id="delete-department-{{ $item->id }}"
                                            action="{{ route('settings.departments.destroy', $item->id) }}"
                                            method="POST" class="hidden">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                        <button type="button"
                                            @click="openDeleteModal('delete-department-{{ $item->id }}')"
                                            class="text-red-600 hover:text-red-900" title="Delete">
                                            <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                                                    clip-rule="evenodd"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </li>
                            @empty
                                <li class="text-gray-500 text-sm">No departments found.</li>
                            @endforelse
                        </ul>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 flex flex-col h-full">
                        <h3 class="text-lg font-semibold text-gray-800 border-b pb-3 mb-4">Manage Positions</h3>
                        <form action="{{ route('settings.positions.store') }}" method="POST" class="space-y-4">
                            @csrf
                            <div>
                                <label for="position_name" class="sr-only">Position Name</label>
                                <input id="position_name" type="text" name="name"
                                    placeholder="Add new position..."
                                    class="block w-full rounded-md border-gray-300 shadow-sm" required>
                            </div>
                            <div>
                                <label for="department_id" class="sr-only">Department</label>
                                <select id="department_id" name="department_id"
                                    class="block w-full rounded-md border-gray-300 shadow-sm" required>
                                    <option value="">-- Assign to Department --</option>
                                    @foreach ($departments as $department)
                                        <option value="{{ $department->id }}">{{ $department->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <button type="submit"
                                class="w-full justify-center px-4 py-2 bg-green-600 text-white rounded-md text-sm font-semibold hover:bg-green-700">Add
                                Position</button>
                        </form>
                        <div class="mt-4 flex-grow max-h-60 overflow-y-auto border-t pt-4">
                            <ul class="space-y-2">
                                @forelse($positions as $item)
                                    <li class="flex justify-between items-center p-2 bg-gray-50 rounded-md">
                                        <div>
                                            <span>{{ $item->name }}</span>
                                            <span
                                                class="text-xs text-gray-500 ml-2">({{ $item->department->name ?? 'N/A' }})</span>
                                        </div>
                                        <div>
                                            <form id="delete-position-{{ $item->id }}"
                                                action="{{ route('settings.positions.destroy', $item->id) }}"
                                                method="POST" class="hidden">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                            <button type="button"
                                                @click="openDeleteModal('delete-position-{{ $item->id }}')"
                                                class="text-red-600 hover:text-red-900" title="Delete">
                                                <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd"
                                                        d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                                                        clip-rule="evenodd"></path>
                                                </svg>
                                            </button>
                                        </div>
                                    </li>
                                @empty
                                    <li class="text-gray-500 text-sm p-2">No positions found.</li>
                                @endforelse
                            </ul>
                        </div>
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
                                class="px-4 py-2 bg-green-600 text-white rounded-md text-sm font-semibold hover:bg-green-700 flex-shrink-0">Add</button>
                        </form>
                        <ul class="mt-4 space-y-2">
                            @forelse($locations as $item)
                                <li class="flex justify-between items-center p-2 bg-gray-50 rounded-md">
                                    <span>{{ $item->name }}</span>
                                    <div>
                                        <form id="delete-location-{{ $item->id }}"
                                            action="{{ route('settings.locations.destroy', $item->id) }}"
                                            method="POST" class="hidden">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                        <button type="button"
                                            @click="openDeleteModal('delete-location-{{ $item->id }}')"
                                            class="text-red-600 hover:text-red-900" title="Delete">
                                            <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                                                    clip-rule="evenodd"></path>
                                            </svg>
                                        </button>
                                    </div>
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
                                class="px-4 py-2 bg-green-600 text-white rounded-md text-sm font-semibold hover:bg-green-700 flex-shrink-0">Add</button>
                        </form>
                        <ul class="mt-4 space-y-2">
                            @forelse($brands as $brand)
                                <li class="flex justify-between items-center p-2 bg-gray-50 rounded-md">
                                    <span>{{ $brand->name }}</span>
                                    <div>
                                        <form id="delete-brand-{{ $brand->id }}"
                                            action="{{ route('settings.brands.destroy', $brand->id) }}"
                                            method="POST" class="hidden">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                        <button type="button"
                                            @click="openDeleteModal('delete-brand-{{ $brand->id }}')"
                                            class="text-red-600 hover:text-red-900" title="Delete">
                                            <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                                                    clip-rule="evenodd"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </li>
                            @empty
                                <li class="text-gray-500 text-sm">No brands found.</li>
                            @endforelse
                        </ul>
                    </div>
                </div>
            </div>

            <div class="mt-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg" x-data="{ selectedType: '' }">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-semibold border-b pb-3 mb-4">Assign Brands to Asset Types</h3>
                        <form action="{{ route('settings.assign_brands.store') }}" method="POST">
                            @csrf
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

            <x-modal name="confirm-setting-deletion" focusable>
                <div class="p-6 text-center">
                    <svg class="mx-auto mb-4 text-gray-400 w-12 h-12" aria-hidden="true"
                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 11V6m0 8h.01M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                    <h2 class="mb-5 text-lg font-normal text-gray-500">
                        Are you sure you want to delete this item?
                    </h2>
                    <div class="flex justify-center gap-4">
                        <x-secondary-button x-on:click="$dispatch('close')">
                            No, cancel
                        </x-secondary-button>
                        <x-danger-button @click="confirmDelete()">
                            Yes, I'm sure
                        </x-danger-button>
                    </div>
                </div>
            </x-modal>

            <x-modal name="error-notification" focusable>
                <div class="p-6 text-center">
                    <svg class="mx-auto mb-4 text-red-500 w-12 h-12" aria-hidden="true"
                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 11V6m0 8h.01M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                    <h2 class="mb-5 text-lg font-normal text-gray-500" x-text="errorModal.message">
                        {{-- Error message will be injected here --}}
                    </h2>
                    <div class="flex justify-center gap-4">
                        <x-secondary-button x-on:click="$dispatch('close')">
                            OK
                        </x-secondary-button>
                    </div>
                </div>
            </x-modal>

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

                Alpine.data('settingsManager', () => ({
                    formIdToDelete: null,
                    errorModal: {
                        show: false,
                        message: ''
                    },
                    init() {
                        @error('name')
                            this.errorModal.message = {{ Js::from($message) }};
                            this.errorModal.show = true;
                            this.$nextTick(() => {
                                this.$dispatch('open-modal', 'error-notification');
                            });
                        @enderror
                    },
                    openDeleteModal(formId) {
                        this.formIdToDelete = formId;
                        this.$dispatch('open-modal', 'confirm-setting-deletion');
                    },
                    confirmDelete() {
                        if (this.formIdToDelete) {
                            const form = document.getElementById(this.formIdToDelete);
                            if (form) {
                                form.submit();
                            }
                        }
                        this.$dispatch('close');
                    }
                }));
            });
        </script>
    @endpush
</x-app-layout>
