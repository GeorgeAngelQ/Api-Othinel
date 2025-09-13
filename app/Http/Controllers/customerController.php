<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use Illuminate\Support\Facades\Validator;

class customerController extends Controller
{
    public function list(){
        $customers = Customer::all();
        if($customers->isEmpty()){
            $data = [
                'message' => 'There are no customers',
                'status' => 200
            ];
            return response()->json($data,200);
        }
        return response()->json($customers,200);
    }

    public function show($id){
        $customer = Customer::find($id);

        if(!$customer){
            $data = [
                'message' => 'Customer not found',
                'status' => '404'
            ];
            return response()->json($data,404);
        }

        $data = [
            'customer' => $customer,
            'status' => 200
        ];
        return response()->json($data,200);
    }

    public function store(Request $request){
        $validator = Validator::make($request->all(),[
            'nombre' => 'required|max:50',
            'RUC_DNI' => 'required|max:15',
            'telefono' => 'required|max:15',
            'correo' => 'required|email|max:100',
            'direccion' => 'required|max:150'
        ]);

        if($validator->fails()){
            $data = [
                'message' => 'Data validation error',
                'error' => $validator->errors(),
                'status' => 400
            ];
            return response()->json($data,400);
        }

        $customer = Customer::create([
            'nombre' => $request->nombre,
            'RUC_DNI' => $request->RUC_DNI,
            'telefono' => $request->telefono,
            'correo' => $request->correo,
            'direccion' => $request->direccion
        ]);

        if(!$customer){
            $data = [
                'message' => 'Error to create customer',
                'status' => 500
            ];
            return response()->json($data,500);
        }

        $data = [
            'customer' => $customer,
            'status' => 201
        ];

        return response()->json($data,201);

    }

    public function update(Request $request, $id){
        $customer = Customer::find($id);

        if(!$customer){
            $data = [
                'message' => 'Customer not found',
                'status' => 404
            ];
            return response()->json($data,404);
        }

        $validator = Validator::make($request->all(),[
            'nombre' => 'required|max:50',
            'RUC_DNI' => 'required|max:15',
            'telefono' => 'required|max:15',
            'correo' => 'required|email|max:100',
            'direccion' => 'required|max:150'
        ]);

        if($validator->fails()){
            $data = [
                'message' => 'Data validation error',
                'error' => $validator->errors(),
                'status' => 400
            ];
            return response()->json($data,400);
        }

        $customer->nombre = $request->nombre;
        $customer->RUC_DNI = $request->RUC_DNI;
        $customer->telefono = $request->telefono;
        $customer->correo = $request->correo;
        $customer->direccion = $request->direccion;

        $customer->save();

        $data = [
            'message' => 'Customer updated',
            'customer' => $customer,
            'status' => 200
        ];

        return response()->json($data,200);

    }

    public function partialUpdate(Request $request, $id){
        $customer = Customer::find($id);

        if(!$customer){
            $data = [
                'message' => 'Customer not found',
                'status' => 404
            ];
            return response()->json($data,404);
        }

        $validator = Validator::make($request->all(),[
            'nombre' => 'max:50',
            'RUC_DNI' => 'max:15',
            'telefono' => 'max:15',
            'correo' => 'email|max:100',
            'direccion' => 'max:150'
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
            $customer->nombre = $request->nombre;
        }
        if($request->has('RUC_DNI')){
            $customer->RUC_DNI = $request->RUC_DNI;
        }
        if($request->has('telefono')){
            $customer->telefono = $request->telefono;
        }
        if($request->has('correo')){
            $customer->correo = $request->correo;
        }
        if($request->has('direccion')){
            $customer->direccion = $request->direccion;
        }

        $customer->save();

        $data = [
            'message' => 'Customer updated',
            'customer' => $customer,
            'status' => 200
        ];
        return response()->json($data,200);
    }

    public function destroy($id){
        $customer = Customer::find($id);

        if(!$customer){
            $data = [
                'message' => 'Customer not found',
                'status' => 404
            ];
            return response()->json($data,404);
        }
        $customer->delete();

        $data = [
            'message' => 'Customer deleted',
            'status' => 200
        ];
        return response()->json($data,200);
    }
}
