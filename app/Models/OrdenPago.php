<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Carbon\Carbon;

class OrdenPago extends Model
{
    use HasFactory;

    protected $table = 'orden_pago';
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = false;

    protected $fillable = [
        'RefVentaId',
        'id_metodo_pago',
        'culqi_order_id',
        'order_number',
        'amount',
        'currency_code',
        'description',
        'payment_code',
        'state',
        'total_fee',
        'net_amount',
        'fee_details',
        'creation_date',
        'expiration_date',
        'updated_at',
        'paid_at',
        'available_on',
        'metadata',
        'qr',
        'cuotealo',
        'url_pe',
    ];

    protected $casts = [
        'fee_details' => 'array',
        'metadata'    => 'array',
        'amount'      => 'integer',
        'total_fee'   => 'decimal:2',
        'net_amount'  => 'decimal:2',
        'available_on'=> 'date',
    ];

    // Relaciones
    public function venta()
    {
        return $this->belongsTo(Venta::class, 'RefVentaId', 'numFac');
    }

    public function metodoPago()
    {
        return $this->belongsTo(MetodoPago::class, 'id_metodo_pago', 'id');
    }

    protected function creationDate(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value ? Carbon::createFromTimestamp($value) : null,
            set: fn ($value) => $value instanceof Carbon ? $value->getTimestamp() : (is_numeric($value) ? (int)$value : null)
        );
    }

    protected function expirationDate(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value ? Carbon::createFromTimestamp($value) : null,
            set: fn ($value) => $value instanceof Carbon ? $value->getTimestamp() : (is_numeric($value) ? (int)$value : null)
        );
    }

    protected function paidAt(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value ? Carbon::createFromTimestamp($value) : null,
            set: fn ($value) => $value instanceof Carbon ? $value->getTimestamp() : (is_numeric($value) ? (int)$value : null)
        );
    }

    protected function updatedAt(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value ? Carbon::createFromTimestamp($value) : null,
            set: fn ($value) => $value instanceof Carbon ? $value->getTimestamp() : (is_numeric($value) ? (int)$value : null)
        );
    }

    public function isPaid(): bool
    {
        return in_array($this->state, ['paid', 'pending']) && ! is_null($this->paid_at);
    }
}
