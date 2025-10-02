<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manage Repair Requests') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8" x-data="repairRequestManager()">

            @if (session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative"
                    role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Submitted By
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Asset Number
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Problem
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Status
                                    </th>

                                    <th
                                        class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($requests as $request)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $request->user->name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $request->asset_number ?? 'N/A' }}
                                        </td>
                                        <td class="px-6 py-4">{{ Str::limit($request->problem_description, 40) }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @php
                                                $statusClass = match ($request->status) {
                                                    'Resolved' => 'bg-green-100 text-green-800',
                                                    'In Progress' => 'bg-yellow-100 text-yellow-800',
                                                    'Rejected' => 'bg-red-100 text-red-800',
                                                    default => 'bg-gray-100 text-gray-800',
                                                };
                                            @endphp
                                            <span
                                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusClass }}">
                                                {{ $request->status }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <div class="flex items-center justify-end space-x-4">

                                                <a href="{{ route('repair_requests.edit', $request->id) }}"
                                                    class="text-yellow-600 hover:text-yellow-900" title="Edit Status">
                                                    <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                                        <path
                                                            d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z">
                                                        </path>
                                                    </svg>
                                                </a>

                                                <form id="delete-request-{{ $request->id }}"
                                                    action="{{ route('repair_requests.destroy', $request->id) }}"
                                                    method="POST" class="hidden">
                                                    @csrf
                                                    @method('DELETE')
                                                </form>
                                                <button type="button"
                                                    @click="openDeleteModal('delete-request-{{ $request->id }}')"
                                                    class="text-red-600 hover:text-red-900" title="Delete">
                                                    <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd"
                                                            d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                                                            clip-rule="evenodd"></path>
                                                    </svg>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-16 text-center">
                                            <div class="flex flex-col items-center justify-center text-gray-500">
                                                <svg class="h-16 w-16 text-gray-400 mb-4"
                                                    xmlns="http://www.w3.org/2000/svg" fill="none"
                                                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
                                                </svg>
                                                <h3 class="text-xl font-semibold text-gray-700">ยังไม่มีรายการแจ้งซ่อม
                                                </h3>
                                                <p class="mt-2 text-sm">
                                                    เมื่อมีผู้ใช้แจ้งซ่อมสินทรัพย์เข้ามา<br>รายการทั้งหมดจะแสดงอยู่ที่นี่
                                                </p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <x-modal name="confirm-request-deletion" focusable>
                <div class="p-6 text-center">
                    <svg class="mx-auto mb-4 text-gray-400 w-12 h-12" aria-hidden="true"
                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 11V6m0 8h.01M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                    <h2 class="mb-5 text-lg font-normal text-gray-500">
                        Are you sure you want to delete this repair request?
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

        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('alpine:init', () => {
                Alpine.data('repairRequestManager', () => ({
                    formIdToDelete: null,
                    openDeleteModal(formId) {
                        this.formIdToDelete = formId;
                        this.$dispatch('open-modal', 'confirm-request-deletion');
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
