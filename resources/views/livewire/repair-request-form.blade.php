<form wire:submit.prevent="save">
    @csrf
    <div class="space-y-6">

        {{-- Asset Number Search and Dropdowns --}}
        <div class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4 items-start">
                <div class="relative">
                    <label for="asset_number_search" class="block text-sm font-medium text-gray-700">Asset Number
                        (ถ้าทราบ)</label>
                    <div class="flex items-center space-x-2 mt-1">
                        <div class="flex-grow relative">
                            <input type="text" name="asset_number" id="asset_number_search"
                                class="block w-full rounded-md border-gray-300 shadow-sm"
                                placeholder="พิมพ์เพื่อค้นหา..." wire:model.live.debounce.300ms="asset_number"
                                autocomplete="off">
                            @if (!empty($searchResults) && $searchResults->count() > 0)
                                <div
                                    class="absolute z-10 w-full mt-1 bg-white border border-gray-300 rounded-md shadow-lg">
                                    <ul class="max-h-60 overflow-y-auto">
                                        @foreach ($searchResults as $result)
                                            <li class="px-4 py-2 cursor-pointer hover:bg-gray-100"
                                                wire:click.prevent="selectAsset({{ $result->id }})">
                                                <div class="font-semibold">{{ $result->asset_number }}</div>
                                                <div class="text-xs text-gray-500">{{ $result->assetType->name ?? '' }}
                                                    - {{ $result->brand->name ?? '' }}</div>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                        </div>
                        <button type="button" id="scan-qr-btn"
                            class="inline-flex items-center space-x-2 rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 flex-shrink-0">
                            <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M3.75 4.875c0-1.036.84-1.875 1.875-1.875h4.5c1.036 0 1.875.84 1.875 1.875v4.5c0 1.036-.84 1.875-1.875 1.875h-4.5A1.875 1.875 0 013.75 9.375v-4.5zM3.75 14.625c0-1.036.84-1.875 1.875-1.875h4.5c1.036 0 1.875.84 1.875 1.875v4.5c0 1.036-.84 1.875-1.875 1.875h-4.5a1.875 1.875 0 01-1.875-1.875v-4.5zM13.5 4.875c0-1.036.84-1.875 1.875-1.875h4.5c1.036 0 1.875.84 1.875 1.875v4.5c0 1.036-.84 1.875-1.875 1.875h-4.5a1.875 1.875 0 01-1.875-1.875v-4.5zM13.5 14.625c0-1.036.84-1.875 1.875-1.875h4.5c1.036 0 1.875.84 1.875 1.875v4.5c0 1.036-.84 1.875-1.875 1.875h-4.5a1.875 1.875 0 01-1.875-1.875v-4.5z" />
                            </svg>
                            <span>Scan QR</span>
                        </button>
                    </div>
                    @isset($lookupMessage)
                        @if ($lookupMessage)
                            <div
                                class="mt-2 text-sm p-2 rounded-md {{ $lookupMessageType === 'success' ? 'bg-green-50 text-green-800' : 'bg-red-50 text-red-700' }}">
                                {{ $lookupMessage }}
                            </div>
                        @endif
                    @endisset
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
                <div>
                    <label for="category" class="block text-sm font-medium text-gray-700">หมวดหมู่ <span
                            class="text-red-600">*</span></label>
                    {{-- ▼▼▼ [แก้ไขจุดที่ 1] เพิ่ม isset() ▼▼▼ --}}
                    <select id="category" name="asset_category_id" wire:model.live="asset_category_id"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
                        @if (isset($assetFound) && $assetFound) disabled @endif>
                        <option value="">-- กรุณาเลือก --</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="asset_type" class="block text-sm font-medium text-gray-700">ประเภทอุปกรณ์ <span
                            class="text-red-600">*</span></label>
                    {{-- ▼▼▼ [แก้ไขจุดที่ 2] เพิ่ม isset() ▼▼▼ --}}
                    <select id="asset_type" name="asset_type_id" wire:model="asset_type_id"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
                        @if ((isset($assetFound) && $assetFound) || $types->isEmpty()) disabled @endif>
                        @if ($types->isEmpty())
                            <option value="">-- กรุณาเลือกหมวดหมู่ก่อน --</option>
                        @else
                            <option value="">-- กรุณาเลือกประเภท --</option>
                            @foreach ($types as $type)
                                <option value="{{ $type->id }}">{{ $type->name }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
            </div>
            <div>
                <label for="location" class="block text-sm font-medium text-gray-700">Location <span
                        class="text-red-600">*</span></label>
                {{-- ▼▼▼ [แก้ไขจุดที่ 3] เพิ่ม isset() ▼▼▼ --}}
                <select id="location" name="location_id" wire:model="location_id"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
                    @if (isset($assetFound) && $assetFound) disabled @endif>
                    <option value="">-- กรุณาเลือกสถานที่ --</option>
                    @foreach ($locations as $location)
                        <option value="{{ $location->id }}">{{ $location->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        {{-- Problem Description and Image Upload --}}
        <div>
            <label for="problem_description" class="block text-sm font-medium text-gray-700">กรุณาอธิบายปัญหา <span
                    class="text-red-600">*</span></label>
            <textarea name="problem_description" id="problem_description" rows="4" wire:model="problem_description"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required></textarea>
            @error('problem_description')
                <span class="text-red-500 text-xs">{{ $message }}</span>
            @enderror
        </div>
        <div>
            <label for="image" class="block text-sm font-medium text-gray-700">แนบรูปภาพปัญหา (ถ้ามี)</label>
            <input type="file" name="image" id="image" wire:model="image"
                class="mt-1 block w-full text-sm ...">
            @error('image')
                <span class="text-red-500 text-xs">{{ $message }}</span>
            @enderror
        </div>

        {{-- Buttons --}}
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
