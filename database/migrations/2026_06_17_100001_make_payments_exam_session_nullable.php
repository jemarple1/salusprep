<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropForeign(['exam_session_id']);
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->unsignedBigInteger('exam_session_id')->nullable()->change();
            $table->foreign('exam_session_id')->references('id')->on('exam_sessions')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropForeign(['exam_session_id']);
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->unsignedBigInteger('exam_session_id')->nullable(false)->change();
            $table->foreign('exam_session_id')->references('id')->on('exam_sessions')->cascadeOnDelete();
        });
    }
};
