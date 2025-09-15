<?php

namespace App\Services;

use App\Models\Venta;
use App\Models\Pago;
use MercadoPago\Client\Preference\PreferenceClient;
use MercadoPago\MercadoPagoConfig;

class MercadoPagoService
{
    public function __construct()
    {
        MercadoPagoConfig::setAccessToken(config('services.mercadopago.access_token'));
    }

    public function crearOrden(Venta $venta, Pago $pago)
    {
        $client = new PreferenceClient();

        $preference = $client->create([
            "items" => [
                [
                    "title"       => "Orden #" . $venta->numFac,
                    "quantity"    => 1,
                    "unit_price"  => (float) $venta->total,
                    "currency_id" => "PEN"
                ]
            ],
            "payer" => [
                "name"    => $venta->cliente->nombre,
                "email"   => $venta->cliente->correo,
                "phone"   => [
                    "number" => $venta->cliente->telefono,
                ],
            ],
            "back_urls" => [
                "success" => url("/pagos/success"),
                "failure" => url("/pagos/failure"),
                "pending" => url("/pagos/pending"),
            ],
            "auto_return" => "approved"
        ]);

        $pago->update([
            'estado' => 'pending',
            'monto'  => $venta->total,
        ]);

        return $preference;
    }
}
