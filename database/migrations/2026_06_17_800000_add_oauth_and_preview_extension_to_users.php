<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('google_id')->nullable()->unique()->after('password');
            $table->string('facebook_id')->nullable()->unique()->after('google_id');
            $table->string('twitter_id')->nullable()->unique()->after('facebook_id');
            $table->timestamp('preview_extension_granted_at')->nullable()->after('preview_started_at');
            $table->timestamp('preview_extension_ends_at')->nullable()->after('preview_extension_granted_at');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'google_id',
                'facebook_id',
                'twitter_id',
                'preview_extension_granted_at',
                'preview_extension_ends_at',
            ]);
        });
    }
};
