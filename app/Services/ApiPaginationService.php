<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class ApiPaginationService
{
    public function handle(Builder $query, Request $request, array $relations = [], array $fieldMapping = [])
    {
        try {
            // Mengambil data yang sudah divalidasi dari FormRequest
            $payload = $request->validated();

            if (!empty($relations)) {
                $query->with($relations);
            }

            // 1. Hitung total records sebelum filter (Total Keseluruhan)
            $totalRecords = $query->count();

            // 2. Terapkan Filtering
            $this->applyFilters($query, $payload['filterParams'] ?? [], $fieldMapping);

            // 3. Hitung total setelah filter (untuk info pagination)
            $totalFiltered = $query->count();

            // 4. Terapkan Sorting
            $this->applySorting($query, $payload['sortParams'] ?? [], $fieldMapping);

            // 5. Konfigurasi Pagination
            $perPage = (int) ($payload['per_page'] ?? 10);
            $currentPage = (int) ($payload['page'] ?? 1);

            $paginate = $query->paginate($perPage, ['*'], 'page', $currentPage);

            return [
                'success' => true,
                'records' => $paginate->items(),
                'pagination' => [
                    'current_page'        => $paginate->currentPage(),
                    'total_records'       => $totalRecords, // Total awal
                    'total_filtered'      => $totalFiltered, // Total setelah search
                    'total_rows_per_page' => count($paginate->items()),
                    'last_page'           => $paginate->lastPage(),
                ],
            ];
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    protected function applyFilters(Builder $query, array $filters, array $fieldMapping)
    {
        foreach ($filters as $filter) {
            $field    = $filter['field'];
            $operator = $filter['operator'];
            $value    = $filter['value'];

            $dbField = $fieldMapping[$field] ?? $field;

            if ($operator === 'like') {
                $value = '%' . $value . '%';
            }

            if (in_array($operator, ['in', 'not in'])) {
                $values = is_array($value) ? $value : explode(',', $value);
                $query->whereIn($dbField, $values, 'and', $operator === 'not in');
            } else {
                $query->where($dbField, $operator, $value);
            }
        }
    }

    protected function applySorting(Builder $query, array $sorts, array $fieldMapping)
    {
        if (empty($sorts)) {
            // Default sorting jika tidak ada parameter
            $query->orderBy('id', 'desc');
            return;
        }

        foreach ($sorts as $sort) {
            $field     = $sort['field'];
            $direction = $sort['direction'] ?? 'asc';
            $dbField   = $fieldMapping[$field] ?? $field;

            $query->orderBy($dbField, $direction);
        }
    }
}