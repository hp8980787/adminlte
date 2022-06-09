<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use  App\Http\Requests\Admin\TraitValidator\BaseValidator;

class ProductRequest extends FormRequest
{
    use BaseValidator;
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
        switch ($this->method()) {
            case 'POST':
                return [
                    'name' => 'required',
                    'dl' => 'required',
                    'dy' => 'required',
                    'sku' => 'required|unique:admin_products,sku',
                    'brand' => 'required',
                    'cover_img' => 'required',
                    'replace' => 'required',
                    'description' => 'required',

                ];
        }
        return [
            //
        ];
    }


}
