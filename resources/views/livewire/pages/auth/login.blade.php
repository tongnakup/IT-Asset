<?php

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component {
    public LoginForm $form;

    /**
     * Handle an incoming authentication request.
     */
    public function login(): void
    {
        $this->validate();

        $this->form->authenticate();

        Session::regenerate();

        $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
    }
}; ?>

<div>

    <h2 class="text-2xl font-bold text-gray-900">
        Sign in
    </h2>
    <p class="mt-2 text-sm text-gray-600">
        Please enter your credentials to continue.
    </p>
    {{-- ▲▲▲ [ จบส่วนที่เพิ่ม ] ▲▲▲ --}}

    <div class="mt-6">
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form wire:submit="login">
            <div>
                <x-input-label for="email" :value="__('Email')" />
                <x-text-input wire:model="form.email" id="email" class="block mt-1 w-full" type="email" name="email"
                    required autofocus autocomplete="username" />
                <x-input-error :messages="$errors->get('form.email')" class="mt-2" />
            </div>

            <div class="mt-4">
                <x-input-label for="password" :value="__('Password')" />

                <x-text-input wire:model="form.password" id="password" class="block mt-1 w-full" type="password"
                    name="password" required autocomplete="current-password" />

                <x-input-error :messages="$errors->get('form.password')" class="mt-2" />
            </div>

            <div class="flex items-center justify-between mt-4">
                <label for="remember" class="inline-flex items-center">
                    <input wire:model="form.remember" id="remember" type="checkbox"
                        class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                    <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
                </label>

            </div>

            <div class="mt-6">
                <button type="submit"
                    class="w-full inline-flex items-center justify-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    {{ __('Log in') }}
                </button>
            </div>
        </form>
    </div>
</div>
