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
        // [แก้ไข] 1. เปลี่ยนชื่อตารางเป็น 'assets' ให้ตรงกับฐานข้อมูลจริง
        Schema::create('assets', function (Blueprint $table) {
            $table->id();
            
            // [แก้ไข] 2. ทำให้ employee_id เป็น nullable (อนุญาตให้เว้นว่างได้)
            $table->string('employee_id')->nullable()->comment('ID พนักงานที่ได้รับมอบหมาย');

            // [เพิ่ม] 3. เพิ่มคอลัมน์ที่จำเป็นทั้งหมดที่ขาดไป
            $table->string('asset_number')->unique();
            $table->foreignId('asset_category_id')->constrained('asset_categories');
            $table->string('type');
            $table->string('brand');
            $table->string('status');
            $table->string('location')->nullable();
            $table->string('category'); // ชื่อหมวดหมู่ (จาก Controller)
            $table->json('specifications')->nullable();
            $table->string('image_path')->nullable();
            
            // [แก้ไข] 4. ลบคอลัมน์ที่ไม่จำเป็นออก (ข้อมูลเหล่านี้ควรอยู่ในตาราง employees)
            // $table->string('first_name');
            // $table->string('last_name');
            // $table->string('position');
            
            $table->date('start_date')->comment('วันที่เริ่มใช้งาน');
            $table->date('end_date')->nullable()->comment('วันที่เลิกใช้');
            
            $table->timestamps();
            $table->softDeletes(); // เพิ่มเข้ามาสำหรับฟีเจอร์ Trash
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // [แก้ไข] เปลี่ยนชื่อตารางให้ตรงกัน
        Schema::dropIfExists('assets');
    }
};
