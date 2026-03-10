<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;

use App\Services\BuilderDataTableService;

use App\Models\Tag;

use Illuminate\Http\Request;

class TagController extends Controller
{
    public function __construct(private BuilderDataTableService $dataTableService)
    { }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Tag::query();

        // Use the DataTableService or any other logic as needed
        $response = $this->dataTableService->getJsonResponse($request, $query);

        return response()->json($response, 200);
    }

    public function options()
    {
        $tags = Tag::
            select('id', 'name') // Pilih kolom yang diperlukan
            ->orderBy('name', 'asc') // Urutkan berdasarkan nama
            ->get();

        return response()->json([
            'success' => true,
            'message' => "Tags retrieved successfully!",
            'data' => $tags,
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
