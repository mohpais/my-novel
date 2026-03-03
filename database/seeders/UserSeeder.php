<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use App\Models\Province;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            ['name' => 'Superadmin', 'email' => 'superadmin@kone.com', 'role' => 'superadmin'],
            ['name' => 'Budi Silaban', 'email' => 'budi@kone.com', 'role' => 'managing_director'],
            ['name' => 'Shervy Tjia', 'email' => 'shervy.tjia@kone.com', 'role' => 'hod_finance'],
            ['name' => 'Dimas Prasetyo', 'email' => 'dimasp@kone.com', 'role' => 'finance_controller'],
            ['name' => 'Feri Fitrianto', 'email' => 'feri.fitrianto@kone.com', 'role' => 'procurement'],
            ['name' => 'Rizky Maulana', 'email' => 'maulanar@kone.com', 'role' => 'budget_owner'],
            ['name' => 'Julian Valentino', 'email' => 'julian.valen@kone.com', 'role' => 'requester'],
            ['name' => 'Mohamad Pais', 'email' => 'm.pais@kone.com', 'role' => 'requester'],
            ['name' => 'Claudia Tong', 'email' => 'claudia.tong@kone.com', 'role' => 'finance_ksea'],
        ];

        foreach ($users as $user) {
            $createdUser = User::create([
                'name' => $user['name'],
                'email' => $user['email'],
                'password' => Hash::make('Admin123!'),
                'role_id' => Role::where('code', $user['role'])->first()->id,
            ]);
        }
    }
}
