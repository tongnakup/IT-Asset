<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if ($latestAnnouncement)
                <div x-data="{ show: !sessionStorage.getItem('dismissed_announcement_{{ $latestAnnouncement->id }}') }" x-show="show" x-transition
                    class="mb-6 bg-indigo-600 text-white p-4 rounded-lg shadow-lg flex items-center justify-between">

                    <div class="flex items-center">
                        <svg class="h-6 w-6 mr-3 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-2.236 9.168-5.584C18.354 1.85 19.17 1 20 1c.828 0 1.5.85 1.5 1.75v11.5c0 .9-1.01 1.488-1.729 1.026C19.11 14.52 18.29 14 17.5 14c-.828 0-1.5.85-1.5 1.75v5.5c0 .9-1.01 1.488-1.729 1.026C13.11 21.52 12.29 21 11.5 21c-.828 0-1.5.85-1.5 1.75v.25c0 .9-1.01 1.488-1.729 1.026C7.11 23.52 6.29 23 5.5 23c-.828 0-1.5-.85-1.5-1.75v-11.5c0-.9 1.01-1.488 1.729-1.026C6.11 9.48 6.709 10 7.5 10c.828 0 1.5-.85 1.5-1.75v-5.5z" />
                        </svg>
                        <div>
                            <p class="font-bold">{{ $latestAnnouncement->title }}</p>
                            <p class="text-sm">{{ $latestAnnouncement->content }}</p>
                        </div>
                    </div>

                    <button
                        @click="show = false; sessionStorage.setItem('dismissed_announcement_{{ $latestAnnouncement->id }}', true)"
                        class="text-indigo-200 hover:text-white ml-4 flex-shrink-0">
                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            @endif

            {{-- ================== ADMIN DASHBOARD ================== --}}
            @if (auth()->user()->role == 'admin')

                {{-- General Summary Cards --}}
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <div class="bg-blue-500 text-white p-6 rounded-lg shadow-lg">
                        <h3 class="text-lg font-semibold text-blue-100">Total Assets</h3>
                        <p class="text-4xl font-bold mt-2">{{ $stats['total'] }}</p>
                    </div>
                    <div class="bg-orange-500 text-white p-6 rounded-lg shadow-lg">
                        <h3 class="text-lg font-semibold text-orange-100">Pending Requests</h3>
                        <p class="text-4xl font-bold mt-2">{{ $stats['pending_requests'] }}</p>
                    </div>
                </div>

                {{-- ▼▼▼ [START] แก้ไขการแสดงผลการ์ด ▼▼▼ --}}
                @foreach ($categoryStats as $category)
                    <div class="mt-10">
                        <h3 class="text-2xl font-semibold text-gray-800 mb-4">{{ $category['name'] }}</h3>

                        <div class="flex flex-wrap -mx-3">
                            @forelse ($category['types'] as $index => $type)
                                @php
                                    // กำหนดชุดสีที่เข้มและมองเห็นชัดเจน
                                    $cardColors = [
                                        'bg-blue-500',
                                        'bg-red-500',
                                        'bg-green-500',
                                        'bg-purple-500',
                                        'bg-teal-500',
                                        'bg-pink-500',
                                        'bg-orange-500',
                                        'bg-cyan-500',
                                    ];
                                    $colorClass = $cardColors[$index % count($cardColors)];
                                @endphp
                                <div class="w-full sm:w-1/2 lg:w-1/4 px-3 mb-6">
                                    <div class="{{ $colorClass }} text-white p-6 rounded-lg shadow-lg h-full">
                                        <h4 class="text-lg font-semibold opacity-75">{{ $type['name'] }}</h4>
                                        <p class="text-4xl font-bold mt-2">{{ $type['count'] }}</p>
                                    </div>
                                </div>
                            @empty
                                <div class="w-full px-3">
                                    <p class="text-center text-gray-500 py-4">No asset types found in this category.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                @endforeach
                {{-- ▲▲▲ [END] จบส่วนแสดงผล ▲▲▲ --}}

                {{-- Charts --}}
                <div class="mt-8 grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <div class="bg-white p-6 rounded-lg shadow-lg">
                        <h3 class="text-lg font-semibold text-gray-800">Assets by Type</h3>
                        <div class="relative mx-auto h-80 w-80"><canvas id="assetTypeChart"></canvas></div>
                    </div>
                    <div class="bg-white p-6 rounded-lg shadow-lg">
                        <h3 class="text-lg font-semibold text-gray-800">Assets by Brand</h3>
                        <div class="relative h-80"><canvas id="assetBrandChart"></canvas></div>
                    </div>
                    <div class="bg-white p-6 rounded-lg shadow-lg lg:col-span-2">
                        <h3 class="text-lg font-semibold text-gray-800">Assets by Status</h3>
                        <div class="relative mx-auto h-80 w-80"><canvas id="assetStatusChart"></canvas></div>
                    </div>
                </div>

                {{-- ================== USER DASHBOARD ================== --}}
            @else
                <div class="space-y-8">
                    <div class="p-6 bg-white rounded-lg shadow-sm">
                        <h2 class="text-2xl font-semibold text-gray-800">
                            Welcome back, <span class="text-indigo-600">{{ Auth::user()->name }}</span>!
                        </h2>
                        <p class="mt-1 text-gray-600">Here's a summary of your IT assets and requests.</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="bg-white p-6 rounded-lg shadow-sm flex items-center">
                            <div class="bg-blue-100 p-3 rounded-full">
                                <svg class="h-6 w-6 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-sm font-medium text-gray-500">My Assets</h3>
                                <p class="text-2xl font-bold text-gray-800">{{ $userStats['totalAssets'] }}</p>
                            </div>
                        </div>
                        <div class="bg-white p-6 rounded-lg shadow-sm flex items-center">
                            <div class="bg-yellow-100 p-3 rounded-full">
                                <svg class="h-6 w-6 text-yellow-600" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-sm font-medium text-gray-500">Pending Requests</h3>
                                <p class="text-2xl font-bold text-gray-800">{{ $userStats['pendingRequests'] }}</p>
                            </div>
                        </div>
                        <div class="bg-white p-6 rounded-lg shadow-sm flex items-center">
                            <div class="bg-green-100 p-3 rounded-full">
                                <svg class="h-6 w-6 text-green-600" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-sm font-medium text-gray-500">Resolved Requests</h3>
                                <p class="text-2xl font-bold text-gray-800">{{ $userStats['resolvedRequests'] }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">

                        <div class="lg:col-span-2 bg-white rounded-lg shadow-sm">
                            <div class="p-6">
                                <h3 class="text-lg font-semibold text-gray-800 border-b pb-3 mb-4">My IT Assets</h3>
                                <div class="space-y-2">
                                    @forelse($userAssets as $asset)
                                        {{-- ▼▼▼ [ADDED] เพิ่ม Hover Effect ที่นี่ ▼▼▼ --}}
                                        <a href="#"
                                            class="block p-4 rounded-lg cursor-pointer transition-all duration-300 ease-in-out hover:shadow-lg hover:-translate-y-1 hover:bg-gray-50">
                                            <div class="flex justify-between items-center">
                                                <div class="flex items-center gap-4">
                                                    {{-- [Optional] เพิ่มส่วนแสดงรูปภาพ ถ้ามี --}}
                                                    <div class="hidden sm:block bg-gray-100 p-2 rounded-lg">
                                                        <svg class="h-8 w-8 text-gray-500" fill="none"
                                                            stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                                                            </path>
                                                        </svg>
                                                    </div>
                                                    <div>
                                                        <p class="font-semibold text-gray-800">
                                                            {{ $asset->asset_number }}</p>
                                                        <p class="text-sm text-gray-600">
                                                            {{ $asset->assetType?->name ?? 'N/A' }} -
                                                            {{ $asset->brand?->name ?? 'N/A' }}</p>
                                                    </div>
                                                </div>
                                                <div class="flex items-center space-x-4">
                                                    @php
                                                        $statusClass = match ($asset->status) {
                                                            'In Use' => 'bg-green-100 text-green-800',
                                                            'In Storage' => 'bg-yellow-100 text-yellow-800',
                                                            default => 'bg-red-100 text-red-800',
                                                        };
                                                    @endphp
                                                    <span
                                                        class="hidden md:inline-flex px-2 text-xs leading-5 font-semibold rounded-full {{ $statusClass }}">{{ $asset->status }}</span>
                                                    {{-- ▼▼▼ [ADDED] เพิ่ม Hover Effect ที่นี่ ▼▼▼ --}}
                                                    <a href="{{ route('repair_requests.create', ['asset_number' => $asset->asset_number, 'asset_type' => $asset->type]) }}"
                                                        class="text-sm text-indigo-600 hover:text-indigo-800 hover:underline font-semibold transition-colors duration-200">
                                                        Request Repair
                                                    </a>
                                                </div>
                                            </div>
                                        </a>
                                    @empty
                                        <p class="p-4 text-center text-gray-500">You have no assigned assets.</p>
                                    @endforelse
                                </div>
                            </div>
                        </div>

                        <div class="space-y-8">
                            <div class="bg-white rounded-lg shadow-sm">
                                <div class="p-6">
                                    <h3 class="text-lg font-semibold text-gray-800 border-b pb-3 mb-4">Quick Actions
                                    </h3>
                                    <div class="space-y-3">
                                        <a href="{{ route('repair_requests.create') }}"
                                            class="w-full flex items-center justify-center px-4 py-3 bg-indigo-600 text-white rounded-md font-semibold text-sm hover:bg-indigo-700 transition duration-200">
                                            <svg class="h-5 w-5 mr-2" xmlns="http://www.w3.org/2000/svg"
                                                viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd"
                                                    d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                            New Repair Request
                                        </a>
                                        <a href="{{ route('repair_requests.my') }}"
                                            class="w-full flex items-center justify-center px-4 py-3 bg-white border border-gray-300 text-gray-700 rounded-md font-semibold text-sm hover:bg-gray-50 transition duration-200">
                                            View All My Requests
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-white rounded-lg shadow-sm">
                                <div class="p-6">
                                    <h3 class="text-lg font-semibold text-gray-800 border-b pb-3 mb-4">My Profile</h3>
                                    <div class="grid grid-cols-2 gap-4 text-sm">
                                        <div>
                                            <dt class="text-gray-500 font-medium">Name</dt>
                                            <dd class="mt-1 text-gray-900 font-semibold">{{ Auth::user()->name }}</dd>
                                        </div>
                                        <div>
                                            <dt class="text-gray-500 font-medium">Employee ID</dt>
                                            <dd class="mt-1 text-gray-900 font-semibold">
                                                {{ Auth::user()->employee?->employee_id ?? 'N/A' }}</dd>
                                        </div>
                                        <div class="col-span-2">
                                            <dt class="text-gray-500 font-medium">Position</dt>
                                            <dd class="mt-1 text-gray-900 font-semibold">
                                                {{ Auth::user()->employee?->position ?? 'N/A' }}</dd>
                                        </div>
                                        <div class="col-span-2">
                                            <dt class="text-gray-500 font-medium">Department</dt>
                                            <dd class="mt-1 text-gray-900 font-semibold">
                                                {{ Auth::user()->employee?->department ?? 'N/A' }}</dd>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            @endif
        </div>
    </div>

    @if (auth()->user()->role == 'admin')
        @push('scripts')
            <script>
                // [เปลี่ยน] จาก 'DOMContentLoaded' เป็น 'livewire:navigated'
                document.addEventListener('livewire:navigated', function() {

                    // --- Chart 1: Assets by Type (Pie Chart) ---
                    const pieCanvas = document.getElementById('assetTypeChart');
                    if (pieCanvas) {
                        // [เพิ่ม] ตรวจสอบและทำลายกราฟเก่า ถ้ามี
                        let existingPieChart = Chart.getChart(pieCanvas);
                        if (existingPieChart) {
                            existingPieChart.destroy();
                        }

                        const pieCtx = pieCanvas.getContext('2d');
                        const pieLabels = @json($pieChartData['labels'] ?? []);
                        const pieData = @json($pieChartData['data'] ?? []);
                        if (pieLabels.length > 0) {
                            new Chart(pieCtx, {
                                type: 'pie',
                                data: {
                                    labels: pieLabels,
                                    datasets: [{
                                        data: pieData,
                                        backgroundColor: ['rgba(54, 162, 235, 0.8)',
                                            'rgba(255, 99, 132, 0.8)', 'rgba(255, 206, 86, 0.8)',
                                            'rgba(75, 192, 192, 0.8)', 'rgba(153, 102, 255, 0.8)',
                                            'rgba(255, 159, 64, 0.8)'
                                        ],
                                    }]
                                },
                                options: {
                                    responsive: true,
                                    maintainAspectRatio: false,
                                    plugins: {
                                        legend: {
                                            position: 'top'
                                        }
                                    }
                                }
                            });
                        }
                    }

                    // --- Chart 2: Assets by Brand (Bar Chart) ---
                    const barCanvas = document.getElementById('assetBrandChart');
                    if (barCanvas) {
                        // [เพิ่ม] ตรวจสอบและทำลายกราฟเก่า ถ้ามี
                        let existingBarChart = Chart.getChart(barCanvas);
                        if (existingBarChart) {
                            existingBarChart.destroy();
                        }

                        const barCtx = barCanvas.getContext('2d');
                        const barLabels = @json($barChartData['labels'] ?? []);
                        const barData = @json($barChartData['data'] ?? []);
                        if (barLabels.length > 0) {
                            new Chart(barCtx, {
                                type: 'bar',
                                data: {
                                    labels: barLabels,
                                    datasets: [{
                                        label: 'Total Assets',
                                        data: barData,
                                        backgroundColor: 'rgba(75, 192, 192, 0.7)',
                                    }]
                                },
                                options: {
                                    responsive: true,
                                    maintainAspectRatio: false,
                                    scales: {
                                        y: {
                                            beginAtZero: true,
                                            ticks: {
                                                stepSize: 1
                                            }
                                        }
                                    },
                                    plugins: {
                                        legend: {
                                            display: false
                                        }
                                    }
                                }
                            });
                        }
                    }

                    // --- Chart 3: Assets by Status (Doughnut Chart) ---
                    const statusCanvas = document.getElementById('assetStatusChart');
                    if (statusCanvas) {
                        // [เพิ่ม] ตรวจสอบและทำลายกราฟเก่า ถ้ามี
                        let existingStatusChart = Chart.getChart(statusCanvas);
                        if (existingStatusChart) {
                            existingStatusChart.destroy();
                        }

                        const statusCtx = statusCanvas.getContext('2d');
                        const statusLabels = @json($statusChartData['labels'] ?? []);
                        const statusData = @json($statusChartData['data'] ?? []);
                        if (statusLabels.length > 0) {
                            new Chart(statusCtx, {
                                type: 'doughnut',
                                data: {
                                    labels: statusLabels,
                                    datasets: [{
                                        data: statusData,
                                        backgroundColor: ['rgba(75, 192, 192, 0.7)',
                                            'rgba(255, 206, 86, 0.7)', 'rgba(255, 99, 132, 0.7)',
                                            'rgba(201, 203, 207, 0.7)'
                                        ],
                                    }]
                                },
                                options: {
                                    responsive: true,
                                    maintainAspectRatio: false,
                                    plugins: {
                                        legend: {
                                            position: 'top'
                                        }
                                    }
                                }
                            });
                        }
                    }
                });
            </script>
        @endpush
    @endif
</x-app-layout>
