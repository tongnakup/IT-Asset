<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            // เพิ่มคอลัมน์ location ต่อจาก department (nullable คืออนุญาตให้เป็นค่าว่างได้)
            $table->string('location')->nullable()->after('department');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            // คำสั่งสำหรับตอนที่ต้องการยกเลิก migration นี้ (ลบคอลัมน์)
            $table->dropColumn('location');
        });
    }
};