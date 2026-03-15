<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create default admin user
        User::create([
            'name' => 'Admin',
            'email' => 'neerajanbohora123@gmail.com',
            'password' => Hash::make('neerajan@123'),
            'number' => '1234567890',
            'interested' => 'Admin',
            'address' => 'Admin Office',
            'resume_file_name' => null,
        ]);

        $this->command->info('✅ Admin user created successfully!');
        $this->command->info('📧 Email: neerajanbobora123@gmail.com');
        $this->command->info('🔑 Password: neerajan@123');
        $this->command->warn('⚠️  Please change the password after first login!');
    }
}