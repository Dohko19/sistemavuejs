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
                $ingresos = Ingreso::join('personas','ingresos.idproveedor','=','personas.id')
                    ->join('users','ingresos.idusuario','=','users.id')
                    ->select('ingresos.id','ingresos.tipo_comprobante','ingresos.serie_comprobante',
                        'ingresos.num_comprobante','ingresos.fecha_hora','ingresos.impuesto','ingresos.total',
                        'ingresos.estado','personas.nombre','users.usuario')
                    ->orderBy('ingresos.id', 'desc')->paginate(3);
            }
            else
            {
                $ingresos = Ingreso::join('personas','ingresos.idproveedor','=','personas.id')
                    ->join('users','ingresos.idusuario','=','users.id')
                    ->select('ingresos.id','ingresos.tipo_comprobante','ingresos.serie_comprobante',
                        'ingresos.num_comprobante','ingresos.fecha_hora','ingresos.impuesto','ingresos.total',
                        'ingresos.estado','personas.nombre','users.usuario')
                    ->where('ingresos.'.$criterio, 'like', '%'. $buscar . '%')->orderBy('ingresos.id', 'desc')->paginate(3);

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


    public function desactivar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $user = Ingreso::findOrFail($request->id);
        $user->estado = 'Anulado';
        $user->save();
    }

}
