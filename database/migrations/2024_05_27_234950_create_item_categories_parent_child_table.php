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
        Schema::create('item_categories_parent_child', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parent_id');
            $table->foreign('parent_id')
                ->references('id')
                ->on('item_categories');
            $table->foreignId('child_id');
            $table->foreign('child_id')
                ->references('id')
                ->on('item_categories');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('item_categories_parent_child');
    }
};
