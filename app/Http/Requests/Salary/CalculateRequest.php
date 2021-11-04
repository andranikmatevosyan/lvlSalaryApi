<?php

namespace App\Http\Requests\Salary;

use App\traits\ApiResponse;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class CalculateRequest extends FormRequest
{
    use ApiResponse;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'salary' => 'required|numeric|min:0',
            'month_days_norm' => 'required|integer|max:31',
            'month_days_work' => 'required|integer|max:31',
            'has_mzp' => 'required|in:yes,no',
            'year' => 'required|integer|max:2022',
            'month' => 'required|integer|max:12',
            'is_retiree' => 'required|in:yes,no',
            'is_handicapped' => 'required|in:yes,no',
            'handicapped_group' => 'nullable|required_if:is_handicapped,==,yes|integer|in:1,2,3'
        ];
    }

    /**
     * @param Validator $validator
     */
    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException($this->sendError($validator->errors()));
    }
}
