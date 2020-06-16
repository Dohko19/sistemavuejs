<?php

namespace App\Http\Controllers;

use App\Categoria;
use PDF;
use Illuminate\Http\Request;
use App\Articulo;
use Illuminate\Support\Facades\DB;


class ArticuloController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return array
     */
    public function index(Request $request)
    {

        if (request()->wantsJson())
        {
            $buscar = $request->buscar;
            $criterio = $request->criterio;

            if ($buscar == '')
            {
                $articulos = Articulo::join('categorias', 'articulos.idcategoria', '=', 'categorias.id')
                    ->select('articulos.id', 'articulos.idcategoria', 'articulos.codigo', 'articulos.nombre', 'categorias.nombre as nombre_categoria', 'articulos.precio_venta', 'articulos.stock', 'articulos.descripcion', 'articulos.condicion')
                    ->orderBy('articulos.id', 'DESC')->paginate(5);
            }
            else
            {
                $articulos = Articulo::join('categorias', 'articulos.idcategoria', '=', 'categorias.id')
                    ->select('articulos.id', 'articulos.idcategoria', 'articulos.codigo', 'articulos.nombre', 'categorias.nombre as nombre_categoria', 'articulos.precio_venta', 'articulos.stock', 'articulos.descripcion', 'articulos.condicion')
                    ->where('articulos.'.$criterio, 'LIKE', '%'. $buscar .'%')
                    ->orderBy('articulos.id', 'DESC')->paginate(5);

//                dd($articulos);
            }
            return ['pagination'=> [
                'total' => $articulos->total(),
                'current_page' => $articulos->currentPage(),
                'per_page' => $articulos->perPage(),
                'last_page' => $articulos->lastPage(),
                'from' => $articulos->firstItem(),
                'to' => $articulos->lastItem(),
            ],
                'articulos' => $articulos
            ];
        }
        return redirect('/');
    }

    public function listarArticulo(Request $request)
    {

        if (request()->wantsJson())
        {
            $buscar = $request->buscar;
            $criterio = $request->criterio;

            if ($buscar == '')
            {
                $articulos = Articulo::join('categorias', 'articulos.idcategoria', '=', 'categorias.id')
                    ->select('articulos.id', 'articulos.idcategoria', 'articulos.codigo', 'articulos.nombre', 'categorias.nombre as nombre_categoria', 'articulos.precio_venta', 'articulos.stock', 'articulos.descripcion', 'articulos.condicion')
                    ->orderBy('articulos.id', 'DESC')->paginate(10);
            }
            else
            {
                $articulos = Articulo::join('categorias', 'articulos.idcategoria', '=', 'categorias.id')
                    ->select('articulos.id', 'articulos.idcategoria', 'articulos.codigo', 'articulos.nombre', 'categorias.nombre as nombre_categoria', 'articulos.precio_venta', 'articulos.stock', 'articulos.descripcion', 'articulos.condicion')
                    ->where('articulos.'.$criterio, 'LIKE', '%'. $buscar .'%')
                    ->orderBy('articulos.id', 'DESC')->paginate(10);

            }
            return [ 'articulos' => $articulos ];
        }
        return redirect('/');
    }

    public function listarArticuloVenta(Request $request)
    {

        if (request()->wantsJson())
        {
            $buscar = $request->buscar;
            $criterio = $request->criterio;

            if ($buscar == '')
            {
                $articulos = Articulo::join('categorias', 'articulos.idcategoria', '=', 'categorias.id')
                    ->select('articulos.id', 'articulos.idcategoria', 'articulos.codigo', 'articulos.nombre', 'categorias.nombre as nombre_categoria', 'articulos.precio_venta', 'articulos.stock', 'articulos.descripcion', 'articulos.condicion')
                    ->where('articulos.stock', '>', '0')
                    ->orderBy('articulos.id', 'DESC')->paginate(10);
            }
            else
            {
                $articulos = Articulo::join('categorias', 'articulos.idcategoria', '=', 'categorias.id')
                    ->select('articulos.id', 'articulos.idcategoria', 'articulos.codigo', 'articulos.nombre', 'categorias.nombre as nombre_categoria', 'articulos.precio_venta', 'articulos.stock', 'articulos.descripcion', 'articulos.condicion')
                    ->where('articulos.stock', '>', '0')
                    ->where('articulos.'.$criterio, 'LIKE', '%'. $buscar .'%')
                    ->orderBy('articulos.id', 'DESC')->paginate(10);

            }
            return [ 'articulos' => $articulos ];
        }
        return redirect('/');
    }

    public function listarPdf()
    {
        $articulos = Articulo::with('categoria')->join('categorias', 'articulos.idcategoria', '=', 'categorias.id')
            ->select('articulos.id', 'articulos.idcategoria', 'articulos.codigo', 'articulos.nombre',
                'categorias.nombre as nombre_categoria', 'articulos.precio_venta', 'articulos.stock',
                'articulos.descripcion', 'articulos.condicion')
            ->orderBy('articulos.nombre', 'DESC')->get();
        $con = Articulo::count();
        $pdf = PDF::loadView('pdf.articulospdf', ['articulos' => $articulos, 'con' => $con]);

        return $pdf->stream('articulos.pdf');
    }

    public function buscarArticulo(Request $request)
    {
        $filtro = $request->filtro;
        $articulos = Articulo::where('codigo', '=', $filtro)
            ->select('id','nombre')
            ->orderBy('nombre', 'ASC')
            ->take(1)
            ->get();

        if (request()->wantsJson())
        {
            return ['articulos' => $articulos];
        }
    }

    public function buscarArticuloVenta(Request $request)
    {
        $filtro = $request->filtro;
        $articulos = Articulo::where('codigo', '=', $filtro)
            ->select('id','nombre', 'stock', 'precio_venta')
            ->where('stock', '>', '0')
            ->orderBy('nombre', 'ASC')
            ->take(1)
            ->get();

        if (request()->wantsJson())
        {
            return ['articulos' => $articulos];
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $articulo = new Articulo();
        $articulo->idcategoria = $request->idcategoria;
        $articulo->codigo = $request->codigo;
        $articulo->nombre = $request->nombre;
        $articulo->precio_venta = $request->precio_venta;
        $articulo->stock = $request->stock;
        $articulo->descripcion = $request->descripcion;
        $articulo->condicion = '1';
        $articulo->save();

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Categoria  $categoria
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {

        if (!$request->ajax()) return redirect('/');
        $articulo = Articulo::findOrFail($request->id);
        $articulo->idcategoria = $request->idcategoria;
        $articulo->codigo = $request->codigo;
        $articulo->nombre = $request->nombre;
        $articulo->precio_venta = $request->precio_venta;
        $articulo->stock = $request->stock;
        $articulo->descripcion = $request->descripcion;
        $articulo->condicion = '1';
        $articulo->save();
    }

    public function desactivar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $articulo = Articulo::findOrFail($request->id);
        $articulo->condicion = '0';
        $articulo->save();
    }

    public function activar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $articulo = Articulo::findOrFail($request->id);
        $articulo->condicion = '1';
        $articulo->save();
    }
}
