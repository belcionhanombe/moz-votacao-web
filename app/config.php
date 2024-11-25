<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class config extends Model
{
    protected $table = "config";
    protected $primaryKey ="idConfig";
    protected $fillable =[
        "administradores_idAdministradores",
        "tipoEleicao",
        "ano",
        "distritos_idDistritos",
         "dataInicio",
         "dataFim",
         "horaInicio",
         "horaFim"
    ];
    public $timestamps = false;
}
