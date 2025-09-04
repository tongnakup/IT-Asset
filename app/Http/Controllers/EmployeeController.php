<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // ส่งหน้าฟอร์มสำหรับสร้างพนักงานใหม่
        return view('employees.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // 1. ตรวจสอบความถูกต้องของข้อมูล
        $validated = $request->validate([
            'employee_id' => 'required|string|max:255|unique:employees,employee_id',
            'user_id' => 'required|integer|unique:employees,user_id',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'position' => 'nullable|string|max:255',
            'department' => 'nullable|string|max:255',
            'phone_number' => 'nullable|string|max:20',
            'start_date' => 'required|date',
        ]);

        // 2. สร้างข้อมูลพนักงานใหม่
        Employee::create($validated);

        // 3. กลับไปยังหน้ารายชื่อพนักงาน (หรือหน้าที่ต้องการ) พร้อมข้อความแจ้งเตือน
        // หากยังไม่มีหน้ารายชื่อพนักงาน อาจจะ redirect ไปหน้า dashboard ก่อน
        return redirect()->route('dashboard')->with('success', 'Employee created successfully.');
    }
}