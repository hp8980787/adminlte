<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class SupplierRequest extends FormRequest
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

        switch ($this->method()) {
            case 'POST':
                return [
                    'name' => 'required',
                    'email' => 'required|email',
                    'phone' => 'required',
                    'address' => 'required|min:5'
                ];
            case 'PUT':
                return [

                    'email' => 'email',

                ];
        }
        return [

        ];
    }
}
