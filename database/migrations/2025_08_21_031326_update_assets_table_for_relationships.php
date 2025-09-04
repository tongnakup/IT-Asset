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
        Schema::table('assets', function (Blueprint $table) {
            // 1. เพิ่มคอลัมน์ใหม่สำหรับเก็บ ID ของข้อมูลที่เชื่อมโยงกัน
            // เราจะเพิ่มไว้หลังคอลัมน์ 'asset_category_id'
            $table->foreignId('asset_type_id')->nullable()->after('asset_category_id')->constrained('asset_types');
            $table->foreignId('brand_id')->nullable()->after('asset_type_id')->constrained('brands');
            $table->foreignId('status_id')->nullable()->after('brand_id')->constrained('asset_statuses');
            $table->foreignId('location_id')->nullable()->after('status_id')->constrained('locations');

            // 2. (แนะนำ) ลบคอลัมน์เก่าที่เป็นข้อความธรรมดาออก เพื่อไม่ให้สับสน
            $table->dropColumn(['type', 'brand', 'status', 'location', 'category']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('assets', function (Blueprint $table) {
            // คำสั่งสำหรับย้อนกลับ (เผื่อต้องการยกเลิก)
            $table->dropForeign(['asset_type_id']);
            $table->dropForeign(['brand_id']);
            $table->dropForeign(['status_id']);
            $table->dropForeign(['location_id']);

            $table->dropColumn(['asset_type_id', 'brand_id', 'status_id', 'location_id']);

            $table->string('type')->nullable();
            $table->string('brand')->nullable();
            $table->string('status')->nullable();
            $table->string('location')->nullable();
            $table->string('category')->nullable();
        });
    }
};