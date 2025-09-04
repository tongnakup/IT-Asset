<div class="relative">
    <input type="text" name="asset_number" id="asset_number"
        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
        placeholder="พิมพ์เพื่อค้นหา Asset Number, Type, Brand, User" wire:model.live.debounce.300ms="query"
        autocomplete="off" value="{{ old('asset_number') }}">

    @if (!empty($assets) && strlen($query) >= 2)
        <ul
            class="absolute z-10 w-full bg-white border border-gray-300 rounded-md mt-1 shadow-lg max-h-60 overflow-y-auto">
            @forelse($assets as $asset)
                <li class="px-4 py-3 cursor-pointer hover:bg-gray-100 border-b last:border-b-0"
                    wire:click="selectAsset('{{ $asset->asset_number }}')">
                    <div class="flex justify-between items-center">
                        <div>
                            <p class="font-semibold text-gray-800">{{ $asset->asset_number }}</p>
                            <p class="text-sm text-gray-500">{{ $asset->type }} - {{ $asset->brand }}</p>
                        </div>
                        <div class="text-right text-sm">
                            @if ($asset->employee)
                                <p class="text-gray-600">
                                    {{ $asset->employee->first_name }}
                                </p>
                            @else
                                <p class="text-gray-400 italic">Unassigned</p>
                            @endif
                        </div>
                    </div>
                </li>
            @empty
                <li class="px-4 py-2 text-gray-500">No results found for '<span
                        class="font-semibold">{{ $query }}</span>'</li>
            @endforelse
        </ul>
    @endif
</div>
