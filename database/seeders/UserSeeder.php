<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * PLACEHOLDER — Ganti password di bawah sebelum deploy ke production!
     */
    public function run(): void
    {
        $roles = Role::all()->keyBy('slug');

        $accounts = [
            ['email' => 'superadmin@ptjts.id', 'name' => 'Super Admin JTS', 'role' => 'super_admin', 'password' => 'SuperAdmin@JTS2024!'],
            ['email' => 'admin@ptjts.id', 'name' => 'Administrator JTS', 'role' => 'admin', 'password' => 'Admin@JTS2024!'],
            ['email' => 'editor@ptjts.id', 'name' => '[PLACEHOLDER] Editor Blog JTS', 'role' => 'editor', 'password' => 'Editor@JTS2024!'],
            ['email' => 'marketing@ptjts.id', 'name' => '[PLACEHOLDER] Tim Marketing JTS', 'role' => 'marketing', 'password' => 'Marketing@JTS2024!'],
            ['email' => 'operator@ptjts.id', 'name' => '[PLACEHOLDER] NOC Operator JTS', 'role' => 'operator', 'password' => 'Operator@JTS2024!'],
        ];

        foreach ($accounts as $account) {
            User::updateOrCreate(
                ['email' => $account['email']],
                [
                    'uuid' => Str::uuid(),
                    'name' => $account['name'],
                    'role_id' => $roles[$account['role']]?->id,
                    'status' => 'active',
                    'email_verified_at' => now(),
                    'password' => Hash::make($account['password']),
                ]
            );
        }

        $this->command->info('Users seeded: '.count($accounts).' default admin accounts created.');
        $this->command->warn('⚠  PERHATIAN: Segera ganti password default sebelum deploy ke production!');
    }
}
