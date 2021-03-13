<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrderRequest extends FormRequest
{
    public function rules()
    {
        return [
            'customer_id' => 'required|numeric',
            'address_id' => 'required|numeric',
            'type_of_receipt' => 'required|numeric',
            'shipping_method' => 'required|numeric',
            'payment_type_number' => 'nullable|numeric',
            'comments' => 'nullable',
            'discount_type' => 'nullable|numeric',
            'discount_amount' => 'nullable|numeric|min:0',
            'order_status' => 'required|numeric',
            'order_total' => 'numeric',
            'shipping_cost' => 'numeric',
            'payment_cost' => 'numeric',
            'manage_cost' => 'numeric|min:0',
            'payment_type' => 'required|numeric',
            'payment_type_number' => 'nullable',
            'waitting_shelf_id' => 'nullable',
            'order_product' => 'array',
            'order_product.*.product_id' => 'numeric|required',
            'order_product.*.product_quantity_id' => 'numeric|required',
            'order_product.*.quantity' => 'numeric|required',
            'order_product.*.price' => 'numeric|required',
            'order_product.*.product_tax' => 'numeric|required',
            'order_product.*.tax_class_id' => 'numeric|required',
            'order_product.*.environmental_tax' => 'numeric|nullable',
        ];
    }
}
