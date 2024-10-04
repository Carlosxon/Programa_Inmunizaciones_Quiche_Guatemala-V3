<?php

namespace App\Http\Controllers;

use App\Services\InventoryService;
use App\Models\StockAdjustment;
use App\Models\Product;
use App\Models\Warehouse;
use App\Models\Inventory; // Añade esta línea para importar el modelo Inventory
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StockAdjustmentController extends Controller
{
    protected $inventoryService;

    /**
     * Create a new controller instance.
     */
    public function __construct(InventoryService $inventoryService)
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

        $this->inventoryService = $inventoryService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = StockAdjustment::with(['product', 'warehouse']);

        if ($request->has('product_id') && $request->product_id != '') {
            $query->where('product_id', $request->product_id);
        }

        if ($request->has('warehouse_id') && $request->warehouse_id != '') {
            $query->where('warehouse_id', $request->warehouse_id);
        }

        if ($request->has('date_from') && $request->date_from != '') {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->has('date_to') && $request->date_to != '') {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $adjustments = $query->latest()->paginate(10);
        $products = Product::all();
        $warehouses = Warehouse::all();

        return view('stock_adjustments.index', compact('adjustments', 'products', 'warehouses'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $products = Product::all();
        $warehouses = Warehouse::all();

        return view('stock_adjustments.create', compact('products', 'warehouses'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'warehouse_id' => 'required|exists:warehouses,id',
            'adjustment_quantity' => 'required|integer',
            'reason' => 'nullable|string',
        ]);

        DB::beginTransaction();

        try {
            $adjustment = StockAdjustment::create($request->all());

            // Obtener el inventario actual
            $currentInventory = $this->inventoryService->getCurrentInventory(
                $request->warehouse_id,
                $request->product_id
            );

            // Calcular la nueva cantidad sumando el ajuste
            $newQuantity = $currentInventory + $request->adjustment_quantity;

            $this->inventoryService->updateInventory(
                $request->warehouse_id,
                $request->product_id,
                $newQuantity,
                true
            );

            DB::commit();
            return redirect()->route('stock_adjustments.index')->with('success', 'Ajuste de inventario creado exitosamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => $e->getMessage()])->withInput();
        }
    }

    // Otros métodos (show, edit, update, destroy) pueden ser implementados según sea necesario
}
