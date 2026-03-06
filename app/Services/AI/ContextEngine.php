<?php

namespace App\Services\AI;

use App\Models\AiMessage;

class ContextEngine
{
    protected $ai;
    protected $memory;
    protected $vector;

    public function __construct(
        GroqAIService $ai,
        AiMemoryService $memory,
        VectorMemoryService $vector
    )
    {
        $this->ai = $ai;
        $this->memory = $memory;
        $this->vector = $vector;
    }

    public function ask($novelId, $prompt, $sessionId = null)
    {
        // Cek apakah prompt membutuhkan konteks novel
        if (!$this->isLoreRelated($prompt)) {
            return $this->ai->chat($prompt); // Langsung chat tanpa kirim konteks berat
        }

        // Ambil hanya 5 vector terdekat dan 5 memori terbaru/terpenting
        $vectorContexts = $this->vector->search($novelId, $prompt); 
        $memories = AiMemory::where('novel_id', $novelId)
            ->orderBy('importance', 'desc')
            ->latest()
            ->take(5)
            ->get();

        $contextText = "CONTEXT LORE:\n";
        foreach ($vectorContexts as $vc) {
            $contextText .= "- " . $vc['content'] . "\n";
        }

        foreach ($memories as $m) {
            $contextText .= "- " . $m->content . "\n";
        }

        // Ambil riwayat percakapan
        $history = "";
        if ($sessionId) {
            $history = AiMessage::whereHas('conversation', function($q) use ($sessionId) {
                $q->where('session_id', $sessionId);
            })->latest()->take(10)->get()->reverse();
            // $conversation = AiConversation::where('session_id', $sessionId)->first();
            // if ($conversation) {
            //     $messages = $conversation->messages()->latest()->take(6)->get()->reverse();
            //     foreach ($messages as $msg) {
            //         $history .= "{$msg->role}: {$msg->content}\n";
            //     }
            // }
        }

        // $fullPrompt = "History:\n{$history}\nContext:\n{$contextText}\nQuestion: {$prompt}";
        // return $this->ai->chat($fullPrompt);

        // Logika Pemangkasan: Jika total karakter terlalu panjang, kurangi pesan lama
        $maxChars = 12000; // Estimasi aman untuk context window 4k-8k tokens
        $currentChars = strlen($contextText) + strlen($prompt);
        
        $finalHistory = [];
        foreach ($history as $msg) {
            if (($currentChars + strlen($msg->content)) < $maxChars) {
                $finalHistory[] = ['role' => $msg->role, 'content' => $msg->content];
                $currentChars += strlen($msg->content);
            }
        }

        return $this->ai->chat($prompt, $contextText, $finalHistory);
    }

    private function isLoreRelated($prompt): bool
    {
        $keywords = ['siapa', 'apa itu', 'coba jelaskan', 'jelaskan', 'bagaimana', 'karakter', 'dunia', 'kekuatan', 'aetherial traits', 'latar', 'setting', 'novel', 'cerita', 'plot', 'konflik', 'tema'];
        return \Str::contains(strtolower($prompt), $keywords);
    }
}