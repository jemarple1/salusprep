<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('guest_section_progress', function (Blueprint $table) {
            $table->timestamp('preview_started_at')->nullable()->after('certification_level');
        });

        Schema::table('section_accesses', function (Blueprint $table) {
            $table->timestamp('preview_started_at')->nullable()->after('certification_level');
        });
    }

    public function down(): void
    {
        Schema::table('guest_section_progress', function (Blueprint $table) {
            $table->dropColumn('preview_started_at');
        });

        Schema::table('section_accesses', function (Blueprint $table) {
            $table->dropColumn('preview_started_at');
        });
    }
};
