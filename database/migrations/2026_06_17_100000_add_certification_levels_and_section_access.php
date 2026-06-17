<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('questions', function (Blueprint $table) {
            $table->string('certification_level')->default('emt_basic')->after('id');
            $table->index(['certification_level', 'difficulty']);
        });

        Schema::table('exam_sessions', function (Blueprint $table) {
            $table->string('certification_level')->default('emt_basic')->after('user_id');
            $table->dropColumn('paid_at');
        });

        Schema::create('section_accesses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('certification_level');
            $table->unsignedSmallInteger('free_questions_used')->default(0);
            $table->timestamp('unlocked_at')->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'certification_level']);
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->string('certification_level')->nullable()->after('exam_session_id');
            $table->string('stripe_checkout_session_id')->nullable()->after('provider');
            $table->string('stripe_payment_intent_id')->nullable()->after('stripe_checkout_session_id');
        });
    }

    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn(['certification_level', 'stripe_checkout_session_id', 'stripe_payment_intent_id']);
        });

        Schema::dropIfExists('section_accesses');

        Schema::table('exam_sessions', function (Blueprint $table) {
            $table->timestamp('paid_at')->nullable();
            $table->dropColumn('certification_level');
        });

        Schema::table('questions', function (Blueprint $table) {
            $table->dropColumn('certification_level');
        });
    }
};
