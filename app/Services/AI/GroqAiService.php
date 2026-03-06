<?php

namespace App\Services\AI;

use Illuminate\Support\Facades\Http;
use App\Models\AiLog;

class GroqAiService
{
    protected $apiKey;
    protected $model;

    public function __construct()
    {
        $this->apiKey = config('services.groq.key');
        $this->model = config('services.groq.model');
    }

    public function chat($prompt, $context = null, $history = [])
    {
        $systemPrompt = "
            You are a Professional Novel Writing Assistant.
            Your job: Assist the author with the provided LORE-based information.

            STRICT RULES:
            1. Use ONLY information from the provided 'Novel Context'. Do not contradict existing worldbuilding.
            2. If information isn't in the context or history, say: 'Sorry, that data hasn't been recorded in your novel's memory.'
            3. Never invent new names, places, or powers unless the author asks you to 'brainstorm' or 'suggest'.
            4. Always maintain character and world consistency.
        ";

        $messages = [
            [
                "role" => "system",
                "content" => $systemPrompt
            ]
        ];

        if ($context) {
            $messages[] = [
                "role" => "system",
                "content" => "Novel Context:\n".$context
            ];
        }

        // Masukkan riwayat pesan yang sudah dipangkas (history)
        foreach ($history as $msg) {
            $messages[] = $msg;
        }

        $messages[] = [
            "role" => "user",
            "content" => $prompt
        ];


        // Sisanya lakukan request HTTP seperti sebelumnya...
        // Tambahkan try-catch untuk error handling
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer '.$this->apiKey,
                'Content-Type' => 'application/json',
            ])->post('https://api.groq.com/openai/v1/chat/completions', [
                "model" => $this->model,
                "messages" => $messages,
                "temperature" => 0.1,
                "max_tokens" => 1200
            ]);

            if ($response->successful()) {
                $data = $response->json();
                
                // Catat log penggunaan
                AiLog::create([
                    'provider' => 'groq',
                    'model' => $this->model,
                    'prompt_tokens' => $data['usage']['prompt_tokens'] ?? 0,
                    'completion_tokens' => $data['usage']['completion_tokens'] ?? 0,
                    'response_time' => microtime(true) - $startTime,
                ]);

                return $data['choices'][0]['message']['content'];
            }
        } catch (\Exception $e) {
            \Log::error("AI Chat Error: " . $e->getMessage());
            return "Maaf, pustaka memori sedang tidak bisa diakses.";
        }
    }
}