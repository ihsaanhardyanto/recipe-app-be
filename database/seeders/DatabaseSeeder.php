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
     * 
     * Run with: php artisan db:seed
     */
    public function run(): void
    {
        // First, create admin user
        $this->call(AdminSeeder::class);

        // Create demo users with known credentials for testing
        $user1 = User::factory()->create([
            'name' => 'Chef Budi',
            'email' => 'budi@gmail.com',
            'role' => 'user',
        ]);

        $user2 = User::factory()->create([
            'name' => 'Chef Sari',
            'email' => 'sari@gmail.com',
            'role' => 'user',
        ]);

        $user3 = User::factory()->create([
            'name' => 'Chef Andi',
            'email' => 'andi@gmail.com',
            'role' => 'user',
        ]);

        // Create recipes for each user
        // User 1: 5 recipes
        Recipe::factory()->count(5)->create(['user_id' => $user1->id]);

        // User 2: 4 recipes
        Recipe::factory()->count(4)->create(['user_id' => $user2->id]);

        // User 3: 3 recipes
        Recipe::factory()->count(3)->create(['user_id' => $user3->id]);

        // Total: 12 recipes across 3 users + 1 admin
        // With pagination default 5, this creates 3 pages

        $this->command->info('Demo data seeded successfully!');
        $this->command->info('Demo users: budi@gmail.com, sari@gmail.com, andi@gmail.com (password: password)');
    }
}
