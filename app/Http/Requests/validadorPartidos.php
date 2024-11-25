<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class validadorPartidos extends FormRequest
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
            "acronimo" => ["required"],
            "logo" =>["required"],
        ];
    }

    public function messages(){
        return[
            "nome.required" => "Preenche o nome do partido",
            "acronimo.required" =>  "Preenche o acrónimo",
            "logo.required" =>  "Adcione a foto do Partido"

        ];
    }

}
