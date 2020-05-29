<?php

namespace App\Http\Controllers;

use App\DetalleIngreso;
use App\Ingreso;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class IngresoController extends Controller
{
    public function index(Request $request)
    {

        if (request()->wantsJson())
        {
            $buscar = $request->buscar;
            $criterio = $request->criterio;

            if ($buscar == '')
            {
                $ingresos = Ingreso::join('personas','ingresos.idproveedor', '=', 'personas.id')
                    ->join('users', 'ingresos`.idrol', '=', 'user.id')
                    ->select('ingresos.*', 'personas.nombre', 'users.usuario')
                    ->orderBy('ingresos.id', 'DESC')->paginate(5);
            }
            else
            {
                $ingresos = User::join('personas','ingresos.idproveedor', '=', 'personas.id')
                    ->join('users', 'ingresos`.idrol', '=', 'user.id')
                    ->select('ingresos.*', 'personas.nombre', 'users.usuario')
                    ->where('ingresos.'.$criterio, 'LIKE', '%'. $buscar .'%')->orderBy('ingresos.id', 'DESC')->paginate(5);

            }
            return ['pagination'=> [
                'total' => $ingresos->total(),
                'current_page' => $ingresos->currentPage(),
                'per_page' => $ingresos->perPage(),
                'last_page' => $ingresos->lastPage(),
                'from' => $ingresos->firstItem(),
                'to' => $ingresos->lastItem(),
            ],
                'ingresos' => $ingresos
            ];
        }
//
        return redirect('/');
    }

    public function store(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        try {
            DB::beginTransaction();

            $mytime = Carbon::now(America/Mexico_City);

            $ingreso = new Ingreso();
            $ingreso->idproveedor = $request->idproveedor;
            $ingreso->idusuario = auth()->user()->id;
            $ingreso->tipo_comprobante = $request->tipo_comprobante;
            $ingreso->serie_comprobante = $request->serie_comprobante;
            $ingreso->num_comprobante = $request->num_comprobante;
            $ingreso->impuesto = $request->impuesto;
            $ingreso->total = $request->total;
            $ingreso->fecha_hora = $mytime->toDateString();
            $ingreso->estado = 'Registrado';
            $ingreso->save();

            $detalles = $request->data;
            foreach ($detalles as $ep => $det)
            {
                $detalle = new DetalleIngreso();
                $detalle->idingreso = $ingreso->id;
                $detalle->idarticulo = $det['idarticulo'];
                $detalle->cantidad = $det['cantidad'];
                $detalle->precio = $det['precio'];
            }

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

            $user = User::findOrFail($request->id);

            $persona = Persona::findOrFail($user->id);

            $persona->nombre = $request->nombre;
            $persona->tipo_documento = $request->tipo_documento;
            $persona->num_documento = $request->num_documento;
            $persona->direccion = $request->direccion;
            $persona->telefono = $request->telefono;
            $persona->email = $request->email;
            $persona->save();

            $user->usuario = $request->usuario;
            if($request->filled('password'))
            {
                $user->password = bcrypt($request->password);
            }
            $user->condicion = 1;
            $user->idrol = $request->idrol;
            $user->save();

            DB::commit();
        } catch (\Exception $e){
            DB::rollBack();
        }
    }

    public function desactivar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $user = User::findOrFail($request->id);
        $user->condicion = '0';
        $user->save();
    }

}
