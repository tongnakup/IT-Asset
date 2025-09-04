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
        Schema::table('asset_statuses', function (Blueprint $table) {
            $table->string('color', 7)->nullable()->after('name'); // กำหนดให้เก็บรหัสสี Hex (เช่น #FF0000)
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('asset_statuses', function (Blueprint $table) {
            $table->dropColumn('color');
        });
    }
};