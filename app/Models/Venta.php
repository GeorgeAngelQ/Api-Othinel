<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Venta extends Model
{
    use HasFactory;

    protected $table = 'venta';
    protected $primaryKey = 'numFac';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = false;

    protected $fillable = [
        'RefClienteId',
        'fecha',
        'montoTotal',
        'igv',
        'total',
        'estado',
    ];

    protected $casts = [
        'fecha' => 'date:Y-m-d',
        'montoTotal' => 'float',
        'igv' => 'float',
        'total' => 'float',
    ];

    public function cliente()
    {
        return $this->belongsTo(Customer::class, 'RefClienteId', 'id');
    }

    public function pagos()
    {
        return $this->hasMany(Pago::class, 'RefVentaId', 'numFac');
    }

    public function ordenesPago()
    {
        return $this->hasMany(OrdenPago::class, 'RefVentaId', 'numFac');
    }
}
