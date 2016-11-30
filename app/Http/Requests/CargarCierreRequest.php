<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class CargarCierreRequest extends Request
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
          'fechaDeInicioDeVenta' => 'required|min:9|max:10',
          'fechaFinDeVenta' => 'required|min:9|max:10',
          'archivoDeCierre' =>  'required|mimes:xls,xlsx',
        ];
    }
}
