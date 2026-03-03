<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\BuilderDataTableService;

use App\Models\BusinessUnit;
use App\Models\CostCenter;
use App\Models\ProfitCenter;

use Illuminate\Http\Request;

class FinanceController extends Controller
{
    public function __construct(private BuilderDataTableService $dataTableService)
    { }

    /**
     * Display a listing of the resource.
     */
    public function list(Request $request)
    {
        $type = $request->route('type', 'cost_center');
        if ($type == 'cost_center') {
            $query = CostCenter::query()
                ->leftJoin('business_units', 'cost_centers.business_unit_id', '=', 'business_units.id')
                ->leftJoin('profit_centers', 'cost_centers.profit_center_id', '=', 'profit_centers.id')
                ->select(
                    'cost_centers.id',
                    'cost_centers.code',
                    'cost_centers.name',
                    'cost_centers.description',
                    'profit_centers.code as profit_center',
                    'business_units.code as business_unit',
                    'cost_centers.created_at', 'cost_centers.updated_at'
                );
        } else if ($type == 'business_unit') {
            $query = BusinessUnit::query();
        } else if ($type == 'profit_center') {
            $query = ProfitCenter::query();
        }

        // Use the DataTableService or any other logic as needed
        $response = $this->dataTableService->getJsonResponse($request, $query);

        return response()->json($response, 200);
    }

    public function count()
    {
        $countBU = BusinessUnit::count();
        $countCC = CostCenter::count();
        $countPC = ProfitCenter::count();

        return response()->json([
            'success' => true,
            'data' => [
                'business_unit' => $countBU,
                'cost_center' => $countCC,
                'profit_center' => $countPC,
            ]
        ], 200);
    }
}
