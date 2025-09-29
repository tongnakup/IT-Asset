<div x-show="sidebarOpen" class="md:hidden fixed inset-0 bg-gray-900 bg-opacity-75 z-20" @click="sidebarOpen = false"
    x-cloak></div>

<div :class="{ 'translate-x-0 ease-out': sidebarOpen, '-translate-x-full ease-in': !sidebarOpen }"
    class="fixed inset-y-0 left-0 w-64 bg-gray-800 text-white flex flex-col shadow-lg z-30
            transform transition-transform duration-300
            md:translate-x-0">
    <div class="h-20 flex items-center justify-center border-b border-gray-700 p-4 flex-shrink-0">
        <a href="{{ route('dashboard') }}">
            <img src="{{ asset('images/logo2.png') }}" alt="ANJI-NYK Logo" style="height: auto; width: auto;">
        </a>
    </div>

    <nav class="flex-grow px-4 py-6 overflow-y-auto">
        <a href="{{ route('dashboard') }}"
            class="flex items-center px-4 py-2 mt-2 text-gray-300 rounded-md hover:bg-gray-700 hover:text-white {{ request()->routeIs('dashboard') ? 'bg-gray-900' : '' }}">
            <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
            </svg>
            <span class="mx-4 font-medium">Dashboard</span>
        </a>

        {{-- ===== Support Menu (For All Users) ===== --}}
        <div class="mt-6">
            <p class="px-4 text-xs uppercase text-gray-400 font-semibold tracking-wider">Support</p>
            <div class="mt-2 space-y-1">
                <a href="{{ route('repair_requests.create') }}"
                    class="flex items-center px-4 py-2 mt-2 text-gray-300 rounded-md hover:bg-gray-700 hover:text-white {{ request()->routeIs('repair_requests.create') ? 'bg-gray-900' : '' }}">
                    <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    <span class="mx-4 font-medium">New Repair Request</span>
                </a>
                <a href="{{ route('repair_requests.my') }}"
                    class="flex items-center px-4 py-2 mt-2 text-gray-300 rounded-md hover:bg-gray-700 hover:text-white {{ request()->routeIs('repair_requests.my') ? 'bg-gray-900' : '' }}">
                    <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span class="mx-4 font-medium">My Requests</span>
                </a>
            </div>

            <a href="{{ route('announcements.index') }}"
                class="flex items-center px-4 py-2.5 mt-2 text-gray-300 rounded-lg hover:bg-gray-700 hover:text-white transition-colors duration-200 {{ request()->routeIs('announcements.*') ? 'bg-gray-900 text-white' : '' }}">

                <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                    stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" />
                </svg>
                <span class="ml-4 font-medium">Announcements</span>
            </a>
        </div>

        {{-- Admin Menu --}}
        @if (auth()->user()->role == 'admin')
            <div class="mt-6">
                <p class="px-4 text-xs uppercase text-gray-400 font-semibold tracking-wider">Admin Controls</p>
                <div class="mt-2 space-y-1">
                    <a href="{{ route('it_assets.index') }}"
                        class="flex items-center px-4 py-2 mt-2 text-gray-300 rounded-md hover:bg-gray-700 hover:text-white {{ request()->routeIs('it_assets.*') ? 'bg-gray-900' : '' }}">
                        <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                        <span class="mx-4 font-medium">IT Asset</span>
                    </a>
                    <a href="{{ route('users.index') }}"
                        class="flex items-center px-4 py-2 mt-2 text-gray-300 rounded-md hover:bg-gray-700 hover:text-white {{ request()->routeIs('users.*') ? 'bg-gray-900' : '' }}">
                        <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        <span class="mx-4 font-medium">User Management</span>
                    </a>
                    <a href="{{ route('activity_logs.index') }}"
                        class="flex items-center px-4 py-2 mt-2 text-gray-300 rounded-md hover:bg-gray-700 hover:text-white {{ request()->routeIs('activity_logs.index') ? 'bg-gray-900' : '' }}">
                        <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                        </svg>
                        <span class="mx-4 font-medium">Activity Log</span>
                    </a>
                    <a href="{{ route('repair_requests.index') }}"
                        class="flex items-center px-4 py-2 mt-2 text-gray-300 rounded-md hover:bg-gray-700 hover:text-white {{ request()->routeIs('repair_requests.index', 'repair_requests.edit') ? 'bg-gray-900' : '' }}">
                        <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
                        </svg>
                        <span class="mx-4 font-medium">Manage Repairs</span>
                    </a>

                    {{-- ▼▼▼ [START] โค้ดที่แก้ไขแล้ว ▼▼▼ --}}
                    <a href="{{ route('settings.index') }}"
                        class="flex items-center px-4 py-2 mt-2 text-gray-300 rounded-md hover:bg-gray-700 hover:text-white {{ request()->routeIs('settings.*') ? 'bg-gray-900' : '' }}">
                        <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <span class="mx-4 font-medium">Settings</span>
                    </a>

                </div>
            </div>
        @endif
    </nav>
</div>
