<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class validadorConfig extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [

            "tipoEleicoes" => ["required", "regex:/^[0-1]{1}$/"],
            "inicio" => "Required",
            "fim" => "Required"

        ];
    }
    public function messages(){
        return[

            "tipoEleicoes.required" => "Informar o tipo de Eleições",
            "tipoEleicoes.regex" => "Tipo de Eleições invalido",
            "inicio.required" => "Indica dias de contagem de votos",
            "fim.required"  => "Indica dias de contagem de votos",
        ];
    }
}
