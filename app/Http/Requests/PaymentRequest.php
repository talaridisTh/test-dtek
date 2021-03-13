<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PaymentRequest extends FormRequest
{
    public function rules()
    {
        return [
            'customer_id' => 'nullable|numeric',
            'order_id' => 'nullable|numeric',
            'amount' => 'required|numeric',
            'description' => 'nullable|max:191',
            'date_of_payment' => 'required|date'
        ];
    }
}
