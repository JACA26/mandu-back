<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\division;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

DEFINE('MESSAGES', [
    'required' => 'El campo :attribute es obligatorio',
    'string' => 'El campo :attribute debe ser texto',
    'max' => 'El campo :attribute debe tener un máximo de :max caracteres',
    'integer' => 'El campo :attribute debe ser un número entero',
]);

class DivisionController extends Controller
{
    public function getDivisiones()
    {
        $response = [
            'status' => false,
            'data' => [],
            'message' => 'No hay divisiones',
        ];
        
        //traer query params para paginacion
        $page = request()->query('page', 1);
        $limit = request()->query('limit', 5);
        
        //traer divisiones paginadas
        $divisiones = division::with('parent')->paginate($limit, ['*'], 'page', $page);
        
        if ($divisiones->count() > 0) {
            
            foreach ($divisiones as $division) {
                
                $sub_divisiones_id = DB::table('division_subdivisiones')
                    ->where('id_division', $division->id)
                    ->get();
                
                $division['subdivisiones'] = $sub_divisiones_id ? count($sub_divisiones_id) : 0;
            }
            
            $response['status'] = true;
            $response['data'] = $divisiones;
            $response['message'] = 'Divisiones encontradas';
        }
        
        return response()->json($response, 200);
    }
    
    public function getDivision($id)
    {
        $response = [
            'status' => false,
            'data' => [],
            'message' => 'Division no encontrada',
        ];
        
        $division = division::with('parent')->find($id);
        $sub_divisiones_id = [];
        $sub_divisiones_data = [];
        
        if ($division) {
            
            $sub_divisiones_id = DB::table('division_subdivisiones')
                ->where('id_division', $id)
                ->get();
            
            if (count($sub_divisiones_id) > 0) {
                foreach ($sub_divisiones_id as $sub_division_id) {
                    $sub_division_data = division::with('parent')->find($sub_division_id, ['id, nombre']);
                    array_push($sub_divisiones_data, $sub_division_data);
                }
                
                $division['subdivisiones'] = $sub_divisiones_data;
            }
            
                $response['status'] = true;
                $response['data'] = $division;
                $response['message'] = 'Division encontrada';
        }
        
        return response()->json($response, 200);
    }
    
    public function createDivision(Request $request)
    {
        $response = [
            'status' => false,
            'data' => [],
            'message' => 'Error al crear la division',
            'errors' => [],
        ];
        
        //Validar los datos
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:45',
            'nivel' => 'required|integer',
            'colaboradores' => 'required|integer',
            'subdivisiones' => 'nullable|array',
            'embajador' => 'string|max:100',
            'divisionSuperior' => 'nullable|integer',
        ], MESSAGES);
        
        if ($validator->fails()) {
            $response['errors'] = $validator->errors();
            return response()->json($response, 400);
        }
        
        $data_validated = $validator->validated();
        
        //Cambiar divisionSuperior por parent_id
        $data_validated['parent_id'] = $data_validated['divisionSuperior'];
        unset($data_validated['divisionSuperior']);
        if($data_validated['parent_id'] == null || $data_validated['parent_id'] == 0 || $data_validated['parent_id'] == ''){
            $data_validated['parent_id'] = null;
        }
        
        //Sub divisiones
        $subdivisiones = [];
        if (isset($data_validated['subdivisiones']) && count($data_validated['subdivisiones']) > 0 && $data_validated['subdivisiones'] != null) {
            $subdivisiones = $data_validated['subdivisiones'];
            unset($data_validated['subdivisiones']);
        }
        
        $division = division::create($data_validated);
        
        if ($division) {
            if (count($subdivisiones) > 0) {
                foreach ($subdivisiones as $subdivision) {
                    DB::table('division_subdivisiones')->insert([
                        'id_division' => $division->id,
                        'id_subdivision' => $subdivision,
                    ]);
                }
            }
            
            $response['status'] = true;
            $response['data'] = $division;
            $response['message'] = 'Division creada';
        }
        
        return response()->json($response, 200);
    }
    
    public function updateDivision(Request $request, $id)
    {
        $response = [
            'status' => false,
            'data' => [],
            'message' => 'Error al actualizar la division',
            'errors' => [],
        ];
        
        //Validar los datos
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:45',
            'subdivisiones' => 'nullable|array',
            'nivel' => 'required|integer',
            'colaboradores' => 'required|integer',
            'embajador' => 'required|string|max:100',
            'parent_id' => 'nullable|integer',
        ], MESSAGES);
        
        if ($validator->fails()) {
            $response['errors'] = $validator->errors();
            return response()->json($response, 400);
        }
        
        $data_validated = $validator->validated();
        
        $subdivisiones = [];
        
        if (isset($data_validated['subdivisiones']) && count($data_validated['subdivisiones']) > 0 && $data_validated['subdivisiones'] != null) {
            $subdivisiones = $data_validated['subdivisiones'];
            unset($data_validated['subdivisiones']);
        }
        
        
        $division = division::find($id);
        
        if ($division) {
            $division->nombre = $data_validated['nombre'];
            $division->nivel = $data_validated['nivel'];
            $division->colaboradores = $data_validated['colaboradores'];
            $division->embajador = $data_validated['embajador'];
            $division->parent_id = $data_validated['parent_id'];
            
            $division->save();
            
            //Eliminar subdivisiones para volver a insertar
            DB::table('division_subdivisiones')->where('id_division', $division->id)->delete();
            
            if (count($subdivisiones) > 0) {
                foreach ($subdivisiones as $subdivision) {
                    DB::table('division_subdivisiones')->insert([
                        'id_division' => $division->id,
                        'id_subdivision' => $subdivision,
                    ]);
                }
            }
            
            $response['status'] = true;
            $response['data'] = $division;
            $response['message'] = 'Division actualizada';
        }
        
        return response()->json($response, 200);
    }
    
    public function deleteDivision($id)
    {
        $response = [
            'status' => false,
            'data' => [],
            'message' => 'Error al eliminar la division',
        ];
        
        $division = division::find($id);

        
        if ($division) {
            
            //Eliminar subdivisiones
            DB::table('division_subdivisiones')->where('id_division', $division->id)->delete();
            
            $division->delete();
            
            $response['status'] = true;
            $response['message'] = 'Division eliminada';
        }
        
        return response()->json($response, 200);
    }
}
