<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\ApiPaginationRequest;

use App\Models\ApprovalStage;
use App\Models\ApprovalLog;
use App\Models\AssetRequest;
use App\Models\Budget;
use App\Models\RequestStatus;
use App\Models\WorkflowTemplate;

use App\Services\ApiPaginationService;
use App\Services\BuilderDataTableService;
use App\Services\EloquentDataTableService;
use App\Services\QueryDataTableService;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Tymon\JWTAuth\Facades\JWTAuth;

class RequestController extends Controller
{
    protected $apiService;
    protected $builderDataTableService;

    public function __construct(BuilderDataTableService $builderDataTableService, ApiPaginationService $apiService)
    {
        $this->apiService = $apiService;
        $this->builderDataTableService = $builderDataTableService;
    }

    /**
     * Display a listing of the request submitted.
     */
    public function list(Request $request, QueryDataTableService $service)
    {
        try {
            $query = DB::table('asset_requests as request')
                ->select(
                    'request.id',
                    'request.purchase_quantity',
                    'request.request_status_id',
                    'request.name',
                    'request.description',
                    'request.reason',
                    'request.replacement',
                    'request.useful_life',
                    'request.initial_value',
                    'request.currency',
                    DB::raw("CONCAT(cost_center.code, ' - ', cost_center.name) as cost_center"),
                    'profit_center.code as profit_center',
                    'business_unit.code as business_unit',
                    'budget.code as budget_code',
                    'request.created_at',
                    'request.updated_at'
                )
                ->leftJoin('cost_centers as cost_center', 'request.cost_center_id', '=', 'cost_center.id')
                ->leftJoin('profit_centers as profit_center', 'profit_center.id', '=', 'cost_center.profit_center_id')
                ->leftJoin('business_units as business_unit', 'business_unit.id', '=', 'cost_center.business_unit_id')
                ->leftJoin('budgets as budget', 'request.budget_id', '=', 'budget.id');

            // Tambahkan kondisi 'where' dengan ID pengguna yang sedang login
            $query->where('request.user_id', auth()->user()->id);

            // Tentukan kolom yang akan di-select setelah menambahkan JOIN
            $query->selectRaw("CONCAT(cost_center.code, ' - ', cost_center.name) as cost_center");

            // Meneruskan objek query builder ke service Anda
            return response()->json($service->getJsonResponse($request, $query));

        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => "Oops something wrong when get list asset request!",
                'error' => $th->getMessage(),
                'file' => $th->getFile(),
                'line' => $th->getLine(),
                'trace' => $th->getTraceAsString(),
            ], 500);
        }
    }

    public function waiting(ApiPaginationRequest $request)
    {
        try {
            $user = auth()->user();
            $userRole = $user->role; // contoh kalau pakai relasi roles()

            $requestStatus = RequestStatus::where('code', 'waiting_approval')->first();
            // $requestStatus = $userRole->code == 'budget_owner'
            //     ? RequestStatus::where('code', 'waiting_quotation')->first()
            //         : RequestStatus::where('code', 'waiting_approval')->first();

            $query = AssetRequest::query()
                ->join('budgets', 'budgets.id', '=', 'asset_requests.budget_id')
                ->join('cost_centers as cost_center', 'asset_requests.cost_center_id', '=', 'cost_center.id')
                ->join('request_statuses as request_status', 'asset_requests.request_status_id', '=', 'request_status.id')
                ->join('approval_stages', 'asset_requests.id', '=', 'approval_stages.asset_request_id')
                ->whereNull('approval_stages.user_id')
                ->where('approval_stages.request_status_id', $requestStatus->id)
                ->where(function ($query) use ($user) {
                    $query->where(function ($q) use ($user) {
                        $q->where('approval_stages.locked_to_user', true)
                            ->where('approval_stages.assigned_user_id', $user->id);
                    })
                    ->orWhere(function ($q) use ($user) {
                        $q->where('approval_stages.locked_to_user', false)
                            ->where('approval_stages.role_id', $user->role->id)
                            ->whereNull('approval_stages.user_id');
                    });
                })
                ->select(
                    'asset_requests.id',
                    'asset_requests.code',
                    'asset_requests.name',
                    'asset_requests.budget_id',
                    DB::raw("CONCAT(budgets.code, ' - ', budgets.item) as budget"),
                    'asset_requests.cost_center_id',
                    DB::raw("CONCAT(cost_center.code, ' - ', cost_center.name) as cost_center"),
                    'request_status.label as status', // Berikan alias untuk kemudahan
                    'asset_requests.purchase_quantity',
                    'approval_stages.stage_order'
                )
                ->orderBy('approval_stages.stage_order', 'asc');

            // 1. Definisikan pemetaan field API ke kolom database
            $fieldMapping = [
                'status'        => 'request_status.label',
                'cost_center'   => 'cost_center.name', // atau 'cost_center.code' sesuai kebutuhan
                'budget'        => 'budgets.code', // atau 'budgets.item' sesuai kebutuhan
                // Tambahkan mapping lain jika diperlukan
            ];

            // 2. Teruskan query dan field mapping ke service
            $assetRequests = $this->apiService->handle($query, $request, [], $fieldMapping);

            return response()->json($assetRequests);

        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => "Oops something wrong when get list asset request!",
                'error' => $th->getMessage(),
                'file' => $th->getFile(),
                'line' => $th->getLine(),
                'trace' => $th->getTraceAsString(),
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function unfinished()
    {
        try {
            $currentUser = JWTAuth::parseToken()->authenticate();
            $assetRequest = AssetRequest::find($currentUser->id);

            return response()->json([
                'success' => true,
                'data' => [
                    'request' => $assetRequest,
                    'asset'   => $assetRequest ? $assetRequest->details : null,
                ],
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => "Oops something wrong when get unfinished asset request!",
                'error' => $th->getMessage(),
                'file' => $th->getFile(),
                'line' => $th->getLine(),
                'trace' => $th->getTraceAsString(),
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            // 1. Validasi Input
            $request->validate([
                'budget_id'          => 'required|exists:budgets,id',
                'name'               => 'required|string|max:255',
                'description'        => 'nullable|string',
                'brief_reason'       => 'nullable|string',
                'replacement'        => 'nullable|boolean',
                'useful_life'        => 'nullable|integer|min:1',
                // 'purchase_quantity'  => 'required|integer|min:1',

                'quotations'          => 'required|array|min:1',
                'quotations.*.vendor_name'       => 'required|string|max:255',
                'quotations.*.currency'          => 'required|string|size:3',
                'quotations.*.purchase_quantity' => 'required|integer|min:1',
                'quotations.*.base_rate'         => 'required|numeric|min:0',
                'quotations.*.tax_rate'          => 'required|numeric|min:0',
                'quotations.*.luxury_goods'      => 'boolean',
                'quotations.*.amount'            => 'required|numeric|min:0',
                'quotations.*.vat_amount'        => 'required|numeric|min:0',
                'quotations.*.total'             => 'required|numeric|min:0',

                'justifications'          => 'required|array',
                'justifications.*.id'     => 'required|integer|exists:questions,id',
                'justifications.*.answer' => 'required|string',
            ]);

            // Cari budget yang akan dibebankan untuk permintaan
            $budget = Budget::with('costCenter')->findOrFail($request->budget_id);
            // Dapatkan ID status dari database terlebih dahulu
            $statusSubmitted = RequestStatus::where('code', 'submitted')->firstOrFail();

            return DB::transaction(function () use ($request, $budget, $statusSubmitted) {
                // Hitung total purchase_quantity dari semua quotations
                $totalPurchaseQuantity = collect($request->quotations)->sum('purchase_quantity');

                // 2. Buat Asset Request
                $assetRequest = AssetRequest::create([
                    'purchase_quantity' => $request->purchase_quantity,
                    'user_id'           => auth()->id(),
                    'budget_id'         => $budget->id,
                    'cost_center_id'    => $budget->costCenter->id,
                    'name'              => $request->name,
                    'description'       => $request->description,
                    'reason'            => $request->brief_reason,
                    'replacement'       => $request->replacement,
                    'useful_life'       => $request->useful_life,
                    'currency'          => $request->currency,
                    'purchase_quantity' => $totalPurchaseQuantity, // Mengisi nilai awal dari quotation pertama
                ]);

                // 3. Masukkan Justifikasi dan Quotation
                // Insert quotation sekaligus (mass insert)
                $assetRequest->quotations()->createMany(
                    collect($request->quotations)->map(fn($value) => [
                        'purchase_quantity' => $value['purchase_quantity'],
                        'vendor_name'       => $value['vendor_name'],
                        'currency'          => $value['currency'],
                        'base_rate'         => $value['base_rate'],
                        'amount'            => $value['amount'],
                        'tax_rate'          => $value['tax_rate'],
                        'vat_amount'        => $value['vat_amount'],
                        'total'             => $value['total'],
                        'luxury_goods'      => $value['luxury_goods'],
                    ])->toArray()
                );

                // 4. Masukkan Justifikasi dan Quotation
                // Insert justifikasi sekaligus (mass insert)
                $assetRequest->justifications()->createMany(
                    collect($request->justifications)->map(fn($value) => [
                        'question_id' => $value['id'],
                        'answer'      => $value['answer'],
                    ])->toArray()
                );

                // 5. Inisialisasi Workflow dan Log
                $this->initializeApprovalStages($assetRequest);

                // 6. Update Staage dan Insert Log
                // $currentStage = $this->getStage($assetRequest, $stageOrder);
                $currentStage =  $assetRequest->approvalStages()
                    ->when($stageOrder, fn($q) =>
                        $q->where('stage_order', $stageOrder)
                    )
                    ->whereNull('approved_by') # ambil yang belum diproses
                    ->orderBy('stage_order');

                return $query->first();
                $currentStage->update([
                    'request_status_id' => $requestStatus->id,
                    'actioned_by' => auth()->id(),
                    'actioned_at' => now(),
                ]);
                // $this->processStageAndLog($assetRequest, $statusSubmitted, 'Permintaan aset baru telah diajukan.', 1);

                // 7. Proses Next Stage
                $this->provideNextStage($assetRequest);

                return response()->json([
                    'success' => true,
                    'message' => "Asset request created successfully!",
                    'data' => $assetRequest,
                ], 201);
            });
        } catch (\Throwable $th) {
            // DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => "Oops something wrong when create asset request!",
                'error' => $th->getMessage(),
                'file' => $th->getFile(),
                'line' => $th->getLine(),
                'trace' => $th->getTraceAsString(),
            ], 500);
        }
    }

    /**
     * Inisialisasi semua approval stages dan log awal untuk request baru.
     *
     * @param  \App\Models\AssetRequest  $assetRequest
     * @return void
     */
    private function initializeApprovalStages(AssetRequest $assetRequest): void
    {
        // 1. Ambil template workflow yang relevan
        $workflowTemplate = WorkflowTemplate::with('stages')->where('name', 'Permohonan Pembelian Aset')->firstOrFail();

        // 2. Buat approval stages
        $assetRequest->approvalStages()->createMany(
            $workflowTemplate->stages->map(fn($stage) => [
                'role_id'       => $stage->role_id,
                'stage_order'   => $stage->stage_order
            ])->toArray()
        );
    }

    /**
     * Memproses tahap saat ini
     *
     * @param  \App\Models\AssetRequest  $assetRequest
     * @param  \App\Models\RequestStatus  $requestStatus
     * @param  string  $notes
     * @param  int|null  $stageOrder
     * @return void
     */
    public function processStageAndLog(
        AssetRequest $assetRequest,
        RequestStatus $requestStatus,
        string $notes,
        ?int $stageOrder = null
    ): void {
        // Ambil current stage (yang sedang aktif atau sesuai stageOrder)
        $currentStage = $this->getStage($assetRequest, $stageOrder);

        if (!$currentStage) {
            throw new \Exception("Approval stage not found or already processed.");
        }

        if ($requestStatus->code === 'revision_requested') {
            $currentStage->update([
                'locked_to_user'   => true,
                'assigned_user_id' => auth()->id(),
            ]);
        }

        // Jika bukan revision_submitted
        if ($requestStatus->code != 'revision_submitted') {
            $currentStage->update(['approved_by' => auth()->id(), 'approver_at' => now()]);
        }

        $currentStage->update([
            'request_status_id' => $requestStatus->id,
            'updated_at' => now(),
        ]);

        $this->logApprovalAction(
            assetRequest: $assetRequest,
            requestStatus: $requestStatus,
            roleId: auth()->user()->role_id,
            userId: auth()->id(),
            notes: $notes,
            stageOrder: $currentStage->stage_order
        );

        $assetRequest->update(['request_status_id' => $requestStatus->id]);
    }

    /**
     * Memproses transisi ke tahap berikutnya dalam alur persetujuan.
     *
     * @param  \App\Models\AssetRequest  $assetRequest
     * @return
     */
    private function provideNextStage(AssetRequest $assetRequest)
    {
        $nextStage = $this->getStage($assetRequest);

        // Tidak ada stage berikutnya → workflow selesai
        if (!$nextStage) {
            $nextStage->update([
                'status' => RequestStatus::where('code', 'completed')->value('id'),
                'updated_at' => now(),
            ]);
            return;
        }

        $requestStatus = RequestStatus::where('code', 'waiting_approval')->first();

        // Reset data stage berikutnya
        $nextStage->update([
            'request_status_id' => $requestStatus->id,
        ]);

        $this->logApprovalAction(
            assetRequest: $assetRequest,
            requestStatus: $requestStatus,
            roleId: $nextStage->role_id,
            userId: null,
            notes: null,
            stageOrder: $nextStage->stage_order
        );

        $assetRequest->update(['request_status_id' => $requestStatus->id]);

        // Kirim notifikasi ke semua user di role stage berikutnya
        // $this->notifyStageUsers($nextStage);
    }

    /**
     * Helper function untuk mencatat log persetujuan.
     *
     * @param  \App\Models\AssetRequest  $assetRequest
     * @param  \App\Models\RequestStatus  $requestStatus
     * @param  string|null  $notes
     * @param  int|null  $stageOrder
     * @return void
     */
    private function logApprovalAction(
        AssetRequest $assetRequest,
        RequestStatus $requestStatus,
        int $roleId,
        ?int $userId = null,
        ?string $notes = null,
        ?int $stageOrder = null
    ): void {
        if (isset($userId)) {
            $log = $assetRequest->approvalLogs()
                ->where('stage_order', $stageOrder)
                ->where('role_id', $roleId)
                ->whereNull('user_id')
                ->firstOrFail();
            $log->update([
                'user_id'  => $userId,
                'is_shown' => false,
            ]);
        } else {
            $assetRequest->approvalLogs()->create([
                'asset_request_id' => $assetRequest->id,
                'request_status_id' => $requestStatus->id,
                'role_id' => $roleId,
                'user_id' => $userId,
                'stage_order' => $stageOrder,
                'notes' => $notes,
            ]);
        }
    }

    /**
     * Ambil stage approval saat ini.
     */
    private function getStage(AssetRequest $assetRequest, ?int $stageOrder = null) : ApprovalStage
    {
        $query =  $assetRequest->approvalStages()
            ->when($stageOrder, fn($q) =>
                $q->where('stage_order', $stageOrder)
            )
            ->whereNull('approved_by') # ambil yang belum diproses
            ->orderBy('stage_order');

        return $query->first();
    }

    /**
     * Ambil log approval saat ini.
     */
    private function getLog(AssetRequest $assetRequest, int $stageOrder, int $roleId, ?int $userId = null) : ApprovalStage
    {
        $query = $assetRequest->approvalLogs()
            ->when($userId, fn($q) =>
                $q->where('user_id', $userId)
            )
            ->where('stage_order', $stageOrder)
            ->where('role_id', $roleId);

        return $query->first();
    }

    public function approve(Request $request)
    {
        $request->validate([
            'request_id' => 'required|exists:budgets,id',
            'remarks'    => 'nullable|string'
        ]);

        try {
            $currentUser = JWTAuth::parseToken()->authenticate();

            $assetRequest = AssetRequest::find($request->id);
            if (!isset($assetRequest)) {
                return response()->json([
                    'success' => false,
                    'message' => "Asset request not found!"
                ], 404);
            }

            $flow = $assetRequest->flows->firstWhere('status', $assetRequest->status);

            if ($currentUser->role != $flow->status->actor) {
                return response()->json([
                    'success' => false,
                    'message' => "You not allowed to approve this request!"
                ], 404);
            }

            $nextStatus = '';
            switch ($assetRequest->status) {
                case 'waiting_approval_budget_owner':
                    $nextStatus = 'waiting_approval_finance_controller';
                    break;
                case 'waiting_approval_finance_controller':
                    $nextStatus = 'waiting_approval_head_finance';
                    break;
                case 'waiting_approval_head_finance':
                    $nextStatus = 'waiting_approval_managing_director';
                    break;
                case 'waiting_approval_managing_director':
                    $nextStatus = 'waiting_approval_finance_ksea';
                    break;
                case 'waiting_approval_finance_ksea':
                    $nextStatus = 'waiting_approval_finance_ksea';
                    break;
                default:
                    $nextStatus = 'finished';
                    break;
            }

            if ($nextStatus != 'finished') {
                $assetRequest->flows()->create([
                    'actor_id'   => $currentUser->id,
                    'actor_name' => $currentUser->name,
                    'status'     => 'approved',
                ]);
            }

            // Buat flow lewat relasi AssetRequest
            $asset = $assetRequest->flows()->create([
                'actor_id'   => $currentUser->id,
                'actor_name' => $currentUser->name,
                'status'     => $nextStatus
            ]);

            return response()->json([
                'success' => true,
                'message' => "Approved asset request successfully!"
            ], 201);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => "Oops something wrong when create asset request!",
                'error' => $th->message
            ], 500);
        }
    }
}
