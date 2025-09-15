<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Venta;
use App\Models\Pago;
use App\Services\CulqiService;
use Illuminate\Support\Facades\Validator;

class PagoController extends Controller
{
    protected $culqi;

    public function __construct(CulqiService $culqi)
    {
        $this->culqi = $culqi;
    }

    public function iniciarPago(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'numFac'        => 'required|exists:ventas,numFac',
            'id_metodo_pago' => 'required|in:1,2', // 1 = Culqi, 2 = CoinGate (ejemplo)
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation error',
                'errors'  => $validator->errors()
            ], 422);
        }

        $venta = Venta::with('cliente')->find($request->numFac);

        $pago = Pago::create([
            'RefVentaId'    => $venta->numFac,
            'id_metodo_pago'=> $request->id_metodo_pago,
            'estado'        => 'pending'
        ]);

        if ($request->id_metodo_pago == 1) {
            $data = $this->culqi->crearOrden($venta, $pago);

            return response()->json([
                'message' => 'Orden creada en Culqi',
                'order'   => $data
            ], 201);
        }

        // Si es CoinGate u otro método
        return response()->json([
            'message' => 'Método de pago aún no implementado'
        ], 400);
    }
}
