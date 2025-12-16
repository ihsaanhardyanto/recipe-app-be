<?php

namespace Database\Seeders;

use App\Models\Recipe;
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
        // Create 2 demo users with known credentials
        $user1 = User::factory()->create([
            'name' => 'Chef Budi',
            'email' => 'budi@example.com',
        ]);

        $user2 = User::factory()->create([
            'name' => 'Chef Sari',
            'email' => 'sari@example.com',
        ]);

        // Create 6 recipes for each user (total 12 recipes)
        // With pagination default 5, this creates 3 pages
        Recipe::factory()->count(6)->create(['user_id' => $user1->id]);
        Recipe::factory()->count(6)->create(['user_id' => $user2->id]);
    }
}
