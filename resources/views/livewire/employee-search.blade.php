<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    
    <div>
        <label for="search" class="block text-sm font-medium text-gray-700">ID พนักงาน</label>
        <div class="relative mt-1">
            <input type="text" 
                   id="search"
                   wire:model.live.debounce.300ms="search"
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
            {{-- ▼▼▼ แก้ไขเงื่อนไขตรงนี้ โดยเพิ่ม && empty($first_name) เข้าไป ▼▼▼ --}}
            @elseif(strlen($search) > 0 && count($searchResults) == 0 && empty($first_name))
                 <div class="absolute z-10 w-full mt-1 bg-white border border-gray-300 rounded-md shadow-lg">
                    <div class="px-4 py-2 text-gray-500">ไม่พบข้อมูล</div>
                </div>
            @endif
        </div>
        <input type="hidden" name="employee_id" value="{{ $selected_employee_id }}">
    </div>

    <div>
        <label for="position" class="block text-sm font-medium text-gray-700">ตำแหน่ง</label>
        <input type="text" name="position" id="position" wire:model="position" readonly class="mt-1 block w-full rounded-md border-gray-300 shadow-sm bg-gray-100 cursor-not-allowed">
    </div>

    <div>
        <label for="first_name" class="block text-sm font-medium text-gray-700">ชื่อ</label>
        <input type="text" name="first_name" id="first_name" wire:model="first_name" readonly class="mt-1 block w-full rounded-md border-gray-300 shadow-sm bg-gray-100 cursor-not-allowed">
    </div>

    <div>
        <label for="last_name" class="block text-sm font-medium text-gray-700">นามสกุล</label>
        <input type="text" name="last_name" id="last_name" wire:model="last_name" readonly class="mt-1 block w-full rounded-md border-gray-300 shadow-sm bg-gray-100 cursor-not-allowed">
    </div>
</div>
