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
        Schema::create('item_template_media', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_template_id');
            $table->foreign('item_template_id')
                ->references('id')
                ->on('item_template');
            $table->foreignId('media_id');
            $table->foreign('media_id')
                ->references('id')
                ->on('media');
            $table->integer('order');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('item_template_media');
    }
};
