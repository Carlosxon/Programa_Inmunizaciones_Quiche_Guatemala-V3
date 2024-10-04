<?php

namespace App\Http\Controllers;


use App\Models\User;

use App\Models\Warehouse;
use DB;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role as SpatieRole;
use Spatie\Permission\Models\Permission as SpatiePermission;
use illuminate\App\Model\warehouse_ids;

use Illuminate\Support\Facades\Auth;


/*
// En un controlador
public function __construct()
{
    $this->middleware('role:Administrador')->only(['index', 'show']);
    $this->middleware('role:Encargado de Sucursal')->only(['create', 'store']);
}

*/
class UserController extends Controller
{


    public function __construct()
    {
        $this->middleware('auth'); // Asegura que el usuario esté autenticado
    
        // Verificar que el usuario tenga el rol adecuado
        $this->middleware(function ($request, $next) {
            if (!Auth::user()->hasRole('Administrador')) {
                // Redirigir a la ruta dashboard si el usuario no tiene el rol adecuado
                return redirect()->route('dashboard')->with('error', 'No tienes permisos para acceder a esta sección.');
            }
    
            return $next($request);
        });
    }

    public function create()
    {
        $roles = Role::all(); // Obtener todos los roles
        $permissions = Permission::all(); // Obtener todos los permisos
        $warehouses = Warehouse::all(); // Obtener todas las bodegas

        return view('users.create', compact('roles', 'permissions', 'warehouses'));
    }
    public function index(Request $request)
    {
        $query = User::with('roles');

        if ($request->has('name') && $request->name != '') {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        if ($request->has('email') && $request->email != '') {
            $query->where('email', 'like', '%' . $request->email . '%');
        }

        if ($request->has('role') && $request->role != '') {
            $query->whereHas('roles', function($q) use ($request) {
                $q->where('id', $request->role);
            });
        }

        $users = $query->latest()->paginate(10);
        $roles = Role::all();

        return view('users.index', compact('users', 'roles'));
    }

   

    public function store(Request $request)
{
    // Validar la solicitud
    $validatedData = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|string|confirmed|min:8',
        'phone' => 'required|string',
        'address' => 'required|string',
        'warehouse_ids' => 'nullable|array',
        'warehouse_ids.*' => 'exists:warehouses,id',
        'role' => 'required|exists:roles,id',
        'permissions' => 'nullable|array',
        'permissions.*' => 'exists:permissions,id',
    ]);

    // Crear el usuario
    $user = User::create([
        'name' => $validatedData['name'],
        'email' => $validatedData['email'],
        'password' => bcrypt($validatedData['password']),
        'phone' => $validatedData['phone'],
        'address' => $validatedData['address'],
    ]);

    // Asignar sucursales al usuario
    if (isset($validatedData['warehouse_ids'])) {
        $user->warehouses()->attach($validatedData['warehouse_ids']);
    }

    // Asignar rol al usuario
    $user->roles()->attach($validatedData['role']);

    // Asignar permisos al usuario
    if (isset($validatedData['permissions'])) {
        $user->permissions()->attach($validatedData['permissions']);
    }

    return redirect()->route('users.index')->with('success', 'Usuario creado con éxito.');
}

    

    public function show($id)
    {
        $user = User::findOrFail($id);
        return view('users.show', compact('user'));
    }

    public function edit($id)
    {
        if ($id!=1)
        $user = User::findOrFail($id); // Obtener el usuario por ID
    
        $roles = Role::all(); // Obtener todos los roles
        $permissions = Permission::all(); // Obtener todos los permisos
    
        $authUser = auth()->user(); // Obtener el usuario autenticado
    
        // Si el usuario autenticado tiene un warehouse_id, filtrar las bodegas, de lo contrario obtener todas
        $warehouses = $authUser->warehouse_id ? Warehouse::where('id', $authUser->warehouse_id)->get() : Warehouse::all();
    
        return view('users.edit', compact('user', 'roles', 'permissions', 'warehouses')); // Pasar los datos a la vista
    }
    
