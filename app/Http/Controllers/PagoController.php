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
        // Cargar la venta con cliente y detalles de productos
        $venta = Venta::with(['cliente', 'detalles.producto'])->findOrFail($ventaId);

        // Obtener el pago asociado (si existe)
        $pago = Pago::where('RefVentaId', $ventaId)->first();

        // Crear preferencia en Mercado Pago
        $preference = $this->mercadopago->crearOrden($venta, $pago);

        // Manejar errores
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



    public function success()
{
    return view('pagos.success'); 
}

public function failure()
{
    return view('pagos.failure');
}

public function pending()
{
    return view('pagos.pending');
}

public function notification(Request $request)
{
    return response()->json(['status' => 'ok']);
}

}
