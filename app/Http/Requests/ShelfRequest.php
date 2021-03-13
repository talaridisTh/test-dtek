<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ShelfRequest extends FormRequest
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
            'warehouse_id' => 'required|min:0',
            'is_product_shelf' => 'boolean',
        ];
    }
}
