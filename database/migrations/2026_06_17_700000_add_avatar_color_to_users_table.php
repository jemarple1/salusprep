<?php

use App\Support\UserAvatar;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('avatar_color', 16)->default('green')->after('name');
        });

        foreach (DB::table('users')->pluck('id') as $id) {
            DB::table('users')
                ->where('id', $id)
                ->update(['avatar_color' => UserAvatar::randomColor()]);
        }
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('avatar_color');
        });
    }
};
