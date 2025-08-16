<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Role;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Buat semua role
        $roles = [
            'admin',
            'ob',
            'teknisi',
            'superadmin',
        ];

        $roleMap = [];

        foreach ($roles as $roleName) {
            $roleMap[$roleName] = Role::create([
                'name_role' => $roleName,
                'created_by' => null,
                'updated_by' => null,
            ]);
        }

        // Buat semua user
        $users = [
            [
                'name' => 'Teknisi 1',
                'email' => 'teknisi1@gmail.com',
                'password' => Hash::make('12345678'),
                'role' => 'teknisi',
            ],
            [
                'name' => 'OB 1',
                'email' => 'ob1@gmail.com',
                'password' => Hash::make('12345678'),
                'role' => 'ob',
            ],
            [
                'name' => 'Dev Admin',
                'email' => 'devadmin@yahoo.com',
                'password' => Hash::make('12345678'),
                'role' => 'superadmin',
            ],
            [
                'name' => 'Admin 1',
                'email' => 'admin1@gmail.com',
                'password' => Hash::make('12345678'),
                'role' => 'admin',
            ],
        ];

        foreach ($users as $data) {
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => $data['password'],
                'id_role' => $roleMap[$data['role']]->id,
            ]);

            // Optional: update created_by dan updated_by pada role
            $roleMap[$data['role']]->update([
                'created_by' => $user->id,
                'updated_by' => $user->id,
            ]);
        }
    }
}
