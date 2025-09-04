<form wire:submit.prevent="saveEmployee">
    @csrf

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <label for="employee_id" class="block font-medium text-sm text-gray-700">Employee ID</label>
            <input id="employee_id" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm" type="text" wire:model="employee_id" required autofocus />
            @error('employee_id') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
        </div>

        <div>
            <label for="user_id" class="block font-medium text-sm text-gray-700">User Account (ID)</label>
            <input id="user_id" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm" type="number" wire:model="user_id" required />
            @error('user_id') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
        </div>

        <div>
            <label for="first_name" class="block font-medium text-sm text-gray-700">First Name</label>
            <input id="first_name" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm" type="text" wire:model="first_name" required />
            @error('first_name') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
        </div>

        <div>
            <label for="last_name" class="block font-medium text-sm text-gray-700">Last Name</label>
            <input id="last_name" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm" type="text" wire:model="last_name" required />
            @error('last_name') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
        </div>
        
        {{-- ▼▼▼ [แก้ไข] เปลี่ยนเป็น Dropdown ▼▼▼ --}}
        <div>
            <label for="position" class="block font-medium text-sm text-gray-700">Position</label>
            <select id="position" wire:model.live="selectedPosition" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm" required>
                <option value="">-- Select Position --</option>
                @foreach($positions as $position)
                    <option value="{{ $position->id }}">{{ $position->name }}</option>
                @endforeach
            </select>
            @error('selectedPosition') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
        </div>
        
        {{-- ▼▼▼ [แก้ไข] เปลี่ยนเป็น Dropdown ที่ถูกควบคุม ▼▼▼ --}}
        <div>
            <label for="department" class="block font-medium text-sm text-gray-700">Department</label>
            <select id="department" wire:model="selectedDepartment" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm bg-gray-100" required disabled>
                <option value="">-- Automatically selected --</option>
                @foreach($departments as $department)
                    <option value="{{ $department->id }}">{{ $department->name }}</option>
                @endforeach
            </select>
            @error('selectedDepartment') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
        </div>

        <div>
            <label for="phone_number" class="block font-medium text-sm text-gray-700">Phone Number</label>
            <input id="phone_number" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm" type="text" wire:model="phone_number" />
        </div>

        <div>
            <label for="start_date" class="block font-medium text-sm text-gray-700">Start Date</label>
            <input id="start_date" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm" type="date" wire:model="start_date" required />
            @error('start_date') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
        </div>
    </div>

    <div class="flex items-center justify-end mt-6">
        <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
            Save Employee
        </button>
    </div>
</form>
