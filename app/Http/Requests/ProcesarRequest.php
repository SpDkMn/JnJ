<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class ProcesarRequest extends Request
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
            'archivoGanadores'  => 'required|mimes:pdf,xls,xlsx',
            'archivoErrados' => 'required|mimes:pdf,xls,xlsx',
            'registrosBuenos' => 'required|integer',
            'registrosErrados'  => 'required|integer',
        ];
    }
}
