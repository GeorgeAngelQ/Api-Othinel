<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vendor;
use Illuminate\Support\Facades\Validator;

class vendorController extends Controller
{
    public function list(){
        $vendors = Vendor::all();
        if($vendors->isEmpty()){
            $data = [
                'message' => 'There are no vendors',
                'status' => 200
            ];
            return response()->json($data,200);
        }
        return response()->json($$vendors,200);
    }

    public function show($id){
        $vendors = Vendor::find($id);

        if(!$vendors){
            $data = [
                'message' => 'Vendor not found',
                'status' => '404'
            ];
            return response()->json($data,404);
        }

        $data = [
            'vendor' => $vendors,
            'status' => 200
        ];
        return response()->json($data,200);
    }

    public function store(Request $request){
        $validator = Validator::make($request->all(),[
            'nombre' => 'required|max:100',
            'RUC_DNI' => 'required|max:11',
            'direccion' => 'required|max:150',
            'telefono' => 'required|max:15',
            'correo' => 'required|email|max:100'
        ]);

        if($validator->fails()){
            $data = [
                'message' => 'Data validation error',
                'error' => $validator->errors(),
                'status' => 400
            ];
            return response()->json($data,400);
        }

        $vendor = Vendor::create([
            'nombre' => $request->nombre,
            'RUC_DNI' => $request->RUC_DNI,
            'direccion' => $request->direccion,
            'telefono' => $request->telefono,
            'correo' => $request->correo,
        ]);

        if(!$vendor){
            $data = [
                'message' => 'Error to create vendor',
                'status' => 500
            ];
            return response()->json($data,500);
        }

        $data = [
            'vendor' => $vendor,
            'status' => 201
        ];

        return response()->json($data,201);

    }

    public function update(Request $request, $id){
        $vendor = Vendor::find($id);

        if(!$vendor){
            $data = [
                'message' => 'Vendor not found',
                'status' => 404
            ];
            return response()->json($data,404);
        }

        $validator = Validator::make($request->all(),[
            'nombre' => 'required|max:100',
            'RUC_DNI' => 'required|max:11',
            'direccion' => 'required|max:150',
            'telefono' => 'required|max:15',
            'correo' => 'required|email|max:100'
        ]);

        if($validator->fails()){
            $data = [
                'message' => 'Data validation error',
                'error' => $validator->errors(),
                'status' => 400
            ];
            return response()->json($data,400);
        }

        $vendor->nombre = $request->nombre;
        $vendor->RUC_DNI = $request->RUC_DNI;
        $vendor->telefono = $request->telefono;
        $vendor->correo = $request->correo;
        $vendor->direccion = $request->direccion;

        $vendor->save();

        $data = [
            'message' => 'Vendor updated',
            'vendor' => $vendor,
            'status' => 200
        ];

        return response()->json($data,200);

    }

    public function partialUpdate(Request $request, $id){
        $vendor = Vendor::find($id);

        if(!$vendor){
            $data = [
                'message' => 'Vendor not found',
                'status' => 404
            ];
            return response()->json($data,404);
        }

        $validator = Validator::make($request->all(),[
            'nombre' => 'max:100',
            'RUC_DNI' => 'max:11',
            'direccion' => 'max:150',
            'telefono' => 'max:15',
            'correo' => 'email|max:100'
        ]);

        if($validator->fails()){
            $data = [
                'message' => 'Data validation error',
                'error' => $validator->errors(),
                'status' => 400
            ];
            return response()->json($data,400);
        }

        if($request->has('nombre')){
            $vendor->nombre = $request->nombre;
        }
        if($request->has('RUC_DNI')){
            $vendor->RUC_DNI = $request->RUC_DNI;
        }
        if($request->has('direccion')){
            $vendor->direccion = $request->direccion;
        }
        if($request->has('telefono')){
            $vendor->telefono = $request->telefono;
        }
        if($request->has('correo')){
            $vendor->correo = $request->correo;
        }

        $vendor->save();

        $data = [
            'message' => 'Vendor updated',
            'vendor' => $vendor,
            'status' => 200
        ];
        return response()->json($data,200);
    }

    public function destroy($id){
        $vendor = Vendor::find($id);

        if(!$vendor){
            $data = [
                'message' => 'Vendor not found',
                'status' => 404
            ];
            return response()->json($data,404);
        }
        $vendor->delete();

        $data = [
            'message' => 'Vendor deleted',
            'status' => 200
        ];
        return response()->json($data,200);
    }
}
