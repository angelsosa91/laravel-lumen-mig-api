<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use  App\Models\Usuarios;
use Illuminate\Support\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
     /**
     * Instantiate a new UserController instance.
     *
     * @return void
     */

    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Get the authenticated User.
     *
     * @return Response
     */

     public function profile()
     {
         //user
         $user = Auth::user();
         //rol
         $rol = DB::table('roles')
             ->select('roles.*')
             ->where('id', $user->rol)
             ->get();
         //privileges
         $module = DB::table('privileges')
             ->join('modules', 'privileges.id_module', '=', 'modules.id')
             ->join('roles', 'privileges.id_rol', '=', 'roles.id')
             ->select('roles.role', 'modules.module', 'modules.submodule', 'modules.url', 'modules.icon') //privileges.*
             ->where('id_rol', $user->rol)
             ->where('menu', 1)
             ->orderBy('order')
             ->get();

         $roles = array(); $modules = array();
         //fetch
         foreach ($rol as $r) {
             $roles[] = $r;
         }
         //fetch
         foreach ($module as $p) {
             $modules[] = $p;
         }

         //response
         return response()->json(['user' => $user, 'roles' => $roles, 'modules' => $module], 200);
    }

    /**
     * Get all User.
     *
     * @return Response
     */
    public function show(Request $request)
    {
        //$users = Usuarios::all();
        //request
        $page = ($request->has('page')) ? intval($request->input('page')) : 1;
        $rows = ($request->has('rows')) ? intval($request->input('rows')) : 50;
        $sort = ($request->has('sort')) ? strval($request->input('sort')) : "id";
        $order = ($request->has('order')) ? strval($request->input('order')) : "asc";
        $estado = ($request->has('estado')) ? intval($request->input('estado')) : 1;
        $offset = ($page-1)*$rows;
        //array
        $items = array();
        //filters
        //$search = Usuarios::where('estado', $estado);
        $search = DB::table('users')
            ->join('roles', 'roles.id', '=', 'users.rol')
            ->select('users.*', 'roles.role')
            ->where('users.estado', $estado);
        //This field uses a LIKE match, handle it separately
        if ($request->has('nombre')) {
            $search->where('name', 'like', '%' . $request->input('nombre') . '%');
        }
        if ($request->has('usuario')) {
            $search->where('username', 'like', '%' . $request->input('usuario') . '%');
        }
        if ($request->has('email')) {
            $search->where('email', 'like', '%' . $request->input('email') . '%');
        }
        //query
        $users = $search->orderBy($sort, $order)->limit($rows)->offset($offset)->get();
        //fetch
        foreach ($users as $u) {
            $object = new \stdClass();
            $object->id = $u->id;
            $object->nombre = $u->name;
            $object->email = $u->email;
            $object->usuario = $u->username;
            $object->rol = $u->rol;
            $object->role = $u->role;
            $object->estado = ($u->estado == 1) ? 'ACTIVO' : 'INACTIVO';
            $object->created_at = date("d/m/Y H:i", strtotime($u->created_at));
            $object->updated_at = date("d/m/Y H:i", strtotime($u->updated_at));
            //push
            array_push($items, $object);
        }
        return response()->json($items, 200);
    }

    //create - register
    public function create(Request $request)
    {
        //validate incoming request
        $this->validate($request, [
            'nombre' => 'required|string',
            'email' => 'required|string|unique:usuarios',
            'usuario' => 'required|string|unique:usuarios',
            'password' => 'required|confirmed',
            'rol' => 'required',
        ]);
        //trye
        try {
            //model
            $user = new Usuarios;
            $user->name = strtoupper($request->input('nombre'));
            $user->email = strtolower($request->input('email'));
            $user->username = strtolower($request->input('usuario'));
            $user->rol = intval($request->input('rol'));
            $user->password = app('hash')->make($request->input('password'));
            //save
            $user->save();
            //return successful response
            return response()->json(['user' => $user, 'message' => 'CREATED'], 201);
            //end
        } catch (\Exception $e) {
            //return error message
            return response()->json(['message' => 'User Registration Failed!'], 409);
        }
    }

    //update
    public function update(Request $request, $id)
    {
        //validate incoming request
        $this->validate($request, [
            'nombre' => 'required|string',
            'email' => 'required|string',
            'usuario' => 'required|string',
            'password' => 'required|confirmed',
            'rol' => 'required',
        ]);
        //trye
        try {
            //find
            $userFind = Usuarios::findOrFail($id);
            $userFind->update([
                'name' => strtoupper($request->input('nombre')),
                'email' => strtolower($request->input('email')),
                'username' => strtolower($request->input('usuario')),
                'rol' => intval($request->input('rol')),
                'password' => app('hash')->make($request->input('password')),
            ]);
            //return successful response
            return response()->json(['user' => $userFind, 'message' => 'UPDATED'], 201);
            //end
        } catch (\Exception $e) {
            //return error message
            return response()->json(['message' => $e->getMessage() . 'User Registration Failed!'], 409);
        }
    }
    //delete
    public function delete($id)
    {
        //trye
        try {
            //find
            $userFind = Usuarios::findOrFail($id);
            $userFind->delete();
            //return successful response
            return response()->json(['message' => 'DELETED'], 201);
            //end
        } catch (\Exception $e) {
            //return error message
            return response()->json(['message' => 'User Deleted Failed!'], 409);
        }
    }

    public function validateToken(Request $request)
    {
        if (Auth::guard('api')->check()) {
            return response()->json(['success' => true, 'message' => 'alive'], 200);
        }
        // return general data
        return response(['message' => 'Unauthenticated user'], 401);
    }

    public function privileges($module)
    {
        //user
        $user = Auth::user();
        //rol
        $rolId = DB::table('users')
            ->select('rol')
            ->where('id', $user->id)
            ->get()[0]->rol;
        //module
        $moduleId = DB::table('modules')
            ->select('id')
            ->where('url', trim(base64_decode($module)))
            ->get()[0]->id;
        //privileges
        $permission = DB::table('privileges')
            ->select('access', 'status', 'read', 'write', 'update', 'delete')
            ->where('id_rol', $rolId)
            ->where('id_module', $moduleId)
            ->get();

        $permissions = array();
        //fetch
        foreach ($permission as $p) {
            $permissions[] = $p;
        }
        //response
        return response()->json(['permission' => $permissions], 200);
    }

}
