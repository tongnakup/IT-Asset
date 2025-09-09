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
            // ▼▼▼ เพิ่มโค้ดส่วนนี้เข้าไป ▼▼▼
            $table->foreignId('location_id')->nullable()->constrained('locations')->after('problem_description');
            $table->foreignId('asset_type_id')->nullable()->constrained('asset_types')->after('location_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('repair_requests', function (Blueprint $table) {
            // ▼▼▼ เพิ่มโค้ดส่วนนี้เข้าไป ▼▼▼
            $table->dropForeign(['location_id']);
            $table->dropColumn('location_id');
            $table->dropForeign(['asset_type_id']);
            $table->dropColumn('asset_type_id');
        });
    }
};
