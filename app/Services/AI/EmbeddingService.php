<?php

namespace App\Services\AI;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class EmbeddingService
{
    /**
     * URL API Ollama Lokal
     */
    protected $baseUrl;
    
    /**
     * Nama Model Embedding yang digunakan (nomic-embed-text)
     */
    protected $model;

    public function __construct()
    {
        // Mengambil konfigurasi dari config/services.php atau fallback ke default Ollama
        $this->baseUrl = config('services.ollama.url', 'http://localhost:11434/api/embeddings');
        $this->model = config('services.ollama.model', 'nomic-embed-text');
    }

    /**
     * Menghasilkan vektor embedding dari sebuah teks menggunakan Ollama
     *
     * @param string $text
     * @return array
     * @throws \Exception
     */
    public function generateEmbedding(string $text): array
    {
        try {
            // Log proses untuk memantau aktivitas di laravel.log (opsional)
            Log::info("Memulai proses embedding lokal dengan Ollama...");

            // Melakukan request POST ke server Ollama lokal
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->post($this->baseUrl, [
                "model" => $this->model,
                "prompt" => $text // Ollama menggunakan key 'prompt'
            ]);

            // Jika request gagal (Ollama mati atau model belum di-pull)
            if ($response->failed()) {
                Log::error("Ollama Lokal Error", [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
                throw new \Exception("Gagal mengambil embedding. Pastikan Ollama sudah berjalan dan model {$this->model} sudah di-pull.");
            }

            $result = $response->json();

            // Struktur response Ollama: {"embedding": [0.1, 0.2, ...]}
            if (!isset($result['embedding'])) {
                Log::error("Format Response Ollama Tidak Valid", ['data' => $result]);
                throw new \Exception("Response dari Ollama tidak mengandung data vektor.");
            }

            return $result['embedding'];

        } catch (\Exception $e) {
            Log::error("Exception pada EmbeddingService: " . $e->getMessage());
            throw $e;
        }
    }
}