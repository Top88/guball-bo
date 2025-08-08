<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Crypt;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $user = User::factory([
            'full_name' => 'Super Admin',
            'email' => 'admin@guball.com',
            'phone' => '0812345678',
            'password' => Crypt::encryptString('Zaxscd123!'),
        ])->active()->create();

        $user->assignRole('super-admin');
    }
}
