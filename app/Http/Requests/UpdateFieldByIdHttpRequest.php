<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateFieldByIdHttpRequest extends FormRequest
{
    public function rules()
    {
        return [
            'field_id' => 'required|integer',
            'new_value' => 'string|required'
        ];
    }
}
