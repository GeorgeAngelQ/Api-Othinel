<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    protected $table = 'producto';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'tipo',
        'nombre',
        'talla',
        'color',
        'precioVenta',
        'stock'
    ];
}
