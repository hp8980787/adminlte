<?php

namespace App\Http\Requests\Admin\TraitValidator;

trait BaseValidator
{
    public function withValidator($validator)
    {
        if ($validator->fails()) {
            $validator->after(function ($validator) {
                $messages = $validator->errors()->messages();
                foreach ($messages as  $message) {
                    toast( $message[0], 'error');
                }
            });
        }
    }
}
