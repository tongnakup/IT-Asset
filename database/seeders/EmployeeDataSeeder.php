<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Employee;
use App\Models\ItAsset;
use Illuminate\Support\Facades\DB;

class EmployeeDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Employee::truncate();

        $users = User::all();

        foreach ($users as $user) {
            // ===== เพิ่มการตรวจสอบตรงนี้ =====
            // ทำงานเฉพาะเมื่อ user มี employee_id เท่านั้น
            if ($user->employee_id) {
                $asset = ItAsset::where('employee_id', $user->employee_id)->first();

                Employee::create([
                    'user_id' => $user->id,
                    'employee_id' => $user->employee_id,
                    'first_name' => $asset->first_name ?? strtok($user->name, ' '),
                    'last_name' => $asset->last_name ?? (strstr($user->name, ' ') ? substr(strstr($user->name, ' '), 1) : ''),
                    'position' => $asset->position ?? null,
                    'department' => null,
                    'phone_number' => null,
                    'start_date' => null,
                ]);
            }
            // ===============================
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        
        $this->command->info('Employee data migrated successfully!');
    }
}
