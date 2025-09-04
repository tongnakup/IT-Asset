<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Add New User & Employee') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <!-- Display Validation Errors -->
                    @if ($errors->any())
                        <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative"
                            role="alert">
                            <strong class="font-bold">Oops! Something went wrong.</strong>
                            <ul class="mt-2 list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('users.store') }}" method="POST">
                        @csrf

                        <!-- User Account Details -->
                        <div class="mb-8">
                            <h3 class="text-lg font-medium text-gray-900 border-b pb-2 mb-4">User Account Details</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="name" class="block text-sm font-medium text-gray-700">Name (For
                                        Login)</label>
                                    <input type="text" name="name" id="name" value="{{ old('name') }}"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                                </div>
                                <div>
                                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                                    <input type="email" name="email" id="email" value="{{ old('email') }}"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                                </div>
                                <div>
                                    <label for="password"
                                        class="block text-sm font-medium text-gray-700">Password</label>
                                    <input type="password" name="password" id="password"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                                </div>
                                <div>
                                    <label for="password_confirmation"
                                        class="block text-sm font-medium text-gray-700">Confirm Password</label>
                                    <input type="password" name="password_confirmation" id="password_confirmation"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                                </div>
                                <div>
                                    <label for="role" class="block text-sm font-medium text-gray-700">Role</label>
                                    <select name="role" id="role"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                        <option value="user" @selected(old('role') == 'user')>User</option>
                                        <option value="admin" @selected(old('role') == 'admin')>Admin</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Employee Details -->
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 border-b pb-2 mb-4">Employee Details</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="employee_id" class="block text-sm font-medium text-gray-700">Employee
                                        ID</label>
                                    <input type="text" name="employee_id" id="employee_id"
                                        value="{{ old('employee_id') }}"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                                </div>
                                <div>
                                    <label for="phone_number" class="block text-sm font-medium text-gray-700">Phone
                                        Number</label>
                                    <input type="text" name="phone_number" id="phone_number"
                                        value="{{ old('phone_number') }}"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                </div>
                                <div>
                                    <label for="first_name" class="block text-sm font-medium text-gray-700">First
                                        Name</label>
                                    <input type="text" name="first_name" id="first_name"
                                        value="{{ old('first_name') }}"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                                </div>
                                <div>
                                    <label for="last_name" class="block text-sm font-medium text-gray-700">Last
                                        Name</label>
                                    <input type="text" name="last_name" id="last_name" value="{{ old('last_name') }}"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                                </div>
                                <div>
                                    <label for="position"
                                        class="block text-sm font-medium text-gray-700">Position</label>
                                    <select name="position" id="position"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                        <option value="" disabled selected>-- Please Select --</option>
                                        @foreach ($positions as $position)
                                            <option value="{{ $position }}" @selected(old('position') == $position)>
                                                {{ $position }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label for="department"
                                        class="block text-sm font-medium text-gray-700">Department</label>
                                    <select name="department" id="department"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                        <option value="" disabled selected>-- Please Select --</option>
                                        @foreach ($departments as $department)
                                            <option value="{{ $department }}" @selected(old('department') == $department)>
                                                {{ $department }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label for="location"
                                        class="block text-sm font-medium text-gray-700">Location</label>
                                    <select name="location" id="location"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                        <option value="" disabled selected>-- Please Select --</option>
                                        @foreach ($locations as $location)
                                            <option value="{{ $location }}" @selected(old('location') == $location)>
                                                {{ $location }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label for="start_date" class="block text-sm font-medium text-gray-700">Start
                                        Date</label>
                                    <input type="date" name="start_date" id="start_date"
                                        value="{{ old('start_date') }}"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-end space-x-4 mt-8 pt-4 border-t">
                            <a href="{{ route('users.index') }}"
                                class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50">Cancel</a>
                            <button type="submit"
                                class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">Create
                                User</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
