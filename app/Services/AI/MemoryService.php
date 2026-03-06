<?php

namespace App\Services\AI;

use App\Models\AiMemory;

class MemoryService
{
    public function getContext($novelId)
    {
        $memories = AiMemory::where('novel_id', $novelId)
            ->orderBy('created_at')
            ->get();

        $context = "";

        foreach ($memories as $memory) {

            $context .= strtoupper($memory->type) . ": ";
            $context .= $memory->title . "\n";
            $context .= $memory->content . "\n\n";
        }

        return $context;
    }

    public function store($novelId, $sourceId, $sourceType, $title, $content)
    {
        // Ambil service embedding (bisa di-inject di constructor)
        $embeddingService = app(EmbeddingService::class);
        $embedding = $embeddingService->generateEmbedding($content);

        return AiMemory::create([
            'novel_id'    => $novelId,
            'source_id'   => $sourceId,
            'source_type' => $sourceType,
            'title'       => $title,
            'content'     => $content,
            'embedding'   => $embedding, // Pastikan model sudah menggunakan $casts array
        ]);
    }

    public function searchSimilar($novelId, $embedding, $limit = 5)
    {
        $memories = AiMemory::where('novel_id',$novelId)->get();

        $results = [];

        foreach($memories as $memory){

            $score = $this->cosineSimilarity(
                $embedding,
                json_decode($memory->embedding, true)
            );

            $results[] = [
                'memory'=>$memory,
                'score'=>$score
            ];

        }

        usort($results,function($a,$b){
            return $b['score'] <=> $a['score'];
        });

        return array_slice($results,0,$limit);

    }

    private function cosineSimilarity($a,$b)
    {

        $dot = 0;
        $normA = 0;
        $normB = 0;

        foreach($a as $i=>$v){

            $dot += $v * $b[$i];
            $normA += $v * $v;
            $normB += $b[$i] * $b[$i];

        }

        return $dot / (sqrt($normA) * sqrt($normB));
    }
}