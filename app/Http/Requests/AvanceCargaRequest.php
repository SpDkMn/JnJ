<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class AvanceCargaRequest extends Request
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
          'concursos' => 'required|exists:concursos,id',
          'checked' => 'required',
          'archivoDeAvances' => 'required|mimes:xls,xlsx',
          'fechaDeInicio' => 'required|date_format:"d-m-Y"',
          'fechaDeFin' => 'required|date_format:"d-m-Y"',
        ];
    }
}
