<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class DatosDistribuidoraRequest extends Request
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
          "nombreDelDistribuidor" => "required",
          "ruc" => "required",
          "direccion" => "required|max:255",
          "referencia" => "required",
          "telefono" => "required|max:20",
          "correoElectronico" => "required|max:255|email"
        ];
    }
}
