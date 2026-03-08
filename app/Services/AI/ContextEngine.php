<?php

namespace App\Services\AI;

use App\Models\AiMessage;
use App\Models\AiConversation;
use Illuminate\Support\Str;

class ContextEngine
{
    protected $ai;
    protected $vector;

    public function __construct(GroqAiService $ai, VectorMemoryService $vector)
    {
        $this->ai = $ai;
        $this->vector = $vector;
    }

    public function ask($novelId, $prompt, $sessionId = null)
    {
        // 1. Ambil atau buat percakapan (Conversation)
        // Kita butuh ID conversation untuk relasi di tabel ai_messages
        $conversation = AiConversation::firstOrCreate(
            ['session_id' => $sessionId, 'novel_id' => $novelId]
        );

        // 2. SIMPAN PESAN USER KE DATABASE
        $conversation->messages()->create([
            'role' => 'user',
            'content' => $prompt
        ]);

        // 3. Identifikasi apakah butuh data World Building
        $contextText = "";
        if ($this->isLoreRelated($prompt)) {
            $relevantLores = $this->vector->search($novelId, $prompt, 7);
            
            $contextText = "SYSTEM CONTEXT (WORLD BUILDING):\n";
            foreach ($relevantLores as $lore) {
                $type = isset($lore['type']) ? class_basename($lore['type']) : 'Lore';
                $contextText .= "[{$type}]: " . $lore['content'] . "\n";
            }
        }

        // 4. Ambil riwayat percakapan (termasuk pesan user yang baru saja disimpan)
        $history = $this->getChatHistory($sessionId);

        // 5. Kirim ke LLM (Groq)
        $aiResponse = $this->ai->chat($prompt, $contextText, $history);

        // Cek apakah AI memberikan instruksi [UPDATE]
        if (preg_match('/\[UPDATE: (.*?)\]/s', $aiResponse, $matches)) {
            $updateData = json_decode($matches[1], true);

            // JALANKAN CONSISTENCY CHECKER
            $consistencyResult = $this->validateConsistency($novelId, $updateData);

            if (trim($consistencyResult) !== 'AMAN') {
                // Jika ada plot hole, ganti jawaban AI dengan peringatan
                $aiResponse = "Saran perubahan Anda saya deteksi memiliki potensi Plot Hole:\n\n" 
                            . $consistencyResult 
                            . "\n\nApakah Anda ingin tetap melanjutkan update ini atau menyesuaikannya?";
            } else {
                // Jika AMAN, eksekusi update ke database
                $this->handleDataUpdate($novelId, $aiResponse);
            }
        }

        // 6. SIMPAN JAWABAN AI KE DATABASE
        $conversation->messages()->create([
            'role' => 'assistant',
            'content' => $aiResponse
        ]);

        // // 2. Cek apakah ada instruksi [UPDATE] dalam jawaban AI
        // if (Str::contains($aiResponse, '[UPDATE:')) {
        //     // Jalankan fungsi update database yang kita bahas sebelumnya
        //     $this->handleDataUpdate($novelId, $aiResponse);
        // }

        return $aiResponse;
    }

    private function getChatHistory($sessionId)
    {
        if (!$sessionId) return [];

        return AiMessage::whereHas('conversation', function($q) use ($sessionId) {
                $q->where('session_id', $sessionId);
            })
            ->latest()
            ->take(10) // Ambil sedikit lebih banyak untuk konteks yang lebih baik
            ->get()
            ->reverse()
            ->map(fn($msg) => ['role' => $msg->role, 'content' => $msg->content])
            ->toArray();
    }

    private function isLoreRelated($prompt): bool
    {
        $keywords = [
            'siapa', 'apa itu', 'jelaskan', 'dimana', 'lokasi', 
            'sejarah', 'kekuatan', 'budaya', 'ras', 'karakter',
            'dunia', 'geografi', 'mitos', 'timeline', 'dia', 'mereka'
        ];
        return Str::contains(strtolower($prompt), $keywords);
    }

    private function handleDataUpdate($novelId, $aiResponse)
    {
        // Cari apakah ada pola JSON di dalam jawaban AI
        // Contoh prompt system ke AI: "Jika user meminta perubahan, kembalikan JSON di akhir jawaban dengan format [UPDATE: {...}]"
        if (preg_match('/\[UPDATE: (.*?)\]/s', $aiResponse, $matches)) {
            $updateData = json_decode($matches[1], true);
            
            if ($updateData['type'] === 'character') {
                $character = \App\Models\Character::where('novel_id', $novelId)
                    ->where('name', 'like', '%' . $updateData['name'] . '%')
                    ->first();

                if ($character) {
                    $character->update($updateData['changes']);
                    // Observer akan otomatis memicu ProcessAiEmbedding
                }
            }
        }
    }

    /**
     * Mengecek apakah perubahan data bertabrakan dengan lore lama
     */
    private function validateConsistency($novelId, $updateData)
    {
        // 1. Ambil inti perubahan (misal: "Rambut hitam legam")
        $newFact = json_encode($updateData['changes']);
        
        // 2. Cari 5 memori paling relevan dari Vector DB untuk dibandingkan
        $existingLore = $this->vector->search($novelId, $newFact, 5);
        
        if (empty($existingLore)) return "AMAN";

        $contextString = collect($existingLore)->pluck('content')->implode("\n");

        // 3. Minta AI melakukan audit internal
        $auditPrompt = "
            Tugas: Audit Konsistensi Lore.
            Informasi Baru yang akan dimasukkan: {$newFact} (untuk entitas: {$updateData['name']})
            
            Data Lama yang relevan di database:
            {$contextString}

            Instruksi:
            - Jika informasi baru TIDAK bertabrakan dengan data lama, balas dengan satu kata: 'AMAN'.
            - Jika ada kontradiksi (Plot Hole), jelaskan di mana letak ketidakkonsistenannya secara singkat.
        ";

        return $this->ai->chat($auditPrompt);
    }
}