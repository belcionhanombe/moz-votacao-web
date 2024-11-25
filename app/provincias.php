<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class provincias extends Model
{
    protected $table = "provincias";
    protected $primaryKey ="idProvincias";
    protected $fillable =[
       "nome",
       "idProvincias"
    ];
    public $timestamps = false;
}