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
        Schema::table('asset_types', function (Blueprint $table) {
            // เพิ่มคอลัมน์สำหรับเชื่อมโยงไปที่ตาราง asset_categories
            $table->foreignId('asset_category_id')
                  ->constrained('asset_categories')
                  ->after('id'); // (Optional) ให้คอลัมน์นี้อยู่หลังคอลัมน์ id
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('asset_types', function (Blueprint $table) {
            // คำสั่งสำหรับตอนที่ต้องการ rollback
            $table->dropForeign(['asset_category_id']);
            $table->dropColumn('asset_category_id');
        });
    }
};