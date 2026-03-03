<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ApiPaginationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'filterParams'                       => 'nullable|array',
            'filterParams.*.field'               => 'required|string',
            'filterParams.*.operator'            => 'required|string|in:=,>,<,>=,<=,!=,like,in,not in',
            'filterParams.*.comparator'          => 'nullable',
            'filterParams.*.isNumber'            => 'required|boolean',
            'filterParams.*.isDate'              => 'required|boolean',
            'filterParams.*.filterType'          => 'required|string|in:text,number,date',
            'filterParams.*.value'               => 'required',
            'sortParams'                         => 'nullable|array',
            'sortParams.*.field'                 => 'required|string',
            'sortParams.*.direction'             => 'required|string|in:asc,desc',
            'page'                               => 'nullable|integer|min:1',
            'per_page'                           => 'nullable|integer|min:1',
        ];
    }
}
