<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class ApiPaginationService
{
    /**
     * Handle complex query with pagination, filtering, and sorting.
     */
    public function handle(Builder $query, Request $request, array $relations = [], array $fieldMapping = [])
    {
        try {
            $payload = $request->validated();

            if (!empty($relations)) {
                $query->with($relations);
            }

            // Clone the original query to get total records count
            $totalRecordsQuery = clone $query;
            $totalRecords = $totalRecordsQuery->count();

            // Teruskan field mapping ke method filtering
            $this->applyFilters($query, $payload['filterParams'] ?? [], $fieldMapping);

            // Get the total count after applying searching
            $totalFiltered = $query->count();

            // Apply ordering
            $this->applySorting($query, $payload['sortParams'] ?? [], $fieldMapping);

            // $perPage = $payload['per_page'] ?? 15;
            // return $query->paginate($perPage);

             // Apply pagination
            $perPage = $request->input('per_page', $request->route('per_page', 10));
            $currentPage = $request->input('page', $request->route('page', 1));
            $totalPages = ceil($totalRecords / $perPage);

            $paginate = $query->paginate($perPage, ['*'], 'page', $currentPage);
            $requestedColumns = collect($request->input('columns'))->pluck('data')->toArray();

            return [
                'success' => true,
                'records' => $paginate->items(),
                'pagination' => [
                    'current_page'        => $paginate->currentPage(),
                    'total_records'       => $totalRecords,
                    'total_rows_per_page' => count($paginate->items()),
                    'last_page'           => $paginate->lastPage(),
                    'total_pages'         => $totalPages,
                ],
            ];
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Apply dynamic filters to the query builder.
     */
    protected function applyFilters(Builder $query, array $filters, array $fieldMapping)
    {
        foreach ($filters as $filter) {
            $field    = $filter['field'];
            $operator = $filter['operator'];
            $value    = $filter['value'];

            // Gunakan mapped field jika ada, jika tidak, gunakan field asli
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

    /**
     * Apply dynamic sorting to the query builder.
     */
    protected function applySorting(Builder $query, array $sorts, array $fieldMapping)
    {
        if (empty($sorts)) {
            return;
        }

        foreach ($sorts as $sort) {
            $field     = $sort['field'];
            $direction = $sort['direction'] ?? 'asc';

            // Gunakan mapped field jika ada, jika tidak, gunakan field asli
            $dbField = $fieldMapping[$field] ?? $field;

            $query->orderBy($dbField, $direction);
        }
    }
}
