<?php

namespace App\Http\Controllers;

use App\Models\Venta;
use Illuminate\Http\Request;

class VentaController extends Controller
{
    public function index()
    {
        return Venta::with('cliente')->get();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'RefClienteId' => 'required|exists:cliente,id',
            'montoTotal'   => 'required|numeric|min:0',
            'igv'          => 'required|numeric|min:0',
            'total'        => 'required|numeric|min:0',
        ]);

        $validated['fecha'] = now();
        $validated['estado'] = 'pendiente';

        $venta = Venta::create($validated);

        return response()->json($venta, 201);
    }

    public function show($numFac)
    {
        return Venta::with(['cliente', 'pagos', 'ordenesPago'])->findOrFail($numFac);
    }
}
