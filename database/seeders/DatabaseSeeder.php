<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Administrator;
use App\Models\Comment;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Administrator::factory()
            ->hasProfiles(5)
            ->count(3)
            ->create()
            ->each(function ($administrator) {
                $administrator->profiles->each(function ($profile) use ($administrator) {
                    Comment::factory()
                        ->for($administrator)
                        ->for($profile)
                        ->create();
                });
            });
    }
}
