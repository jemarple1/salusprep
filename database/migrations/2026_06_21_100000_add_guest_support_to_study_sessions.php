<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('study_sessions', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->change();
            $table->string('guest_token', 36)->nullable()->after('user_id');
            $table->index(['guest_token', 'certification_level', 'status']);
        });
    }

    public function down(): void
    {
        Schema::table('study_sessions', function (Blueprint $table) {
            $table->dropIndex(['guest_token', 'certification_level', 'status']);
            $table->dropColumn('guest_token');
        });
    }
};
