<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->string('category');
            $table->unsignedTinyInteger('difficulty');
            $table->text('stem');
            $table->string('option_a');
            $table->string('option_b');
            $table->string('option_c');
            $table->string('option_d');
            $table->char('correct_option', 1);
            $table->text('explanation')->nullable();
            $table->timestamps();

            $table->index(['difficulty', 'category']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('questions');
    }
};
