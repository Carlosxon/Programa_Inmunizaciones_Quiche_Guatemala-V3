<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\StockAdjustment;
use App\Models\StockTransfer;
use App\Models\Transfer;
use App\Models\Warehouse;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Inventory;

class DashboardController extends Controller
{
    public function index()
    {
        $productCount = Product::count();
        $adjustmentCount = StockAdjustment::count();
        $transferCount = StockTransfer::count();
        $warehouseCount = Warehouse::count();
        $userCount = User::count();

        // Obtén los ajustes mensuales
        $monthlyAdjustments = StockAdjustment::select(DB::raw('MONTH(created_at) as month'), DB::raw('count(*) as total'))
        ->groupBy('month')
        ->pluck('total', 'month')
        ->toArray();

        // Obtén las transferencias mensuales
        $monthlyTransfers = StockTransfer::select(DB::raw('MONTH(created_at) as month'), DB::raw('count(*) as total'))
            ->groupBy('month')
            ->pluck('total', 'month')
            ->toArray();

        // Llena los meses para el eje X
        $months = array_fill(1, 12, 0); // Asume 12 meses, inicializa en 0
        foreach ($monthlyAdjustments as $month => $total) {
            $months[$month] = $total;
        }
        $monthlyAdjustments = $months;

        $months = array_fill(1, 12, 0); // Asume 12 meses, inicializa en 0
        foreach ($monthlyTransfers as $month => $total) {
            $months[$month] = $total;
        }
        $monthlyTransfers = $months;

        // Obtener movimientos recientes
        $recentMovements = collect();

        // Obtener transferencias recientes
        $recentTransfers = StockTransfer::with(['product', 'fromWarehouse', 'toWarehouse'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        foreach ($recentTransfers as $transfer) {
            $recentMovements->push([
                'type' => 'Transferencia',
                'product' => $transfer->product->name,
                'quantity' => $transfer->quantity,
                'from' => $transfer->fromWarehouse->name,
                'to' => $transfer->toWarehouse->name,
                'date' => $transfer->created_at
            ]);
        }

        // Obtener ajustes recientes
        $recentAdjustments = StockAdjustment::with(['product', 'warehouse'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        foreach ($recentAdjustments as $adjustment) {
            $recentMovements->push([
                'type' => 'Ajuste',
                'product' => $adjustment->product->name,
                'quantity' => $adjustment->quantity,
                'warehouse' => $adjustment->warehouse->name,
                'date' => $adjustment->created_at
            ]);
        }

        // Ordenar todos los movimientos por fecha
        $recentMovements = $recentMovements->sortByDesc('date')->take(5);

        // Obtener todas las bodegas
        $warehouses = Warehouse::all();

        // Consolidados mensuales y anuales
        $consolidados = [];

        foreach ($warehouses as $warehouse) {
            $consolidados[$warehouse->id] = [
                'nombre' => $warehouse->name,
                'mensual' => $this->getConsolidadoMensual($warehouse->id),
                'anual' => $this->getConsolidadoAnual($warehouse->id)
            ];
        }

        return view('dashboard.index', compact('productCount', 'adjustmentCount', 'transferCount', 'warehouseCount', 'userCount', 'monthlyAdjustments', 'monthlyTransfers', 'consolidados', 'recentMovements'));
    }

    private function getConsolidadoMensual($warehouseId)
    {
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();

        return $this->getConsolidado($warehouseId, $startOfMonth, $endOfMonth);
    }

    private function getConsolidadoAnual($warehouseId)
    {
        $startOfYear = Carbon::now()->startOfYear();
        $endOfYear = Carbon::now()->endOfYear();

        return $this->getConsolidado($warehouseId, $startOfYear, $endOfYear);
    }

    private function getConsolidado($warehouseId, $startDate, $endDate)
    {
        $entradas = Transfer::where('to_warehouse_id', $warehouseId)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum('quantity');

        $salidas = Transfer::where('from_warehouse_id', $warehouseId)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum('quantity');

        $ajustes = StockAdjustment::where('warehouse_id', $warehouseId)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum('adjustment_quantity');

        $inventarioActual = Inventory::where('warehouse_id', $warehouseId)->sum('quantity');

        return [
            'entradas' => $entradas,
            'salidas' => $salidas,
            'ajustes' => $ajustes,
            'inventario_actual' => $inventarioActual
        ];
    }
}
