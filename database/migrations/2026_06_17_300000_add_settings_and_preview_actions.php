<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->timestamps();
        });

        DB::table('settings')->insert([
            'key' => 'preview_actions_limit',
            'value' => '25',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        Schema::table('section_accesses', function (Blueprint $table) {
            $table->unsignedSmallInteger('preview_actions_used')->default(0)->after('certification_level');
        });

        DB::table('section_accesses')->update([
            'preview_actions_used' => DB::raw('free_questions_used'),
        ]);

        Schema::table('section_accesses', function (Blueprint $table) {
            $table->dropColumn('free_questions_used');
        });

        Schema::table('guest_section_progress', function (Blueprint $table) {
            $table->unsignedSmallInteger('preview_actions_used')->default(0)->after('certification_level');
        });

        DB::table('guest_section_progress')->update([
            'preview_actions_used' => DB::raw('free_questions_used'),
        ]);

        Schema::table('guest_section_progress', function (Blueprint $table) {
            $table->dropColumn('free_questions_used');
        });
    }

    public function down(): void
    {
        Schema::table('guest_section_progress', function (Blueprint $table) {
            $table->unsignedSmallInteger('free_questions_used')->default(0)->after('certification_level');
        });

        DB::table('guest_section_progress')->update([
            'free_questions_used' => DB::raw('preview_actions_used'),
        ]);

        Schema::table('guest_section_progress', function (Blueprint $table) {
            $table->dropColumn('preview_actions_used');
        });

        Schema::table('section_accesses', function (Blueprint $table) {
            $table->unsignedSmallInteger('free_questions_used')->default(0)->after('certification_level');
        });

        DB::table('section_accesses')->update([
            'free_questions_used' => DB::raw('preview_actions_used'),
        ]);

        Schema::table('section_accesses', function (Blueprint $table) {
            $table->dropColumn('preview_actions_used');
        });

        Schema::dropIfExists('settings');
    }
};
