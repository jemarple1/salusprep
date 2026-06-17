<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('exam_sessions', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->change();
            $table->string('guest_token', 36)->nullable()->after('user_id');
            $table->index(['guest_token', 'certification_level']);
        });

        Schema::create('guest_section_progress', function (Blueprint $table) {
            $table->id();
            $table->string('guest_token', 36);
            $table->string('certification_level');
            $table->unsignedSmallInteger('free_questions_used')->default(0);
            $table->timestamps();

            $table->unique(['guest_token', 'certification_level']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('guest_section_progress');

        Schema::table('exam_sessions', function (Blueprint $table) {
            $table->dropIndex(['guest_token', 'certification_level']);
            $table->dropColumn('guest_token');
        });
    }
};
