<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Employee;
use Livewire\Attributes\On; 

class EmployeeSearch extends Component
{
    // --- Properties ที่แก้ไขและเพิ่มเติม ---
    public $search = ''; // เปลี่ยนจาก employee_id มาเป็น search สำหรับช่องค้นหา
    public $searchResults = []; // สำหรับเก็บผลลัพธ์การค้นหา
    
    // Properties เดิมสำหรับแสดงผล
    public $first_name = '';
    public $last_name = '';
    public $position = '';

    // Property สำหรับส่งค่า employee_id สุดท้ายไปกับฟอร์ม
    public $selected_employee_id = ''; 
    
    // --- ฟังก์ชันที่แก้ไขและเพิ่มเติม ---

    /**
     * ทำงานทุกครั้งที่ช่องค้นหามีการเปลี่ยนแปลง
     */
    public function updatedSearch()
    {
        if (strlen($this->search) >= 1) {
            // ค้นหาพนักงานที่มี employee_id ขึ้นต้นด้วยคำที่ค้นหา
            $this->searchResults = Employee::where('employee_id', 'like', $this->search . '%')
                ->limit(5) // แสดงผลลัพธ์ไม่เกิน 5 รายการ
                ->get();
        } else {
            // ถ้าช่องค้นหาว่าง ให้ล้างผลลัพธ์และข้อมูลทั้งหมด
            $this->resetAllFields();
        }
    }

    /**
     * ทำงานเมื่อผู้ใช้คลิกเลือกพนักงานจากในรายการ
     */
    public function selectEmployee($employeeId)
    {
        $employee = Employee::find($employeeId);

        if ($employee) {
            // แสดงข้อมูลในช่องต่างๆ
            $this->first_name = $employee->first_name;
            $this->last_name = $employee->last_name;
            $this->position = $employee->position;
            
            // แสดง ID ที่เลือกในช่องค้นหา
            $this->search = $employee->employee_id;
            
            // เก็บ ID ที่เลือกไว้สำหรับส่งไปกับฟอร์ม
            $this->selected_employee_id = $employee->employee_id;
            
            // ซ่อนรายการผลลัพธ์
            $this->searchResults = [];
        }
    }

    private function resetAllFields()
    {
        $this->search = '';
        $this->selected_employee_id = '';
        $this->first_name = '';
        $this->last_name = '';
        $this->position = '';
        $this->searchResults = [];
    }

    public function render()
    {
        return view('livewire.employee-search');
    }
}