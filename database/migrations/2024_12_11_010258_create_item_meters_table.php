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
        Schema::create('item_meters', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_id');
            $table->foreign('item_id')
                ->references('id')
                ->on('items');
            $table->decimal('meter_start', total: 8, places: 2);
            $table->decimal('meter_end', total: 8, places: 2)->nullable();
            $table->integer('meterable_id');
            $table->string('meterable_type');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('item_meters');
    }
};
