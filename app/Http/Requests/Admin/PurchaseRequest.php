<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use App\Http\Requests\Admin\TraitValidator\BaseValidator;

class PurchaseRequest extends FormRequest
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
        switch ($this->method()){
            case 'POST':
                return [
                   'supplier_id'=>'required',
                    'deadline_at'=>'required',
                    'product_id'=>'required|array',
                    'price'=>'required|array',
                    'quantity'=>'required|array',
                ];
        }
    }
}
