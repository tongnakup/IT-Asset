<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use App\Models\Position;
use App\Models\Department;
use App\Models\Location;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class UserEditModal extends Component
{
    public $user;
    public $userId;
    public $name;
    public $email;
    public $role;

    public $employeeData = [];
    public $showModal = false;

    public $positions = [];
    public $departments = [];
    public $locations = [];
    public $roles = ['admin', 'user'];

    protected $listeners = ['showUserEditModal' => 'edit'];

    public function edit($userId)
    {
        $this->userId = $userId;
        $user = User::with('employee')->findOrFail($userId);
        
        // Populate user data
        $this->name = $user->name;
        $this->email = $user->email;
        $this->role = $user->role;

        // Populate employee data
        $this->employeeData = $user->employee ? $user->employee->toArray() : [
            'employee_id' => '',
            'first_name' => '',
            'last_name' => '',
            'position' => null,
            'department' => null,
            'location' => null,
            'phone_number' => '',
            'start_date' => '',
        ];

        // Load dropdown data
        $this->positions = Position::orderBy('name')->pluck('name')->toArray();
        $this->departments = Department::orderBy('name')->pluck('name')->toArray();
        $this->locations = Location::orderBy('name')->pluck('name')->toArray();

        $this->showModal = true;
    }

    public function update()
    {
        $user = User::findOrFail($this->userId);

        // Validation rules
        $validatedData = $this->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($this->userId)],
            'role' => 'required|string|in:admin,user',
            
            'employeeData.employee_id' => ['required', 'string', 'max:255', Rule::unique('employees', 'employee_id')->ignore(optional($user->employee)->id)],
            'employeeData.first_name' => 'required|string|max:255',
            'employeeData.last_name' => 'required|string|max:255',
            'employeeData.position' => 'nullable|string|max:255',
            'employeeData.department' => 'nullable|string|max:255',
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

            if ($user->employee) {
                $user->employee->update($this->employeeData);
            } else {
                $user->employee()->create($this->employeeData);
            }
        });
        
        $this->closeModal();
        $this->dispatch('userUpdated'); // Emit event to refresh the user list
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