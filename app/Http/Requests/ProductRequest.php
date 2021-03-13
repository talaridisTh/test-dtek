<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        //check if user is owner of product
        // $this->middleware('auth');
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'manufacturer_id' => 'required|min:0',
            'category_id' => 'nullable|numeric',
            'name' => 'nullable',
            'model' => 'required|between:0,250',
            'notify_quantity' => 'nullable|numeric',
            'description' => 'nullable',
            'width' => 'required',
            'height_percentage' => 'nullable|min:0|max:100',
            'radial_structure' => 'required',
            'diameter' => 'required|numeric|min:0',
            'fitting_position' => 'required|min:1|max:3',
            'speed_flag' => 'required',
            'weight_flag' => 'required',
            'tube_type' => 'required|min:0|max:2',
            'buying_price' => 'nullable|numeric',
            'general_price' => 'required|numeric',
            'wholesale_price' => 'nullable|numeric',
            'is_heavy' => 'boolean',
            'comments' => 'nullable',
            'future_qty' => 'array',
            'future_qty.*.stock','numeric',
            'future_qty.*.arrival_date','between:7,12',
            'qty' => 'array',
            'qty.*.product_quantity_id' => 'nullable|numeric',
            'qty.*.warehouse_id' => 'numeric|required',
            'qty.*.shelf_id' => 'numeric|required',
            'qty.*.batch' => 'numeric|required',
            'qty.*.stock' => 'numeric|required'
        ];
    }
}
