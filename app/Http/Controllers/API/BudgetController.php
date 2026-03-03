<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Budget;

use Illuminate\Http\Request;

class BudgetController extends Controller
{
    function list() {
        $budgets = Budget::all();

        return response()->json([
            'success' => true,
            'data' => $budgets
        ]);
    }
    /**
     * Get a list of budgets for options.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function options()
    {
        $budgets = Budget::select('id', 'code')
            ->orderBy('code', 'asc')
            ->get()
            ->map(function ($budget) {
                return [
                    'id' => $budget->id,
                    'text' => $budget->code,
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $budgets
        ]);
    }
}
