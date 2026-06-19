<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('exercise_scenario_completions', function (Blueprint $table) {
            $table->unsignedTinyInteger('exercise_level')->default(1)->after('exercise_slug');

            $table->dropUnique('exercise_completions_user_unique');
            $table->dropUnique('exercise_completions_guest_unique');

            $table->unique(
                ['user_id', 'certification_level', 'exercise_slug', 'exercise_level', 'scenario_index'],
                'exercise_completions_user_unique',
            );
            $table->unique(
                ['guest_token', 'certification_level', 'exercise_slug', 'exercise_level', 'scenario_index'],
                'exercise_completions_guest_unique',
            );
        });
    }

    public function down(): void
    {
        Schema::table('exercise_scenario_completions', function (Blueprint $table) {
            $table->dropUnique('exercise_completions_user_unique');
            $table->dropUnique('exercise_completions_guest_unique');

            $table->dropColumn('exercise_level');

            $table->unique(
                ['user_id', 'certification_level', 'exercise_slug', 'scenario_index'],
                'exercise_completions_user_unique',
            );
            $table->unique(
                ['guest_token', 'certification_level', 'exercise_slug', 'scenario_index'],
                'exercise_completions_guest_unique',
            );
        });
    }
};
