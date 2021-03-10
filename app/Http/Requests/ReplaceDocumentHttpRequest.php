<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReplaceDocumentHttpRequest extends FormRequest
{
    public function rules()
    {
        return [
            'task_id' => 'required|integer',
            'document_id' => 'required|integer'
        ];
    }
}
