<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TaxClassRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|between:0,190',
            'type' => 'required|boolean',
            'amount' => 'required|numeric|min: 0',
        ];
    }
}
