<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $table = 'cliente';
    protected $fillable = [
        'nombre',
        'RUC/DNI',
        'telefono',
        'correo',
        'direccion'
    ];
    
}
