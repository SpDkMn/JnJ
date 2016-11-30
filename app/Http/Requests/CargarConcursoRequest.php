<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class CargarConcursoRequest extends Request
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
            'archivoDeConcurso' => 'required|mimes:pdf,ppt,pptx,doc',
            'nombreDelConcurso' => 'required|min:3',
            'periodo' => 'required|min:3',
            //'codigoDeConcurso' => 'required',
            'fechaDeInicio' => 'required|date_format:"d-m-Y"',
            'fechaDeFin' => 'required|date_format:"d-m-Y"',
            'cantidad' => 'integer'
        ];
    }
}
