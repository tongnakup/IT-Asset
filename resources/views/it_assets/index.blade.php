<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('IT Asset Management') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div x-data="assetManager()">

                {{-- Notification Alert --}}
                <div x-show="notification.show"
                    :class="{
                        'bg-green-100 border-green-400 text-green-700': notification.type === 'success',
                        'bg-red-100 border-red-400 text-red-700': notification.type === 'error'
                    }"
                    class="mb-4 border px-4 py-3 rounded relative" role="alert" x-transition>
                    <span class="block sm:inline" x-text="notification.message"></span>
                </div>

                {{-- Filter and Action Buttons --}}
                <div class="flex flex-wrap justify-between items-center mb-4 gap-4">
                    <div class="w-full lg:w-auto">
                        <form action="{{ route('it_assets.index') }}" method="GET"
                            class="flex flex-wrap items-center gap-2">
                            <input type="text" name="search" placeholder="Search..."
                                class="border-gray-300 rounded-md shadow-sm" value="{{ request('search') }}">

                            {{-- ▼▼▼ [แก้ไข] กลับไปใช้โค้ดดั้งเดิมที่ถูกต้อง ▼▼▼ --}}
                            <select name="type" class="border-gray-300 rounded-md shadow-sm">
                                <option value="">All Types</option>
                                @foreach ($types as $type)
                                    <option value="{{ $type }}" @selected(request('type') == $type)>{{ $type }}
                                    </option>
                                @endforeach
                            </select>

                            {{-- ▼▼▼ [แก้ไข] กลับไปใช้โค้ดดั้งเดิมที่ถูกต้อง ▼▼▼ --}}
                            <select name="status" class="border-gray-300 rounded-md shadow-sm">
                                <option value="">All Statuses</option>
                                @foreach ($statuses as $status)
                                    <option value="{{ $status }}" @selected(request('status') == $status)>{{ $status }}
                                    </option>
                                @endforeach
                            </select>

                            <button type="submit"
                                class="px-4 py-2 bg-gray-800 text-white rounded-md text-sm">Filter</button>
                        </form>
                    </div>
                    <div class="flex items-center space-x-2">
                        <a href="{{ route('it_assets.trash') }}"
                            class="px-4 py-2 bg-white border border-gray-300 text-gray-800 rounded-md text-sm font-medium hover:bg-gray-50">View
                            Trash</a>
                        <a href="{{ route('it_assets.create') }}"
                            class="px-4 py-2 bg-gray-800 text-white rounded-md text-sm font-medium hover:bg-gray-700">+
                            Add New Asset</a>
                    </div>
                </div>

                {{-- Assets Table --}}
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Employee ID</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            User</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Phone Number</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Type</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Brand</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Asset Number</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Status</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Actions</th>
                                    </tr>
                                </thead>
                                {{-- คัดลอกส่วนนี้ไปวางทับของเดิมได้เลย --}}
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @forelse ($assets as $asset)
                                        <tr id="asset-row-{{ $asset->id }}">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                {{-- แสดง Employee ID --}}
                                                {{ $asset->employee->employee_id ?? '' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                {{-- แสดงชื่อ-นามสกุล User --}}
                                                {{ $asset->employee ? $asset->employee->first_name . ' ' . $asset->employee->last_name : 'Unassigned' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                {{-- แสดงเบอร์โทรศัพท์ --}}
                                                {{ $asset->employee->phone_number ?? '' }}
                                            </td>

                                            {{-- ▼▼▼ [แก้ไขตรงนี้] ▼▼▼ --}}
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                {{-- ใช้ $asset->type->name แทน assetType --}}
                                                {{ $asset->type->name ?? 'N/A' }}
                                            </td>

                                            <td class="px-6 py-4 whitespace-nowrap">
                                                {{-- ส่วนของ Brand ถูกต้องแล้ว --}}
                                                {{ $asset->brand->name ?? 'N/A' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap font-medium">
                                                {{ $asset->asset_number }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @php
                                                    // ส่วนของ Status ถูกต้องแล้ว
                                                    $statusName = $asset->status->name ?? 'Unknown';
                                                    $statusClass = match (strtolower($statusName)) {
                                                        'use' => 'bg-green-100 text-green-800',
                                                        'stock' => 'bg-yellow-100 text-yellow-800',
                                                        'broken' => 'bg-red-100 text-red-800',
                                                        default => 'bg-gray-100 text-gray-800',
                                                    };
                                                @endphp
                                                <span
                                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusClass }}">
                                                    {{ $statusName }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                {{-- ส่วนของ Actions --}}
                                                <div class="flex items-center justify-start space-x-2">
                                                    <div class="flex items-center justify-start space-x-2">
                                                        {{-- Print Label Button --}}
                                                        <a href="{{ route('it_assets.label', $asset->id) }}"
                                                            target="_blank" class="text-gray-500 hover:text-gray-800"
                                                            title="Print Label">
                                                            <svg class="h-5 w-5" fill="currentColor"
                                                                viewBox="0 0 20 20">
                                                                <path fill-rule="evenodd"
                                                                    d="M5 4v3H4a2 2 0 00-2 2v6a2 2 0 002 2h12a2 2 0 002-2V9a2 2 0 00-2-2h-1V4a2 2 0 00-2-2H7a2 2 0 00-2 2zm8 0H7v3h6V4zm0 8H7v4h6v-4z"
                                                                    clip-rule="evenodd"></path>
                                                            </svg>
                                                        </a>

                                                        {{-- View Button --}}
                                                        <a href="{{ route('it_assets.show', $asset->id) }}"
                                                            class="text-indigo-600 hover:text-indigo-900"
                                                            title="View">
                                                            <svg class="h-5 w-5" fill="currentColor"
                                                                viewBox="0 0 20 20">
                                                                <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"></path>
                                                                <path fill-rule="evenodd"
                                                                    d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.022 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z"
                                                                    clip-rule="evenodd"></path>
                                                            </svg>
                                                        </a>

                                                        {{-- Edit Button --}}
                                                        <button @click="openEditModal({{ $asset->id }})"
                                                            class="text-yellow-600 hover:text-yellow-900"
                                                            title="Edit">
                                                            <svg class="h-5 w-5" fill="currentColor"
                                                                viewBox="0 0 20 20">
                                                                <path
                                                                    d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z">
                                                                </path>
                                                            </svg>
                                                        </button>

                                                        {{-- Delete Button Form --}}
                                                        <form id="delete-form-{{ $asset->id }}"
                                                            action="{{ route('it_assets.destroy', $asset->id) }}"
                                                            method="POST" class="hidden">
                                                            @csrf
                                                            @method('DELETE')
                                                        </form>

                                                        <button type="button"
                                                            @click="openDeleteModal({{ $asset->id }})"
                                                            class="text-red-600 hover:text-red-900" title="Delete">
                                                            <svg class="h-5 w-5" fill="currentColor"
                                                                viewBox="0 0 20 20">
                                                                <path fill-rule="evenodd"
                                                                    d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                                                                    clip-rule="evenodd"></path>
                                                            </svg>
                                                        </button>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" class="px-6 py-4 text-center text-gray-500">
                                                No assets found.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-4">{{ $assets->links() }}</div>
                    </div>
                </div>

                {{-- Edit Modal --}}
                <div x-show="showEditModal"
                    class="fixed inset-0 bg-gray-600 bg-opacity-75 overflow-y-auto h-full w-full flex items-center justify-center"
                    x-cloak>
                    <div @click.away="showEditModal = false"
                        class="relative mx-auto p-6 border w-full max-w-4xl shadow-lg rounded-md bg-white">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-xl leading-6 font-bold text-gray-900">Edit IT Asset</h3>
                            <button @click="showEditModal = false"
                                class="text-gray-400 hover:text-gray-600">&times;</button>
                        </div>

                        <form @submit.prevent="submitEditForm()" x-ref="editForm">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-4">

                                <div class="space-y-4">
                                    <h4 class="text-lg font-semibold text-gray-700 border-b pb-2">Assignment Details
                                    </h4>
                                    <div class="relative">
                                        <label for="employee_search"
                                            class="block text-sm font-medium text-gray-700">ID
                                            พนักงาน</label>
                                        <input type="text" id="employee_search" x-model="employeeSearchQuery"
                                            @input.debounce.300ms="searchEmployees()"
                                            @focus="showEmployeeResults = true" placeholder="พิมพ์เพื่อค้นหา..."
                                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                                            autocomplete="off">
                                        <input type="hidden" name="employee_id" :value="editFormData.employee_id">
                                        <div x-show="showEmployeeResults && employeeSearchResults.length > 0"
                                            @click.away="showEmployeeResults = false"
                                            class="absolute z-10 w-full mt-1 bg-white border border-gray-300 rounded-md shadow-lg max-h-40 overflow-y-auto">
                                            <ul>
                                                <template x-for="employee in employeeSearchResults"
                                                    :key="employee.id">
                                                    <li @click="selectEmployee(employee)"
                                                        class="px-4 py-2 cursor-pointer hover:bg-gray-100">
                                                        <div x-text="`${employee.first_name} ${employee.last_name}`">
                                                        </div>
                                                        <div class="text-xs text-gray-500"
                                                            x-text="employee.employee_id"></div>
                                                    </li>
                                                </template>
                                            </ul>
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">ชื่อ</label>
                                        <input name="first_name" x-model="editFormData.first_name" type="text"
                                            readonly
                                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm bg-gray-100">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">นามสกุล</label>
                                        <input name="last_name" x-model="editFormData.last_name" type="text"
                                            readonly
                                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm bg-gray-100">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">ตำแหน่ง</label>
                                        <input name="position" x-model="editFormData.position" type="text"
                                            readonly
                                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm bg-gray-100">
                                    </div>
                                </div>

                                <div class="space-y-4">
                                    <h4 class="text-lg font-semibold text-gray-700 border-b pb-2">Asset Details</h4>
                                    <div><label class="block text-sm font-medium">Asset Number</label><input
                                            name="asset_number" x-model="editFormData.asset_number" type="text"
                                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"></div>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div><label class="block text-sm font-medium">Category</label><select
                                                name="asset_category_id" x-model="editFormData.asset_category_id"
                                                @change="categoryChanged($event.target.value)"
                                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"><template
                                                    x-for="category in dropdowns.categories" :key="category.id">
                                                    <option :value="category.id" x-text="category.name"></option>
                                                </template></select></div>

                                        <div>
                                            <label class="block text-sm font-medium">Type</label>
                                            <select name="asset_type_id" x-model="editFormData.asset_type_id"
                                                @change="typeChanged($event.target.value)"
                                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                                <template x-for="type in dropdowns.types" :key="type.id">
                                                    <option :value="type.id" x-text="type.name"></option>
                                                </template>
                                            </select>
                                        </div>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium">Brand</label>
                                        <input type="text" name="brand_name" x-model="editFormData.brand_name"
                                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                                            placeholder="Enter brand name" required>
                                    </div>

                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium">Status</label>
                                            <select name="status_id" x-model="editFormData.status_id"
                                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                                <template x-for="status in dropdowns.statuses" :key="status.id">
                                                    <option :value="status.id" x-text="status.name"></option>
                                                </template>
                                            </select>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium">Location</label>
                                            <select name="location_id" x-model="editFormData.location_id"
                                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                                <option value="">-- Select Location --</option>
                                                <template x-for="loc in dropdowns.locations" :key="loc.id">
                                                    <option :value="loc.id" x-text="loc.name"></option>
                                                </template>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div><label class="block text-sm font-medium">Purchase Date</label><input
                                                name="purchase_date"
                                                :value="editFormData.purchase_date ? editFormData.purchase_date.substring(0,
                                                    10) : ''"
                                                type="date"
                                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"></div>
                                        <div><label class="block text-sm font-medium">Warranty End</label><input
                                                name="warranty_end_date"
                                                :value="editFormData.warranty_end_date ? editFormData.warranty_end_date
                                                    .substring(0, 10) : ''"
                                                type="date"
                                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"></div>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Image</label>
                                        <input name="image" type="file" @change="handleImageUpload"
                                            class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-gray-100 file:text-gray-700 hover:file:bg-gray-200">
                                        <div class="mt-2">
                                            <p class="text-xs text-gray-500 mb-1">Current Image:</p>
                                            <img :src="imagePreview" class="h-24 w-auto rounded border p-1">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="pt-6 mt-6 border-t flex justify-end space-x-3">
                                <button type="button" @click="showEditModal = false"
                                    class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50">Cancel</button>
                                <button type="submit" :disabled="isLoading"
                                    class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 disabled:bg-indigo-300">
                                    <span x-show="!isLoading">Update Asset</span>
                                    <span x-show="isLoading" class="flex items-center"><svg
                                            class="animate-spin -ml-1 mr-3 h-5 w-5 text-white"
                                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10"
                                                stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor"
                                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                            </path>
                                        </svg>Updating...</span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                <x-modal name="confirm-asset-deletion" focusable>
                    <div class="p-6">
                        <h2 class="text-lg font-medium text-gray-900">
                            {{ __('Are you sure you want to delete this asset?') }}
                        </h2>

                        <p class="mt-1 text-sm text-gray-600">
                            {{ __('Once the asset is deleted, this action cannot be undone.') }}
                        </p>

                        <div class="mt-6 flex justify-end">
                            <x-secondary-button x-on:click="$dispatch('close')">
                                {{ __('Cancel') }}
                            </x-secondary-button>

                            <x-danger-button class="ms-3" @click="confirmDelete()">
                                {{ __('Delete Asset') }}
                            </x-danger-button>
                        </div>
                    </div>
                </x-modal>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            function assetManager() {
                return {
                    showEditModal: false,
                    isLoading: false,
                    assetToDeleteId: null,
                    notification: {
                        show: false,
                        message: '',
                        type: 'success'
                    },
                    editFormData: {},
                    imagePreview: '',
                    dropdowns: {
                        types: [],
                        statuses: [],
                        categories: [],
                        locations: [],
                        brandsForType: []
                    },
                    currentAssetId: null,
                    employeeSearchQuery: '',
                    employeeSearchResults: [],
                    showEmployeeResults: false,

                    openEditModal(assetId) {
                        this.currentAssetId = assetId;
                        fetch(`/it_assets/${assetId}/edit-data`)
                            .then(response => response.json())
                            .then(data => {

                                this.dropdowns.types = data.types;
                                this.dropdowns.statuses = data.statuses;
                                this.dropdowns.categories = data.categories;
                                this.dropdowns.locations = data.locations;

                                this.$nextTick(() => {
                                    this.editFormData = data.asset;
                                    this.editFormData.brand_name = data.asset.brand ? data.asset.brand.name : '';
                                    this.imagePreview = data.asset.image_path ?
                                        `/uploads/${data.asset.image_path}` : '';
                                    if (data.asset.employee) {
                                        this.employeeSearchQuery = data.asset.employee.employee_id;
                                        this.editFormData.first_name = data.asset.employee.first_name;
                                        this.editFormData.last_name = data.asset.employee.last_name;
                                        this.editFormData.position = data.asset.employee.position;
                                        this.editFormData.employee_id = data.asset.employee.id;
                                    } else {
                                        this.editFormData.employee = {};
                                        this.employeeSearchQuery = '';
                                        this.editFormData.first_name = '';
                                        this.editFormData.last_name = '';
                                        this.editFormData.position = '';
                                        this.editFormData.employee_id = null;
                                    }

                                    this.typeChanged(this.editFormData.asset_type_id, true);
                                });

                                this.showEditModal = true;
                                this.employeeSearchResults = [];
                            });
                    },

                    handleImageUpload(event) {
                        const file = event.target.files[0];
                        if (file) {
                            this.imagePreview = URL.createObjectURL(file);
                        }
                    },

                    searchEmployees() {
                        if (this.employeeSearchQuery.trim() === '') {
                            this.employeeSearchResults = [];
                            return;
                        }
                        fetch(`/employees/search?query=${this.employeeSearchQuery}`)
                            .then(response => response.json())
                            .then(data => {
                                this.employeeSearchResults = data;
                            });
                    },

                    selectEmployee(employee) {
                        this.editFormData.employee_id = employee.id;
                        this.editFormData.first_name = employee.first_name;
                        this.editFormData.last_name = employee.last_name;
                        this.editFormData.position = employee.position;
                        this.employeeSearchQuery = employee.employee_id;
                        this.showEmployeeResults = false;
                    },

                    categoryChanged(categoryId) {
                        this.editFormData.asset_type_id = null;
                        if (!categoryId) {
                            this.dropdowns.types = [];
                            this.dropdowns.brandsForType = [];
                            return;
                        }
                        fetch(`/api/types/${categoryId}`)
                            .then(response => response.json())
                            .then(data => {
                                this.dropdowns.types = data;
                            });
                    },

                    typeChanged(typeId, isInitialLoad = false) {
                        if (!isInitialLoad) {
                            this.editFormData.brand_id = null;
                        }

                        if (!typeId) {
                            this.dropdowns.brandsForType = [];
                            return;
                        }

                        fetch(`/api/types/${typeId}/brands`)
                            .then(response => response.json())
                            .then(data => {
                                this.dropdowns.brandsForType = data;
                            });
                    },

                    submitEditForm() {
                        this.isLoading = true;
                        const formData = new FormData(this.$refs.editForm);

                        if (this.editFormData.employee_id) {
                            formData.set('employee_id', this.editFormData.employee_id);
                        } else {
                            formData.set('employee_id', '');
                        }

                        formData.append('_method', 'PUT');

                        fetch(`/it_assets/${this.currentAssetId}`, {
                                method: 'POST',
                                headers: {
                                    'Accept': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                        'content')
                                },
                                body: formData
                            })
                            .then(response => response.json().then(data => ({
                                ok: response.ok,
                                data
                            })))
                            .then(({
                                ok,
                                data
                            }) => {
                                if (!ok) {
                                    throw data;
                                }
                                this.showEditModal = false;
                                this.showNotification(data.message, 'success');
                                // You might want to update the row in the table here instead of reloading
                                location.reload();
                            })
                            .catch(error => {
                                let errorMessage = 'An error occurred.';
                                if (error.message) {
                                    errorMessage = error.message;
                                }
                                if (error.errors) {
                                    errorMessage = Object.values(error.errors).join(' ');
                                }
                                this.showNotification(errorMessage, 'error');
                            })
                            .finally(() => this.isLoading = false);
                    },

                    openDeleteModal(assetId) {
                        this.assetToDeleteId = assetId;
                        this.$dispatch('open-modal', 'confirm-asset-deletion');
                    },

                    confirmDelete() {
                        if (this.assetToDeleteId) {
                            const form = document.getElementById('delete-form-' + this.assetToDeleteId);
                            if (form) {
                                form.submit();
                            }
                        }
                        this.$dispatch('close');
                    },

                    showNotification(message, type) {
                        this.notification.message = message;
                        this.notification.type = type;
                        this.notification.show = true;
                        setTimeout(() => {
                            this.notification.show = false;
                        }, 5000);
                    }
                }
            }
        </script>
    @endpush
</x-app-layout>
