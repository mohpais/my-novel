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
                'description' => 'Memiliki akses penuh ke seluruh sistem dan pengelolaan pengguna.'
            ],
            [
                'code' => 'managing_director',
                'name' => 'Managing Director',
                'description' => 'Bertanggung jawab atas pengambilan keputusan strategis dan pengawasan operasional.'
            ],
            [
                'code' => 'hod_finance',
                'name' => 'Head of Finance Department',
                'description' => 'Memimpin departemen keuangan dan mengelola kebijakan keuangan perusahaan.'
            ],
            [
                'code' => 'finance_controller',
                'name' => 'Finance Controller',
                'description' => 'Mengawasi pelaporan keuangan dan memastikan kepatuhan terhadap regulasi.'
            ],
            [
                'code' => 'procurement',
                'name' => 'Procurement',
                'description' => 'Mengelola proses pengadaan barang dan jasa untuk kebutuhan perusahaan.'
            ],
            [
                'code' => 'budget_owner',
                'name' => 'Budget Owner',
                'description' => 'Bertanggung jawab atas pengelolaan dan pengawasan anggaran tertentu.'
            ],
            [
                'code' => 'requester',
                'name' => 'Requester',
                'description' => 'Mengajukan permintaan pembelian atau kebutuhan barang/jasa.'
            ],
            [
                'code' => 'finance_ksea',
                'name' => 'Finance KSEA',
                'description' => 'Finance Pusat'
            ],
        ];

        foreach ($roles as $role) {
            Role::Create($role);
        }
    }
}
