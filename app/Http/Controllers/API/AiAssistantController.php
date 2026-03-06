<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;

use App\Services\AI\ContextEngine;

use Illuminate\Http\Request;

class AiAssistantController extends Controller
{
    protected $ai;

    public function __construct(ContextEngine $ai)
    {
        $this->ai = $ai;
    }

    public function ask(Request $request)
    {
        $novelId = $request->novel_id;
        $prompt = $request->prompt;

        $response = $this->ai->ask($novelId, $prompt);

        return response()->json([
            'response' => $response
        ]);
    }
}
