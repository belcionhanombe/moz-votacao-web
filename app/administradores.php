<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class administradores extends Model
{
    protected $table = "administradores";
    protected $primaryKey ="contacto";
    protected $fillable =[
        "mesas_idMesas",
        "primeiroNome",
        "segundoNome",
        "bi",
        "nivel",
        "provincias_idProvincias",
        "senha",
        "contacto",
        "distritos_idDistritos",
        "otp",
        "email"
    ];
    public $timestamps = false;
}

