<?php

namespace App\Services\AI;

use App\Models\AiVector;

class VectorMemoryService
{

    protected $embedding;

    public function __construct(EmbeddingService $embedding)
    {
        $this->embedding = $embedding;
    }

    public function store($novelId, $category, $id, $content)
    {
        // Ambil embedding dari service
        $embedding = $this->embedding->generateEmbedding($content);

        return AiVector::create([
            'novel_id' => $novelId,
            'source_category' => $category,
            'source_id' => $id,
            'content' => $content,
            'embedding' => $embedding
        ]);
    }

    public function search($novelId, $query, $limit = 5, $category = null)
    {
        $queryVector = $this->embedding->generateEmbedding($query); //
        
        // Ambil data dengan filter awal (Pre-Filtering)
        // Kita ambil 100 data terbaru agar perhitungan cosine similarity tidak terlalu berat
        $dbQuery = AiVector::where('novel_id', $novelId);

        if ($category) {
            $dbQuery->where('source_category', $category);
        }

        $vectors = $dbQuery->latest()->take(100)->get();

        $scores = [];
        foreach ($vectors as $vector) {
            // Model casting memastikan $vector->embedding sudah menjadi array
            $similarity = $this->cosineSimilarity($queryVector, $vector->embedding);

            $scores[] = [
                'content' => $vector->content,
                'score' => $similarity
            ];
        }

        usort($scores, fn($a, $b) => $b['score'] <=> $a['score']);
        return array_slice($scores, 0, $limit);
    }

    private function cosineSimilarity($a, $b)
    {
        if (count($a) !== count($b)) return 0; // Proteksi dimensi

        $dot = 0; $normA = 0; $normB = 0;
        foreach ($a as $i => $value) {
            $dot += $a[$i] * $b[$i];
            $normA += $a[$i] * $a[$i];
            $normB += $b[$i] * $b[$i];
        }
        
        $divider = sqrt($normA) * sqrt($normB);
        return ($divider == 0) ? 0 : ($dot / $divider);

    }

    public function hybridSearch($novelId, $query, $limit = 5)
    {
        // 1. Keyword Search (Pencarian tepat)
        $exactMatches = AiVector::where('novel_id', $novelId)
            ->where('content', 'LIKE', "%{$query}%")
            ->take($limit)
            ->get();

        // 2. Vector Search (Pencarian semantik)
        $vectorMatches = $this->search($novelId, $query, $limit);

        // Gabungkan dan hapus duplikat
        return collect($vectorMatches)->merge($exactMatches)->unique('content')->take($limit);
    }
}