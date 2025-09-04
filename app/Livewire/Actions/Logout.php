<?php

namespace App\Livewire\Actions;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\RedirectResponse; // เพิ่ม use statement นี้

class Logout
{
    /**
     * Log the user out of the application.
     */
    public function __invoke(): RedirectResponse // เปลี่ยน return type เป็น RedirectResponse
    {
        Auth::guard('web')->logout();

        Session::invalidate();
        Session::regenerateToken();

        // เปลี่ยนจาก redirect ไปหน้าแรก เป็นไปหน้า login โดยตรง
        return redirect()->route('login');
    }
}
