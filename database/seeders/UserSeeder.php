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
            ['name' => 'Mohamad Pais', 'email' => 'mohamad.pais30@gmail.com', 'role' => 'superadmin'],
            ['name' => 'Ani Riyani', 'email' => 'riyanii19@gmail.com', 'role' => 'editor']
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
