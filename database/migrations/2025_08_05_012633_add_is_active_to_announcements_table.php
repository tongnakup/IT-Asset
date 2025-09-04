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
        Schema::table('announcements', function (Blueprint $table) {
            // เพิ่มคอลัมน์ is_active เป็น boolean, ค่าเริ่มต้นคือ true (ใช้งาน)
            $table->boolean('is_active')->default(true)->after('content');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('announcements', function (Blueprint $table) {
            // คำสั่งสำหรับตอน rollback (ลบคอลัมน์ทิ้ง)
            $table->dropColumn('is_active');
        });
    }
};