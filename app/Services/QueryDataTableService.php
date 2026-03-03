<?php

namespace App\Services;

use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Exception;

class QueryDataTableService
{
    public function getJsonResponse(Request $request, Builder $query)
    {
        try {
            // Clone the original query to get total records count
            $totalRecordsQuery = clone $query;
            $totalRecords = $totalRecordsQuery->count();

            // Apply searching if any
            $this->applyFilter($query, $request);

            // Get the total count after applying searching
            $totalFiltered = $query->count();

            // Apply ordering
            $this->applySorting($query, $request);

            // Apply pagination
            $perPage = $request->input('per_page', $request->route('per_page', 10));
            $currentPage = $request->input('page', $request->route('page', 1));
            $totalPages = ceil($totalRecords / $perPage);

            $paginate = $query->paginate($perPage, ['*'], 'page', $currentPage);

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
        } catch (Exception $ex) {
            return $ex;
        }
    }

    protected function applyFilter(Builder $query, Request $request)
    {
        if ($request->has('filterParams')) {
            $searchConditions  = $request->input('filterParams');
            foreach ($searchConditions as $searchCondition) {
                $column   = $searchCondition['field'];
                $value    = is_string($searchCondition['value']) ? trim($searchCondition['value']) : $searchCondition['value'];
                $operator = isset($searchCondition['operator']) ? $searchCondition['operator'] : "=";

                $query->orWhere(function ($query) use ($column, $value, $operator) {
                    if (Str::lower($operator) === 'like') {
                        $query->where($column, 'like', '%' . $value . '%');
                    } elseif (Str::lower($operator) === 'in') {
                        $query->whereIn($column, $value);
                    }  else {
                        $query->where($column, $operator, $value);
                    }
                });
            }
        }
    }

    protected function applySorting(Builder $query, Request $request)
    {
        if ($request->has('sortParams')) {
            $orders = $request->input('sortParams');

            foreach ($orders as $order) {
                $orderColumn = $order['sortBy'];
                if (empty($orderColumn)) {
                    continue;
                }
                $orderDirection = $order['sortType'];

                $query->orderBy($orderColumn, $orderDirection);
            }
        }
    }
}