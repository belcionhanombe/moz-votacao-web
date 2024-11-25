<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class localdevotacao extends Model
{
    protected $table = "localdevotacao";
    protected $primaryKey ="idLocalDeVotacao";
    protected $fillable =[
       "nome",
       "Distritos_idDistritos",
       "idLocalDeVotacao",
       "nrDeMesas",
       "outroNome"
    ];

    public $timestamps = false;
}

