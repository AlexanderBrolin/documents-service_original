<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GetRecognizedDataHttpRequest extends FormRequest
{
    public function rules()
    {
        return [
            'task_key' => 'required|string'
        ];
    }
}
