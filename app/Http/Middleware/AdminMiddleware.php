<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // เช็คว่าล็อกอินแล้ว และมี role เป็น 'admin' หรือไม่
        if (Auth::check() && Auth::user()->role == 'admin') {
            return $next($request); // ถ้าใช่ ให้ไปต่อ
        }

        // ถ้าไม่ใช่ ให้เด้งกลับไปหน้า dashboard
        return redirect('/dashboard')->with('error', 'You do not have permission.');
    }
}