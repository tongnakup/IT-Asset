<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('User Management') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8" x-data="userPageManager()">

            <div>
                @if (session()->has('success'))
                    <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative"
                        role="alert">
                        <span class="block sm:inline">{{ session('success') }}</span>
                    </div>
                @endif

                <div class="flex justify-end mb-4">
                    <button @click="$dispatch('openCreateModal')"
                        class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                        + Add New User
                    </button>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Name</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Email</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Employee ID</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Position</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Role</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @forelse ($users as $user)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ $user->name }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ $user->email }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ $user->employee?->employee_id }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ $user->employee?->position }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span
                                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $user->role == 'admin' ? 'bg-indigo-100 text-indigo-800' : 'bg-gray-100 text-gray-800' }}">
                                                    {{ ucfirst($user->role) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <div class="flex items-center justify-start space-x-4">
                                                    {{-- ▼▼▼ [ 1. เปลี่ยนปุ่ม Edit เป็นไอคอนดินสอ ] ▼▼▼ --}}
                                                    <button
                                                        onclick="Livewire.dispatch('showUserEditModal', { userId: {{ $user->id }} })"
                                                        class="text-yellow-600 hover:text-yellow-900" title="Edit">
                                                        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                                            <path
                                                                d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z">
                                                            </path>
                                                        </svg>
                                                    </button>

                                                    @if (auth()->id() != $user->id)
                                                        {{-- ▼▼▼ [ 2. เปลี่ยนปุ่ม Delete เป็นไอคอนถังขยะ ] ▼▼▼ --}}
                                                        <form id="delete-form-{{ $user->id }}"
                                                            action="{{ route('users.destroy', $user->id) }}"
                                                            method="POST" class="hidden">
                                                            @csrf
                                                            @method('DELETE')
                                                        </form>
                                                        <button type="button"
                                                            @click="openDeleteModal({{ $user->id }})"
                                                            class="text-red-600 hover:text-red-900" title="Delete">
                                                            <svg class="h-5 w-5" fill="currentColor"
                                                                viewBox="0 0 20 20">
                                                                <path fill-rule="evenodd"
                                                                    d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                                                                    clip-rule="evenodd"></path>
                                                            </svg>
                                                        </button>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="px-6 py-4 text-center text-gray-500">No users
                                                found.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-4 px-6 pb-4">{{ $users->links() }}</div>
                    </div>
                </div>

                <x-modal name="confirm-user-deletion" focusable>
                    <div class="p-6">
                        <h2 class="text-lg font-medium text-gray-900">
                            {{ __('Are you sure you want to delete this user?') }}
                        </h2>
                        <p class="mt-1 text-sm text-gray-600">
                            {{ __('Once this user is deleted, all of their related data will be permanently removed. This action cannot be undone.') }}
                        </p>
                        <div class="mt-6 flex justify-end">
                            <x-secondary-button x-on:click="$dispatch('close')">
                                {{ __('Cancel') }}
                            </x-secondary-button>
                            <x-danger-button class="ms-3" @click="confirmDelete()">
                                {{ __('Delete User') }}
                            </x-danger-button>
                        </div>
                    </div>
                </x-modal>

                <livewire:user-create-modal />
                <livewire:user-edit-modal />
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('alpine:init', () => {
                Alpine.data('userPageManager', () => ({
                    userToDeleteId: null,
                    openDeleteModal(userId) {
                        this.userToDeleteId = userId;
                        this.$dispatch('open-modal', 'confirm-user-deletion');
                    },
                    confirmDelete() {
                        if (this.userToDeleteId) {
                            const form = document.getElementById('delete-form-' + this.userToDeleteId);
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
