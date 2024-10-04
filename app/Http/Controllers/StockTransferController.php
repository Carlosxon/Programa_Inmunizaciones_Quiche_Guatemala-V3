<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\InventoryService;
use App\Models\StockTransfer;
use App\Models\Transfer;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use PDF;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\Inventory;
use Illuminate\Support\Facades\Auth;

class StockTransferController extends Controller
{
    protected $inventoryService;

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

    public function index(Request $request)
    {
        $query = Transfer::with(['fromWarehouse', 'toWarehouse', 'product', 'stockTransfer']);

        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->whereHas('fromWarehouse', function($q) use ($search) {
                    $q->where('name', 'like', "%$search%");
                })
                ->orWhereHas('toWarehouse', function($q) use ($search) {
                    $q->where('name', 'like', "%$search%");
                })
                ->orWhereHas('product', function($q) use ($search) {
                    $q->where('name', 'like', "%$search%");
                })
                ->orWhere('transfer_date', 'like', "%$search%")
                ->orWhere('status', 'like', "%$search%");
            });
        }

        $stockTransfers = $query->latest()->paginate(10);
        return view('stock_transfers.index', compact('stockTransfers'));
    }

    public function create()
    {
        $warehouses = Warehouse::all();
        $products = Product::all();
        return view('stock_transfers.create', compact('warehouses', 'products'));
    }

    public function store(Request $request)
    {
        // Validación básica
        $request->validate([
            'from_warehouse_id' => 'required|exists:warehouses,id',
            'to_warehouse_id' => 'required|exists:warehouses,id|different:from_warehouse_id',
            'transfer_date' => 'required|date',
            'selected_products' => 'required|array',
            'selected_products.*' => 'exists:products,id',
        ]);

        // Validación dinámica para las cantidades
        $quantityRules = [];
        foreach ($request->selected_products as $productId) {
            $quantityRules["quantities.$productId"] = 'required|integer|min:1';
        }
        $request->validate($quantityRules);

        DB::beginTransaction();

        try {
            $stockTransfer = StockTransfer::create([
                'from_warehouse_id' => $request->from_warehouse_id,
                'to_warehouse_id' => $request->to_warehouse_id,
                'transfer_date' => $request->transfer_date,
                'is_received' => false
            ]);

            foreach ($request->selected_products as $productId) {
                $quantity = $request->quantities[$productId];
                
                Transfer::create([
                    'stock_transfer_id' => $stockTransfer->id,
                    'from_warehouse_id' => $request->from_warehouse_id,
                    'to_warehouse_id' => $request->to_warehouse_id,
                    'product_id' => $productId,
                    'quantity' => $quantity,
                    'transfer_date' => $request->transfer_date,
                    'status' => 'pending',
                    'is_received' => false
                ]);
            }

            DB::commit();
            return redirect()->route('stock-transfers.index')->with('success', 'Transferencia de stock creada con éxito.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => $e->getMessage()])->withInput();
        }
    }

    public function generatePdf(StockTransfer $stockTransfer)
    {
        $pdf = PDF::loadView('stock_transfers.pdf', compact('stockTransfer'));
        return $pdf->download('transferencia_' . $stockTransfer->id . '.pdf');
    }

    public function listarTransferenciasPendientes($warehouseId)
    {
        $warehouse = Warehouse::findOrFail($warehouseId);

        $transferencias = StockTransfer::where('to_warehouse_id', $warehouseId)
            ->where('is_received', 0)
            ->get();

        $inventario = Inventory::where('warehouse_id', $warehouseId)->get();

        return view('transferencias.pendientes', compact('warehouse', 'transferencias', 'inventario'));
    }

    public function aceptarTransferencia(StockTransfer $transferencia)
    {
        DB::beginTransaction();

        try {
            foreach ($transferencia->transfers as $transfer) {
                // Restar del inventario de la bodega de origen
                $this->inventoryService->updateInventory(
                    $transfer->from_warehouse_id,
                    $transfer->product_id,
                    -$transfer->quantity
                );

                // Sumar al inventario de la bodega de destino
                $this->inventoryService->updateInventory(
                    $transfer->to_warehouse_id,
                    $transfer->product_id,
                    $transfer->quantity
                );

                $transfer->is_received = true;
                $transfer->save();
            }

            $transferencia->is_received = true;
            $transferencia->save();

            DB::commit();
            return redirect()->route('transferencias.listar', $transferencia->to_warehouse_id)
                             ->with('success', 'Transferencia aceptada y los inventarios han sido actualizados.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al aceptar transferencia: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error al aceptar la transferencia: ' . $e->getMessage());
        }
    }

    public function cancelTransfer(Transfer $transfer)
    {
        if ($transfer->is_received) {
            return redirect()->back()->with('error', 'No se puede cancelar una transferencia ya recibida.');
        }

        DB::beginTransaction();

        try {
            // Actualizar el estado de la transferencia
            $transfer->update([
                'status' => 'cancelled'
            ]);

            // Si hay alguna lógica adicional para el rollback, agrégala aquí
            // Por ejemplo, si necesitas actualizar inventarios:
            // $this->inventoryService->revertTransfer($transfer);

            DB::commit();
            return redirect()->route('stock-transfers.index')->with('success', 'Transferencia cancelada con éxito.');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error al cancelar la transferencia: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error al cancelar la transferencia: ' . $e->getMessage());
        }
    }

    public function getWarehouseInventory(Request $request)
    {
        $warehouseId = $request->input('warehouse_id');
        $inventories = Inventory::where('warehouse_id', $warehouseId)->pluck('quantity', 'product_id');
        return response()->json($inventories);
    }
}