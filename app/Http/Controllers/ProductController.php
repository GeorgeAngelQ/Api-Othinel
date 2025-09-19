<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\Producto;

class ProductController extends Controller
{
    public function list()
    {
        $products = Producto::all();
        if ($products->isEmpty()) {
            $data = [
                'message' => 'There are no products',
                'status' => 200
            ];
            return response()->json($data, 200);
        }
        return response()->json($products, 200);
    }

    public function show($id)
    {
        $product = Producto::find($id);

        if (!$product) {
            $data = [
                'message' => 'Product not found',
                'status' => '404'
            ];
            return response()->json($data, 404);
        }

        $data = [
            'product' => $product,
            'status' => 200
        ];
        return response()->json($data, 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tipo' => 'required|max:50',
            'nombre' => 'required|max:50',
            'talla' => 'required|max:50',
            'color' => 'required|max:50',
            'precioVenta' => 'required|decimal:2|max:150',
            'stock' => 'required|integer'
        ]);

        if ($validator->fails()) {
            $data = [
                'message' => 'Data validation error',
                'error' => $validator->errors(),
                'status' => 400
            ];
            return response()->json($data, 400);
        }

        $product = Producto::create([
            'tipo' => $request->tipo,
            'nombre' => $request->nombre,
            'talla' => $request->talla,
            'color' => $request->color,
            'precioVenta' => $request->precioVenta,
            'stock' => $request->stock
        ]);

        if (!$product) {
            $data = [
                'message' => 'Error to create product',
                'status' => 500
            ];
            return response()->json($data, 500);
        }

        $data = [
            'product' => $product,
            'status' => 201
        ];

        return response()->json($data, 201);
    }

    public function update(Request $request, $id)
    {
        $product = Producto::find($id);

        if (!$product) {
            $data = [
                'message' => 'Product not found',
                'status' => 404
            ];
            return response()->json($data, 404);
        }

        $validator = Validator::make($request->all(), [
            'tipo' => 'required|max:50',
            'nombre' => 'required|max:50',
            'talla' => 'required|max:50',
            'color' => 'required|max:50',
            'precioVenta' => 'required|decimal:2|max:150',
            'stock' => 'required|integer'
        ]);

        if ($validator->fails()) {
            $data = [
                'message' => 'Data validation error',
                'error' => $validator->errors(),
                'status' => 400
            ];
            return response()->json($data, 400);
        }

        $product->tipo = $request->tipo;
        $product->nombre = $request->nombre;
        $product->talla = $request->talla;
        $product->correo = $request->correo;
        $product->color = $request->color;
        $product->precioVenta = $request->precioVenta;
        $product->stock = $request->stock;
        $product->save();

        $data = [
            'message' => 'Product updated',
            'customer' => $product,
            'status' => 200
        ];

        return response()->json($data, 200);
    }

    public function partialUpdate(Request $request, $id)
    {
        $product = Producto::find($id);

        if (!$product) {
            $data = [
                'message' => 'Product not found',
                'status' => 404
            ];
            return response()->json($data, 404);
        }

        $validator = Validator::make($request->all(), [
            'tipo' => 'max:50',
            'nombre' => 'max:50',
            'talla' => 'max:50',
            'color' => 'max:50',
            'precioVenta' => 'decimal:2|max:150',
            'stock' => 'integer'
        ]);

        if ($validator->fails()) {
            $data = [
                'message' => 'Data validation error',
                'error' => $validator->errors(),
                'status' => 400
            ];
            return response()->json($data, 400);
        }

        if ($request->has('tipo')) {
            $product->tipo = $request->tipo;
        }
        if ($request->has('nombre')) {
            $product->nombre = $request->nombre;
        }
        if ($request->has('talla')) {
            $product->talla = $request->talla;
        }
        if ($request->has('color')) {
            $product->color = $request->color;
        }
        if ($request->has('precioVenta')) {
            $product->precioVenta = $request->precioVenta;
        }
        if ($request->has('stock')) {
            $product->stock = $request->stock;
        }

        $product->save();

        $data = [
            'message' => 'Product updated',
            'product' => $product,
            'status' => 200
        ];
        return response()->json($data, 200);
    }

    public function destroy($id)
    {
        $product = Producto::find($id);

        if (!$product) {
            $data = [
                'message' => 'Product not found',
                'status' => 404
            ];
            return response()->json($data, 404);
        }
        $product->delete();

        $data = [
            'message' => 'Product deleted',
            'status' => 200
        ];
        return response()->json($data, 200);
    }
}
