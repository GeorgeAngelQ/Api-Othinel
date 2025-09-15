<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\MercadoPagoService;

class PagoController extends Controller
{
    protected $mercadopago;

    public function __construct(MercadoPagoService $mercadopago)
    {
        $this->mercadopago = $mercadopago;
    }

    public function iniciarPago()
{
    $preference = $this->mercadopago->crearOrden();

    if (!$preference || !isset($preference->init_point)) {
        return response()->json([
            'preference' => $preference,
            'message' => 'No se pudo crear la preferencia en Mercado Pago.'
        ], 500);
    }

    return response()->json([
        'message' => 'Preferencia creada correctamente',
        'init_point' => $preference->init_point,
        'sandbox_init_point' => $preference->sandbox_init_point,
        'response_data' => $preference
    ]);
}
}
