<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $username = env('ADMIN_USERNAME');
        $password = env('ADMIN_PASSWORD');

        if (! is_string($username) || $username === '' || ! is_string($password) || $password === '') {
            return;
        }

        Admin::query()->updateOrCreate(
            ['username' => $username],
            ['password' => $password],
        );
    }
}
