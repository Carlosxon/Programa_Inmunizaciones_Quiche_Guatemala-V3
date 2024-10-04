<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use App\Models\Product;
use App\Models\StockTransfer;
use App\Models\StockAdjustment;
use App\Models\User;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
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
    public function inventoryReport(Request $request)
    {
        $query = Inventory::with(['product', 'warehouse']);

        if ($request->has('warehouse_id')) {
            $query->where('warehouse_id', $request->warehouse_id);
        }

        if ($request->has('product_id')) {
            $query->where('product_id', $request->product_id);
        }

        $inventories = $query->get();

        $lowStockProducts = $inventories->filter(function ($inventory) {
            return $inventory->quantity < $inventory->product->min_stock;
        });

        $totalValue = $inventories->sum(function ($inventory) {
            return $inventory->quantity * $inventory->product->price;
        });

        $warehouseSummary = $inventories->groupBy('warehouse_id')->map(function ($group) {
            return [
                'name' => $group->first()->warehouse->name,
                'total_items' => $group->sum('quantity'),
                'unique_products' => $group->count(),
                'value' => $group->sum(function ($inventory) {
                    return $inventory->quantity * $inventory->product->price;
                }),
            ];
        });

        $reportIndex = [
            'Resumen general',
            'Productos con bajo stock',
            'Resumen por almacén',
            'Detalle de inventario'
        ];

        return view('reports.inventory', compact('inventories', 'lowStockProducts', 'totalValue', 'warehouseSummary', 'reportIndex'));
    }

    public function transfersReport(Request $request)
    {
        $query = StockTransfer::with(['fromWarehouse', 'toWarehouse', 'transfers.product']);

        if ($request->has('start_date') && $request->has('end_date')) {
            $query->whereBetween('transfer_date', [$request->start_date, $request->end_date]);
        }

        if ($request->has('product_id')) {
            $query->whereHas('transfers', function ($q) use ($request) {
                $q->where('product_id', $request->product_id);
            });
        }

        $transfers = $query->get();

        $transfersByProduct = $transfers->flatMap->transfers->groupBy('product_id')->map(function ($group) {
            return [
                'name' => $group->first()->product->name,
                'quantity' => $group->sum('quantity'),
                'value' => $group->sum(function ($transfer) {
                    return $transfer->quantity * $transfer->product->price;
                }),
            ];
        })->sortByDesc('quantity');

        $transfersByWarehouse = $transfers->groupBy('from_warehouse_id')->map(function ($group) {
            return [
                'name' => $group->first()->fromWarehouse->name,
                'outgoing' => $group->count(),
                'total_quantity' => $group->sum(function ($transfer) {
                    return $transfer->transfers->sum('quantity');
                }),
            ];
        })->sortByDesc('outgoing');

        $topUsers = $transfers->groupBy('user_id')->map(function ($group) {
            return [
                
                'transfers' => $group->count(),
                'total_quantity' => $group->sum(function ($transfer) {
                    return $transfer->transfers->sum('quantity');
                }),
            ];
        })->sortByDesc('transfers')->take(5);

        $reportIndex = [
            'Resumen de transferencias',
            'Transferencias por producto',
            'Transferencias por almacén',
            'Usuarios más activos',
            'Detalle de transferencias'
        ];

        return view('reports.transfers', compact('transfers', 'transfersByProduct', 'transfersByWarehouse', 'topUsers', 'reportIndex'));
    }

    public function adjustmentsReport(Request $request)
    {
        $query = StockAdjustment::with(['product', 'warehouse']);

        if ($request->has('start_date') && $request->has('end_date')) {
            $query->whereBetween('created_at', [$request->start_date, $request->end_date]);
        }

        if ($request->has('warehouse_id')) {
            $query->where('warehouse_id', $request->warehouse_id);
        }

        $adjustments = $query->get();

        $adjustmentReasons = $adjustments->groupBy('reason')->map->count()->sortDesc();

        $adjustmentsByProduct = $adjustments->groupBy('product_id')->map(function ($group) {
            return [
                'name' => $group->first()->product->name,
                'total_adjustments' => $group->count(),
                'net_quantity' => $group->sum('adjustment_quantity'),
                'value' => $group->sum(function ($adjustment) {
                    return $adjustment->adjustment_quantity * $adjustment->product->price;
                }),
            ];
        })->sortByDesc('total_adjustments');

        $adjustmentsByWarehouse = $adjustments->groupBy('warehouse_id')->map(function ($group) {
            return [
                'name' => $group->first()->warehouse->name,
                'total_adjustments' => $group->count(),
                'net_quantity' => $group->sum('adjustment_quantity'),
            ];
        })->sortByDesc('total_adjustments');

        $reportIndex = [
            'Resumen de ajustes',
            'Razones de ajuste',
            'Ajustes por producto',
            'Ajustes por almacén',
            'Detalle de ajustes'
        ];

        return view('reports.adjustments', compact('adjustments', 'adjustmentReasons', 'adjustmentsByProduct', 'adjustmentsByWarehouse', 'reportIndex'));
    }

    public function usersReport(Request $request)
    {
        $users = User::withCount(['stockTransfers', 'stockAdjustments'])->get();

        $userActivity = $users->map(function ($user) {
            $lastTransfer = $user->stockTransfers()->latest()->first();
            $lastAdjustment = $user->stockAdjustments()->latest()->first();
            return [
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->roles->pluck('name')->implode(', '),
                'transfers' => $user->stock_transfers_count,
                'adjustments' => $user->stock_adjustments_count,
                'total_actions' => $user->stock_transfers_count + $user->stock_adjustments_count,
                'last_activity' => $lastTransfer && $lastAdjustment ? 
                    max($lastTransfer->created_at, $lastAdjustment->created_at) : 
                    ($lastTransfer ? $lastTransfer->created_at : ($lastAdjustment ? $lastAdjustment->created_at : null)),
            ];
        })->sortByDesc('total_actions');

        $topUsersByTransfers = $userActivity->sortByDesc('transfers')->take(5);
        $topUsersByAdjustments = $userActivity->sortByDesc('adjustments')->take(5);

        $reportIndex = [
            'Resumen de actividad de usuarios',
            'Usuarios más activos en transferencias',
            'Usuarios más activos en ajustes',
            'Detalle de actividad por usuario'
        ];

        return view('reports.users', compact('userActivity', 'topUsersByTransfers', 'topUsersByAdjustments', 'reportIndex'));
    }

    public function consolidatedReport(Request $request)
    {
        $warehouses = Warehouse::all();
        $startDate = $request->input('start_date') ? Carbon::parse($request->input('start_date'))->startOfDay() : Carbon::now()->startOfMonth()->startOfDay();
        $endDate = $request->input('end_date') ? Carbon::parse($request->input('end_date'))->endOfDay() : Carbon::now()->endOfMonth()->endOfDay();
        $warehouseId = $request->input('warehouse_id');
    
        $query = Product::with(['inventory' => function ($query) use ($warehouseId) {
            if ($warehouseId) {
                $query->where('warehouse_id', $warehouseId);
            }
        }]);
    
        $products = $query->get();
    
        $consolidatedData = $products->map(function ($product) use ($startDate, $endDate, $warehouseId) {
            $initialStock = Inventory::where('product_id', $product->id)
                ->when($warehouseId, function ($query) use ($warehouseId) {
                    return $query->where('warehouse_id', $warehouseId);
                })
                ->where('created_at', '<', $startDate)
                ->sum('quantity');
    
            $transfers = StockTransfer::whereBetween('transfer_date', [$startDate, $endDate])
                ->whereHas('transfers', function ($query) use ($product) {
                    $query->where('product_id', $product->id);
                })
                ->when($warehouseId, function ($query) use ($warehouseId) {
                    return $query->where(function ($q) use ($warehouseId) {
                        $q->where('from_warehouse_id', $warehouseId)
                          ->orWhere('to_warehouse_id', $warehouseId);
                    });
                })
                ->get();
    
            $incomingTransfers = $transfers->where('to_warehouse_id', $warehouseId)->sum('quantity');
            $outgoingTransfers = $transfers->where('from_warehouse_id', $warehouseId)->sum('quantity');
    
            $adjustments = StockAdjustment::where('product_id', $product->id)
                ->whereBetween('created_at', [$startDate, $endDate])
                ->when($warehouseId, function ($query) use ($warehouseId) {
                    return $query->where('warehouse_id', $warehouseId);
                })
                ->sum('adjustment_quantity');
    
            $finalStock = $initialStock + $incomingTransfers - $outgoingTransfers + $adjustments;
    
            return [
                'product_name' => $product->name,
                'initial_stock' => $initialStock,
                'incoming_transfers' => $incomingTransfers,
                'outgoing_transfers' => $outgoingTransfers,
                'adjustments' => $adjustments,
                'final_stock' => $finalStock
            ];
        });
    
        return view('reports.consolidated', compact('consolidatedData', 'warehouses', 'startDate', 'endDate', 'warehouseId'));
    }
    public function productsReport()
    {
        return view('reports.products');
    }

    public function warehousesReport()
    {
        return view('reports.warehouses');
    }

    public function kardexReport()
    {
        return view('reports.kardex');
    }
}