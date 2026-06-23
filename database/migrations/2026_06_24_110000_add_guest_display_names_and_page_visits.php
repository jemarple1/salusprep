<?php

use App\Models\GuestDevice;
use App\Support\GuestNickname;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('guest_devices', function (Blueprint $table) {
            $table->string('display_name')->nullable()->after('device_id');
            $table->index('display_name');
        });

        GuestDevice::query()->each(function (GuestDevice $device): void {
            $device->update([
                'display_name' => GuestNickname::fromDeviceId($device->device_id),
            ]);
        });

        Schema::create('guest_page_visits', function (Blueprint $table) {
            $table->id();
            $table->uuid('device_id');
            $table->string('path', 512);
            $table->string('route_name')->nullable();
            $table->timestamp('visited_at');
            $table->timestamps();

            $table->foreign('device_id')
                ->references('device_id')
                ->on('guest_devices')
                ->cascadeOnDelete();

            $table->index(['device_id', 'visited_at']);
            $table->index(['device_id', 'path', 'visited_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('guest_page_visits');

        Schema::table('guest_devices', function (Blueprint $table) {
            $table->dropIndex(['display_name']);
            $table->dropColumn('display_name');
        });
    }
};
