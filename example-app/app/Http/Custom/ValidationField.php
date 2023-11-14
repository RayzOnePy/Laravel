<?php

namespace App\Http\Custom;

use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Validator;

class ValidationField
{
    public static function fieldValidation(Validator $validator)
    {
        $errors = "";
        foreach ($validator->errors()->all() as $item) {
            $item = substr($item, 0, -1);
            $errors = $errors . $item . ". ";
        }

        $errors = substr($errors, 0, -2) . ". ";
        throw new HttpResponseException(response()->json(['success' => false, 'code' => 422, 'message' => $errors],422));
    }
}
