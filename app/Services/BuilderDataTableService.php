<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class BuilderDataTableService
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
        } catch (Exception $ex) {
            return $ex;
        }
    }

    protected function applyFilter(Builder $query, Request $request)
    {
        // if ($request->has('filterParams')) {
        //     $searchConditions  = $request->input('filterParams');
        //     foreach ($searchConditions as $searchCondition) {
        //         $column   = $searchCondition['field']; // name
        //         $value    = is_string($searchCondition['value']) ? trim($searchCondition['value']) : $searchCondition['value'];
        //         $operator = isset($searchCondition['operator']) ? $searchCondition['operator'] : "=";

        //         // Apply the search condition to the specified column
        //         $query->orWhere(function ($query) use ($column, $value, $operator) {
        //             // Assuming you have $columns defined somewhere
        //             if (Str::lower($operator) === 'like') {
        //                 $query->where($column, 'like', '%' . $value . '%');
        //             } elseif (Str::lower($operator) === 'in') {
        //                 $query->whereIn($column, $value); // Handling 'in' operator
        //             }  else {
        //                 $query->where($column, $operator, $value);
        //             }
        //         });
        //     }
        // }
        if ($request->has('filterParams')) {
            $searchConditions  = $request->input('filterParams');
            foreach ($searchConditions as $searchCondition) {
                $column   = $searchCondition['field'];
                $value    = is_string($searchCondition['value']) ? trim($searchCondition['value']) : $searchCondition['value'];
                $operator = isset($searchCondition['operator']) ? $searchCondition['operator'] : "=";

                // $parts = explode('.', $column);
                // $isRelation = count($parts) > 1;

                // if ($isRelation) {
                //     $relation = $parts[0];
                //     $relatedColumn = $parts[1];

                //     $query->orWhereHas($relation, function ($q) use ($relatedColumn, $value, $operator) {
                //         if (Str::lower($operator) === 'like') {
                //             $q->where($relatedColumn, 'like', '%' . $value . '%');
                //         } elseif (Str::lower($operator) === 'in') {
                //             $q->whereIn($relatedColumn, (array) $value);
                //         } else {
                //             $q->where($relatedColumn, $operator, $value);
                //         }
                //     });
                // } else {
                //     if (Str::lower($operator) === 'like') {
                //         $query->orWhere($column, 'like', '%' . $value . '%');
                //     } elseif (Str::lower($operator) === 'in') {
                //         $query->orWhereIn($column, (array) $value);
                //     } else {
                //         $query->orWhere($column, $operator, $value);
                //     }
                // }

                // Memisahkan nama tabel/alias dan kolom
                $parts = explode('.', $column);

                if (count($parts) > 1) {
                    // Jika field memiliki prefix tabel (e.g., 'asset_requests.code')
                    $tableName = $parts[0];
                    $columnName = $parts[1];
                    $query->orWhere($tableName . '.' . $columnName, $operator, $operator === 'like' ? '%' . $value . '%' : $value);
                } else {
                    // Jika field adalah alias (e.g., 'budget', 'cost_center', 'status')
                    // Anda juga bisa menambahkan logic untuk handle specific aliases here
                    if ($column === 'budget' || $column === 'cost_center' || $column === 'status') {
                        // Logika filter untuk alias yang dibuat dengan DB::raw
                        // Contoh untuk filter 'budget'
                        if ($column === 'budget') {
                            $query->orWhere(DB::raw("CONCAT(budgets.code, ' - ', budgets.item)"), $operator, $operator === 'like' ? '%' . $value . '%' : $value);
                        }
                        // Tambahkan logika serupa untuk 'cost_center' dan 'status'
                    } else {
                        // Jika field tidak memiliki prefix tabel atau bukan alias khusus
                        $query->orWhere($column, $operator, $operator === 'like' ? '%' . $value . '%' : $value);
                    }
                }
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
