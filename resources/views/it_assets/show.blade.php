<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Asset Details: {{ $itAsset->asset_number }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                        
                        {{-- Asset Information Column --}}
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 border-b pb-3 mb-4">Asset Information</h3>
                            <dl class="space-y-4">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Asset Number</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $itAsset->asset_number }}</dd>
                                </div>
                                {{-- ▼▼▼ [แก้ไข] ดึงข้อมูลจาก Relationship ▼▼▼ --}}
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Type</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $itAsset->assetType->name ?? 'N/A' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Brand</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $itAsset->brand->name ?? 'N/A' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Status</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $itAsset->status->name ?? 'N/A' }}</dd>
                                </div>
                                @if($itAsset->location)
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Location</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $itAsset->location->name ?? 'N/A' }}</dd>
                                </div>
                                @endif
                                {{-- ▲▲▲ สิ้นสุดส่วนที่แก้ไข ▲▲▲ --}}
                            </dl>
                        </div>

                        {{-- User Information Column --}}
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 border-b pb-3 mb-4">User Information</h3>
                            <dl class="space-y-4">
                                @if ($itAsset->employee)
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Employee ID</dt>
                                        <dd class="mt-1 text-sm text-gray-900">{{ $itAsset->employee->employee_id }}</dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Full Name</dt>
                                        <dd class="mt-1 text-sm text-gray-900">{{ $itAsset->employee->first_name }} {{ $itAsset->employee->last_name }}</dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Position</dt>
                                        <dd class="mt-1 text-sm text-gray-900">{{ $itAsset->employee->position }}</dd>
                                    </div>
                                @else
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Assigned To</dt>
                                        <dd class="mt-1 text-sm text-gray-500">Unassigned</dd>
                                    </div>
                                @endif
                                {{-- ▼▼▼ [แก้ไข] เปลี่ยนชื่อคอลัมน์ให้ถูกต้อง ▼▼▼ --}}
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Purchase Date</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ optional($itAsset->purchase_date)->format('Y-m-d') ?? '-' }}</dd>
                                </div>
                                 <div>
                                    <dt class="text-sm font-medium text-gray-500">Warranty End Date</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ optional($itAsset->warranty_end_date)->format('Y-m-d') ?? '-' }}</dd>
                                </div>
                                {{-- ▲▲▲ สิ้นสุดส่วนที่แก้ไข ▲▲▲ --}}
                            </dl>
                        </div>
                    </div>

                    {{-- Image Display --}}
                    @if ($itAsset->image_path)
                        <div class="mt-6 border-t border-gray-200 pt-6">
                            <h3 class="text-lg font-medium text-gray-900">Asset Image</h3>
                            <div class="mt-4">
                                <img src="{{ Storage::url($itAsset->image_path) }}" alt="Asset Image" class="rounded-md max-h-96">
                            </div>
                        </div>
                    @endif

                    {{-- History Sections --}}
                    <div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-8">
                        <!-- Repair History -->
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 border-b pb-3 mb-4">Repair History</h3>
                            <div class="space-y-4">
                                @forelse($repairHistory as $history)
                                    <div>
                                        <p class="font-semibold">{{ $history->created_at->format('Y-m-d') }} - <span class="font-normal">{{ $history->problem_description }}</span></p>
                                        <p class="text-sm text-gray-500">Status: {{ $history->status }} | Reported by: {{ $history->user->name }}</p>
                                    </div>
                                @empty
                                    <p class="text-gray-500">No repair history found.</p>
                                @endforelse
                            </div>
                        </div>
                        <!-- Update History -->
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 border-b pb-3 mb-4">Update History</h3>
                            <div class="space-y-4">
                                @forelse($updateHistory as $history)
                                     <div>
                                        <p class="font-semibold">{{ $history->created_at->format('Y-m-d H:i') }} - <span class="font-normal">{{ $history->description }}</span></p>
                                        <p class="text-sm text-gray-500">Action by: {{ $history->user->name ?? 'System' }}</p>
                                    </div>
                                @empty
                                    <p class="text-gray-500">No update history found.</p>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    <div class="mt-6 border-t border-gray-200 pt-6 flex items-center justify-end space-x-4">
                         <a href="{{ route('it_assets.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-800 uppercase tracking-widest hover:bg-gray-300">
                            Back to List
                        </a>
                        <button onclick="Livewire.dispatch('showItAssetEditModal', { assetId: {{ $itAsset->id }} })" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                            Edit Asset
                        </button>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
