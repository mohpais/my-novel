<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;

use App\Services\AI\EmbeddingService;
use Illuminate\Http\Request;
use App\Models\AiConversation;
use Illuminate\Support\Facades\Http;
use App\Services\AI\ContextEngine;
use Illuminate\Support\Facades\DB;

class AiAssistantController extends Controller
{
    protected $embeddingService;
    protected $contextEngine;

    public function __construct(EmbeddingService $embeddingService, ContextEngine $contextEngine)
    {
        $this->embeddingService = $embeddingService;
        $this->contextEngine = $contextEngine;
    }

    /**
     * Endpoint untuk bertanya ke AI dengan konteks Lore
     */
    public function ask(Request $request)
    {
        $request->validate([
            'novel_id'   => 'required|exists:novels,id',
            'session_id' => 'nullable|string', // Untuk tracking history chat
        ]);

        try {
            // 1. Inisialisasi atau ambil session chat
            $sessionId = $request->session_id ?? uniqid('sess_');
            
            AiConversation::firstOrCreate([
                'session_id' => $sessionId,
                'novel_id'   => $request->novel_id
            ]);

            // 2. Panggil Engine untuk mencari context & hit AI API
            $response = $this->contextEngine->ask(
                $request->novel_id,
                $request->prompt,
                $sessionId
            );

            return response()->json([
                'session_id' => $sessionId,
                'answer'     => $response,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
    
    // public function ask(Request $request)
    // {
    //     $request->validate([
    //         'question' => 'required|string|max:1000',
    //         'novel_id' => 'required|exists:novels,id'
    //     ]);

    //     try {
    //         $question = $request->question;

    //         // LANGKAH 1: Ubah pertanyaan jadi Vektor via OLLAMA
    //         $questionVector = $this->embeddingService->generateEmbedding($question);

    //         // LANGKAH 2: Cari Lore relevan di Database (Similarity Search)
    //         // Asumsi tabel bernama 'ai_vectors' dengan kolom 'embedding' (vector) dan 'content'
    //         $relevantLore = DB::table('ai_vectors')
    //             ->select('content')
    //             ->where('novel_id', $request->novel_id)
    //             ->orderByRaw("embedding <=> '" . json_encode($questionVector) . "' ASC")
    //             ->limit(3)
    //             ->get()
    //             ->pluck('content')
    //             ->implode("\n\n");

    //         // LANGKAH 3: Rakit Prompt untuk GROQ
    //         $prompt = "Anda adalah asisten penulis novel yang ahli. 
    //         Gunakan informasi latar belakang (Lore) berikut untuk menjawab pertanyaan user. 
    //         Jika informasi tidak ada di lore, jawablah berdasarkan imajinasi kreatif yang konsisten.

    //         LORE RELEVAN:
    //         $relevantLore

    //         PERTANYAAN USER:
    //         $question";

    //         // LANGKAH 4: Kirim ke GROQ (Otak AI)
    //         $response = Http::withHeaders([
    //             'Authorization' => 'Bearer ' . config('services.groq.key'),
    //             'Content-Type' => 'application/json',
    //         ])->post('https://api.groq.com/openai/v1/chat/completions', [
    //             "model" => config('services.groq.model', 'llama-3.3-70b-versatile'),
    //             "messages" => [
    //                 ["role" => "system", "content" => "Asisten novel yang kreatif dan konsisten."],
    //                 ["role" => "user", "content" => $prompt]
    //             ],
    //             "temperature" => 0.7
    //         ]);

    //         if ($response->failed()) {
    //             throw new \Exception("Groq Error: " . $response->body());
    //         }

    //         $answer = $response->json()['choices'][0]['message']['content'];

    //         return response()->json([
    //             'status' => 'success',
    //             'answer' => $answer,
    //             'context_used' => $relevantLore ? 'Berdasarkan Lore Novel' : 'Berdasarkan AI Umum'
    //         ]);

    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'status' => 'error',
    //             'message' => $e->getMessage()
    //         ], 500);
    //     }
    // }
}
