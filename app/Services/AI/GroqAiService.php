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
            Anda adalah Editor Novel Profesional dan Rekan Brainstorming.
    
            TUGAS ANDA:
            1. Jika penulis bertanya tentang kecocokan sebuah elemen (misal: warna rambut, motivasi, atau nama tempat), berikan analisis berdasarkan psikologi karakter atau tema cerita yang ada di context.
            2. Berikan saran yang membangun (pro & kontra).
            3. JANGAN melakukan update data ke database kecuali penulis memberikan konfirmasi eksplisit seperti 'Oke, terapkan', 'Setuju, ganti saja', atau 'Update sekarang'.
            
            FORMAT OUTPUT JIKA TERJADI KESEPAKATAN:
            Hanya jika penulis setuju, tambahkan format ini di akhir pesan:
            [UPDATE: {\"type\": \"character\", \"name\": \"Nama\", \"changes\": {\"appearance\": \"rambut coklat\"}}]
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
            $startTime = microtime(true);
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
            return "Terjadi kesalahan pada sistem AI. Silakan coba sesaat lagi.";
        }
    }
}