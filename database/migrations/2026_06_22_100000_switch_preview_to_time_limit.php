<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('preview_devices', function (Blueprint $table) {
            $table->string('device_id', 36)->primary();
            $table->timestamp('preview_started_at');
            $table->timestamps();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->timestamp('preview_started_at')->nullable()->after('remember_token');
        });

        DB::table('settings')->updateOrInsert(
            ['key' => 'preview_minutes_limit'],
            ['value' => '20', 'created_at' => now(), 'updated_at' => now()],
        );
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('preview_started_at');
        });

        Schema::dropIfExists('preview_devices');

        DB::table('settings')->where('key', 'preview_minutes_limit')->delete();
    }
};
