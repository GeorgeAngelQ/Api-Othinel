<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pago extends Model
{
    use HasFactory;

    protected $table = 'pago';
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = false;

    protected $fillable = [
        'RefVentaId',
        'id_metodo_pago',
        'monto',
        'moneda',
        'estado',
        'transaction_id',
        'detalle_respuesta',
        'fecha'
    ];

    protected $casts = [
        'detalle_respuesta' => 'array',
        'monto' => 'decimal:2',
    ];

    public function venta()
    {
        return $this->belongsTo(Venta::class, 'RefVentaId', 'numFac');
    }

    public function metodoPago()
    {
        return $this->belongsTo(MetodoPago::class, 'id_metodo_pago', 'id');
    }
}
