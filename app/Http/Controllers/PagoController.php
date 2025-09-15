<?php

namespace App\Http\Controllers;

use App\Models\Venta;
use App\Models\Pago;
use App\Services\CulqiService;
use App\Services\CulquiService;
use Illuminate\Http\Request;

class PagoController extends Controller
{
    public function iniciarPago(Request $request, CulquiService $culqiService)
    {
        $validated = $request->validate([
            'RefVentaId'     => 'required|exists:venta,numFac',
            'id_metodo_pago' => 'required|exists:metodo_pago,id',
        ]);

        $venta = Venta::findOrFail($validated['RefVentaId']);

        $pago = Pago::create([
            'RefVentaId'     => $venta->numFac,
            'id_metodo_pago' => $validated['id_metodo_pago'],
            'monto'          => $venta->total,
            'moneda'         => 'PEN',
            'estado'         => 'pendiente',
            'fecha'          => now(),
        ]);

        if ($pago->id_metodo_pago == 1) {
            $orden = $culqiService->crearOrden($venta, $pago);
            return response()->json([
                'message' => 'Orden creada en Culqi',
                'orden'   => $orden
            ]);
        }

        if ($pago->id_metodo_pago == 2) {
        }

        return response()->json(['message' => 'Método de pago no implementado aún'], 400);
    }
}
