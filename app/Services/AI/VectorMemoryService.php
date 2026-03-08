<?php

namespace App\Services\AI;

use App\Models\AiVector;
use Illuminate\Database\Eloquent\Model;

class VectorMemoryService
{
    protected $embeddingService;

    public function __construct(EmbeddingService $embeddingService)
    {
        $this->embeddingService = $embeddingService;
    }

    /**
     * Menyimpan atau mengupdate vector berdasarkan Model (Lore, Location, dll)
     */
    public function updateMemory(Model $model, string $content, string $tags = null)
    {
        $vector = $this->embeddingService->generateEmbedding($content);

        return AiVector::updateOrCreate(
            [
                'vectorable_id'   => $model->id,
                'vectorable_type' => get_class($model),
            ],
            [
                'novel_id'  => $model->novel_id,
                'content'   => $content,
                'embedding' => $vector,
                'tags'      => $tags ?? $model->type ?? null, // Menggunakan kolom tags baru
            ]
        );
    }

    /**
     * Mencari konten yang paling relevan berdasarkan query dan novel tertentu.
     */
    public function search($novelId, $query, $limit = 5, $type = null)
    {
        $queryVector = $this->embeddingService->generateEmbedding($query);
        
        $dbQuery = AiVector::where('novel_id', $novelId);

        // Filter berdasarkan tipe model jika spesifik (misal hanya cari di 'App\Models\Location')
        if ($type) {
            $dbQuery->where('vectorable_type', $type);
        }

        // Kita ambil 100 data terbaru sebagai candidate pool
        $vectors = $dbQuery->latest()->take(100)->get();

        $scores = [];
        foreach ($vectors as $vector) {
            // Memastikan embedding di-cast sebagai array di Model AiVector
            $similarity = $this->cosineSimilarity($queryVector, $vector->embedding);

            $scores[] = [
                'content' => $vector->content,
                'type'    => $vector->vectorable_type,
                'score'   => $similarity
            ];
        }

        usort($scores, fn($a, $b) => $b['score'] <=> $a['score']);
        return array_slice($scores, 0, $limit);
    }

    private function cosineSimilarity($a, $b)
    {
        if (count($a) !== count($b)) return 0;
        $dot = 0; $normA = 0; $normB = 0;
        foreach ($a as $i => $value) {
            $dot += $a[$i] * $b[$i];
            $normA += $a[$i] * $a[$i];
            $normB += $b[$i] * $b[$i];
        }
        $divider = sqrt($normA) * sqrt($normB);
        return ($divider == 0) ? 0 : ($dot / $divider);
    }
}