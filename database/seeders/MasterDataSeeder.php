<?php

namespace Database\Seeders;

use App\Models\Budget;
use App\Models\CostCenter;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MasterDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Approval Status
        DB::table('request_statuses')->insert([
            [
                'code' => 'draft',
                'label' => 'Draft',
                'description' => 'Request masih dalam penyusunan oleh requester',
            ],
            [
                'code' => 'submitted',
                'label' => 'Submitted',
                'description' => 'Request telah dibuat',
            ],
            [
                'code' => 'waiting_approval',
                'label' => 'Waiting Approval',
                'description' => 'Request sedang menunggu persetujuan dari approver',
            ],
            [
                'code' => 'approved',
                'label' => 'Approved',
                'description' => 'Request telah disetujui',
            ],
            [
                'code' => 'rejected',
                'label' => 'Rejected',
                'description' => 'Request ditolak oleh approver',
            ],
            // [
            //     'code' => 'waiting_quotation',
            //     'label' => 'Waiting Quotation',
            //     'description' => 'Request sedang menunggu quotation dari budget owner',
            // ],
            // [
            //     'code' => 'quotation_submitted',
            //     'label' => 'Quotation Submitted',
            //     'description' => 'Quotation telah disubmit',
            // ],
            [
                'code' => 'revision_requested',
                'label' => 'Revision Requested',
                'description' => 'Approver meminta revisi dari requester',
            ],
            [
                'code' => 'revision_submitted',
                'label' => 'Revision Submitted',
                'description' => 'Revisi telah disubmit',
            ],
            [
                'code' => 'completed',
                'label' => 'Completed',
                'description' => 'Request sudah selesai diproses',
            ],
            [
                'code' => 'cancelled',
                'label' => 'Cancelled',
                'description' => 'Request dibatalkan',
            ],
        ]);
        // DB::table('request_statuses')->insert([
        //     ['code' => 'draft', 'name' => 'Draft', 'actor' => null],
        //     ['code' => 'submitted', 'name' => 'Submitted', 'actor' => null],
        //     // ['code' => 'waiting_for_procurement_process', 'name' => 'Waiting for The Procurement Proses', 'actor' => 'procurement'],
        //     // ['code' => 'procurement_submitted', 'name' => 'Request for Quotation Submitted', 'actor' => null],
        //     // ['code' => 'vendor_evaluation', 'name' => 'Vendor Evaluation & Selection Stage', 'actor' => 'procurement'],
        //     // ['code' => 'vendor_selected', 'name' => 'Vendor Evaluation & Selection', 'actor' => null],
        //     ['code' => 'waiting_approval_budget_owner', 'name' => 'Waiting Approval Budget Owner', 'actor' => 'budget_owner'],
        //     ['code' => 'waiting_approval_finance_controller', 'name' => 'Waiting Approval Finance Controller', 'actor' => 'finance_controller'],
        //     ['code' => 'waiting_approval_head_finance', 'name' => 'Waiting Approval Head of Finance', 'actor' => 'hod_finance'],
        //     ['code' => 'waiting_approval_managing_director', 'name' => 'Waiting Approval Managing Director', 'actor' => 'managing_director'],
        //     ['code' => 'waiting_approval_finance_ksea', 'name' => 'Waiting Approval Finance KSEA', 'actor' => 'finance_ksea'],
        //     ['code' => 'approved', 'name' => 'Approved', 'actor' => null],
        //     ['code' => 'rejected', 'name' => 'Rejected', 'actor' => null],
        //     // ['code' => 'need_revision', 'name' => 'Need Revision', 'actor' => null],
        //     // ['code' => 'revised', 'name' => 'Revised', 'actor' => null],
        //     // ['code' => 'waiting_acknowledge', 'name' => 'Waiting Acknowledge', 'actor' => null],
        //     // ['code' => 'acknowledged', 'name' => 'Acknowledged', 'actor' => null],
        //     ['code' => 'finished', 'name' => 'Finished', 'actor' => null],
        // ]);

        // Departments
        DB::table('departments')->insert([
            ['code' => 'HRD', 'name' => 'Human Resources', 'description' => 'Mengelola SDM, rekrutmen, training, payroll'],
            ['code' => 'FIN', 'name' => 'Finance', 'description' => 'Mengelola keuangan, anggaran, laporan keuangan'],
            ['code' => 'ACC', 'name' => 'Accounting', 'description' => 'Akuntansi, pembukuan, pajak'],
            ['code' => 'MKT', 'name' => 'Marketing', 'description' => 'Promosi, branding, riset pasar'],
            ['code' => 'SLS', 'name' => 'Sales', 'description' => 'Penjualan, hubungan dengan pelanggan'],
            ['code' => 'PRD', 'name' => 'Production', 'description' => 'Produksi barang/jasa'],
            ['code' => 'QAC', 'name' => 'Quality Control / Assurance', 'description' => 'Pengawasan dan penjaminan kualitas'],
            ['code' => 'ENG', 'name' => 'Engineering / Technical', 'description' => 'Pengembangan teknis, R&D, pemeliharaan mesin'],
            ['code' => 'ITD', 'name' => 'Information Technology', 'description' => 'Pengelolaan sistem & infrastruktur IT'],
            ['code' => 'PRC', 'name' => 'Procurement / Purchasing', 'description' => 'Pengadaan barang dan jasa'],
            ['code' => 'LOG', 'name' => 'Logistics', 'description' => 'Distribusi, gudang, pengiriman'],
            ['code' => 'LEG', 'name' => 'Legal', 'description' => 'Hukum, perizinan, kontrak'],
            ['code' => 'GA',  'name' => 'General Affairs', 'description' => 'Umum, fasilitas kantor, administrasi'],
            ['code' => 'CS',  'name' => 'Customer Service', 'description' => 'Layanan pelanggan, after sales'],
            ['code' => 'RND', 'name' => 'Research and Development', 'description' => 'Penelitian & pengembangan produk'],
        ]);

        // Positions
        DB::table('positions')->insert([
            ['code' => 'DIR', 'name' => 'Direktur Utama', 'description' => 'Pimpinan tertinggi perusahaan'],
            ['code' => 'DRI', 'name' => 'Direktur', 'description' => 'Memimpin divisi/departemen strategis'],
            ['code' => 'GM',  'name' => 'General Manager', 'description' => 'Mengawasi seluruh operasi perusahaan atau cabang'],
            ['code' => 'AGM', 'name' => 'Assistant General Manager', 'description' => 'Membantu GM dalam mengelola operasional'],
            ['code' => 'MGR', 'name' => 'Manager', 'description' => 'Memimpin satu departemen'],
            ['code' => 'AM',  'name' => 'Assistant Manager', 'description' => 'Membantu Manager'],
            ['code' => 'SPV', 'name' => 'Supervisor', 'description' => 'Mengawasi tim kecil di dalam departemen'],
            ['code' => 'LDR', 'name' => 'Team Leader', 'description' => 'Memimpin kelompok kerja tertentu'],
            ['code' => 'SPM', 'name' => 'Sr. Project Manager', 'description' => null],
            ['code' => 'SR',  'name' => 'Senior Staff', 'description' => 'Staf berpengalaman di bidangnya'],
            ['code' => 'STF', 'name' => 'Staff', 'description' => 'Pelaksana operasional harian'],
            ['code' => 'JRF', 'name' => 'Junior Staff', 'description' => 'Staf baru atau level entry'],
            ['code' => 'INT', 'name' => 'Intern / Magang', 'description' => 'Peserta magang / trainee'],
            ['code' => 'OFC', 'name' => 'Officer', 'description' => 'Posisi staf dengan tanggung jawab khusus (umum di bank)'],
            ['code' => 'ENG', 'name' => 'Engineer', 'description' => 'Spesialis teknis/rekayasa'],
            ['code' => 'TCH', 'name' => 'Technician', 'description' => 'Teknisi lapangan atau perawatan mesin'],
            ['code' => 'CSO', 'name' => 'Customer Service Officer', 'description' => 'Layanan pelanggan'],
            ['code' => 'ACC', 'name' => 'Accountant', 'description' => 'Akuntansi dan keuangan'],
            ['code' => 'SEC', 'name' => 'Secretary', 'description' => 'Sekretaris pimpinan/departemen'],
            ['code' => 'DRV', 'name' => 'Driver', 'description' => 'Sekretaris pimpinan/departemen'],
            ['code' => 'CLN', 'name' => 'Cleaner', 'description' => 'Petugas kebersihan kantor/pabrik'],
        ]);

        // Regions
        DB::table('regions')->insert([
            ['code' => 'R01', 'name' => 'Sumatera'],
            ['code' => 'R02', 'name' => 'Jawa'],
            ['code' => 'R03', 'name' => 'Kalimantan'],
            ['code' => 'R04', 'name' => 'Sulawesi'],
            ['code' => 'R05', 'name' => 'Bali & Nusa Tenggara'],
            ['code' => 'R06', 'name' => 'Maluku'],
            ['code' => 'R07', 'name' => 'Papua'],
        ]);

        // Provinces
        DB::table('provinces')->insert([
            // Sumatera
            ['code' => '11', 'name' => 'Aceh', 'region_id' => 1],
            ['code' => '12', 'name' => 'Sumatera Utara', 'region_id' => 1],
            ['code' => '13', 'name' => 'Sumatera Barat', 'region_id' => 1],
            ['code' => '14', 'name' => 'Riau', 'region_id' => 1],
            ['code' => '15', 'name' => 'Jambi', 'region_id' => 1],
            ['code' => '16', 'name' => 'Sumatera Selatan', 'region_id' => 1],
            ['code' => '17', 'name' => 'Bengkulu', 'region_id' => 1],
            ['code' => '18', 'name' => 'Lampung', 'region_id' => 1],
            ['code' => '19', 'name' => 'Kepulauan Bangka Belitung', 'region_id' => 1],
            ['code' => '21', 'name' => 'Kepulauan Riau', 'region_id' => 1],

            // Jawa
            ['code' => '31', 'name' => 'DKI Jakarta', 'region_id' => 2],
            ['code' => '32', 'name' => 'Jawa Barat', 'region_id' => 2],
            ['code' => '33', 'name' => 'Jawa Tengah', 'region_id' => 2],
            ['code' => '34', 'name' => 'DI Yogyakarta', 'region_id' => 2],
            ['code' => '35', 'name' => 'Jawa Timur', 'region_id' => 2],

            // Kalimantan
            ['code' => '61', 'name' => 'Kalimantan Barat', 'region_id' => 3],
            ['code' => '62', 'name' => 'Kalimantan Tengah', 'region_id' => 3],
            ['code' => '63', 'name' => 'Kalimantan Selatan', 'region_id' => 3],
            ['code' => '64', 'name' => 'Kalimantan Timur', 'region_id' => 3],
            ['code' => '65', 'name' => 'Kalimantan Utara', 'region_id' => 3],

            // Sulawesi
            ['code' => '71', 'name' => 'Sulawesi Utara', 'region_id' => 4],
            ['code' => '72', 'name' => 'Sulawesi Tengah', 'region_id' => 4],
            ['code' => '73', 'name' => 'Sulawesi Selatan', 'region_id' => 4],
            ['code' => '74', 'name' => 'Sulawesi Tenggara', 'region_id' => 4],
            ['code' => '75', 'name' => 'Gorontalo', 'region_id' => 4],
            ['code' => '76', 'name' => 'Sulawesi Barat', 'region_id' => 4],

            // Bali & Nusa Tenggara
            ['code' => '51', 'name' => 'Bali', 'region_id' => 5],
            ['code' => '52', 'name' => 'Nusa Tenggara Barat', 'region_id' => 5],
            ['code' => '53', 'name' => 'Nusa Tenggara Timur', 'region_id' => 5],

            // Maluku
            ['code' => '81', 'name' => 'Maluku', 'region_id' => 6],
            ['code' => '82', 'name' => 'Maluku Utara', 'region_id' => 6],

            // Papua
            ['code' => '91', 'name' => 'Papua Barat Daya', 'region_id' => 7],
            ['code' => '92', 'name' => 'Papua Barat', 'region_id' => 7],
            ['code' => '93', 'name' => 'Papua', 'region_id' => 7],
            ['code' => '94', 'name' => 'Papua Tengah', 'region_id' => 7],
            ['code' => '95', 'name' => 'Papua Pegunungan', 'region_id' => 7],
            ['code' => '96', 'name' => 'Papua Selatan', 'region_id' => 7],
        ]);

        // Profit Centers
        DB::table('profit_centers')->insert([
            ['code' => '244101', 'description' => 'Mengawasi operasional dan penjualan di wilayah Jakarta Raya yang dikelola oleh unit bisnis ELB dan VA, termasuk manajemen proyek dan pengawasan zona.'],
            ['code' => '244102', 'description' => 'Bertanggung jawab atas operasional, penjualan, dan manajemen umum di wilayah Surabaya Raya, melibatkan unit bisnis ADM, ELB, dan VA.'],
            ['code' => '244104', 'description' => 'Mengelola operasional dan penjualan di wilayah Jawa Tengah, yang dilakukan oleh unit bisnis ELB dan VA.'],
            ['code' => '244106', 'description' => 'Mengawasi operasional Jawa Barat di bawah unit bisnis ELB dan VA.'],
            ['code' => '244107', 'description' => 'Mengelola operasional di wilayah Kalimantan di bawah unit bisnis VA.'],
            ['code' => '244108', 'description' => 'Mengelola operasional dan penjualan di wilayah Makassar Raya, yang dilakukan oleh unit bisnis ELB dan VA.'],
            ['code' => '244110', 'description' => 'Bertanggung jawab atas operasional dan penjualan di wilayah Bali dan Lombok, melibatkan unit bisnis ELB dan VA.'],
            ['code' => '244111', 'description' => 'Mengawasi operasional di wilayah Sumatera yang dikelola oleh unit bisnis ELB dan VA.'],
            ['code' => '244216', 'description' => 'Mengelola operasional di wilayah Jawa Barat di bawah unit bisnis VA.'],
            ['code' => '244301', 'description' => 'Mengelola operasional dan penjualan di Jakarta Raya di bawah unit bisnis VBVB.'],
            ['code' => '244302', 'description' => 'Mengelola operasional di Surabaya Raya di bawah unit bisnis VBVB.'],
            ['code' => '244306', 'description' => 'Mengelola penjualan di Jawa Barat di bawah unit bisnis VBVB.'],
            ['code' => '244310', 'description' => 'Mengelola operasional di Bali dan Lombok di bawah unit bisnis VBVB.'],
            ['code' => '244601', 'description' => 'Pusat manajemen umum dan operasional yang lebih luas, termasuk Manajemen Penjualan NEB, Manajemen Operasi VA, dan manajemen operasional/penjualan VBVB, serta manajemen umum KIE dan pengiriman.'],
            ['code' => '244SEAVAKIE', 'description' => 'Terkait dengan KIE SEA VA.'],
            ['code' => '244GHR001', 'description' => 'Terkait dengan HR COE LD INDONESIA.'],
            ['code' => '244GHR002', 'description' => 'Terkait dengan HR COE TA INDONESIA.'],
            ['code' => '244GKSO01', 'description' => 'Terkait dengan KPO.APM.SEA.Indonesia.'],
            ['code' => '244GLOG01', 'description' => 'Terkait dengan LOGISTICS INDONESIA.'],
        ]);

        // Business Units
        DB::table('business_units')->insert([
            ['code' => 'ADM', 'description' => 'Terlibat dalam manajemen umum untuk Greater Surabaya, serta manajemen umum dan fungsional di tingkat yang lebih luas, seperti HR dan Logistik di Indonesia.'],
            ['code' => 'ELB', 'description' => 'Unit bisnis yang sangat aktif di berbagai wilayah, bertanggung jawab atas manajemen proyek, operasional, dan penjualan di Greater Jakarta, Greater Surabaya, Jawa Tengah, Jawa Barat, Greater Makassar, Bali Lombok, dan Sumatera.'],
            ['code' => 'VBCSE', 'description' => 'Terkait dengan CSE INDONESIA.'],
            ['code' => 'VA', 'description' => 'Unit bisnis yang berfokus pada operasional dan penjualan di banyak wilayah, termasuk Greater Jakarta, Greater Surabaya, Jawa Tengah, Jawa Barat, Kalimantan, Greater Makassar, Bali Lombok, dan Sumatera, serta manajemen umum operasional.'],
            ['code' => 'VBVB', 'description' => 'Mengelola operasional dan penjualan di Greater Jakarta, Greater Surabaya, Jawa Barat, dan Bali Lombok.'],
            ['code' => 'GHR', 'description' => 'Terkait dengan fungsi HR (Human Resources) di Indonesia, termasuk COE LD dan COE TA.'],
            ['code' => 'SEA VA', 'description' => 'Terkait dengan KIE SEA VA.'],
            ['code' => 'GKSO', 'description' => 'Terkait dengan fungsi KPO.APM.SEA.Indonesia.'],
            ['code' => 'GLOG', 'description' => 'Terkait dengan fungsi Logistik di Indonesia.'],
        ]);

        // Cost Center
        $costCentersData = [
            // Data untuk Profit Center 244101
            [
                'code' => '2441012',
                'name' => 'District 8 Project',
                'description' => 'Pusat biaya untuk proyek "District 8".',
                'business_unit_id' => 2,
                'profit_center_id' => 1,
            ],
            [
                'code' => '2441051',
                'name' => 'Indonesia 1 Project',
                'description' => 'Pusat biaya untuk proyek "Indonesia 1".',
                'business_unit_id' => 2,
                'profit_center_id' => 1,
            ],
            [
                'code' => '2441050',
                'name' => 'NEB MP Management',
                'description' => 'Pusat biaya untuk manajemen NEB MP.',
                'business_unit_id' => 2,
                'profit_center_id' => 1,
            ],
            [
                'code' => '2441011',
                'name' => 'NEB Opr Greater JKT',
                'description' => 'Pusat biaya untuk operasional NEB di Greater Jakarta.',
                'business_unit_id' => 2,
                'profit_center_id' => 1,
            ],
            [
                'code' => '2441010',
                'name' => 'NEB Sales Great JKT',
                'description' => 'Pusat biaya untuk penjualan NEB di Greater Jakarta.',
                'business_unit_id' => 2,
                'profit_center_id' => 1,
            ],
            [
                'code' => '2442051',
                'name' => 'VA JKT Zone A Spv 2',
                'description' => 'Pusat biaya untuk pengawasan VA Jakarta, Zona A.',
                'business_unit_id' => 4,
                'profit_center_id' => 1,
            ],
            [
                'code' => '2442030',
                'name' => 'VA JKT Zone B Spv 1',
                'description' => 'Pusat biaya untuk pengawasan VA Jakarta, Zona B.',
                'business_unit_id' => 4,
                'profit_center_id' => 1,
            ],
            [
                'code' => '2442054',
                'name' => 'VA JKT Zone B Spv 3',
                'description' => 'Pusat biaya untuk pengawasan VA Jakarta, Zona B.',
                'business_unit_id' => 4,
                'profit_center_id' => 1,
            ],
            [
                'code' => '2442240',
                'name' => 'VA JKT Zone C Spv 3',
                'description' => 'Pusat biaya untuk pengawasan VA Jakarta, Zona C.',
                'business_unit_id' => 4,
                'profit_center_id' => 1,
            ],
            [
                'code' => '2442050',
                'name' => 'VA Opr Greater JKT 1',
                'description' => 'Pusat biaya untuk operasional VA di Greater Jakarta.',
                'business_unit_id' => 4,
                'profit_center_id' => 1,
            ],
            [
                'code' => '2442053',
                'name' => 'VA Opr Greater JKT 2',
                'description' => 'Pusat biaya untuk operasional VA di Greater Jakarta.',
                'business_unit_id' => 4,
                'profit_center_id' => 1,
            ],
            [
                'code' => '2442010',
                'name' => 'VA Sales Greater JKT',
                'description' => 'Pusat biaya untuk penjualan VA di Greater Jakarta.',
                'business_unit_id' => 4,
                'profit_center_id' => 1,
            ],

            // Data untuk Profit Center 244102
            [
                'code' => '2446020',
                'name' => 'Greater SBY Gen Mgt',
                'description' => 'Pusat biaya untuk manajemen umum Greater Surabaya.',
                'business_unit_id' => 1,
                'profit_center_id' => 2,
            ],
            [
                'code' => '2441022',
                'name' => 'NEB MP Management',
                'description' => 'Pusat biaya untuk manajemen NEB MP.',
                'business_unit_id' => 2,
                'profit_center_id' => 2,
            ],
            [
                'code' => '2441021',
                'name' => 'NEB Opr Greater SBY',
                'description' => 'Pusat biaya untuk operasional NEB di Greater Surabaya.',
                'business_unit_id' => 2,
                'profit_center_id' => 2,
            ],
            [
                'code' => '2441020',
                'name' => 'NEB Sales Great SBY',
                'description' => 'Pusat biaya untuk penjualan NEB di Greater Surabaya.',
                'business_unit_id' => 2,
                'profit_center_id' => 2,
            ],
            [
                'code' => '2442056',
                'name' => 'VA Opr Greater SBY',
                'description' => 'Pusat biaya untuk operasional VA di Greater Surabaya.',
                'business_unit_id' => 4,
                'profit_center_id' => 2,
            ],
            [
                'code' => '2442020',
                'name' => 'VA Sales Greater SBY',
                'description' => 'Pusat biaya untuk penjualan VA di Greater Surabaya.',
                'business_unit_id' => 4,
                'profit_center_id' => 2,
            ],
            [
                'code' => '2442111',
                'name' => 'VA Surabaya B',
                'description' => 'Pusat biaya untuk VA Surabaya B.',
                'business_unit_id' => 4,
                'profit_center_id' => 2,
            ],

            // Data untuk Profit Center 244104
            [
                'code' => '2441041',
                'name' => 'NEB Opr Central Java',
                'description' => 'Pusat biaya untuk operasional NEB di Jawa Tengah.',
                'business_unit_id' => 2,
                'profit_center_id' => 3,
            ],
            [
                'code' => '2441040',
                'name' => 'NEB Sales Cntr Java',
                'description' => 'Pusat biaya untuk penjualan NEB di Jawa Tengah.',
                'business_unit_id' => 2,
                'profit_center_id' => 3,
            ],
            [
                'code' => '2442040',
                'name' => 'VA Opr Central Java',
                'description' => 'Pusat biaya untuk operasional VA di Jawa Tengah.',
                'business_unit_id' => 4,
                'profit_center_id' => 3,
            ],
            [
                'code' => '2442041',
                'name' => 'VA Sales Central Jav',
                'description' => 'Pusat biaya untuk penjualan VA di Jawa Tengah.',
                'business_unit_id' => 4,
                'profit_center_id' => 3,
            ],

            // Data untuk Profit Center 244106
            [
                'code' => '2441061',
                'name' => 'NEB Opr West Java',
                'description' => 'Pusat biaya untuk operasional NEB di Jawa Barat.',
                'business_unit_id' => 2,
                'profit_center_id' => 4,
            ],

            // Data untuk Profit Center 244107
            [
                'code' => '2442161',
                'name' => 'VA Opr Kalimantan',
                'description' => 'Pusat biaya untuk operasional VA di Kalimantan.',
                'business_unit_id' => 4,
                'profit_center_id' => 5,
            ],

            // Data untuk Profit Center 244108
            [
                'code' => '2441081',
                'name' => 'NEB Opr Greater MKS',
                'description' => 'Pusat biaya untuk operasional NEB di Greater Makassar.',
                'business_unit_id' => 2,
                'profit_center_id' => 6,
            ],
            [
                'code' => '2441080',
                'name' => 'NEB Sales Great MKS',
                'description' => 'Pusat biaya untuk penjualan NEB di Greater Makassar.',
                'business_unit_id' => 2,
                'profit_center_id' => 6,
            ],
            [
                'code' => '2442059',
                'name' => 'VA Opr Greater MKS',
                'description' => 'Pusat biaya untuk operasional VA di Greater Makassar.',
                'business_unit_id' => 4,
                'profit_center_id' => 6,
            ],

            // Data untuk Profit Center 244110
            [
                'code' => '2441101',
                'name' => 'NEB Opr Bali Lombok',
                'description' => 'Pusat biaya untuk operasional NEB di Bali Lombok.',
                'business_unit_id' => 2,
                'profit_center_id' => 7,
            ],
            [
                'code' => '2441100',
                'name' => 'NEB Sales BaliLombok',
                'description' => 'Pusat biaya untuk penjualan NEB di Bali Lombok.',
                'business_unit_id' => 2,
                'profit_center_id' => 7,
            ],
            [
                'code' => '2442057',
                'name' => 'VA Opr Bali Lombok',
                'description' => 'Pusat biaya untuk operasional VA di Bali Lombok.',
                'business_unit_id' => 4,
                'profit_center_id' => 7,
            ],
            [
                'code' => '2442120',
                'name' => 'VA Sales Bali Lombok',
                'description' => 'Pusat biaya untuk penjualan VA di Bali Lombok.',
                'business_unit_id' => 4,
                'profit_center_id' => 7,
            ],

            // Data untuk Profit Center 244111
            [
                'code' => '2441031',
                'name' => 'NEB Opr Sumatera',
                'description' => 'Pusat biaya untuk operasional NEB di Sumatera.',
                'business_unit_id' => 2,
                'profit_center_id' => 8,
            ],
            [
                'code' => '2442058',
                'name' => 'VA Opr Sumatera',
                'description' => 'Pusat biaya untuk operasional VA di Sumatera.',
                'business_unit_id' => 4,
                'profit_center_id' => 8,
            ],

            // Data untuk Profit Center 244216
            [
                'code' => '2442061',
                'name' => 'VA Opr West Java',
                'description' => 'Pusat biaya untuk operasional VA di Jawa Barat.',
                'business_unit_id' => 4,
                'profit_center_id' => 9,
            ],

            // Data untuk Profit Center 244301
            [
                'code' => '2443011',
                'name' => 'VBVB Opr Greater JKT',
                'description' => 'Pusat biaya untuk operasional VBVB di Greater Jakarta.',
                'business_unit_id' => 5,
                'profit_center_id' => 10,
            ],
            [
                'code' => '2443010',
                'name' => 'VBVB Sales Greater JKT',
                'description' => 'Pusat biaya untuk penjualan VBVB di Greater Jakarta.',
                'business_unit_id' => 5,
                'profit_center_id' => 10,
            ],

            // Data untuk Profit Center 244302
            [
                'code' => '2443020',
                'name' => 'VBVB Opr Greater SBY',
                'description' => 'Pusat biaya untuk operasional VBVB di Greater Surabaya.',
                'business_unit_id' => 5,
                'profit_center_id' => 11,
            ],

            // Data untuk Profit Center 244306
            [
                'code' => '2443060',
                'name' => 'VBVB Sales West Java',
                'description' => 'Pusat biaya untuk penjualan VBVB di Jawa Barat.',
                'business_unit_id' => 5,
                'profit_center_id' => 12,
            ],

            // Data untuk Profit Center 244310
            [
                'code' => '2443101',
                'name' => 'VBVB Opr Bali Lombok',
                'description' => 'Pusat biaya untuk operasional VBVB di Bali Lombok.',
                'business_unit_id' => 5,
                'profit_center_id' => 13,
            ],

            // Data untuk Profit Center 244601
            [
                'code' => '2446010',
                'name' => 'KIE General Mgt',
                'description' => 'Pusat biaya untuk manajemen umum KIE.',
                'business_unit_id' => 1,
                'profit_center_id' => 14,
            ],
            [
                'code' => '244CS01',
                'name' => 'CSE INDONESIA',
                'description' => 'Pusat biaya untuk CSE Indonesia.',
                'business_unit_id' => 2,
                'profit_center_id' => 14,
            ],
            [
                'code' => '2446070',
                'name' => 'Delivery Opr Mgt',
                'description' => 'Pusat biaya untuk manajemen operasional pengiriman.',
                'business_unit_id' => 2,
                'profit_center_id' => 14,
            ],
            [
                'code' => '2441013',
                'name' => 'DelOps Operative',
                'description' => 'Pusat biaya untuk operatif DelOps.',
                'business_unit_id' => 2,
                'profit_center_id' => 14,
            ],
            [
                'code' => '2446011',
                'name' => 'NEB Sales Mgt',
                'description' => 'Pusat biaya untuk manajemen penjualan NEB.',
                'business_unit_id' => 2,
                'profit_center_id' => 14,
            ],
            [
                'code' => '2446014',
                'name' => 'VA Operation Mgt',
                'description' => 'Pusat biaya untuk manajemen operasional VA.',
                'business_unit_id' => 4,
                'profit_center_id' => 14,
            ],
            [
                'code' => '244CS02',
                'name' => 'CSE INDONESIA',
                'description' => 'Pusat biaya untuk CSE Indonesia.',
                'business_unit_id' => 3,
                'profit_center_id' => 14,
            ],
            [
                'code' => '2446200',
                'name' => 'VB Operation Mgt',
                'description' => 'Pusat biaya untuk manajemen operasional VB.',
                'business_unit_id' => 5,
                'profit_center_id' => 14,
            ],
            [
                'code' => '2446016',
                'name' => 'VB Sales Mgt',
                'description' => 'Pusat biaya untuk manajemen penjualan VB.',
                'business_unit_id' => 5,
                'profit_center_id' => 14,
            ],

            // Data di luar Profit Center
            [
                'code' => '244SEA3',
                'name' => 'KIE SEA VA',
                'description' => 'Pusat biaya untuk KIE SEA VA.',
                'business_unit_id' => 7,
                'profit_center_id' => 15,
            ],
            [
                'code' => '244HR01',
                'name' => 'HR COE LD INDONESIA',
                'description' => 'Pusat biaya untuk HR COE LD di Indonesia.',
                'business_unit_id' => 1,
                'profit_center_id' => 16,
            ],
            [
                'code' => '244HR00',
                'name' => 'HR COE TA INDONESIA',
                'description' => 'Pusat biaya untuk HR COE TA di Indonesia.',
                'business_unit_id' => 1,
                'profit_center_id' => 17,
            ],
            [
                'code' => '244KSO',
                'name' => 'KPO.APM.SEA.Indonesi',
                'description' => 'Pusat biaya untuk KPO.APM.SEA di Indonesia.',
                'business_unit_id' => 1,
                'profit_center_id' => 18,
            ],
            [
                'code' => '244LG00',
                'name' => 'LOGISTICS INDONESIA',
                'description' => 'Pusat biaya untuk logistik di Indonesia.',
                'business_unit_id' => 1,
                'profit_center_id' => 19,
            ],
        ];

        DB::table('cost_centers')->insert($costCentersData);

        $costCenters = CostCenter::all()->keyBy('code');

        $budgetsData = [
            // Anggaran untuk setiap cost center (contoh)
            ['code' => 'BGT-2441012-P1', 'item' => 'Anggaran Proyek District 8 Tahap 1', 'amount' => 500000000.00, 'category' => 'Proyek', 'cost_center_code' => '2441012'],
            ['code' => 'BGT-2441012-P2', 'item' => 'Anggaran Proyek District 8 Tahap 2', 'amount' => 350000000.00, 'category' => 'Proyek', 'cost_center_code' => '2441012'],
            ['code' => 'BGT-2441051-A', 'item' => 'Anggaran Proyek Indonesia 1', 'amount' => 1200000000.00, 'category' => 'Proyek', 'cost_center_code' => '2441051'],
            ['code' => 'BGT-2441050-M1', 'item' => 'Anggaran Manajemen MP NEB', 'amount' => 75000000.00, 'category' => 'Manajemen', 'cost_center_code' => '2441050'],
            ['code' => 'BGT-2441011-Opr', 'item' => 'Anggaran Operasional NEB Greater JKT', 'amount' => 150000000.00, 'category' => 'Operasional', 'cost_center_code' => '2441011'],
            ['code' => 'BGT-2441010-Sls', 'item' => 'Anggaran Penjualan NEB Greater JKT', 'amount' => 180000000.00, 'category' => 'Penjualan', 'cost_center_code' => '2441010'],
            ['code' => 'BGT-2442051-Sup', 'item' => 'Gaji Supervisor VA Zone A', 'amount' => 50000000.00, 'category' => 'Gaji', 'cost_center_code' => '2442051'],
            ['code' => 'BGT-2442030-Sup', 'item' => 'Gaji Supervisor VA Zone B', 'amount' => 60000000.00, 'category' => 'Gaji', 'cost_center_code' => '2442030'],
            ['code' => 'BGT-2442054-Sup', 'item' => 'Gaji Supervisor VA Zone B (tambahan)', 'amount' => 45000000.00, 'category' => 'Gaji', 'cost_center_code' => '2442054'],
            ['code' => 'BGT-2442240-Sup', 'item' => 'Gaji Supervisor VA Zone C', 'amount' => 55000000.00, 'category' => 'Gaji', 'cost_center_code' => '2442240'],
            ['code' => 'BGT-2442050-Opr', 'item' => 'Anggaran Operasional VA Greater JKT 1', 'amount' => 250000000.00, 'category' => 'Operasional', 'cost_center_code' => '2442050'],
            ['code' => 'BGT-2442053-Opr', 'item' => 'Anggaran Operasional VA Greater JKT 2', 'amount' => 200000000.00, 'category' => 'Operasional', 'cost_center_code' => '2442053'],
            ['code' => 'BGT-2442010-Sls', 'item' => 'Anggaran Penjualan VA Greater JKT', 'amount' => 190000000.00, 'category' => 'Penjualan', 'cost_center_code' => '2442010'],

            // Lanjutkan data untuk cost center lainnya
            ['code' => 'BGT-2446020-Mgt', 'item' => 'Anggaran Manajemen Umum Greater SBY', 'amount' => 100000000.00, 'category' => 'Manajemen', 'cost_center_code' => '2446020'],
            ['code' => 'BGT-2441022-Mgt', 'item' => 'Anggaran Manajemen Proyek NEB SBY', 'amount' => 85000000.00, 'category' => 'Manajemen', 'cost_center_code' => '2441022'],
            ['code' => 'BGT-2441021-Opr', 'item' => 'Anggaran Operasional NEB Greater SBY', 'amount' => 120000000.00, 'category' => 'Operasional', 'cost_center_code' => '2441021'],
            ['code' => 'BGT-2441020-Sls', 'item' => 'Anggaran Penjualan NEB Greater SBY', 'amount' => 140000000.00, 'category' => 'Penjualan', 'cost_center_code' => '2441020'],
            ['code' => 'BGT-2442056-Opr', 'item' => 'Anggaran Operasional VA Greater SBY', 'amount' => 180000000.00, 'category' => 'Operasional', 'cost_center_code' => '2442056'],
            ['code' => 'BGT-2442020-Sls', 'item' => 'Anggaran Penjualan VA Greater SBY', 'amount' => 160000000.00, 'category' => 'Penjualan', 'cost_center_code' => '2442020'],
            ['code' => 'BGT-2442111-Opr', 'item' => 'Anggaran Operasional VA Surabaya B', 'amount' => 90000000.00, 'category' => 'Operasional', 'cost_center_code' => '2442111'],

            // ... (lanjutkan untuk semua Cost Center yang ada)

            ['code' => 'BGT-2441041-Opr', 'item' => 'Anggaran Operasional NEB Central Java', 'amount' => 80000000.00, 'category' => 'Operasional', 'cost_center_code' => '2441041'],
            ['code' => 'BGT-2441040-Sls', 'item' => 'Anggaran Penjualan NEB Central Java', 'amount' => 110000000.00, 'category' => 'Penjualan', 'cost_center_code' => '2441040'],
            ['code' => 'BGT-2442040-Opr', 'item' => 'Anggaran Operasional VA Central Java', 'amount' => 130000000.00, 'category' => 'Operasional', 'cost_center_code' => '2442040'],
            ['code' => 'BGT-2442041-Sls', 'item' => 'Anggaran Penjualan VA Central Java', 'amount' => 125000000.00, 'category' => 'Penjualan', 'cost_center_code' => '2442041'],
            ['code' => 'BGT-2441061-Opr', 'item' => 'Anggaran Operasional NEB West Java', 'amount' => 95000000.00, 'category' => 'Operasional', 'cost_center_code' => '2441061'],
            ['code' => 'BGT-2442161-Opr', 'item' => 'Anggaran Operasional VA Kalimantan', 'amount' => 115000000.00, 'category' => 'Operasional', 'cost_center_code' => '2442161'],
            ['code' => 'BGT-2441081-Opr', 'item' => 'Anggaran Operasional NEB Greater MKS', 'amount' => 98000000.00, 'category' => 'Operasional', 'cost_center_code' => '2441081'],
            ['code' => 'BGT-2441080-Sls', 'item' => 'Anggaran Penjualan NEB Greater MKS', 'amount' => 105000000.00, 'category' => 'Penjualan', 'cost_center_code' => '2441080'],
            ['code' => 'BGT-2442059-Opr', 'item' => 'Anggaran Operasional VA Greater MKS', 'amount' => 135000000.00, 'category' => 'Operasional', 'cost_center_code' => '2442059'],
            ['code' => 'BGT-2441101-Opr', 'item' => 'Anggaran Operasional NEB Bali Lombok', 'amount' => 70000000.00, 'category' => 'Operasional', 'cost_center_code' => '2441101'],
            ['code' => 'BGT-2441100-Sls', 'item' => 'Anggaran Penjualan NEB Bali Lombok', 'amount' => 80000000.00, 'category' => 'Penjualan', 'cost_center_code' => '2441100'],
            ['code' => 'BGT-2442057-Opr', 'item' => 'Anggaran Operasional VA Bali Lombok', 'amount' => 100000000.00, 'category' => 'Operasional', 'cost_center_code' => '2442057'],
            ['code' => 'BGT-2442120-Sls', 'item' => 'Anggaran Penjualan VA Bali Lombok', 'amount' => 95000000.00, 'category' => 'Penjualan', 'cost_center_code' => '2442120'],
            ['code' => 'BGT-2441031-Opr', 'item' => 'Anggaran Operasional NEB Sumatera', 'amount' => 120000000.00, 'category' => 'Operasional', 'cost_center_code' => '2441031'],
            ['code' => 'BGT-2442058-Opr', 'item' => 'Anggaran Operasional VA Sumatera', 'amount' => 140000000.00, 'category' => 'Operasional', 'cost_center_code' => '2442058'],
            ['code' => 'BGT-2442061-Opr', 'item' => 'Anggaran Operasional VA West Java', 'amount' => 110000000.00, 'category' => 'Operasional', 'cost_center_code' => '2442061'],
            ['code' => 'BGT-2443011-Opr', 'item' => 'Anggaran Operasional VBVB Greater JKT', 'amount' => 170000000.00, 'category' => 'Operasional', 'cost_center_code' => '2443011'],
            ['code' => 'BGT-2443010-Sls', 'item' => 'Anggaran Penjualan VBVB Greater JKT', 'amount' => 210000000.00, 'category' => 'Penjualan', 'cost_center_code' => '2443010'],
            ['code' => 'BGT-2443020-Opr', 'item' => 'Anggaran Operasional VBVB Greater SBY', 'amount' => 150000000.00, 'category' => 'Operasional', 'cost_center_code' => '2443020'],
            ['code' => 'BGT-2443060-Sls', 'item' => 'Anggaran Penjualan VBVB West Java', 'amount' => 130000000.00, 'category' => 'Penjualan', 'cost_center_code' => '2443060'],
            ['code' => 'BGT-2443101-Opr', 'item' => 'Anggaran Operasional VBVB Bali Lombok', 'amount' => 90000000.00, 'category' => 'Operasional', 'cost_center_code' => '2443101'],
            ['code' => 'BGT-2446010-Mgt', 'item' => 'Anggaran Manajemen Umum KIE', 'amount' => 250000000.00, 'category' => 'Manajemen', 'cost_center_code' => '2446010'],
            ['code' => 'BGT-244CS01-Opr', 'item' => 'Anggaran Operasional CSE Indonesia', 'amount' => 120000000.00, 'category' => 'Operasional', 'cost_center_code' => '244CS01'],
            ['code' => 'BGT-2446070-Opr', 'item' => 'Anggaran Manajemen Operasional Pengiriman', 'amount' => 180000000.00, 'category' => 'Operasional', 'cost_center_code' => '2446070'],
            ['code' => 'BGT-2441013-Opr', 'item' => 'Anggaran Operatif DelOps', 'amount' => 100000000.00, 'category' => 'Operasional', 'cost_center_code' => '2441013'],
            ['code' => 'BGT-2446011-Sls', 'item' => 'Anggaran Manajemen Penjualan NEB', 'amount' => 200000000.00, 'category' => 'Manajemen', 'cost_center_code' => '2446011'],
            ['code' => 'BGT-2446014-Opr', 'item' => 'Anggaran Manajemen Operasional VA', 'amount' => 220000000.00, 'category' => 'Manajemen', 'cost_center_code' => '2446014'],
            ['code' => 'BGT-244CS02-Opr', 'item' => 'Anggaran Operasional CSE Indonesia (VBCSE)', 'amount' => 110000000.00, 'category' => 'Operasional', 'cost_center_code' => '244CS02'],
            ['code' => 'BGT-2446200-Opr', 'item' => 'Anggaran Manajemen Operasional VB', 'amount' => 190000000.00, 'category' => 'Manajemen', 'cost_center_code' => '2446200'],
            ['code' => 'BGT-2446016-Sls', 'item' => 'Anggaran Manajemen Penjualan VB', 'amount' => 230000000.00, 'category' => 'Manajemen', 'cost_center_code' => '2446016'],
            ['code' => 'BGT-244SEA3-KIE', 'item' => 'Anggaran KIE SEA VA', 'amount' => 300000000.00, 'category' => 'Proyek', 'cost_center_code' => '244SEA3'],
            ['code' => 'BGT-244HR01-HR', 'item' => 'Anggaran HR COE LD INDONESIA', 'amount' => 150000000.00, 'category' => 'HR', 'cost_center_code' => '244HR01'],
            ['code' => 'BGT-244HR00-HR', 'item' => 'Anggaran HR COE TA INDONESIA', 'amount' => 125000000.00, 'category' => 'HR', 'cost_center_code' => '244HR00'],
            ['code' => 'BGT-244KSO-KPO', 'item' => 'Anggaran KPO.APM.SEA.Indonesia', 'amount' => 90000000.00, 'category' => 'Operasional', 'cost_center_code' => '244KSO'],
            ['code' => 'BGT-244LG00-Log', 'item' => 'Anggaran Logistik Indonesia', 'amount' => 175000000.00, 'category' => 'Operasional', 'cost_center_code' => '244LG00'],
        ];

        foreach ($budgetsData as $data) {
            Budget::create([
                'code' => $data['code'],
                'item' => $data['item'],
                'amount' => $data['amount'],
                'category' => $data['category'],
                'year' => '2025',
                'cost_center_id' => $costCenters[$data['cost_center_code']]->id,
            ]);
        }

        DB::table('questions')->insert([
            [
                'question_text' => 'Justifications, if less than 3 quotations',
                'description' => 'Memastikan bahwa pemohon telah mencoba mendapatkan setidaknya tiga penawaran dari vendor yang berbeda untuk mendapatkan harga terbaik. Jika tidak, pemohon harus menjelaskan alasannya (misalnya, aset tersebut hanya tersedia dari satu vendor, atau waktu sangat mendesak).',
            ],
            [
                'question_text' => 'If first time, mentioned the current practice followed by us without this asset.',
                'description' => 'Memahami bagaimana tim atau departemen saat ini beroperasi tanpa aset yang diminta. Ini membantu pihak yang menyetujui (Budget Owner, Finance Controller, dll.) untuk mengevaluasi apakah aset tersebut benar-benar dibutuhkan untuk meningkatkan efisiensi atau menyelesaikan masalah.',
            ],
            [
                'question_text' => 'If already purchased and additionally required, mentioned current qty available with us',
                'description' => 'Mengetahui apakah aset yang diminta adalah tambahan dari aset yang sudah ada. Ini membantu mencegah pembelian berlebihan dan memastikan bahwa aset yang ada sudah digunakan semaksimal mungkin.',
            ],
            [
                'question_text' => 'If already purchased, mentioned the last purchased price',
                'description' => 'Memberikan data historis harga. Pihak persetujuan dapat membandingkan harga baru dengan harga pembelian terakhir untuk mengidentifikasi kenaikan atau penurunan harga. Ini membantu dalam negosiasi dan memastikan vendor tidak menaikkan harga secara tidak wajar.',
            ],
            [
                'question_text' => 'Confirm whether the prices are negotiated by NPR sourcing and mention the first qouted price and final price.',
                'description' => 'Menunjukkan efektivitas tim pengadaan (NPR sourcing) dalam negosiasi. Pertanyaan ini mengukur seberapa besar penghematan yang berhasil dicapai, yang merupakan metrik penting dalam proses pengadaan.',
            ],
            [
                'question_text' => 'Mention the finalized vendor name & details',
                'description' => 'Mengidentifikasi vendor mana yang akhirnya dipilih. Ini adalah informasi dasar yang penting untuk audit dan pencatatan.',
            ],
            [
                'question_text' => 'Justification for Vendor selection, additional comments if > L1',
                'description' => 'Menjelaskan alasan di balik pemilihan vendor, terutama jika vendor yang dipilih bukanlah penawar dengan harga terendah (L1 - Lowest Bidder). Justifikasi ini bisa berupa kualitas produk yang lebih baik, layanan purna jual yang lebih baik, reputasi vendor, atau faktor lain yang membenarkan pemilihan vendor yang lebih mahal.',
            ],
            [
                'question_text' => 'Mention the final payment terms & retention clause for long term investments.',
                'description' => 'Mendokumentasikan syarat-syarat pembayaran. "Retention clause" penting untuk investasi jangka panjang, di mana sebagian pembayaran ditahan sampai aset terpasang dan berfungsi dengan baik. Ini melindungi perusahaan dari risiko kegagalan vendor.',
            ],
            [
                'question_text' => 'If the vendor is service provider and mention the GST (Goods & Service Tax Identification Number).',
                'description' => 'Informasi ini sangat penting untuk kepatuhan pajak. Nomor identifikasi pajak vendor harus dicatat untuk keperluan pembukuan dan pelaporan keuangan.',
            ],
            [
                'question_text' => 'Mention the warranty period of product and expected annual maintenance cost after warranty period.',
                'description' => 'Memungkinkan perusahaan untuk memperkirakan total biaya kepemilikan (Total Cost of Ownership) dari aset tersebut. Biaya perawatan setelah masa garansi habis adalah faktor penting yang harus dipertimbangkan dalam pengambilan keputusan finansial.',
            ],
        ]);
    }
}
