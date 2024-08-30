<?php

use App\Models\Items\Inspections\ItemTemplate;
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
        Schema::create('item_item_inspection', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_id');
            $table->foreign('item_id')
                ->references('id')
                ->on('items');
            $table->foreignId('item_template_id');
            $table->foreign('item_template_id')
                ->references('id')
                ->on('item_template');
            $table->foreignId('assigned_to_user_id')
                ->nullable();
            $table->foreign('assigned_to_user_id')
                ->references('id')
                ->on('users');
            $table->foreignId('completed_by_user_id')
                ->nullable();
            $table->foreign('completed_by_user_id')
                ->references('id')
                ->on('users');
            $table->foreignId('approved_by_user_id')
                ->nullable();
            $table->foreign('approved_by_user_id')
                ->references('id')
                ->on('users');
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('item_inspections');
    }
};
