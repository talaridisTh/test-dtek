<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Customer;
use Auth;

class CustomerRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'customer_group_id' => 'required|numeric',
            'customer_name' => 'required|between:2,100',
            'phone' => 'nullable|between:2,250',
            'mobile' => 'nullable|between:2,250',
            'fax' => 'nullable|between:2,250',
            'tax_id' => 'nullable',
            'tax_office' => 'nullable|between:2,50',
            'company_name' => 'nullable|max:250',
            'company_kind' => 'nullable|max:250',
            'email' => 'nullable|max:250',
            'password' => 'nullable|min:5|max:30',
            'password2' => 'nullable|min:5|max:30',
            'comments' => 'nullable'
        ];
    }
}
