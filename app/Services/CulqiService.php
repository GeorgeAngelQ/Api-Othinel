<?php

namespace App\Services;

use App\Models\Venta;
use App\Models\Pago;
use App\Models\OrdenPago;
use Illuminate\Support\Facades\Http;

class CulqiService
{
    public function crearOrden(Venta $venta, Pago $pago)
    {
        $response = Http::withToken(env('CULQI_PRIVATE_KEY'))
            ->post('https://api.culqi.com/v2/orders', [
                "amount"        => intval($venta->total * 100), // Culqi trabaja en céntimos
                "currency_code" => "PEN",
                "description"   => "Orden #" . $venta->numFac,
                "order_number"  => "ORDER-" . $venta->numFac,
                "client_details" => [
                    "first_name"   => $venta->cliente->nombre,
                    "email"        => $venta->cliente->correo,
                    "phone_number" => $venta->cliente->telefono,
                ]
            ]);

        if ($response->failed()) {
            throw new \Exception("Error en Culqi API: " . $response->body());
        }

        $data = $response->json();

        // Guardamos en OrdenPago
        OrdenPago::create([
            'RefVentaId'      => $venta->numFac,
            'id_metodo_pago'  => $pago->id_metodo_pago,
            'culqi_order_id'  => $data['id'],
            'order_number'    => $data['order_number'],
            'amount'          => $data['amount'],
            'currency_code'   => $data['currency_code'],
            'description'     => $data['description'],
            'payment_code'    => $data['payment_code'] ?? null,
            'state'           => $data['state'] ?? 'created',
            'total_fee'       => $data['total_fee'] ?? null,
            'net_amount'      => $data['net_amount'] ?? null,
            'fee_details'     => json_encode($data['fee_details'] ?? []),
            'creation_date'   => $data['creation_date'] ?? now(),
            'expiration_date' => $data['expiration_date'] ?? null,
            'qr'              => $data['qr'] ?? null,
            'url_pe'          => $data['url_pe'] ?? null,
            'metadata'        => json_encode($data['metadata'] ?? [])
        ]);

        return $data;
    }
}

