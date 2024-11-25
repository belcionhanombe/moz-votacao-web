<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class partidos extends Model
{
    protected $table = "partidos";
    protected $primaryKey ="idPartidos";
    protected $fillable =[
        "idPartidos",
       "nome",
       "logo",
       "acronimo",
       "ordem"
    ];
    public $timestamps = false;
}
