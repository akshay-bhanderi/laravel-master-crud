<?php

namespace AkshayBhanderi\LaravelMasterCrud\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

/**
 * Seeds exactly one role (role_id = 1, "Super Admin") and one user
 * (user_id = 1) — the root account. role_id = 1 is hardcoded throughout
 * this package (Access::is_allowed()) as the permission-check bypass, and
 * user_id = 1 is excluded from every listing/edit query (User::list(),
 * User::edit(), etc.) so it can never be edited or deleted via the UI.
 *
 * Safe to re-run: uses updateOrInsert keyed on the fixed IDs.
 */
class MasterCrudSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('user_roles')->updateOrInsert(
            ['role_id' => 1],
            [
                'role_title' => 'Super Admin',
                'role_permission' => json_encode([]),
                'status' => 1,
                'is_delete' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        $password = 'password';

        DB::table('users')->updateOrInsert(
            ['user_id' => 1],
            [
                'user_role_id' => 1,
                'user_name' => 'Admin',
                'user_email' => 'admin@example.com',
                'user_phone_no' => '9999999999',
                'user_password' => Hash::make($password),
                'user_sweet_word' => $password,
                'user_sweet_words' => json_encode([$password]),
                'user_gender' => 1,
                'status' => 1,
                'is_delete' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        $this->command?->info('Seeded Super Admin role (role_id=1) and root user (user_id=1).');
        $this->command?->warn('Default login — email: admin@example.com / phone: 9999999999, password: "'.$password.'". Change it immediately.');
    }
}
