<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InvoiceRequest extends FormRequest
{
    public function rules()
    {
        return [
            'order_id' => 'required|numeric',
            'invoice_status' => 'required|numeric'
        ];
    }
}
