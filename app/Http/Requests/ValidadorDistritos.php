<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ValidadorDistritos extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            "nome" => ["required"],	
            "municipio" =>["required"],	         
        ];
    }

    public function messages(){
        return[

            "nome.required" =>  "preenche o Distrito",
            "municipio.required" => "informe se é município?",	
        ];
    }
}