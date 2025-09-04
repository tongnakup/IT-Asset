<?php

namespace App\Livewire\Forms;

use Livewire\Component;
use App\Models\Employee;
use App\Models\User;
use App\Models\Position;
use App\Models\Department;

class EmployeeCreateForm extends Component
{
    // Form fields
    public $employee_id;
    public $user_id;
    public $first_name;
    public $last_name;
    public $phone_number;
    public $start_date;

    // Dropdown data
    public $positions = [];
    public $departments = [];

    // Selected values
    public $selectedPosition = '';
    public $selectedDepartment = '';

    public function mount()
    {
        // โหลดข้อมูลทั้งหมดมาเตรียมไว้
        $this->positions = Position::with('department')->orderBy('name')->get();
        $this->departments = Department::orderBy('name')->get();
    }

    // ฟังก์ชันนี้จะทำงานอัตโนมัติทุกครั้งที่ selectedPosition เปลี่ยนค่า
    public function updatedSelectedPosition($positionId)
    {
        if (!empty($positionId)) {
            $position = Position::find($positionId);
            if ($position) {
                // อัปเดตค่า Department ที่ผูกอยู่กับ Position นั้นโดยอัตโนมัติ
                $this->selectedDepartment = $position->department_id;
            }
        } else {
            $this->selectedDepartment = '';
        }
    }

    public function saveEmployee()
    {
        $validatedData = $this->validate([
            'employee_id' => 'required|string|max:255|unique:employees,employee_id',
            'user_id' => 'required|integer|exists:users,id',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'selectedPosition' => 'required|exists:positions,id',
            'selectedDepartment' => 'required|exists:departments,id',
            'phone_number' => 'nullable|string|max:255',
            'start_date' => 'required|date',
        ]);

        // ดึงชื่อจาก ID เพื่อบันทึกเป็น string ตามโครงสร้างเดิม
        $positionName = Position::find($this->selectedPosition)->name;
        $departmentName = Department::find($this->selectedDepartment)->name;

        Employee::create([
            'employee_id' => $this->employee_id,
            'user_id' => $this->user_id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'position' => $positionName,
            'department' => $departmentName,
            'phone_number' => $this->phone_number,
            'start_date' => $this->start_date,
        ]);

        session()->flash('success', 'Employee created successfully.');
        return redirect()->to(route('employees.index'));
    }

    public function render()
    {
        return view('livewire.forms.employee-create-form');
    }
}
