<?php

namespace App\Jobs;

use App\Services\AI\VectorMemoryService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ProcessAiEmbedding implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $novelId;
    protected $category;
    protected $sourceId;
    protected $content;

    /**
     * Tentukan jumlah percobaan jika gagal (API timeout, dll)
     */
    public $tries = 3;

    /**
     * Waktu tunggu sebelum mencoba lagi (detik)
     */
    public $backoff = 10;

    public function __construct($novelId, $category, $sourceId, $content)
    {
        $this->novelId = $novelId;
        $this->category = $category;
        $this->sourceId = $sourceId;
        $this->content = $content;
    }

    public function handle(VectorMemoryService $vectorService)
    {
        try {
            $vectorService->store(
                $this->novelId,
                $this->category,
                $this->sourceId,
                $this->content
            );
        } catch (\Exception $e) {
            Log::error("Gagal memproses embedding untuk {$this->category} ID {$this->sourceId}: " . $e->getMessage());
            throw $e; // Lempar kembali agar Laravel Queue melakukan retry
        }
    }
}