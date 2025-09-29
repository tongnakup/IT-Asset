<form wire:submit="save">
    <div class="space-y-6">
        <div class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4 items-start">
                <div class="relative">
                    <label for="asset_number_search" class="block text-sm font-medium text-gray-700">Asset Number
                        (ถ้าทราบ)</label>
                    <div class="flex items-center space-x-2 mt-1">
                        {{-- ช่องกรอก --}}
                        <div class="flex-grow relative">
                            <input type="text" id="asset_number_search"
                                class="block w-full rounded-md border-gray-300 shadow-sm"
                                placeholder="พิมพ์เพื่อค้นหา..." wire:model.live.debounce.300ms="asset_number"
                                autocomplete="off">

                            {{-- ▼▼▼ [จุดที่แก้ไข] เปลี่ยน isNotEmpty() เป็น !empty() ▼▼▼ --}}
                            @if (!empty($searchResults))
                                <div
                                    class="absolute z-10 w-full mt-1 bg-white border border-gray-300 rounded-md shadow-lg">
                                    <ul class="max-h-60 overflow-y-auto">
                                        @foreach ($searchResults as $result)
                                            <li class="px-4 py-2 cursor-pointer hover:bg-gray-100"
                                                wire:click="selectAsset({{ $result->id }})"
                                                wire:key="result-{{ $result->id }}">
                                                <div class="font-semibold">{{ $result->asset_number }}</div>
                                                <div class="text-xs text-gray-500">{{ $result->type->name ?? '' }}</div>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                        </div>
                        {{-- ปุ่ม Scan QR Code --}}
                        <button type="button" id="scan-qr-btn" title="Scan QR Code"
                            class="flex-shrink-0 inline-flex items-center justify-center p-2 border border-gray-300 shadow-sm rounded-md text-gray-700 bg-white hover:bg-gray-50">
                            <svg class="h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                fill="currentColor">
                                <path
                                    d="M1.5 1.5A1.5 1.5 0 0 1 3 0h1.5a.75.75 0 0 1 0 1.5H3a.75.75 0 0 0-.75.75v1.5a.75.75 0 0 1-1.5 0V1.5zM22.5 1.5A1.5 1.5 0 0 0 21 0h-1.5a.75.75 0 0 0 0 1.5H21a.75.75 0 0 1 .75.75v1.5a.75.75 0 0 0 1.5 0V1.5zM1.5 22.5A1.5 1.5 0 0 0 3 24h1.5a.75.75 0 0 0 0-1.5H3a.75.75 0 0 1-.75-.75v-1.5a.75.75 0 0 0-1.5 0v1.5zM22.5 22.5A1.5 1.5 0 0 1 21 24h-1.5a.75.75 0 0 1 0-1.5H21a.75.75 0 0 0 .75-.75v-1.5a.75.75 0 0 1 1.5 0v1.5zM3 10.5a.75.75 0 0 1 .75-.75h16.5a.75.75 0 0 1 0 1.5H3.75a.75.75 0 0 1-.75-.75z" />
                            </svg>
                        </button>
                    </div>
                    @if ($lookupMessage)
                        <div
                            class="mt-2 text-sm p-2 rounded-md {{ $lookupMessageType === 'success' ? 'bg-green-50 text-green-800' : 'bg-red-50 text-red-700' }}">
                            {{ $lookupMessage }}
                        </div>
                    @endif
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
                <div>
                    <label for="category" class="block text-sm font-medium text-gray-700">หมวดหมู่ <span
                            class="text-red-600">*</span></label>
                    <input type="text" id="category" wire:model="asset_category_name"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
                        @if ($assetFound) readonly @endif>
                </div>
                <div>
                    <label for="asset_type" class="block text-sm font-medium text-gray-700">ประเภทอุปกรณ์ <span
                            class="text-red-600">*</span></label>
                    <input type="text" id="asset_type" wire:model="asset_type_name"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
                        @if ($assetFound) readonly @endif>
                </div>
            </div>
            <div>
                <label for="location" class="block text-sm font-medium text-gray-700">Location <span
                        class="text-red-600">*</span></label>
                <input type="text" id="location" wire:model="location_name"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
                    @if ($assetFound) readonly @endif>
            </div>
        </div>
        <div>
            <label for="problem_description" class="block text-sm font-medium text-gray-700">กรุณาอธิบายปัญหา <span
                    class="text-red-600">*</span></label>
            <textarea id="problem_description" rows="4" wire:model="problem_description"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required></textarea>
        </div>
        <div>
            <label for="image" class="block text-sm font-medium text-gray-700">แนบรูปภาพปัญหา (ถ้ามี)</label>
            <input type="file" id="image" wire:model="image"
                class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-gray-100 file:text-gray-700 hover:file:bg-gray-200">
        </div>
        <div class="mt-6 flex justify-end space-x-4">
            <a href="{{ route('dashboard') }}"
                class="px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-sm text-gray-700 hover:bg-gray-50">Cancel</a>
            <button type="submit"
                class="px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-sm text-white hover:bg-indigo-700">
                <span wire:loading.remove>Submit Request</span>
                <span wire:loading>Submitting...</span>
            </button>
        </div>
    </div>
</form>
