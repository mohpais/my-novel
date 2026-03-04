<?php

namespace Database\Seeders;

use App\Models\Role;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            [
                'code' => 'superadmin',
                'name' => 'Super Admin',
                'description' => 'Memiliki akses penuh ke seluruh sistem dan pengelolaan website.'
            ],
            [
                'code' => 'editor',
                'name' => 'Editor',
                'description' => 'Bertanggung jawab untuk merapikan tulisanmu sebelum dipublikasikan.'
            ],
            [
                'code' => 'reviewer',
                'name' => 'Reviewer',
                'description' => 'Pembaca yang telah mendaftar ke website ini.'
            ],
        ];

        foreach ($roles as $role) {
            Role::Create($role);
        }
    }
}
