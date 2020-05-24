<?php

namespace App\Http\Controllers;

use App\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function index(Request $request)
    {
        if (request()->wantsJson())
        {
            $buscar = $request->buscar;
            $criterio = $request->criterio;

            if ($buscar == '')
            {
                $roles = Role::orderBy('id', 'DESC')->paginate(5);
            }
            else
            {
                $roles = Role::where($criterio, 'LIKE', '%'. $buscar .'%')->orderBy('id', 'DESC')->paginate(5);

            }
            return ['pagination'=> [
                'total' => $roles->total(),
                'current_page' => $roles->currentPage(),
                'per_page' => $roles->perPage(),
                'last_page' => $roles->lastPage(),
                'from' => $roles->firstItem(),
                'to' => $roles->lastItem(),
            ],
                'roles' => $roles
            ];
        }
//
        return redirect('/');
    }
}
