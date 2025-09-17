<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\MercadoPagoService;
use App\Models\Pago;
use App\Models\Venta;

class PagoController extends Controller
{
    protected $mercadopago;

    public function __construct(MercadoPagoService $mercadopago)
    {
        $this->mercadopago = $mercadopago;
    }

    public function iniciarPago($ventaId)
    {
        $venta = Venta::with(['cliente', 'detalles.producto'])->findOrFail($ventaId);

        $pago = Pago::where('RefVentaId', $ventaId)->first();

        $preference = $this->mercadopago->crearOrden($venta, $pago);

        if (is_array($preference) && isset($preference['message'])) {
            return response()->json($preference, 500);
        }

        return response()->json([
            'message' => 'Preferencia creada correctamente',
            'init_point' => $preference->init_point,
            'sandbox_init_point' => $preference->sandbox_init_point,
            'response_data' => $preference
        ]);
    }

}
