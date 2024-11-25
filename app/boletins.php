<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class boletins extends Model
{
    protected $table = "boletins";
    protected $primaryKey ="idBoletins";
    protected $fillable =[
        "idBoletins",
        "qrCode",
        "mesas_idMesas",
        "ano",
    ];
    public $timestamps = false;
}
