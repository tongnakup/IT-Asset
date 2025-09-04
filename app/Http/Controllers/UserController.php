<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\DB;
use App\Models\Position;     // ▼▼▼ 1. เพิ่ม Model ใหม่ ▼▼▼
use App\Models\Department;    // ▼▼▼ 1. เพิ่ม Model ใหม่ ▼▼▼
use App\Models\Location;      // ▼▼▼ 1. เพิ่ม Model ใหม่ ▼▼▼

class UserController extends Controller
{
    // เราไม่จำเป็นต้องใช้ Properties ที่ Hardcode ไว้อีกต่อไป

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::with('employee')->latest()->paginate(10);
        return view('users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // เมธอดนี้จะไม่ได้ถูกใช้แล้วเมื่อเปลี่ยนเป็น Modal แต่เก็บไว้เผื่อใช้ในอนาคต
        return view('users.create', [
            'positions' => Position::orderBy('name')->pluck('name'),
            'departments' => Department::orderBy('name')->pluck('name'),
            'locations' => Location::orderBy('name')->pluck('name'),
        ]);
    }
    
    /**
     * Fetch data for the create modal.
     */
    public function getCreateData()
    {
        // ▼▼▼ 2. แก้ไขให้ดึงข้อมูลจากฐานข้อมูล ▼▼▼
        return response()->json([
            'positions' => Position::orderBy('name')->pluck('name'),
            'departments' => Department::orderBy('name')->pluck('name'),
            'locations' => Location::orderBy('name')->pluck('name'),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'string', 'in:admin,user'],
            'employee_id' => 'required|string|max:255|unique:employees,employee_id',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'position' => 'nullable|string|max:255',
            'department' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
            'phone_number' => 'nullable|string|max:20',
            'start_date' => 'required|date',
        ]);

        DB::transaction(function () use ($validated) {
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role' => $validated['role'],
            ]);

            $user->employee()->create([
                'employee_id' => $validated['employee_id'],
                'first_name' => $validated['first_name'],
                'last_name' => $validated['last_name'],
                'position' => $validated['position'],
                'department' => $validated['department'],
                'location' => $validated['location'],
                'phone_number' => $validated['phone_number'],
                'start_date' => $validated['start_date'],
            ]);
        });
        
        if ($request->wantsJson()) {
            return response()->json(['message' => 'User and Employee created successfully.']);
        }

        return redirect()->route('users.index')->with('success', 'User and Employee created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        $user->load('employee');
        $roles = ['admin', 'user'];

        return view('users.edit', [
            'user' => $user,
            'roles' => $roles,
            'positions' => Position::orderBy('name')->pluck('name'),
            'departments' => Department::orderBy('name')->pluck('name'),
            'locations' => Location::orderBy('name')->pluck('name'),
        ]);
    }

    /**
     * Fetch data for the edit modal.
     */
    public function getEditData(User $user)
    {
        $user->load('employee');
        // ▼▼▼ 3. แก้ไขให้ดึงข้อมูลจากฐานข้อมูล ▼▼▼
        return response()->json([
            'user' => $user,
            'roles' => ['admin', 'user'],
            'positions' => Position::orderBy('name')->pluck('name'),
            'departments' => Department::orderBy('name')->pluck('name'),
            'locations' => Location::orderBy('name')->pluck('name'),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'role' => 'required|string|in:admin,user',
            'employee_id' => 'required|string|max:255|unique:employees,employee_id,' . optional($user->employee)->id,
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'position' => 'nullable|string|max:255',
            'department' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
            'phone_number' => 'nullable|string|max:20',
            'start_date' => 'required|date',
        ]);

        DB::transaction(function () use ($validated, $user) {
            $user->update([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'role' => $validated['role'],
            ]);

            if ($user->employee) {
                $user->employee->update([
                    'employee_id' => $validated['employee_id'],
                    'first_name' => $validated['first_name'],
                    'last_name' => $validated['last_name'],
                    'position' => $validated['position'],
                    'department' => $validated['department'],
                    'location' => $validated['location'],
                    'phone_number' => $validated['phone_number'],
                    'start_date' => $validated['start_date'],
                ]);
            }
        });

        if ($request->wantsJson()) {
            return response()->json(['message' => 'User updated successfully.']);
        }

        return redirect()->route('users.index')->with('success', 'User updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        if (Auth::id() == $user->id) {
            return redirect()->route('users.index')->with('error', 'You cannot delete your own account.');
        }

        DB::transaction(function () use ($user) {
            $user->employee()->delete();
            $user->delete();
        });

        return redirect()->route('users.index')->with('success', 'User deleted successfully.');
    }
}
