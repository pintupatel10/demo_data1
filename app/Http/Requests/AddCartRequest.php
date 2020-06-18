<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class AddCartRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
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
            'product-id' => 'required|integer',
            'price-group-id' => 'required|integer',
            'type' => 'required|in:tour,ticket,transportation',
            'total-price-ver' => 'required|numeric',
        ];
    }
}
