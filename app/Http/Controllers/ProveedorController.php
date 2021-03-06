<?php

namespace App\Http\Controllers;

use App\Persona;
use App\Proveedor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProveedorController extends Controller
{
    public function index(Request $request)
    {

        if (request()->wantsJson())
        {
            $buscar = $request->buscar;
            $criterio = $request->criterio;

            if ($buscar == '')
            {
                $proveedores = Proveedor::join('personas','proveedores.id', '=', 'personas.id')
                    ->select('personas.id', 'personas.nombre', 'personas.tipo_documento',
                        'personas.num_documento', 'personas.direccion', 'personas.telefono',
                        'personas.email', 'proveedores.contacto', 'proveedores.telefono_contacto')
                    ->orderBy('personas.id', 'DESC')->paginate(5);
            }
            else
            {
                $proveedores = Proveedor::join('personas','proveedores.id', '=', 'personas.id')
                ->select('personas.id', 'personas.nombre', 'personas.tipo_documento',
                    'personas.num_documento', 'personas.direccion', 'personas.telefono',
                    'personas.email', 'proveedores.contacto', 'proveedores.telefono_contacto')
                ->where('personas.'.$criterio, 'LIKE', '%'. $buscar .'%')->orderBy('personas.id', 'DESC')->paginate(5);

            }
            return ['pagination'=> [
                'total' => $proveedores->total(),
                'current_page' => $proveedores->currentPage(),
                'per_page' => $proveedores->perPage(),
                'last_page' => $proveedores->lastPage(),
                'from' => $proveedores->firstItem(),
                'to' => $proveedores->lastItem(),
            ],
                'proveedores' => $proveedores
            ];
        }
//
        return redirect('/');
    }

    public function selectProveedor(Request $request)
    {
        $filtro = $request->filtro;
        $proveedores = Proveedor::join('personas', 'proveedores.id', '=', 'personas.id')
        ->where('personas.nombre', 'LIKE', '%'.$filtro.'%')
        ->orWhere('personas.num_documento', 'LIKE', '%'.$filtro.'%')
        ->select('personas.id','personas.nombre','personas.num_documento')
        ->orderBy('personas.nombre', 'ASC')
        ->get();

         if (request()->wantsJson())
         {
            return ['proveedor' => $proveedores];
         }
         return redirect('/');
    }

    public function store(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        try {
            DB::beginTransaction();
            $persona = new Persona();
            $persona->nombre = $request->nombre;
            $persona->tipo_documento = $request->tipo_documento;
            $persona->num_documento = $request->num_documento;
            $persona->direccion = $request->direccion;
            $persona->telefono = $request->telefono;
            $persona->email = $request->email;
            $persona->save();

            $proveedor = new Proveedor();
            $proveedor->contacto = $request->contacto;
            $proveedor->contacto = $request->telefono_contacto;
            $proveedor->id = $persona->id;
            $proveedor->save();

            DB::commit();
        } catch (\Exception $e){
            DB::rollBack();
        }

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Persona  $Persona
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        try {
            DB::beginTransaction();

            $proveedor = Proveedor::findOrFail($request->id);

            $persona = Persona::findOrFail($proveedor->id);

            $persona->nombre = $request->nombre;
            $persona->tipo_documento = $request->tipo_documento;
            $persona->num_documento = $request->num_documento;
            $persona->direccion = $request->direccion;
            $persona->telefono = $request->telefono;
            $persona->email = $request->email;
            $persona->save();

            $proveedor->contacto = $request->contacto;
            $proveedor->contacto = $request->telefono_contacto;
            $proveedor->id = $persona->id;
            $proveedor->save();

            DB::commit();
        } catch (\Exception $e){
            DB::rollBack();
        }
    }
}
