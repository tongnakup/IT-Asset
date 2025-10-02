<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use App\Models\Position;
use App\Models\Department;
use App\Models\Location;
use App\Models\Employee;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class UserEditModal extends Component
{
    public $userId;
    public $name;
    public $email;
    public $role;

    // Properties สำหรับ Employee
    public $employeeData = [];
    public $selectedPosition = null;
    public $selectedDepartment = null;

    public $showModal = false;

    // Dropdown Data
    public $positions = [];
    public $departments = [];
    public $locations = [];
    public $roles = ['admin', 'user'];

    protected $listeners = ['showUserEditModal' => 'edit'];

    public function edit($userId)
    {
        $this->reset();
        $this->userId = $userId;

        $user = User::with('employee')->findOrFail($userId);

        $this->name = $user->name;
        $this->email = $user->email;
        $this->role = $user->role;

        $this->positions = Position::orderBy('name')->get();
        $this->departments = Department::orderBy('name')->get();
        $this->locations = Location::orderBy('name')->get();

        if ($user->employee) {
            $this->employeeData = $user->employee->toArray();
            $currentPosition = Position::where('name', $user->employee->position)->first();
            if ($currentPosition) {
                $this->selectedPosition = $currentPosition->id;
                $this->selectedDepartment = $currentPosition->department_id;
            }
        } else {
            $this->employeeData = ['employee_id' => '', 'first_name' => '', 'last_name' => '', 'phone_number' => '', 'start_date' => '', 'location' => null];
        }

        $this->showModal = true;
    }

    public function updatedSelectedPosition($positionId)
    {
        if (!empty($positionId)) {
            $position = Position::find($positionId);
            if ($position) {
                $this->selectedDepartment = $position->department_id;
            }
        } else {
            $this->selectedDepartment = null;
        }
    }

    public function update()
    {
        $this->name = trim($this->name);
        $this->email = trim($this->email);
        $this->employeeData['employee_id'] = trim($this->employeeData['employee_id']);
        $this->employeeData['first_name'] = trim($this->employeeData['first_name']);
        $this->employeeData['last_name'] = trim($this->employeeData['last_name']);

        $user = User::findOrFail($this->userId);

        $validatedData = $this->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($this->userId)],
            'role' => 'required|string|in:admin,user',
            'employeeData.employee_id' => ['required', 'string', 'max:255', Rule::unique('employees', 'employee_id')->ignore(optional($user->employee)->id)],
            'employeeData.first_name' => 'required|string|max:255',
            'employeeData.last_name' => 'required|string|max:255',
            'selectedPosition' => 'required|exists:positions,id',
            'selectedDepartment' => 'required|exists:departments,id',
            'employeeData.location' => 'nullable|string|max:255',
            'employeeData.phone_number' => 'nullable|string|max:20',
            'employeeData.start_date' => 'required|date',
        ]);

        DB::transaction(function () use ($user, $validatedData) {
            $user->update([
                'name' => $this->name,
                'email' => $this->email,
                'role' => $this->role,
            ]);

            $positionName = Position::find($this->selectedPosition)->name;
            $departmentName = Department::find($this->selectedDepartment)->name;

            $employeeDetails = $this->employeeData;
            $employeeDetails['position'] = $positionName;
            $employeeDetails['department'] = $departmentName;

            if ($user->employee) {
                $user->employee->update($employeeDetails);
            } else {
                $user->employee()->create($employeeDetails);
            }
        });

        session()->flash('success', 'User updated successfully.');
        return redirect()->route('users.index');
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->reset();
    }

    public function render()
    {
        return view('livewire.user-edit-modal');
    }
}
