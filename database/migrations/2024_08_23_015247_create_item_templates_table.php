<?php

use App\Models\Items\Inspections\Template;
use App\Models\Items\Item;
use App\Models\User;
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
        Schema::create('item_template', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_id');
            $table->foreign('item_id')
                ->references('id')
                ->on('items');
            $table->foreignId('template_id');
            $table->foreign('template_id')
                ->references('id')
                ->on('item_template_types');
            $table->longText('description')
                ->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('item_template');
    }
};
