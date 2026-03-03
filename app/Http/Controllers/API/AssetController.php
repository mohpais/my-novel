<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;

use App\Models\AssetRequest;
use App\Models\AssetRequestFlow;
use App\Models\Budget;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Tymon\JWTAuth\Facades\JWTAuth;

class AssetController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'budget_id'          => 'required|exists:budgets,id',
            'name'               => 'required|string|max:255',
            'description'        => 'nullable|string',
            'brief_reason'       => 'nullable|string',
            'replacement'        => 'nullable|boolean',
            'useful_life'        => 'nullable|integer|min:1',
            'purchase_quantity'  => 'required|integer|min:1',
        ]);

        try {
            $currentUser = JWTAuth::parseToken()->authenticate();

            $budget = Budget::with('costCenter')->findOrFail($request->budget_id);

            return DB::transaction(function () use ($request, $currentUser, $budget) {
                // Create AssetRequest
                $assetRequest = AssetRequest::create([
                    'purchase_quantity' => $request->purchase_quantity,
                    'submitted_by'      => $currentUser->id,
                    'status'            => 'submitted',
                    'name'              => $request->name,
                    'description'       => $request->description,
                    'reason'            => $request->brief_reason,
                    'replacement'       => $request->replacement,
                    'useful_life'       => $request->useful_life,
                    'budget_id'         => $budget->id,
                    'cost_center_id'    => $budget->costCenter->id,
                ]);

                // Buat flow lewat relasi AssetRequest
                $asset = $assetRequest->flows()->create([
                    'actor_id'   => $currentUser->id,
                    'actor_name' => $currentUser->name,
                    'status'     => 'submitted',
                ]);

                return response()->json([
                    'success' => true,
                    'message' => "Asset request created successfully!",
                    'data' => [
                        'request' => $assetRequest,
                        'asset'   => $asset,
                    ],
                ], 201);
            });
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => "Oops something wrong when create asset request!",
                'error' => $th->message
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show()
    {
        try {
            $currentUser = JWTAuth::parseToken()->authenticate();
            $assetRequest = AssetRequest::find($currentUser->id);

            return response()->json([
                'success' => true,
                'data' => $assetRequest,
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => "Oops something wrong when get current request!",
            ], 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
