<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('study_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('certification_level');
            $table->string('filter_category')->nullable();
            $table->json('deck');
            $table->unsignedInteger('initial_deck_size');
            $table->unsignedInteger('cards_studied')->default(0);
            $table->string('status')->default('in_progress');
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'certification_level', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('study_sessions');
    }
};
