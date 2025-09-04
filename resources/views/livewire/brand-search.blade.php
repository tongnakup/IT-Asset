<div class="relative">
    <input 
        type="text" 
        name="brand"
        id="brand"
        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
        placeholder="พิมพ์เพื่อค้นหายี่ห้อ..."
        wire:model.live.debounce.300ms="query"
        autocomplete="off"
        required
    >

    {{-- ▼▼▼ แก้ไขเงื่อนไขตรงนี้ ▼▼▼ --}}
    @if(!empty($brands))
        <ul class="absolute z-10 w-full bg-white border border-gray-300 rounded-md mt-1 shadow-lg max-h-40 overflow-y-auto">
            @forelse($brands as $brand)
                <li 
                    class="px-4 py-2 cursor-pointer hover:bg-gray-100"
                    wire:click="selectBrand('{{ $brand }}')"
                >
                    {{ $brand }}
                </li>
            @empty
                 <li class="px-4 py-2 text-gray-500">ไม่พบยี่ห้อ</li>
            @endforelse
        </ul>
    @endif
</div>
