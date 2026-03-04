<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\BuilderDataTableService;

use App\Models\Novel;

use Illuminate\Http\Request;

class NovelController extends Controller
{
    public function __construct(private BuilderDataTableService $dataTableService)
    { }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Novel::query();

        // Use the DataTableService or any other logic as needed
        $response = $this->dataTableService->getJsonResponse($request, $query);

        return response()->json($response, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'synopsis' => 'nullable|string',
            'author' => 'required|string|max:255',
            'genre_id' => 'required|exists:genres,id',
            'genre_id' => 'required|exists:genres,id',
            // Add other validation rules as necessary
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $novel = Novel::create($request->all());

        return response()->json($novel, 201);
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
