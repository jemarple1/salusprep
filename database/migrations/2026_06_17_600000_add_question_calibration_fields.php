<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('questions', function (Blueprint $table) {
            $table->string('source_key')->nullable()->unique()->after('id');
            $table->unsignedTinyInteger('initial_difficulty')->nullable()->after('difficulty');
            $table->timestamp('difficulty_calibrated_at')->nullable()->after('initial_difficulty');
        });

        DB::table('questions')->orderBy('id')->chunkById(200, function ($questions) {
            foreach ($questions as $question) {
                DB::table('questions')
                    ->where('id', $question->id)
                    ->update([
                        'source_key' => 'legacy-'.$question->id,
                        'initial_difficulty' => $question->difficulty,
                    ]);
            }
        });
    }

    public function down(): void
    {
        Schema::table('questions', function (Blueprint $table) {
            $table->dropColumn(['source_key', 'initial_difficulty', 'difficulty_calibrated_at']);
        });
    }
};
