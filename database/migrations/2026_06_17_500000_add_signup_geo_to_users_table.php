<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('signup_country_code', 2)->nullable()->after('last_login_at');
            $table->string('signup_country_name')->nullable()->after('signup_country_code');
            $table->decimal('signup_latitude', 10, 7)->nullable()->after('signup_country_name');
            $table->decimal('signup_longitude', 10, 7)->nullable()->after('signup_latitude');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'signup_country_code',
                'signup_country_name',
                'signup_latitude',
                'signup_longitude',
            ]);
        });
    }
};
