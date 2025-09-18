<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Venta extends Model
{
    protected $table = 'venta';
    protected $primaryKey = 'numFac';
    public $timestamps = false;

    public function cliente()
    {
        return $this->belongsTo(Customer::class, 'RefClienteId', 'id');
    }

    public function detalles()
    {
        return $this->hasMany(VentaDetalle::class, 'RefVentaId', 'numFac');
    }

    public function pagos()
    {
        return $this->hasMany(Pago::class, 'RefVentaId', 'numFac');
    }
}
