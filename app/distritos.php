<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class distritos extends Model
{
    protected $table = "distritos";
    protected $primaryKey ="idDistritos";
    protected $fillable =[
        "idDistritos",	
        "municipio",	
        "nome",	
        "provincias_idProvincias"
    ];
    public $timestamps = false;
}
