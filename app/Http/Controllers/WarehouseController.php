<?php

namespace App\Http\Controllers;

use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\InventoryService;

class WarehouseController extends Controller
{

   

    public function index(Request $request)
    {
        $user = auth()->user();
        $query = Warehouse::query();

        // Filtrar bodegas según el rol del usuario
        if ($user->hasRole('Encargado de Sucursal')) {
            $query->whereHas('users', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            });
        }

        // Aplicar filtros existentes
        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->input('name') . '%');
        }
        

        // Filtrar por ubicación
        if ($request->filled('location')) {
            $query->where('location', 'like', '%' . $request->input('location') . '%');
        }

        // Filtrar por tipo
        if ($request->filled('type')) {
            $query->where('type', $request->input('type'));
        }

        // Filtrar por estado
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        // Obtener la cantidad de resultados por página
        $perPage = $request->input('per_page', 10); // Valor predeterminado es 10
        $warehouses = $query->paginate($perPage);

        return view('warehouses.index', compact('warehouses'));
    }

    public function create()
    {
        if (auth()->user()->hasRole('Encargado de Sucursal')) {
            return redirect()->route('warehouses.index')
                ->with('error', 'No tienes permiso para crear nuevas bodegas.');
        }
        return view('warehouses.create');
    }

    public function store(Request $request)
    {
        // Validar los datos de entrada
        $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'type' => 'required|in:regular,premium', // Validar el tipo de bodega
            'status' => 'required|in:active,inactive', // Validar el estado
        ]);

        // Crear una nueva bodega
        Warehouse::create([
            'name' => $request->input('name'),
            'location' => $request->input('location'),
            'type' => $request->input('type'),
            'status' => $request->input('status'),
        ]);

        return redirect()->route('warehouses.index')->with('success', 'Bodega creada exitosamente.');
    }

    public function show(Warehouse $warehouse)
    {
        return view('warehouses.show', compact('warehouse'));
    }

    public function edit(Warehouse $warehouse)
    {
        return view('warehouses.edit', compact('warehouse'));
    }

    public function update(Request $request, Warehouse $warehouse)
    {
        // Validar los datos de entrada
        $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'type' => 'nullable|string|in:regular,premium', // Validar tipo
            'status' => 'nullable|string|in:active,inactive', // Validar estado
        ]);

        // Actualizar la bodega
        $warehouse->update($request->all());

        return redirect()->route('warehouses.index')->with('success', 'Bodega actualizada exitosamente.');
    }

    public function destroy(Warehouse $warehouse)
    {
        $warehouse->delete();

        return redirect()->route('warehouses.index')->with('success', 'Bodega eliminada exitosamente.');
    }

    public function __construct(InventoryService $inventoryService)
    {
        $this->middleware('auth'); // Asegura que el usuario esté autenticado
    
        // Verificar que el usuario tenga el rol adecuado
        $this->middleware(function ($request, $next) {
            if (!Auth::user()->hasRole('Encargado de Sucursal')) {
                // Redirigir a la ruta dashboard si el usuario no tiene el rol adecuado
                return redirect()->route('dashboard')->with('error', 'No tienes permisos para acceder a esta sección.');
            }
    
            return $next($request);
        });

        $this->inventoryService = $inventoryService;
    }
}
