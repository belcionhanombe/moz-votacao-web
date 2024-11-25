<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class validadorlocais extends FormRequest
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
            "outroNome" => ["required"]
        ];
    }

    public function messages(){
        return[
            "nome.required" => "O campo nome do local é obrigatório",
            "outroNome.rquired" =>"O campo Designação é obrigatório"
        ];
    }

}
