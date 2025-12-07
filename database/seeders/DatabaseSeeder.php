<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Base company for SuperAdmin (or you can keep it null)
        DB::table('companies')->insert([
            'name'       => 'Global Corp',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $companyId = DB::table('companies')->where('name', 'Global Corp')->value('id');

        $passwordHash = Hash::make('superadmin_password');

        // RAW SQL INSERT
        DB::insert(
            'INSERT INTO users (name, email, password, role, company_id, created_at, updated_at)
             VALUES (?, ?, ?, ?, ?, ?, ?)',
            [
                'Super Admin',
                'superadmin@example.com',
                $passwordHash,
                'SuperAdmin',
                $companyId,
                now(),
                now(),
            ]
        );
    }
}
