<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('guest_section_progress', function (Blueprint $table) {
            $table->string('device_id', 36)->nullable()->after('id');
        });

        DB::table('guest_section_progress')->update([
            'device_id' => DB::raw('guest_token'),
        ]);

        Schema::table('guest_section_progress', function (Blueprint $table) {
            $table->dropUnique(['guest_token', 'certification_level']);
            $table->unique(['device_id', 'certification_level']);
        });

        DB::table('settings')
            ->where('key', 'preview_actions_limit')
            ->update(['value' => '25', 'updated_at' => now()]);
    }

    public function down(): void
    {
        Schema::table('guest_section_progress', function (Blueprint $table) {
            $table->dropUnique(['device_id', 'certification_level']);
            $table->unique(['guest_token', 'certification_level']);
            $table->dropColumn('device_id');
        });

        DB::table('settings')
            ->where('key', 'preview_actions_limit')
            ->update(['value' => '22', 'updated_at' => now()]);
    }
};
