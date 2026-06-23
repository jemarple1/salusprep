<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('study_club_members', function (Blueprint $table) {
            $table->id();
            $table->string('email');
            $table->uuid('device_id')->nullable()->index();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('certification_level')->nullable();
            $table->timestamp('joined_at');
            $table->timestamp('unsubscribed_at')->nullable();
            $table->string('unsubscribe_token', 64)->unique();
            $table->timestamps();

            $table->index(['email', 'unsubscribed_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('study_club_members');
    }
};
