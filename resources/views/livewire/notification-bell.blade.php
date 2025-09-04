<div x-data="{ open: false, shaking: false }" 
     x-on:notification-received.window="shaking = true; setTimeout(() => shaking = false, 1000)"
     class="relative">
    
    <!-- Bell Icon Button -->
    <button @click="open = !open" 
            :class="{ 'animate-shake': shaking }"
            class="relative text-yellow-500 hover:text-yellow-400 focus:outline-none transition-colors duration-200">
        
        <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
        </svg>

        {{-- ▼▼▼ [START] แก้ไขส่วนแสดงผลจำนวน Notification ▼▼▼ --}}
        @if($unreadCount > 0)
            <span class="absolute -top-2 -right-2 flex h-5 w-5 items-center justify-center rounded-full bg-red-500 text-xs font-bold text-white">
                {{ $unreadCount }}
            </span>
        @endif
        {{-- ▲▲▲ [END] จบส่วนที่แก้ไข ▲▲▲ --}}
    </button>

    <!-- Dropdown -->
    <div x-show="open" 
         @click.away="open = false"
         x-transition
         class="absolute top-full right-0 mt-2 w-80 bg-white rounded-lg shadow-lg overflow-hidden z-20"
         style="display: none;">
        
        <div class="p-4 border-b font-semibold text-gray-800">Notifications</div>
        <div class="divide-y max-h-96 overflow-y-auto">
            @forelse($notifications as $notification)
                <a href="{{ $notification->data['url'] ?? '#' }}" wire:click="markAsRead('{{ $notification->id }}')" class="block p-3 hover:bg-gray-100 {{ $notification->read_at ? 'text-gray-500' : 'text-gray-800 font-semibold' }}">
                    <p class="text-sm">{{ $notification->data['message'] }}</p>
                    <div class="text-xs text-gray-400 mt-1">
                        <span>{{ $notification->created_at->diffForHumans() }}</span>
                    </div>
                </a>
            @empty
                <div class="p-4 text-center text-gray-500">
                    You have no notifications.
                </div>
            @endforelse
        </div>
    </div>
</div>
