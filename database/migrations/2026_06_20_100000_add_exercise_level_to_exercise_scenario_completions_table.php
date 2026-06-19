<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('exercise_scenario_completions')) {
            return;
        }

        if (! Schema::hasColumn('exercise_scenario_completions', 'exercise_level')) {
            Schema::table('exercise_scenario_completions', function (Blueprint $table) {
                $table->unsignedTinyInteger('exercise_level')->default(1)->after('exercise_slug');
            });
        }

        $this->upgradeUniqueIndexes();
    }

    public function down(): void
    {
        if (! Schema::hasTable('exercise_scenario_completions')) {
            return;
        }

        if (! Schema::hasColumn('exercise_scenario_completions', 'exercise_level')) {
            return;
        }

        Schema::table('exercise_scenario_completions', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });

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

        Schema::table('exercise_scenario_completions', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
        });
    }

    private function upgradeUniqueIndexes(): void
    {
        Schema::table('exercise_scenario_completions', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });

        Schema::table('exercise_scenario_completions', function (Blueprint $table) {
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

        Schema::table('exercise_scenario_completions', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
        });
    }
};
