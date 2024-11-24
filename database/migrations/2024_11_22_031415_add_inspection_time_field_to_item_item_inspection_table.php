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
        Schema::table('item_item_inspection', function (Blueprint $table) {
            $table->float('inspection_time_in_minutes')
                ->nullable()
                ->virtualAs('ROUND((JULIANDAY(completed_at) - JULIANDAY(started_at)) * 24 * 60, 2)');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('item_item_inspection', function (Blueprint $table) {
            $table->dropColumn('inspection_time_in_minutes');
        });
    }
};
