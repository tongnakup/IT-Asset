<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use App\Models\Employee;
use App\Models\Position;
use App\Models\Department;
use App\Models\Location;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\On;

class UserCreateModal extends Component
{
    public $showModal = false;

    // User fields
    public $name, $email, $password, $password_confirmation, $role = 'user';

    // Employee fields
    public $employee_id, $phone_number, $first_name, $last_name, $location, $start_date;

    // Dropdown data
    public $positions = [], $departments = [], $locations = [];
    public $selectedPosition = '', $selectedDepartment = '';

    #[On('openCreateModal')]
    public function openModal()
    {
        $this->resetInput();
        // [แก้ไข] ไม่ต้อง load 'department' แล้ว
        $this->positions = Position::orderBy('name')->get(); 
        $this->departments = Department::orderBy('name')->get();
        $this->locations = Location::orderBy('name')->get();
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
    }


    public function save()
    {
        $validated = $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|string|in:user,admin',
            'employee_id' => 'required|string|max:255|unique:employees,employee_id',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'selectedPosition' => 'required|exists:positions,id',
            'selectedDepartment' => 'required|exists:departments,id',
            'phone_number' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
            'start_date' => 'required|date',
        ]);

        $user = User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
            'role' => $this->role,
        ]);

        $positionName = Position::find($this->selectedPosition)->name;
        $departmentName = Department::find($this->selectedDepartment)->name;

        Employee::create([
            'user_id' => $user->id,
            'employee_id' => $this->employee_id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'position' => $positionName,
            'department' => $departmentName,
            'phone_number' => $this->phone_number,
            'location' => $this->location,
            'start_date' => $this->start_date,
        ]);

        $this->closeModal();
        $this->dispatch('userCreated');
    }

    private function resetInput()
    {
        $this->resetExcept('showModal');
    }

    public function render()
    {
        return view('livewire.user-create-modal');
    }
}
