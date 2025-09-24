<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

use App\Models\Venta;
use App\Models\VentaDetalle;

class VentaController extends Controller
{
    public function list()
    {
        $sales = Venta::with('cliente')->get();
        if ($sales->isEmpty()) {
            $data = [
                'message' => 'There are no sales',
                'status' => 200
            ];
            return response()->json($data, 200);
        }
        return response()->json($sales, 200);
    }

    public function show($numFac)
    {
        $sale = Venta::with('cliente')->find($numFac);

        if (!$sale) {
            $data = [
                'message' => 'Sale not found',
                'status' => '404'
            ];
            return response()->json($data, 404);
        }

        $data = [
            'sale' => $sale,
            'status' => 200
        ];
        return response()->json($data, 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'RefClienteId' => 'required|exists:cliente,id',
            'montoTotal'   => 'required|decimal:2',
            'igv'          => 'required|decimal:2',
            'total'        => 'required|decimal:1,2',
            'detalles'     => 'required|array',
            'detalles.*.RefProductoId' => 'required|exists:producto,id',
            'detalles.*.cantidad'      => 'required|integer|min:1',
            'detalles.*.subtotal'      => 'required|decimal:1,2',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Data validation error',
                'error'   => $validator->errors(),
                'status'  => 400
            ], 400);
        }

        try {
            DB::beginTransaction();

            // Crear la venta
            $venta = Venta::create([
                'RefClienteId' => $request->RefClienteId,
                'fecha'        => now(),
                'montoTotal'   => $request->montoTotal,
                'igv'          => $request->igv,
                'total'        => $request->total,
            ]);

            foreach ($request->detalles as $detalle) {
                VentaDetalle::create([
                    'RefVentaId'    => $venta->id,
                    'RefProductoId' => $detalle['RefProductoId'],
                    'cantidad'      => $detalle['cantidad'],
                    'subtotal'      => $detalle['subtotal'],
                ]);
            }

            DB::commit();

            return response()->json([
                'message' => 'Venta creada correctamente',
                'venta'   => $venta->load('detalles'),
                'status'  => 201
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'message' => 'Error al crear la venta',
                'error'   => $e->getMessage(),
                'status'  => 500
            ], 500);
        }
    }

    public function destroy($numFac)
    {
        $sale = Venta::find($numFac);

        if (!$sale) {
            $data = [
                'message' => 'Sale not found',
                'status' => 404
            ];
            return response()->json($data, 404);
        }
        $sale->delete();

        $data = [
            'message' => 'Sale deleted',
            'status' => 200
        ];
        return response()->json($data, 200);
    }
}
