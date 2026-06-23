<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('guest_devices', function (Blueprint $table) {
            $table->uuid('device_id')->primary();
            $table->string('first_ip', 45)->nullable();
            $table->string('country_code', 2)->nullable();
            $table->string('country_name')->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->string('referrer', 2048)->nullable();
            $table->string('referrer_host')->nullable();
            $table->string('landing_path', 512)->nullable();
            $table->string('utm_source')->nullable();
            $table->string('utm_medium')->nullable();
            $table->string('utm_campaign')->nullable();
            $table->timestamp('first_seen_at');
            $table->timestamp('last_seen_at');
            $table->unsignedInteger('total_active_seconds')->default(0);
            $table->foreignId('converted_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('converted_at')->nullable();
            $table->timestamps();

            $table->index('last_seen_at');
            $table->index('first_seen_at');
            $table->index('country_code');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('guest_devices');
    }
};
