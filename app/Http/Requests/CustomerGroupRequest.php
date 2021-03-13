<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CustomerGroupRequest extends FormRequest
{

    public function rules()
    {
        return [
            'name' => 'required|between:2,100',
            'discounts' => 'nullable'
        ];
    }
}
