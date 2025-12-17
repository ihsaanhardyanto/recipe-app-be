<?php

namespace Database\Seeders;

use App\Models\User;
;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
  /**
   * Create admin user from environment variables.
   * 
   * Run with: php artisan db:seed --class=AdminSeeder
   */
  public function run(): void
  {
    $adminEmail = env('ADMIN_EMAIL', 'admin@gmail.com');

    // Check if admin already exists
    if (User::where('email', $adminEmail)->exists()) {
      $this->command->info("Admin user with email {$adminEmail} already exists. Skipping.");
      return;
    }

    User::create([
      'name' => env('ADMIN_NAME', 'Administrator'),
      'email' => $adminEmail,
      'password' => Hash::make(env('ADMIN_PASSWORD', 'password')),
      'role' => 'admin',
      'email_verified_at' => now(),
    ]);

    $this->command->info("Admin user created successfully with email: {$adminEmail}");
  }
}
