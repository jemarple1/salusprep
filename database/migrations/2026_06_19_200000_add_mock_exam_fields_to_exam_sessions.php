<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('exam_sessions', function (Blueprint $table) {
            $table->string('exam_type', 16)->default('quiz')->after('certification_level');
            $table->timestamp('expires_at')->nullable()->after('completed_at');
            $table->string('mock_outcome', 16)->nullable()->after('expires_at');
            $table->decimal('ability_estimate', 5, 4)->nullable()->after('mock_outcome');
        });
    }

    public function down(): void
    {
        Schema::table('exam_sessions', function (Blueprint $table) {
            $table->dropColumn(['exam_type', 'expires_at', 'mock_outcome', 'ability_estimate']);
        });
    }
};
