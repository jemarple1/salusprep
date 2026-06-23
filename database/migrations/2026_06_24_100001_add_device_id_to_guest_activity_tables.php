<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('exam_sessions', function (Blueprint $table) {
            $table->string('device_id', 36)->nullable()->after('guest_token')->index();
        });

        Schema::table('study_sessions', function (Blueprint $table) {
            $table->string('device_id', 36)->nullable()->after('guest_token')->index();
        });

        Schema::table('exercise_scenario_completions', function (Blueprint $table) {
            $table->string('device_id', 36)->nullable()->after('guest_token')->index();
        });
    }

    public function down(): void
    {
        Schema::table('exam_sessions', function (Blueprint $table) {
            $table->dropIndex(['device_id']);
            $table->dropColumn('device_id');
        });

        Schema::table('study_sessions', function (Blueprint $table) {
            $table->dropIndex(['device_id']);
            $table->dropColumn('device_id');
        });

        Schema::table('exercise_scenario_completions', function (Blueprint $table) {
            $table->dropIndex(['device_id']);
            $table->dropColumn('device_id');
        });
    }
};
