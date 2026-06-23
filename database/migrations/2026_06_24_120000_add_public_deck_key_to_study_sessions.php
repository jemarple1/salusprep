<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('study_sessions', function (Blueprint $table) {
            $table->string('public_deck_key')->nullable()->after('filter_category');
            $table->index(['certification_level', 'public_deck_key']);
        });
    }

    public function down(): void
    {
        Schema::table('study_sessions', function (Blueprint $table) {
            $table->dropIndex(['certification_level', 'public_deck_key']);
            $table->dropColumn('public_deck_key');
        });
    }
};
