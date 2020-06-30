<?php

namespace App\Http\Controllers;

use App\DetalleVenta;
use App\Notifications\NotifyAdmin;
use App\User;
use App\Venta;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PDF;

class VentaController extends Controller
{
    public function index(Request $request)
    {


            $buscar = $request->buscar;
            $criterio = $request->criterio;

            if ($buscar == '')
            {
                $ventas = Venta::join('personas','ventas.idcliente','=','personas.id')
                    ->join('users','ventas.idusuario','=','users.id')
                    ->where('users.id', auth()->user()->id)
                    ->select('ventas.id','ventas.tipo_comprobante','ventas.serie_comprobante',
                        'ventas.num_comprobante','ventas.fecha_hora','ventas.impuesto','ventas.total',
                        'ventas.estado','personas.nombre','users.usuario')
                    ->orderBy('ventas.id', 'desc')->paginate();
            }
            else
            {
                $ventas = Venta::join('personas','ventas.idcliente','=','personas.id')
                    ->join('users','ventas.idusuario','=','users.id')
                    ->select('ventas.id','ventas.tipo_comprobante','ventas.serie_comprobante',
                        'ventas.num_comprobante','ventas.fecha_hora','ventas.impuesto','ventas.total',
                        'ventas.estado','personas.nombre','users.usuario')
                    ->where('ventas.'.$criterio, 'like', '%'. $buscar . '%')->orderBy('ventas.id', 'desc')->paginate(3);

            }
        if (request()->wantsJson())
        {
            return ['pagination'=> [
                'total' => $ventas->total(),
                'current_page' => $ventas->currentPage(),
                'per_page' => $ventas->perPage(),
                'last_page' => $ventas->lastPage(),
                'from' => $ventas->firstItem(),
                'to' => $ventas->lastItem(),
            ],
                'ventas' => $ventas
            ];
        }
//
        return redirect('/');
    }

    public function pdf(Request $request,$id){
        $venta = $ventas = Venta::join('personas','ventas.idcliente','=','personas.id')
            ->join('users','ventas.idusuario','=','users.id')
            ->select('ventas.*','personas.*','users.usuario')
            ->where('ventas.id', '=', $id)
            ->orderBy('ventas.id', 'desc')->get();

        $detalles = DetalleVenta::join('articulos','detalle_ventas.idarticulo','=','articulos.id')
            ->select('detalle_ventas.cantidad', 'detalle_ventas.precio', 'detalle_ventas.descuento', 'articulos.nombre as articulo')
            ->where('detalle_ventas.idventa',$id)
            ->orderBy('detalle_ventas.id', 'DESC')
            ->get();

        $numventa = Venta::select('num_comprobante')->where('id', $id)->get();

        $pdf = PDF::loadView('pdf.venta', ['venta' => $venta,'detalles' => $detalles]);
        return $pdf->stream('venta-'.$numventa[0]->num_comprobante.'.pdf');
    }

    public function obtenerCabezera(Request $request)
    {
        if (request()->wantsJson())
        {
            $id = $request->id;
            $ventas = Venta::join('personas','ventas.idcliente','=','personas.id')
                ->join('users','ventas.idusuario','=','users.id')
                ->select('ventas.id','ventas.tipo_comprobante','ventas.serie_comprobante',
                    'ventas.num_comprobante','ventas.fecha_hora','ventas.impuesto','ventas.total',
                    'ventas.estado','personas.nombre','users.usuario')
                ->where('ventas.id','=',$id)
                ->orderBy('ventas.id', 'desc')->take(1)->get();

            return ['ventas' => $ventas];

        }
        return response()->json(['err' => 'Ah ah ah']);
    }

    public function obtenerDetalles(Request $request)
    {
        if (request()->wantsJson())
        {
            $id = $request->id;
            $detalles = DetalleVenta::join('articulos','detalle_ventas.idarticulo','=','articulos.id')
                ->select('detalle_ventas.cantidad', 'detalle_ventas.precio', 'detalle_ventas.descuento', 'articulos.nombre as articulo')
                ->where('detalle_ventas.idventa',$id)
                ->orderBy('detalle_ventas.id', 'DESC')
                ->get();

            return [
                'detalles' => $detalles
            ];
        }
    }

    public function store(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        try{
            DB::beginTransaction();


            $mytime= Carbon::now('America/Mexico_City');

            $venta = new Venta();
            $venta->idcliente = $request->idcliente;
            $venta->idusuario = \Auth::user()->id;
            $venta->tipo_comprobante = $request->tipo_comprobante;
            $venta->serie_comprobante = $request->serie_comprobante;
            $venta->num_comprobante = $request->num_comprobante;
            $venta->fecha_hora = $mytime->toDateString();
            $venta->impuesto = $request->impuesto;
            $venta->total = $request->total;
            $venta->estado = 'Registrado';
            $venta->save();

            $detalles = $request->data;//Array de detalles
            //Recorro todos los elementos

            foreach($detalles as $ep => $det)
            {
                $detalle = new Detalleventa();
                $detalle->idventa = $venta->id;
                $detalle->idarticulo = $det['idarticulo'];
                $detalle->cantidad = $det['cantidad'];
                $detalle->precio = $det['precio'];
                $detalle->descuento = $det['descuento'];
                $detalle->save();
            }

            DB::commit();
            $fechaActual = Carbon::now()->format('Y-m-d');
            $numventas = DB::table('ventas')->whereDate('created_at', $fechaActual)->count();
            $numIngresos = DB::table('ingresos')->whereDate('created_at', $fechaActual)->count();

            $arregloDatos = [
                'ventas' => [
                    'numero' => $numventas,
                    'msj' => 'Ventas'
                ],
                'ingresos' => [
                    'numero' => $numIngresos,
                    'msj' => 'Ingresos'
                ]
            ];
            $allUsers = User::all();

            foreach ($allUsers as $notificar)
            {
                User::findOrFail($notificar->id)->notify(new NotifyAdmin($arregloDatos));
            }
            return [
                'id' => $venta->id
            ];
        } catch (\Exception $e){
            DB::rollBack();
        }

    }




    public function desactivar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $venta = Venta::findOrFail($request->id);
        $venta->estado = 'Anulado';
        $venta->save();
    }

}
