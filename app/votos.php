<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class votos extends Model
{
    protected $table = "votos";
    protected $primaryKey ="idVotos";
    protected $fillable =[
       "idVotos",
       "contador",
       "mesas_idMesas",
       "partidos_idPartidos"
    ];
    public $timestamps = false;
}
