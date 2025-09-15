<?php

namespace App\Services;
use App\Models\Venta;
use App\Models\Pago;
use App\Models\OrdenPago;

class CulquiService {

    public function crearOrden(Venta $venta, Pago $pago)
{
    $client = new \GuzzleHttp\Client();

    $response = $client->post('https://api.culqi.com/v2/orders', [
        'headers' => [
            'Authorization' => 'Bearer ' . env('CULQI_PRIVATE_KEY'),
            'Content-Type'  => 'application/json',
        ],
        'json' => [
            "amount"        => intval($venta->total * 100),
            "currency_code" => "PEN",
            "description"   => "Orden #" . $venta->numFac,
            "order_number"  => "ORDER-" . $venta->numFac,
            "client_details" => [
                "first_name" => $venta->cliente->nombre,
                "email"      => $venta->cliente->correo,
                "phone_number" => $venta->cliente->telefono,
            ]
        ]
    ]);

    $data = json_decode($response->getBody(), true);

    OrdenPago::create([
        'RefVentaId'      => $venta->numFac,
        'id_metodo_pago'  => $pago->id_metodo_pago,
        'culqi_order_id'  => $data['id'],
        'order_number'    => $data['order_number'],
        'amount'          => $data['amount'],
        'currency_code'   => $data['currency_code'],
        'description'     => $data['description'],
        'payment_code'    => $data['payment_code'],
        'state'           => $data['state'],
        'total_fee'       => $data['total_fee'] ?? null,
        'net_amount'      => $data['net_amount'] ?? null,
        'fee_details'     => json_encode($data['fee_details'] ?? []),
        'creation_date'   => $data['creation_date'],
        'expiration_date' => $data['expiration_date'],
        'qr'              => $data['qr'] ?? null,
        'url_pe'          => $data['url_pe'] ?? null,
        'metadata'        => json_encode($data['metadata'] ?? [])
    ]);

    return $data;
}

}

