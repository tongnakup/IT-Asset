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
        Schema::table('repair_requests', function (Blueprint $table) {
            // สร้างคอลัมน์ให้มีชนิดข้อมูลตรงกับ assets.id (คาดว่าเป็น bigint)
            $table->unsignedBigInteger('it_asset_id')->nullable()->after('user_id');

            // แก้ไข 'it_assets' ให้เป็น 'assets' ซึ่งเป็นชื่อตารางที่ถูกต้อง
            $table->foreign('it_asset_id')->references('id')->on('assets');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('repair_requests', function (Blueprint $table) {
            // คำสั่งสำหรับตอน rollback migration (ลบคอลัมน์ที่เพิ่มไป)
            $table->dropForeign(['it_asset_id']);
            $table->dropColumn('it_asset_id');
        });
    }
};
