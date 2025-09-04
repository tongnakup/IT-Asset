<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Add New IT Asset') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 md:p-8 text-gray-900">

                    {{-- เรียกใช้ Livewire Component ที่รวมทุกอย่างไว้แล้ว --}}
                    <livewire:create-asset-form />

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
