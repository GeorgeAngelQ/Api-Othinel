<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use App\Models\Venta;
use Illuminate\Http\Request;

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
            'montoTotal' => 'required|decimal:2',
            'igv' => 'required|decimal:2',
            'total' => 'required|decimal:1,2',
        ]);

        if ($validator->fails()) {
            $data = [
                'message' => 'Data validation error',
                'error' => $validator->errors(),
                'status' => 400
            ];
            return response()->json($data, 400);
        }

        $sale = Venta::create([
            'tipo' => $request->tipo,
            'nombre' => $request->nombre,
            'talla' => $request->talla,
            'color' => $request->color,
            'precioVenta' => $request->precioVenta,
            'stock' => $request->stock
        ]);

        if (!$sale) {
            $data = [
                'message' => 'Error to create sale',
                'status' => 500
            ];
            return response()->json($data, 500);
        }

        return response()->json($sale, 201);
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
