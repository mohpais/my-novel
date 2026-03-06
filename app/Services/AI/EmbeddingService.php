<?php

namespace App\Services\AI;

use Illuminate\Support\Facades\Http;

class EmbeddingService
{

    protected $apiKey;

    public function __construct()
    {
        $this->apiKey = config('services.groq.key');
    }

    public function generateEmbedding(string $text): array
    {

        $response = Http::withHeaders([
            'Authorization' => 'Bearer '.$this->apiKey,
        ])->post('https://api.groq.com/openai/v1/embeddings', [
            "model" => "nomic-embed-text-v1",
            "input" => $text

        ]);

        return $response->json()['data'][0]['embedding'];
    }
}