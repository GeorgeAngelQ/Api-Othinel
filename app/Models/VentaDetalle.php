<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VentaDetalle extends Model
{
    protected $table = 'ventadetalle';
    protected $primaryKey = 'id';
    public $timestamps = false;

    public function producto()
    {
        return $this->belongsTo(Producto::class, 'RefProductoID', 'id');
    }
}
