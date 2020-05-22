<?php

namespace App\Http\Controllers;

use App\Persona;
use Illuminate\Http\Request;

class ClienteController extends Controller
{

    public function index(Request $request)
    {

        if (request()->wantsJson())
        {
            $buscar = $request->buscar;
            $criterio = $request->criterio;

            if ($buscar == '')
            {
                $personas = Persona::orderBy('id', 'DESC')->paginate(5);
            }
            else
            {
                $personas = Persona::where($criterio, 'LIKE', '%'. $buscar .'%')->orderBy('id', 'DESC')->paginate(5);

            }
            return ['pagination'=> [
                'total' => $personas->total(),
                'current_page' => $personas->currentPage(),
                'per_page' => $personas->perPage(),
                'last_page' => $personas->lastPage(),
                'from' => $personas->firstItem(),
                'to' => $personas->lastItem(),
            ],
                'personas' => $personas
            ];
        }
//
        return redirect('/');
    }

    public function store(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        Persona::create($request->all());

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

        $persona = Persona::findOrFail($request->id);
        $persona->nombre = $request->nombre;
        $persona->tipo_documento = $request->tipo_documento;
        $persona->num_documento = $request->num_documento;
        $persona->direccion = $request->direccion;
        $persona->telefono = $request->telefono;
        $persona->email = $request->email;
        $persona->save();
    }
}
