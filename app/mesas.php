<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class mesas extends Model
{
    protected $table = "mesas";
    protected $primaryKey ="idMesas";
    protected $fillable =[
       "idMesas",
       "contador",
       "localDeVotacao_idLocalDeVotacao",
       "eleitores",
       "nome"
    ];
    public $timestamps = false;
}
