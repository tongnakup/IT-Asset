<x-guest-layout>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-16">

        {{-- คอลัมน์สำหรับ Login --}}
        <div class="px-6 py-4">
            <h2 class="text-2xl font-bold mb-6 text-center">Log In</h2>
            <livewire:pages.auth.login />
        </div>

        {{-- คอลัมน์สำหรับ Register --}}
        <div class="px-6 py-4 border-t md:border-t-0 md:border-s border-gray-200">
            <h2 class="text-2xl font-bold mb-6 text-center">Register</h2>
            <livewire:pages.auth.register />
        </div>

    </div>
</x-guest-layout>