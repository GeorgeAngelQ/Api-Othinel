<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\VentaDetalle;

class VentaDetalleController extends Controller
{
    public function list(){
        $salesDetail = VentaDetalle::with('venta','producto')->get();
        if ($salesDetail->isEmpty()) {
            $data = [
                'message' => 'There are no details for any sales',
                'status' => 200
            ];
            return response()->json($data, 200);
        }
        return response()->json($salesDetail, 200);
    }

    public function show($RefVentaId){
        $saleDetail = VentaDetalle::with('venta','producto')->find($RefVentaId);

        if (!$saleDetail) {
            $data = [
                'message' => 'Detail for this sale was not found',
                'status' => '404'
            ];
            return response()->json($data, 404);
        }

        $data = [
            'saleDetail' => $saleDetail,
            'status' => 200
        ];
        return response()->json($data,200);
    }

    public function destroy($RefVentaId)
    {
        $saleDetail = VentaDetalle::find($RefVentaId);

        if(!$saleDetail){
            $data = [
                'message'=>'Detail for this sale was not found',
                'status'=> 404
            ];
            return response()->json($data,404);
        }

        $saleDetail->delete();

        $data = [
            'message' => 'Detail for this sale has been deleted',
            'status' => 200
        ];
        return response()->json($data, 200);
    }
}
