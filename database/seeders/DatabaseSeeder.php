<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(QuestionSeeder::class);
        $this->call(AdminSeeder::class);

        if (! app()->environment('local')) {
            return;
        }

        User::query()->updateOrCreate(
            ['email' => 'demo@salusprep.test'],
            [
                'name' => 'Demo Student',
                'password' => bcrypt('password'),
            ],
        );
    }
}
