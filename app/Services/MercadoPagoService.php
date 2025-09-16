<?php

namespace App\Services;

use MercadoPago\MercadoPagoConfig;
use MercadoPago\Client\Preference\PreferenceClient;
use MercadoPago\Exceptions\MPApiException;
use Illuminate\Support\Facades\Log;

class MercadoPagoService
{
    public function crearOrden($venta, $pago)
    {
        try {
            // Configurar el access token
            MercadoPagoConfig::setAccessToken(config('services.mercadopago.access_token'));

            // Generar items desde los detalles de la venta
            $items = $venta->detalles->map(function ($detalle) {
                if (!$detalle->producto) {
                    Log::warning("Detalle sin producto:", ['detalle_id' => $detalle->id, 'RefProductoID' => $detalle->RefProductoID]);
                    return null;
                }

                $unit_price = $detalle->cantidad > 0 ? $detalle->subtotal / $detalle->cantidad : 0;

                return [
                    "id" => $detalle->RefProductoID,
                    "title" => $detalle->producto->nombre ?? 'Producto sin nombre',
                    "description" => ($detalle->producto->talla ?? ''). ' ' .($detalle->producto->color ?? ''),
                    "picture_url" => $detalle->producto->imagen_url ?? '',
                    "category_id" => $detalle->producto->categoria_id ?? null,
                    "quantity" => $detalle->cantidad ?? 1,
                    "currency_id" => "PEN",
                    "unit_price" => $unit_price
                ];
            })->filter()->values()->toArray();

            // Datos del comprador
            $payer = [
                "name" => $venta->cliente->nombre ?? 'Cliente',
                "surname" => $venta->cliente->apellido ?? '',
                "email" => $venta->cliente->correo ?? 'test@example.com',
                "phone" => [
                    "area_code" => $venta->cliente->area_code ?? '+51',
                    "number" => $venta->cliente->telefono ?? ''
                ],
                "identification" => [
                    "type" => $venta->cliente->documento ?? 'RUC O DNI',
                    "number" => $venta->cliente->ruc_dni ?? ''
                ],
                "address" => [
                    "zip_code" => $venta->cliente->direccion ?? '',
                    "street_name" => $venta->cliente->direccion ?? '',
                    "street_number" => $venta->cliente->direccion ?? ''
                ]
            ];

            $data = [
                "items" => $items,
                "payer" => $payer,
                "back_urls" => [
                    "success" => "https://mi-tunel.ngrok.io/pagos/success",
                    "failure" => "https://mi-tunel.ngrok.io/pagos/failure",
                    "pending" => "https://mi-tunel.ngrok.io/pagos/pending"
                ],
                "auto_return" => "approved",
                "external_reference" => $venta->numFac,
                "notification_url" => "https://mi-tunel.ngrok.io/pagos/notification"
            ];

            // Log completo para debug
            Log::info('Datos enviados a Mercado Pago:', $data);

            // Crear preferencia
            $client = new PreferenceClient();
            $preference = $client->create($data);

            Log::info('Respuesta Mercado Pago:', (array) $preference);

            return $preference;
        } catch (MPApiException $e) {
            Log::error('Error Mercado Pago API:', ['api_response' => $e->getApiResponse()]);
            return [
                'message' => 'Error al crear preferencia en Mercado Pago',
                'api_response' => $e->getApiResponse()
            ];
        } catch (\Exception $e) {
            Log::error('Error inesperado Mercado Pago:', [
                'error_message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return [
                'message' => 'Error inesperado',
                'error_message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ];
        }
    }
}
