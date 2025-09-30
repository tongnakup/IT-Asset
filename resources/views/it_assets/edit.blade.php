<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Edit Asset: {{ $itAsset->asset_number }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if ($errors->any())
                        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative"
                            role="alert">
                            {{-- ... Error messages ... --}}
                        </div>
                    @endif

                    <form action="{{ route('it_assets.update', $itAsset->id) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="employee_id" class="block text-sm font-medium text-gray-700">ID
                                    พนักงาน</label>
                                <input type="text" name="employee_id" id="employee_id"
                                    value="{{ old('employee_id', $itAsset->employee_id) }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                            </div>
                            <div>
                                <label for="position" class="block text-sm font-medium text-gray-700">ตำแหน่ง</label>
                                <input type="text" name="position" id="position"
                                    value="{{ old('position', $itAsset->position) }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                            </div>
                            <div>
                                <label for="first_name" class="block text-sm font-medium text-gray-700">ชื่อ</label>
                                <input type="text" name="first_name" id="first_name"
                                    value="{{ old('first_name', $itAsset->first_name) }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                            </div>
                            <div>
                                <label for="last_name" class="block text-sm font-medium text-gray-700">นามสกุล</label>
                                <input type="text" name="last_name" id="last_name"
                                    value="{{ old('last_name', $itAsset->last_name) }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                            </div>
                            <div>
                                <label for="brand" class="block text-sm font-medium text-gray-700">ยี่ห้อ</label>
                                <input type="text" name="brand" id="brand"
                                    value="{{ old('brand', $itAsset->brand) }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                            </div>
                            <div>
                                <label for="asset_number" class="block text-sm font-medium text-gray-700">Asset
                                    Number</label>
                                <input type="text" name="asset_number" id="asset_number"
                                    value="{{ old('asset_number', $itAsset->asset_number) }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                            </div>

                            <div class="md:col-span-2">
                                <label for="image" class="block text-sm font-medium text-gray-700">Asset
                                    Image</label>
                                <input type="file" name="image" id="image"
                                    class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-gray-100 file:text-gray-700 hover:file:bg-gray-200">
                                <p class="text-xs text-gray-500 mt-1">Leave blank to keep the current image.</p>

                                @if ($itAsset->image_path)
                                    <div class="mt-4">
                                        <p class="text-sm font-medium text-gray-700">Current Image:</p>
                                        <img src="{{ asset('uploads/' . $itAsset->image_path) }}"
                                            alt="Current Asset Image" class="mt-2 rounded-md max-h-48">
                                    </div>
                                @endif
                            </div>

                            <div>
                                <label for="type" class="block text-sm font-medium text-gray-700">ประเภท</label>
                                <select name="type" id="type"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                                    @foreach ($types as $type)
                                        <option value="{{ $type }}"
                                            {{ old('type', $itAsset->type) == $type ? 'selected' : '' }}>
                                            {{ $type }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700">สถานะ</label>
                                <select name="status" id="status"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                                    @foreach ($statuses as $status)
                                        <option value="{{ $status }}"
                                            {{ old('status', $itAsset->status) == $status ? 'selected' : '' }}>
                                            {{ $status }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <!-- ===== เพิ่มช่อง Location แบบมีเงื่อนไข ===== -->
                                <div class="md:col-span-2" x-show="status === 'In Use'" x-transition>
                                    <label for="location" class="block text-sm font-medium text-gray-700">พื้นที่ใช้งาน
                                        (Location)</label>
                                    <input type="text" name="location" id="location" value="{{ old('location') }}"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
                                        placeholder="e.g., Office Floor 2, Zone A">
                                </div>

                                <label for="start_date"
                                    class="block text-sm font-medium text-gray-700">วันที่เริ่มใช้งาน</label>
                                <input type="date" name="start_date" id="start_date"
                                    value="{{ old('start_date', $itAsset->start_date) }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                            </div>
                            <div>
                                <label for="end_date"
                                    class="block text-sm font-medium text-gray-700">วันที่เลิกใช้</label>
                                <input type="date" name="end_date" id="end_date"
                                    value="{{ old('end_date', $itAsset->end_date) }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                            </div>
                        </div>

                        <div class="mt-6 flex justify-end space-x-4">
                            <a href="{{ route('it_assets.index') }}"
                                class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-800 uppercase tracking-widest hover:bg-gray-300">
                                Cancel
                            </a>
                            <button type="submit" onclick="this.disabled=true; this.form.submit();"
                                class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 active:bg-green-800 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Update
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
