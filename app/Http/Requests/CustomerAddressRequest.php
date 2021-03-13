<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CustomerAddressRequest extends FormRequest
{
    public function rules()
    {
        return [
            'customer_id' => 'required|numeric',
            'firstname' => 'nullable|between:2,190',
            'lastname' => 'nullable|between:2,190',
            'company' => 'nullable|between:2,190',
            'address_1' => 'nullable|between:2,190',
            'address_2' => 'nullable|between:2,190',
            'city' => 'nullable|between:2,190',
            'postcode' => 'nullable|numeric',
            'country_id' => 'nullable|numeric'
        ];
    }
}
