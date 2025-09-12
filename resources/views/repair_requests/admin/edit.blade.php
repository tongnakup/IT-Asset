<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Update Repair Request Status
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('repair_requests.update', $repairRequest->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <p><strong>Submitted by:</strong> {{ $repairRequest->user->name }}</p>
                            <p><strong>Asset Number:</strong> {{ $repairRequest->asset_number ?? 'N/A' }}</p>
                            <p><strong>Problem:</strong> {{ $repairRequest->problem_description }}</p>
                        </div>

                        @if ($repairRequest->image_path)
                            <div class="mb-4">
                                <p class="font-semibold text-gray-700">Image Evidence:</p>
                                <div class="mt-2">
                                    <a href="{{ Storage::url($repairRequest->image_path) }}" target="_blank"
                                        title="Click to view full image">
                                        <img src="{{ Storage::url($repairRequest->image_path) }}"
                                            alt="Repair Request Image"
                                            class="rounded-md max-h-96 border p-1 hover:shadow-lg transition-shadow">
                                    </a>
                                </div>
                            </div>
                        @endif

                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                            <select name="status" id="status"
                                class="mt-1 block w-full md:w-1/3 rounded-md border-gray-300 shadow-sm">
                                @foreach ($statuses as $status)
                                    <option value="{{ $status }}"
                                        {{ $repairRequest->status == $status ? 'selected' : '' }}>
                                        {{ $status }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mt-6 flex justify-end space-x-2">
                            <a href="{{ route('repair_requests.index') }}"
                                class="px-4 py-2 bg-gray-200 rounded-md">Cancel</a>
                            <button type="submit" class="px-4 py-2 bg-gray-800 text-white rounded-md">Update
                                Status</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
