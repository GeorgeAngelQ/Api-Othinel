<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Venta;
use App\Models\Pago;
use App\Services\MercadoPagoService;
use Illuminate\Support\Facades\Validator;

class PagoController extends Controller
{
    protected $mercadopago;

    public function __construct(MercadoPagoService $mercadopago)
    {
        $this->mercadopago = $mercadopago;
    }

    public function iniciarPago(Request $request, MercadoPagoService $mpService)
{
    $validator = Validator::make($request->all(), [
        'numFac'        => 'required|exists:ventas,numFac',
        'id_metodo_pago'=> 'required|in:1,2', // 1 = MercadoPago, 2 = CoinGate
    ]);

    if ($validator->fails()) {
        return response()->json([
            'message' => 'Validation error',
            'errors'  => $validator->errors()
        ], 422);
    }

    $venta = Venta::with('cliente')->find($request->numFac);

    $pago = Pago::create([
        'RefVentaId'     => $venta->numFac,
        'id_metodo_pago' => $request->id_metodo_pago,
        'estado'         => 'pending',
        'monto'          => $venta->total
    ]);

    if ($request->id_metodo_pago == 1) {
        $preference = $mpService->crearOrden($venta, $pago);

        return response()->json([
            'message'    => 'Orden creada en Mercado Pago',
            'init_point' => $preference->init_point,  // URL para checkout web
            'sandbox_init_point' => $preference->sandbox_init_point // checkout en sandbox
        ]);
    }

    return response()->json([
        'message' => 'Método de pago aún no implementado'
    ], 400);
}

}
