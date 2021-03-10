<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddDocumentForIndividual extends FormRequest
{
    public function rules()
    {
        return [
            'task_id' => 'required|integer',
            'individual_id' => 'required|integer',
            'force' => 'bool'
        ];
    }
}