// En UserController.php (Método update)
public function update(Request $request, $id)
{
    // Encontrar el usuario
    $user = User::findOrFail($id);

    // Validar los datos del formulario
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|max:255|unique:users,email,' . $id,
        'phone' => 'required|string|max:255',
        'address' => 'required|string|max:255',
        'password' => 'nullable|string|min:8|confirmed',
        'warehouse_ids' => 'nullable|array',
        'warehouse_ids.*' => 'exists:warehouses,id',
        'roles' => 'required|array',
        'roles.*' => 'exists:roles,id',
        'permissions' => 'nullable|array',
        'permissions.*' => 'exists:permissions,id',
    ]);

    // Actualizar usuario
    $user->name = $request->input('name');
    $user->email = $request->input('email');
    $user->phone = $request->input('phone');
    $user->address = $request->input('address');

     // Actualiza la contraseña si se proporciona
     if ($request->filled('password')) {
        $user->password = bcrypt($validated['password']);
    }

    // Guarda los cambios del usuario
    $user->save();

    // Sincronizar roles
    $user->roles()->sync($request->input('roles', []));

    // Sincronizar permisos
    $user->permissions()->sync($request->input('permissions', []));

    // Sincronizar bodegas
    if ($request->filled('warehouse_ids')) {
        $user->warehouses()->sync($request->input('warehouse_ids', []));
    } else {
        $user->warehouses()->sync([]);
    }

    return redirect()->route('users.index')->with('success', 'Usuario actualizado con éxito.');
}




    

    public function updateRoles(Request $request, User $user)
    {
        $request->validate([
            'roles' => 'array',
            'roles.*' => 'exists:roles,id'
        ]);

        $user->syncRoles($request->roles);

        return redirect()->route('users.index')->with('success', 'Roles actualizados exitosamente');
    }

    public function updateRolesAndPermissions(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'roles' => 'array',
            'roles.*' => 'exists:roles,id',
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,id'
        ]);

        $roles = $request->input('roles', []);
        $user->syncRoles($roles);

        $permissions = $request->input('permissions', []);
        $user->syncPermissions($permissions);

        return redirect()->route('users.index')->with('success', 'Roles y permisos actualizados correctamente.');
    }

    public function destroy($id)
    {
        // Buscar el usuario por su ID
        $user = User::findOrFail($id);
    
        // Eliminar el usuario
       if ($id!=1)
        $user->delete();
    
        // Redirigir de vuelta al listado de usuarios con un mensaje de éxito
        return redirect()->route('users.index')->with('success', 'Usuario eliminado exitosamente');
    }
    
  
    
    
        // Métodos existentes de UserController como index, create, store, etc.
    
        /**
         * Mostrar todos los roles.
         */

         public function indexRoles()
         {
             $roles = Role::all();
             return view('users.roles.index', compact('roles'));
         }
        public function listRoles()
        {
            $roles = Role::all();
            return view('users.roles.index', compact('roles'));
        }
    
        /**
         * Mostrar el formulario de creación de rol.
         */
        public function createRole()
        {
            return view('users.roles.create');
        }
    
        /**
         * Guardar un nuevo rol.
         */
        public function storeRole(Request $request)
        {
            $request->validate([
                'name' => 'required|string|max:255|unique:roles,name',
            ]);
    
            Role::create(['name' => $request->input('name')]);
    
            return redirect()->route('roles.index')->with('success', 'Rol creado exitosamente');
        }
    
        /**
         * Mostrar el formulario de edición de rol.
         */
        public function editRole($id)
        {
            $role = Role::findOrFail($id);
            return view('users.roles.edit', compact('role'));
        }
    
        /**
         * Actualizar un rol.
         */
        public function updateRole(Request $request, $id)
        {
            $role = Role::findOrFail($id);
    
            $request->validate([
                'name' => 'required|string|max:255|unique:roles,name,' . $role->id,
            ]);
    
            $role->update(['name' => $request->input('name')]);
    
            return redirect()->route('users.roles.index')->with('success', 'Rol actualizado exitosamente');
        }
    
        /**
         * Eliminar un rol.
         */
        public function destroyRole($id)
        {
            $role = Role::findOrFail($id);
            $role->delete();
    
            return redirect()->route('users.roles.index')->with('success', 'Rol eliminado exitosamente');
        }
    
        /**
         * Mostrar todos los permisos.
         *
         */

         public function indexPermissions()
    {
        $permissions = Permission::all();
        return view('users.permissions.index', compact('permissions'));
    }
        public function listPermissions()
        {
            $permissions = Permission::all();
            return view('users.permissions.index', compact('permissions'));
        }
    
        /**
         * Mostrar el formulario de creación de permiso.
         */
        public function createPermission()
        {
            return view('users.permissions.create');
        }
    
        /**
         * Guardar un nuevo permiso.
         */
        public function storePermission(Request $request)
        {
            $request->validate([
                'name' => 'required|string|max:255|unique:permissions,name',
            ]);
    
            Permission::create(['name' => $request->input('name')]);
    
            return redirect()->route('permissions.index')->with('success', 'Permiso creado exitosamente');
        }
    
        /**
         * Mostrar el formulario de edición de permiso.
         */
        public function editPermission($id)
        {
            $permission = Permission::findOrFail($id);
            return view('users.permissions.edit', compact('permission'));
        }
    
        /**
         * Actualizar un permiso.
         */
        public function updatePermission(Request $request, $id)
        {
            $permission = Permission::findOrFail($id);
    
            $request->validate([
                'name' => 'required|string|max:255|unique:permissions,name,' . $permission->id,
            ]);
    
            $permission->update(['name' => $request->input('name')]);
    
            return redirect()->route('permissions.index')->with('success', 'Permiso actualizado exitosamente');
        }
    
        /**
         * Eliminar un permiso.
         */
        public function destroyPermission($id)
        {
            $permission = Permission::findOrFail($id);
            $permission->delete();
    
            return redirect()->route('permissions.index')->with('success', 'Permiso eliminado exitosamente');
        }
    
    


}
