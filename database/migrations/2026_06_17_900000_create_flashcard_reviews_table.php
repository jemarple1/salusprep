<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('flashcard_reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('question_id')->constrained()->cascadeOnDelete();
            $table->string('certification_level');
            $table->integer('ease_score')->default(0);
            $table->unsignedInteger('times_weak')->default(0);
            $table->unsignedInteger('times_strong')->default(0);
            $table->timestamp('last_reviewed_at')->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'question_id', 'certification_level'], 'flashcard_reviews_user_question_level');
            $table->index(['user_id', 'certification_level', 'ease_score']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('flashcard_reviews');
    }
};
