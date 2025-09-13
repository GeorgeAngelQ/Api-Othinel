<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    protected $table = 'proveedor';
    protected $fillable = [
        'nombre',
        'RUC_DNI',
        'direccion',
        'telefono',
        'correo'
    ];
}
