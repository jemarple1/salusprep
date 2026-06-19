<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('exercise_scenario_completions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->cascadeOnDelete();
            $table->uuid('guest_token')->nullable();
            $table->string('certification_level');
            $table->string('exercise_slug');
            $table->unsignedTinyInteger('scenario_index');
            $table->timestamp('completed_at');
            $table->timestamps();

            $table->unique(
                ['user_id', 'certification_level', 'exercise_slug', 'scenario_index'],
                'exercise_completions_user_unique',
            );
            $table->unique(
                ['guest_token', 'certification_level', 'exercise_slug', 'scenario_index'],
                'exercise_completions_guest_unique',
            );
            $table->index(['certification_level', 'exercise_slug']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('exercise_scenario_completions');
    }
};
