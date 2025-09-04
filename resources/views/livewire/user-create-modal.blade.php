<div>
    <div x-show="$wire.showModal" class="fixed inset-0 bg-gray-600 bg-opacity-75 overflow-y-auto h-full w-full flex items-center justify-center" x-cloak>
        <div @click.away="$wire.closeModal()" class="relative mx-auto p-6 border w-full max-w-4xl shadow-lg rounded-md bg-white">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl leading-6 font-bold text-gray-900">Add New User & Employee</h3>
                <button @click="$wire.closeModal()" class="text-gray-400 hover:text-gray-600">&times;</button>
            </div>
            <form wire:submit.prevent="save">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- ... (User Account fields) ... --}}
                    <div class="space-y-4">
                        <h4 class="text-lg font-semibold text-gray-700 border-b pb-2">User Account</h4>
                        <div><label class="block text-sm font-medium">Name</label><input wire:model="name" type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required> @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror</div>
                        <div><label class="block text-sm font-medium">Email</label><input wire:model="email" type="email" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required> @error('email') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror</div>
                        <div><label class="block text-sm font-medium">Password</label><input wire:model="password" type="password" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required> @error('password') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror</div>
                        <div><label class="block text-sm font-medium">Confirm Password</label><input wire:model="password_confirmation" type="password" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required></div>
                        <div><label class="block text-sm font-medium">Role</label><select wire:model="role" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"><option value="user">User</option><option value="admin">Admin</option></select></div>
                    </div>
                    
                    <div class="space-y-4">
                        <h4 class="text-lg font-semibold text-gray-700 border-b pb-2">Employee Details</h4>
                        {{-- ... (Employee Details fields other than dropdowns) ... --}}
                        <div class="grid grid-cols-2 gap-4">
                            <div><label class="block text-sm font-medium">Employee ID</label><input wire:model="employee_id" type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required> @error('employee_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror</div>
                            <div><label class="block text-sm font-medium">Phone Number</label><input wire:model="phone_number" type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"></div>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div><label class="block text-sm font-medium">First Name</label><input wire:model="first_name" type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required> @error('first_name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror</div>
                            <div><label class="block text-sm font-medium">Last Name</label><input wire:model="last_name" type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required> @error('last_name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror</div>
                        </div>

                        {{-- ▼▼▼ [แก้ไข] Position Dropdown ▼▼▼ --}}
                        <div>
                            <label class="block text-sm font-medium">Position</label>
                            {{-- ลบ .live ออกไป --}}
                            <select wire:model="selectedPosition" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                                <option value="">-- Select Position --</option>
                                @foreach($positions as $position)
                                    <option value="{{ $position->id }}">{{ $position->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            {{-- ▼▼▼ [แก้ไข] Department Dropdown ▼▼▼ --}}
                            <div>
                                <label class="block text-sm font-medium">Department</label>
                                {{-- เอา disabled และ bg-gray-100 ออก --}}
                                <select wire:model="selectedDepartment" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                                    <option value="">-- Select Department --</option>
                                    @foreach($departments as $department)
                                        <option value="{{ $department->id }}">{{ $department->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium">Location</label>
                                <select wire:model="location" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                    <option value="">-- Select --</option>
                                    @foreach($locations as $loc)
                                        <option value="{{ $loc->name }}">{{ $loc->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div><label class="block text-sm font-medium">Start Date</label><input wire:model="start_date" type="date" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required> @error('start_date') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror</div>
                    </div>
                </div>
                <div class="pt-6 mt-6 border-t flex justify-end space-x-3">
                    <button type="button" @click="$wire.closeModal()" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50">Cancel</button>
                    <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                        <div wire:loading.remove>Create User</div>
                        <div wire:loading>Creating...</div>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
