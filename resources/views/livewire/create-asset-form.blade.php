<form wire:submit.prevent="save">
    @csrf
    <div class="space-y-8">
        
        <div>
            <h3 class="text-lg font-medium text-gray-900 border-b pb-2 mb-4">Assignment Details</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="employee_search" class="block text-sm font-medium text-gray-700">ID พนักงาน</label>
                    <div class="relative mt-1">
                        <input type="text" id="employee_search"
                               wire:model.live.debounce.300ms="employee_search"
                               class="block w-full rounded-md border-gray-300 shadow-sm"
                               placeholder="พิมพ์เพื่อค้นหา..."
                               autocomplete="off">

                        @if(!empty($searchResults) && $searchResults->count() > 0)
                            <div class="absolute z-10 w-full mt-1 bg-white border border-gray-300 rounded-md shadow-lg">
                                <ul class="max-h-60 overflow-y-auto">
                                    @foreach($searchResults as $result)
                                        <li class="px-4 py-2 cursor-pointer hover:bg-gray-100"
                                            wire:click.prevent="selectEmployee({{ $result->id }})">
                                            {{ $result->employee_id }} - {{ $result->first_name }} {{ $result->last_name }}
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @elseif(strlen($employee_search) > 0 && count($searchResults) == 0 && empty($first_name))
                            <div class="absolute z-10 w-full mt-1 bg-white border border-gray-300 rounded-md shadow-lg">
                                <div class="px-4 py-2 text-gray-500">ไม่พบข้อมูล</div>
                            </div>
                        @endif
                    </div>
                    <input type="hidden" name="employee_id" wire:model="employee_id">
                    @error('employee_id') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label for="position" class="block text-sm font-medium text-gray-700">ตำแหน่ง</label>
                    <input type="text" id="position" wire:model="position" readonly class="mt-1 block w-full rounded-md border-gray-300 shadow-sm bg-gray-100 cursor-not-allowed">
                </div>
                <div>
                    <label for="first_name" class="block text-sm font-medium text-gray-700">ชื่อ</label>
                    <input type="text" id="first_name" wire:model="first_name" readonly class="mt-1 block w-full rounded-md border-gray-300 shadow-sm bg-gray-100 cursor-not-allowed">
                </div>
                <div>
                    <label for="last_name" class="block text-sm font-medium text-gray-700">นามสกุล</label>
                    <input type="text" id="last_name" wire:model="last_name" readonly class="mt-1 block w-full rounded-md border-gray-300 shadow-sm bg-gray-100 cursor-not-allowed">
                </div>
            </div>
        </div>
        
        <div>
            <h3 class="text-lg font-medium text-gray-900 border-b pb-2 mb-4">Asset Details</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
                <div>
                    <label for="asset_number" class="block text-sm font-medium text-gray-700">Asset Number</label>
                    <input type="text" name="asset_number" id="asset_number" wire:model="asset_number" readonly class="mt-1 block w-full rounded-md border-gray-300 shadow-sm bg-gray-100 cursor-not-allowed">
                </div>
                <div></div>
                
                <div>
                    <label for="asset_category_id" class="block text-sm font-medium text-gray-700">Category</label>
                    <select id="asset_category_id" name="asset_category_id" wire:model.live="asset_category_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                        <option value="">-- Please Select a Category --</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                    @error('asset_category_id') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label for="asset_type_id" class="block text-sm font-medium text-gray-700">Type</label>
                    <select id="asset_type_id" name="asset_type_id" wire:model.live="asset_type_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                        @if(count($types) === 0)
                            <option value="">-- Select a Category First --</option>
                        @else
                            <option value="">-- Please Select a Type --</option>
                            @foreach($types as $type)
                                <option value="{{ $type->id }}">{{ $type->name }}</option>
                            @endforeach
                        @endif
                    </select>
                    @error('asset_type_id') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label for="brand_id" class="block text-sm font-medium text-gray-700">Brand</label>
                    <select id="brand_id" name="brand_id" wire:model="brand_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                         @if(count($brands) === 0)
                            <option value="">-- Select a Type First --</option>
                        @else
                            <option value="">-- Please Select a Brand --</option>
                            @foreach($brands as $brand)
                                <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                            @endforeach
                        @endif
                    </select>
                    @error('brand_id') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label for="status_id" class="block text-sm font-medium text-gray-700">สถานะ</label>
                    <select name="status_id" id="status_id" wire:model="status_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                        <option value="">-- Please Select --</option>
                        @foreach ($statuses as $status)
                            <option value="{{ $status->id }}">{{ $status->name }}</option>
                        @endforeach
                    </select>
                    @error('status_id') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>
                
                <div class="md:col-span-2">
                    <label for="location_id" class="block text-sm font-medium text-gray-700">Location</label>
                    <select name="location_id" id="location_id" wire:model="location_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                        <option value="">-- Select Location --</option>
                        @foreach ($locations as $location)
                            <option value="{{ $location->id }}">{{ $location->name }}</option>
                        @endforeach
                    </select>
                    @error('location_id') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>

                {{-- ▼▼▼ [แก้ไข] เปลี่ยน wire:model กลับไปใช้ชื่อเดิม ▼▼▼ --}}
                <div>
                    <label for="start_date" class="block text-sm font-medium text-gray-700">วันที่เริ่มใช้งาน</label>
                    <input type="date" name="start_date" id="start_date" wire:model="start_date" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                    @error('start_date') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label for="end_date" class="block text-sm font-medium text-gray-700">วันที่หมดประกัน</label>
                    <input type="date" name="end_date" id="end_date" wire:model="end_date" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    @error('end_date') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>
                
                <div class="md:col-span-2">
                    <label for="image" class="block text-sm font-medium text-gray-700">Asset Image</label>
                    <input type="file" name="image" id="image" wire:model="image" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-gray-100 file:text-gray-700 hover:file:bg-gray-200">
                    @if ($image)
                        <div class="mt-2">
                            <img src="{{ $image->temporaryUrl() }}" class="h-24 w-auto rounded border p-1">
                        </div>
                    @endif
                    @error('image') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>
    </div>

    <div class="mt-8 flex justify-end space-x-4 pt-4 border-t">
        <a href="{{ route('it_assets.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50">Cancel</a>
        <button type="submit" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700">
            <span wire:loading.remove>Save</span>
            <span wire:loading>Saving...</span>
        </button>
    </div>
</form>
