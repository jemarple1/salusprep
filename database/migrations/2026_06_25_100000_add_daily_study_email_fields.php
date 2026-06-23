<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('daily_study_email_opt_in')->default(true)->after('marketing_emails_opt_in');
        });

        Schema::table('section_accesses', function (Blueprint $table) {
            $table->date('last_daily_study_email_sent_on')->nullable()->after('exam_date');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('daily_study_email_opt_in');
        });

        Schema::table('section_accesses', function (Blueprint $table) {
            $table->dropColumn('last_daily_study_email_sent_on');
        });
    }
};
