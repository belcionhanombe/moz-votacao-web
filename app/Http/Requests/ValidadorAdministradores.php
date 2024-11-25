<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ValidadorAdministradores extends FormRequest
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
            "primeiroNome" => ["required"],
            "segundoNome" =>["required"],
            "bi" =>["required", "regex:/^[0-9]{12}[A-Z]{1}$/"],
            "contacto" => ["required", "regex:/^(\+258|258){0,1}(84|85|86|87|82|83)[0-9]{7}$/"],
            "email" => ["required", "regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/"],
            "senha" => ["required"],
            "senha1"=> ["required"]
        ];
    }


    public function messages(){
        return[

            "primeiroNome.required" =>  "preenche primeiro nome",
            "segundoNome.required" => "preenche segundo nome",
            "bi.required" => "preenche o numero de BI",
            "contacto.required" => "preenche o contacto",
            "contacto.regex" => "preenche contacto valido",
            "bi.regex" => "O Numero de BI deve ser valido",
            "senha.required" => "Preenche a senha",
            "email.rquired"=> "Preenche email",
            "email.regex"=> "Preenche email valido",
            "senha1.required" => "Confirma a senha"
        ];
    }
}





