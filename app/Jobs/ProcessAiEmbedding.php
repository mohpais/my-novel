<?php

namespace App\Jobs;

use App\Services\AI\VectorMemoryService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class ProcessAiEmbedding implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $model;
    protected $content;
    protected $category;
    protected $chunkIndex;

    public $tries = 3;
    public $backoff = 10;

    // Kita passing Model dan Content-nya
    public function __construct(Model $model, string $content, string $category, int $chunkIndex = 0)
    {
        $this->model = $model;
        $this->content = $content;
        $this->category = $category;
        $this->chunkIndex = $chunkIndex;
    }

    public function handle(VectorMemoryService $vectorService)
    {
        try {
            // Menggunakan metode updateMemory yang sudah kita buat di Service sebelumnya
            $vectorService->updateMemory($this->model, $this->content, $this->category, $this->chunkIndex);
            
        } catch (\Exception $e) {
            Log::error("Gagal memproses embedding untuk " . get_class($this->model) . " ID {$this->model->id}: " . $e->getMessage());
            throw $e;
        }
    }
}