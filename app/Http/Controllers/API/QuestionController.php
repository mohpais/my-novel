<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Question;

class QuestionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function list()
    {
        $questions = Question::all();

        return response()->json([
            'success' => true,
            'data' => $questions
        ]);
    }
}
