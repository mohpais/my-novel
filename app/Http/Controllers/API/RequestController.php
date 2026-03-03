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
                    'request.request_code',
                    'request.purchase_quantity',
                    'request.request_status_id',
                    'request_status.code as request_status_code',
                    'request_status.label as request_status_name',
                    'request.name as asset_name',
                    'request.description',
                    'request.reason',
                    // 'request.replacement',
                    // 'request.useful_life',
                    'request.initial_value',
                    'request.currency',
                    DB::raw("CONCAT(cost_center.code, ' - ', cost_center.name) as cost_center"),
                    'profit_center.code as profit_center',
                    'business_unit.code as business_unit',
                    'budget.code as budget_code',
                    // field dari approval stage yang diambil
                    'approval_stage.id as current_stage_id',
                    'approval_stage.role_name as current_stage_role',
                    'request.created_at',
                    'request.updated_at'
                )
                ->leftJoin('request_statuses as request_status', 'request_status.id', '=', 'request.request_status_id')
                ->leftJoin('cost_centers as cost_center', 'request.cost_center_id', '=', 'cost_center.id')
                ->leftJoin('profit_centers as profit_center', 'profit_center.id', '=', 'cost_center.profit_center_id')
                ->leftJoin('business_units as business_unit', 'business_unit.id', '=', 'cost_center.business_unit_id')
                ->leftJoin('budgets as budget', 'request.budget_id', '=', 'budget.id')
                // join subquery approval_stages dengan row_num = 1
                ->leftJoin(DB::raw('(
                    SELECT
                        x.*
                    FROM (
                        SELECT a.*, r.name as role_name,
                        ROW_NUMBER() OVER (PARTITION BY a.asset_request_id ORDER BY a.stage_order ASC) AS row_num
                        FROM approval_stages a
                        LEFT JOIN roles r ON a.role_id = r.id
                        WHERE a.actioned_by IS NULL
                        AND a.actioned_at IS NULL
                    ) x
                    WHERE x.row_num = 1
                ) as approval_stage'), 'approval_stage.asset_request_id', '=', 'request.id');

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


    function get(string $request_code)
    {
        $request = AssetRequest::with([
                'budget',
                'costCenter',
                'requester',
                'status',
                'quotations',
                'justifications',
                'justifications.question',
                'approvalStages',
                'approvalStages.actor',
                'approvalStages.role',
                'approvalLogs' => function($query) {
                    $query->where('is_shown', true)->orderBy('created_at', 'desc')->orderBy('id', 'desc');
                },
                'approvalLogs.user',
                'approvalLogs.role',
                'approvalLogs.status'
            ])
            ->where('request_code', $request_code)->first();

        if (!$request) {
            return response()->json([
                'success' => false,
                'message' => "Asset request not found!"
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $request
        ], 200);
    }

    public function pending(ApiPaginationRequest $request)
    {
        try {
            $user = auth()->user();
            $userRole = $user->role; // contoh kalau pakai relasi roles()

            $requestStatus = RequestStatus::where('code', 'waiting_approval')->first();

            $query = AssetRequest::query()
                ->join('budgets', 'budgets.id', '=', 'asset_requests.budget_id')
                ->join('cost_centers as cost_center', 'asset_requests.cost_center_id', '=', 'cost_center.id')
                ->join('request_statuses as request_status', 'asset_requests.request_status_id', '=', 'request_status.id')
                ->join('approval_stages', 'asset_requests.id', '=', 'approval_stages.asset_request_id')
                ->where('approval_stages.request_status_id', $requestStatus->id)
                ->where(function ($query) use ($user) {
                    $query->where(function ($q) use ($user) {
                        $q->where('approval_stages.is_locked_to_user', true)
                            ->where('approval_stages.assigned_to', $user->id);
                    })
                    ->orWhere(function ($q) use ($user) {
                        $q->where('approval_stages.is_locked_to_user', false)
                            ->where('approval_stages.role_id', $user->role->id)
                            ->whereNull('approval_stages.actioned_by');
                    });
                })
                ->select(
                    'asset_requests.id',
                    'asset_requests.request_code',
                    'asset_requests.name',
                    'asset_requests.budget_id',
                    'asset_requests.cost_center_id',
                    'budgets.code as budget_code',
                    'budgets.item as budget_item',
                    'cost_center.code as cost_center_code',
                    'cost_center.name as cost_center_name',
                    'asset_requests.request_status_id',
                    'request_status.code as request_status_code',
                    'request_status.label as request_status_name',
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

    public function history(ApiPaginationRequest $request)
    {
        try {
            $user = auth()->user();
            $userRole = $user->role; // contoh kalau pakai relasi roles()

            $requestStatus = RequestStatus::where('code', 'approved')->first();

            $query = AssetRequest::query()
                ->join('budgets', 'budgets.id', '=', 'asset_requests.budget_id')
                ->join('cost_centers as cost_center', 'asset_requests.cost_center_id', '=', 'cost_center.id')
                ->join('request_statuses as request_status', 'asset_requests.request_status_id', '=', 'request_status.id')
                ->join('approval_stages', 'asset_requests.id', '=', 'approval_stages.asset_request_id')
                ->where('approval_stages.request_status_id', $requestStatus->id)
                ->where('approval_stages.actioned_by', $user->id)
                ->select(
                    'asset_requests.id',
                    'asset_requests.request_code',
                    'asset_requests.name',
                    'asset_requests.budget_id',
                    'asset_requests.cost_center_id',
                    'budgets.code as budget_code',
                    'budgets.item as budget_item',
                    'cost_center.code as cost_center_code',
                    'cost_center.name as cost_center_name',
                    'asset_requests.request_status_id',
                    'request_status.code as request_status_code',
                    'request_status.label as request_status_name',
                    'asset_requests.purchase_quantity',
                    'approval_stages.actioned_at',
                    'approval_stages.stage_order'
                )
                ->orderBy('approval_stages.actioned_at', 'desc');

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
    public function draft()
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

                // 3. Masukkan Quotation
                // Insert quotations sekaligus (mass insert)
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

                // 4. Masukkan Justifikasi
                // Insert justifikasi sekaligus (mass insert)
                $assetRequest->justifications()->createMany(
                    collect($request->justifications)->map(fn($value) => [
                        'question_id' => $value['id'],
                        'answer'      => $value['answer'],
                    ])->toArray()
                );

                // 5. Inisialisasi approval stage dan Log
                $this->initializeApprovalStages($assetRequest);

                // 6. Update Stage submitted
                // ambil stage untuk role requester
                $currentStage = $assetRequest->approvalStages()->where('role_id', auth()->user()->role_id)->first();
                $currentStage->update([
                    'request_status_id' => $statusSubmitted->id,
                    'actioned_by' => auth()->id(),
                    'actioned_at' => now(),
                ]);

                //  dan Insert Log submitted
                $assetRequest->approvalLogs()->create([
                    'asset_request_id' => $assetRequest->id,
                    'request_status_id' => $statusSubmitted->id,
                    'role_id' => auth()->user()->role_id,
                    'user_id' => auth()->id(),
                    'stage_order' => $currentStage->stage_order,
                    'notes' => 'Permintaan aset baru telah diajukan.',
                ]);

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
     * Memproses transisi ke tahap berikutnya dalam alur persetujuan.
     *
     * @param  \App\Models\AssetRequest  $assetRequest
     * @return
     */
    private function provideNextStage(AssetRequest $assetRequest)
    {
        $nextStage = $this->getStage($assetRequest);

        // Tidak ada stage berikutnya → insert log selesai
        if (!$nextStage) {
            $statusCompletedId = RequestStatus::where('code', 'completed')->value('id');
            $assetRequest->approvalLogs()->create([
                'asset_request_id' => $assetRequest->id,
                'request_status_id' => $statusCompletedId,
                'role_id' => auth()->user()->role_id,
                'user_id' => auth()->id(),
                'stage_order' => $nextStage->stage_order,
                'notes' => 'Pengajuan telah selesai.',
            ]);

            $assetRequest->update(['request_status_id' => $requestStatus->id]);
            return;
        }

        $statusWaitingApprovalId = RequestStatus::where('code', 'waiting_approval')->value('id');
        // Update stage berikutnya ke status waiting approval id
        $nextStage->update([
            'request_status_id' => $statusWaitingApprovalId
        ]);

        // Insert status waiting approval ke log
        $assetRequest->approvalLogs()->create([
            'asset_request_id' => $assetRequest->id,
            'request_status_id' => $statusWaitingApprovalId,
            'role_id' => $nextStage->role_id,
            'stage_order' => $nextStage->stage_order,
        ]);

        // Update asset request ke waiting status
        $assetRequest->update(['request_status_id' => $statusWaitingApprovalId, 'updated_at' => now()]);
    }

    /**
     * Ambil stage approval saat ini.
     */
    private function getStage(AssetRequest $assetRequest, ?int $stageOrder = null)
    {
        $query = $assetRequest->approvalStages()
            ->when($stageOrder, fn($q) =>
                $q->where('stage_order', $stageOrder)
            )
            ->whereNull('actioned_by') # ambil yang belum diproses
            ->orderBy('stage_order');

        return $query->first();
    }

    public function approve(Request $request)
    {
        try {
            $request->validate([
                'asset_request_id' => 'required|exists:asset_requests,id',
                'remarks'    => 'nullable|string'
            ]);

            //
            $assetRequest = AssetRequest::find($request->asset_request_id);
            if (!isset($assetRequest)) {
                return response()->json([
                    'success' => false,
                    'message' => "Asset request not found!"
                ], 404);
            }

            $currentStage = $this->getStage($assetRequest);
            if (auth()->user()->role_id <> $currentStage->role_id) {
                return response()->json([
                    'success' => false,
                    'message' => "You not allowed to approve this request!"
                ], 403);
            }

            // Update current stage
            $statusApprovedId = RequestStatus::where('code', 'approved')->value('id');
            $currentStage->update([
                'request_status_id' => $statusApprovedId,
                'actioned_by' => auth()->id(),
                'actioned_at' => now(),
            ]);

            // Cari log yang sedang waiting approval dan update data
            $statusWaitingApprovalId = RequestStatus::where('code', 'waiting_approval')->value('id');
            $logAwating = $assetRequest->approvalLogs()
                ->whereNull('user_id')
                ->where('request_status_id', $statusWaitingApprovalId) # ambil yang sedang waiting approval
                ->where('is_shown', true)
                ->first();

            //
            $logAwating->update([
                'user_id'  => auth()->id(),
                'is_shown' => false
            ]);

            // dan Insert Log approval
            $assetRequest->approvalLogs()->create([
                'asset_request_id' => $assetRequest->id,
                'request_status_id' => $statusApprovedId,
                'role_id' => auth()->user()->role_id,
                'user_id' => auth()->id(),
                'stage_order' => $currentStage->stage_order,
                'notes' => $request->remarks
            ]);

            // 7. Proses Next Stage
            $this->provideNextStage($assetRequest);

            return response()->json([
                'success' => true,
                'message' => "Approved asset request successfully!"
            ], 201);
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

    public function reject(Request $request)
    {
        try {
            $request->validate([
                'asset_request_id' => 'required|exists:asset_requests,id',
                'remarks'    => 'nullable|string'
            ]);

            //
            $assetRequest = AssetRequest::find($request->asset_request_id);
            if (!isset($assetRequest)) {
                return response()->json([
                    'success' => false,
                    'message' => "Asset request not found!"
                ], 404);
            }

            $currentStage = $this->getStage($assetRequest);
            if (auth()->user()->role_id <> $currentStage->role_id) {
                return response()->json([
                    'success' => false,
                    'message' => "You not allowed to reject this request!"
                ], 403);
            }

            // Update current stage
            $statusRejectedId = RequestStatus::where('code', 'rejected')->value('id');
            $currentStage->update([
                'request_status_id' => $statusRejectedId,
                'actioned_by' => auth()->id(),
                'actioned_at' => now(),
            ]);

            // Cari log yang sedang waiting approval dan update data
            $statusWaitingApprovalId = RequestStatus::where('code', 'waiting_approval')->value('id');
            $logAwating = $assetRequest->approvalLogs()
                ->whereNull('user_id')
                ->where('request_status_id', $statusWaitingApprovalId) # ambil yang sedang waiting approval
                ->where('is_shown', true)
                ->first();

            //
            $logAwating->update([
                'user_id'  => auth()->id(),
                'is_shown' => false
            ]);

            // dan Insert Log approval
            $assetRequest->approvalLogs()->create([
                'asset_request_id' => $assetRequest->id,
                'request_status_id' => $statusApprovedId,
                'role_id' => auth()->user()->role_id,
                'user_id' => auth()->id(),
                'stage_order' => $currentStage->stage_order,
                'notes' => $request->remarks
            ]);

            return response()->json([
                'success' => true,
                'message' => "Rejected asset request successfully!"
            ], 201);
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
}
