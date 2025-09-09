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
        Schema::create('it_assets', function (Blueprint $table) {
            $table->id(); // สร้างคอลัมน์ id ชนิด BIGINT UNSIGNED โดยอัตโนมัติ
            $table->string('asset_number')->unique()->comment('หมายเลขทรัพย์สิน');
            $table->string('name')->comment('ชื่อทรัพย์สิน');
            $table->string('serial_number')->nullable()->comment('ซีเรียลนัมเบอร์');

            // สร้าง Foreign key ไปยังตารางอื่นๆ (ถ้ามี)
            $table->foreignId('asset_type_id')->nullable()->constrained('asset_types');
            $table->foreignId('location_id')->nullable()->constrained('locations');
            // คุณสามารถเพิ่มคอลัมน์อื่นๆ ที่จำเป็นได้ เช่น brand_id, status_id เป็นต้น

            $table->timestamps(); // สร้างคอลัมน์ created_at และ updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('it_assets');
    }
};
