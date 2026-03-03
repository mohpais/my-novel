<?php

namespace Database\Seeders;

use App\Models\RequestStatus;
use App\Models\Role;
use App\Models\User;
use App\Models\WorkflowTemplate;
use App\Models\WorkflowTemplateStage;
use Illuminate\Database\Seeder;

class WorkflowTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roleSuperadmin = Role::where('code', 'superadmin')->first();
        $superadmin = User::where('role_id', $roleSuperadmin->id)->first();

        $template = WorkflowTemplate::create([
            'name' => 'Permohonan Pembelian Aset',
            'description' => 'Workflow untuk persetujuan permohonan pembelian aset.',
            'created_by' => $superadmin->id,
        ]);

        // Stage 1: Permintaan data awal dari requester
        $roleRequester = Role::where('code', 'requester')->first();
        WorkflowTemplateStage::create([
            'workflow_template_id' => $template->id,
            'role_id' => $roleRequester->id,
            'stage_order' => 1,
            'stage_name' => 'Input Data Pengajuan',
            'stage_type' => 'input_data',
            'input_schema' => 'asset_request',
            'created_by' => $superadmin->id,
        ]);

        // Stage 2: Input data penawaran vendor oleh Budget Owner
        $roleProcurement = Role::where('code', 'budget_owner')->first();
        WorkflowTemplateStage::create([
            'workflow_template_id' => $template->id,
            'role_id' => $roleProcurement->id,
            'stage_order' => 2,
            'stage_name' => 'Input Data Penawaran',
            'stage_type' => 'approval',
            'input_schema' => null,
            'created_by' => $superadmin->id,
        ]);

        // Stage 3: Persetujuan Procurement
        $roleProcurement = Role::where('code', 'procurement')->first();
        WorkflowTemplateStage::create([
            'workflow_template_id' => $template->id,
            'role_id' => $roleProcurement->id,
            'stage_order' => 3,
            'stage_name' => 'Persetujuan Procurement',
            'stage_type' => 'approval',
            'input_schema' => null,
            'created_by' => $superadmin->id,
        ]);

        // Stage 4: Persetujuan Finance Controller
        $roleFC = Role::where('code', 'finance_controller')->first();
        WorkflowTemplateStage::create([
            'workflow_template_id' => $template->id,
            'role_id' => $roleFC->id,
            'stage_order' => 4,
            'stage_name' => 'Finance Controller',
            'stage_type' => 'approval',
            'input_schema' => null,
            'created_by' => $superadmin->id,
        ]);

        // Stage 5: Persetujuan Head of Finance
        $roleHoF = Role::where('code', 'hod_finance')->first();
        WorkflowTemplateStage::create([
            'workflow_template_id' => $template->id,
            'role_id' => $roleHoF->id,
            'stage_order' => 5,
            'stage_name' => 'Head of Finance Department',
            'stage_type' => 'approval',
            'input_schema' => null,
            'created_by' => $superadmin->id,
        ]);

        // Stage 6: Persetujuan Managing Director
        $roleMD = Role::where('code', 'managing_director')->first();
        WorkflowTemplateStage::create([
            'workflow_template_id' => $template->id,
            'role_id' => $roleMD->id,
            'stage_order' => 6,
            'stage_name' => 'Managing Director',
            'stage_type' => 'approval',
            'input_schema' => null,
            'created_by' => $superadmin->id,
        ]);
    }
}
