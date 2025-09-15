<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OrdenPago;
use App\Models\Pago;

class WebhookController extends Controller
{
    public function culqi(Request $request)
    {
        $payload = $request->all();

        if (!isset($payload['order_number'])) {
            return response()->json(['error' => 'Invalid payload'], 400);
        }

        // Buscar orden
        $orden = OrdenPago::where('order_number', $payload['order_number'])->first();
        if ($orden) {
            $orden->update([
                'state'    => $payload['state'] ?? $orden->state,
                'paid_at'  => $payload['paid_at'] ?? $orden->paid_at,
                'metadata' => $payload['metadata'] ?? $orden->metadata,
            ]);

            // Actualizar Pago asociado
            $pago = Pago::where('RefVentaId', $orden->RefVentaId)
                        ->where('id_metodo_pago', $orden->id_metodo_pago)
                        ->latest()->first();

            if ($pago) {
                $pago->update([
                    'estado'            => $orden->state,
                    'transaction_id'    => $orden->culqi_order_id,
                    'detalle_respuesta' => $payload,
                ]);
            }
        }

        return response()->json(['message' => 'Webhook procesado']);
    }

    public function coingate(Request $request)
    {
        // TODO: Implementar flujo similar para Coingate
        return response()->json(['message' => 'Webhook Coingate recibido']);
    }
}
