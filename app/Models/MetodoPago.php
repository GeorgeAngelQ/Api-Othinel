<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MetodoPago extends Model
{
    use HasFactory;

    protected $table = 'metodo_pago';
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = false;

    protected $fillable = [
        'nombre',
        'descripcion',
        'activo',
    ];

    public function pagos()
    {
        return $this->hasMany(Pago::class, 'id_metodo_pago', 'id');
    }

    public function ordenes()
    {
        return $this->hasMany(OrdenPago::class, 'id_metodo_pago', 'id');
    }
}
