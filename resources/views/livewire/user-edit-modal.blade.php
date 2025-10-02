<div>
    @if ($showModal)
        <div class="fixed inset-0 bg-gray-600 bg-opacity-75 overflow-y-auto h-full w-full flex items-center justify-center"
            x-cloak>
            <div class="relative mx-auto p-6 border w-full max-w-4xl shadow-lg rounded-md bg-white"
                @click.away="$wire.closeModal()">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl leading-6 font-bold text-gray-900">Edit User & Employee</h3>
                    <button wire:click="closeModal" class="text-gray-400 hover:text-gray-600">&times;</button>
                </div>
                <form wire:submit.prevent="update">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- User Account Details --}}
                        <div class="space-y-4">
                            <h4 class="text-lg font-semibold text-gray-700 border-b pb-2">User Account</h4>
                            <div>
                                <label for="name" class="block text-sm font-medium">Name</label>
                                <input id="name" wire:model.defer="name" type="text"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm @error('name') border-red-500 @enderror">
                                @error('name')
                                    <span class="text-red-500 text-xs">{{ $message }}</span>
                                @enderror
                            </div>
                            <div>
                                <label for="email" class="block text-sm font-medium">Email</label>
                                <input id="email" wire:model.defer="email" type="email"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm @error('email') border-red-500 @enderror">
                                @error('email')
                                    <span class="text-red-500 text-xs">{{ $message }}</span>
                                @enderror
                            </div>
                            <div>
                                <label for="role" class="block text-sm font-medium">Role</label>
                                <select id="role" wire:model.defer="role"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm @error('role') border-red-500 @enderror">
                                    @foreach ($roles as $roleOption)
                                        <option value="{{ $roleOption }}">{{ ucfirst($roleOption) }}</option>
                                    @endforeach
                                </select>
                                @error('role')
                                    <span class="text-red-500 text-xs">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        {{-- Employee Details --}}
                        <div class="space-y-4">
                            <h4 class="text-lg font-semibold text-gray-700 border-b pb-2">Employee Details</h4>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label for="employee_id" class="block text-sm font-medium">Employee ID</label>
                                    <input id="employee_id" wire:model.defer="employeeData.employee_id" type="text"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm @error('employeeData.employee_id') border-red-500 @enderror">
                                    @error('employeeData.employee_id')
                                        <span class="text-red-500 text-xs">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div>
                                    <label for="phone_number" class="block text-sm font-medium">Phone Number</label>
                                    <input id="phone_number" wire:model.defer="employeeData.phone_number" type="text"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                </div>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label for="first_name" class="block text-sm font-medium">First Name</label>
                                    <input id="first_name" wire:model.defer="employeeData.first_name" type="text"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm @error('employeeData.first_name') border-red-500 @enderror">
                                    @error('employeeData.first_name')
                                        <span class="text-red-500 text-xs">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div>
                                    <label for="last_name" class="block text-sm font-medium">Last Name</label>
                                    <input id="last_name" wire:model.defer="employeeData.last_name" type="text"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm @error('employeeData.last_name') border-red-500 @enderror">
                                    @error('employeeData.last_name')
                                        <span class="text-red-500 text-xs">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            {{-- ▼▼▼ [ แก้ไขส่วนนี้ทั้งหมด ] ▼▼▼ --}}
                            <div>
                                <label for="position" class="block text-sm font-medium">Position</label>
                                <select id="position" wire:model.live="selectedPosition"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                    <option value="">-- Select --</option>
                                    @foreach ($positions as $position)
                                        <option value="{{ $position->id }}">{{ $position->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label for="department" class="block text-sm font-medium">Department</label>
                                    <select id="department" wire:model="selectedDepartment"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm bg-gray-100 cursor-not-allowed"
                                        disabled>
                                        <option value="">-- Auto Select --</option>
                                        @foreach ($departments as $department)
                                            <option value="{{ $department->id }}">{{ $department->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label for="location" class="block text-sm font-medium">Location</label>
                                    <select id="location" wire:model.defer="employeeData.location"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                        <option value="">-- Select --</option>
                                        @foreach ($locations as $location)
                                            <option value="{{ $location->name }}">{{ $location->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            {{-- ▲▲▲ [ จบส่วนที่แก้ไข ] ▲▲▲ --}}
                            <div>
                                <label for="start_date" class="block text-sm font-medium">Start Date</label>
                                <input id="start_date" wire:model.defer="employeeData.start_date" type="date"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm @error('employeeData.start_date') border-red-500 @enderror">
                                @error('employeeData.start_date')
                                    <span class="text-red-500 text-xs">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="pt-6 mt-6 border-t flex justify-end space-x-3">
                        <button type="button" wire:click="closeModal"
                            class="bg-red-600 py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white hover:bg-red-700">Cancel</button>
                        <button type="submit"
                            class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700">
                            <span wire:loading.remove>Update User</span>
                            <span wire:loading>Updating...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
