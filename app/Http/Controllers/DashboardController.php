<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\StockAdjustment;
use App\Models\StockTransfer;
use App\Models\Transfer;
use App\Models\Warehouse;
use App\Models\User;
use App\Models\Inventory;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;


class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $productCount = Product::count();
        $adjustmentCount = StockAdjustment::count();
        $transferCount = StockTransfer::count();
        $warehouseCount = Warehouse::count();
        $userCount = User::count();

        $warehouses = Warehouse::all();
        $products = Product::all();

        $selectedWarehouse = $request->input('warehouse_id') ? Warehouse::find($request->input('warehouse_id')) : null;
        $selectedProduct = $request->input('product_id') ? Product::find($request->input('product_id')) : null;
        $startDate = $request->input('start_date') ? Carbon::parse($request->input('start_date')) : Carbon::now()->startOfMonth();
        $endDate = $request->input('end_date') ? Carbon::parse($request->input('end_date')) : Carbon::now()->endOfMonth();

        $consolidado = $this->getConsolidado($selectedWarehouse, $selectedProduct, $startDate, $endDate);

        $recentMovements = $this->getRecentMovements($selectedWarehouse, $selectedProduct, $startDate, $endDate);

        $monthlyAdjustments = $this->getMonthlyData(StockAdjustment::class, $startDate, $endDate);
        $monthlyTransfers = $this->getMonthlyData(Transfer::class, $startDate, $endDate);

        return view('dashboard.index', compact(
            'productCount', 'adjustmentCount', 'transferCount', 'warehouseCount', 'userCount',
            'warehouses', 'products', 'selectedWarehouse', 'selectedProduct', 'startDate', 'endDate',
            'consolidado', 'recentMovements', 'monthlyAdjustments', 'monthlyTransfers'
        ));
    }

    private function getConsolidado($warehouse, $product, $startDate, $endDate)
    {
        $query = Inventory::with(['product', 'warehouse'])
            ->whereBetween('created_at', [$startDate, $endDate]);

        if ($warehouse) {
            $query->where('warehouse_id', $warehouse->id);
        }

        if ($product) {
            $query->where('product_id', $product->id);
        }

        $inventories = $query->get();

        $consolidado = [];

        foreach ($inventories as $inventory) {
            $productId = $inventory->product_id;
            $warehouseId = $inventory->warehouse_id;

            if (!isset($consolidado[$productId][$warehouseId])) {
                $consolidado[$productId][$warehouseId] = [
                    'product_name' => $inventory->product->name,
                    'warehouse_name' => $inventory->warehouse->name,
                    'initial_quantity' => 0,
                    'entries' => 0,
                    'exits' => 0,
                    'adjustments' => 0,
                    'final_quantity' => 0
                ];
            }

            $consolidado[$productId][$warehouseId]['final_quantity'] += $inventory->quantity;
        }

        // Calcular entradas, salidas y ajustes
        $transfers = Transfer::whereBetween('created_at', [$startDate, $endDate])->get();
        $adjustments = StockAdjustment::whereBetween('created_at', [$startDate, $endDate])->get();

        foreach ($transfers as $transfer) {
            if (isset($consolidado[$transfer->product_id][$transfer->from_warehouse_id])) {
                $consolidado[$transfer->product_id][$transfer->from_warehouse_id]['exits'] += $transfer->quantity;
            }
            if (isset($consolidado[$transfer->product_id][$transfer->to_warehouse_id])) {
                $consolidado[$transfer->product_id][$transfer->to_warehouse_id]['entries'] += $transfer->quantity;
            }
        }

        foreach ($adjustments as $adjustment) {
            if (isset($consolidado[$adjustment->product_id][$adjustment->warehouse_id])) {
                $consolidado[$adjustment->product_id][$adjustment->warehouse_id]['adjustments'] += $adjustment->adjustment_quantity;
            }
        }

        // Calcular cantidad inicial
        foreach ($consolidado as &$productData) {
            foreach ($productData as &$warehouseData) {
                $warehouseData['initial_quantity'] = $warehouseData['final_quantity'] - $warehouseData['entries'] + $warehouseData['exits'] - $warehouseData['adjustments'];
            }
        }

        return $consolidado;
    }

    private function getRecentMovements($warehouse, $product, $startDate, $endDate)
    {
        $recentMovements = collect();

        $transfersQuery = Transfer::with(['product', 'fromWarehouse', 'toWarehouse'])
            ->whereBetween('created_at', [$startDate, $endDate]);

        $adjustmentsQuery = StockAdjustment::with(['product', 'warehouse'])
            ->whereBetween('created_at', [$startDate, $endDate]);

        if ($warehouse) {
            $transfersQuery->where(function($query) use ($warehouse) {
                $query->where('from_warehouse_id', $warehouse->id)
                      ->orWhere('to_warehouse_id', $warehouse->id);
            });
            $adjustmentsQuery->where('warehouse_id', $warehouse->id);
        }

        if ($product) {
            $transfersQuery->where('product_id', $product->id);
            $adjustmentsQuery->where('product_id', $product->id);
        }

        $recentTransfers = $transfersQuery->orderBy('created_at', 'desc')->take(5)->get();
        $recentAdjustments = $adjustmentsQuery->orderBy('created_at', 'desc')->take(5)->get();

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

        foreach ($recentAdjustments as $adjustment) {
            $recentMovements->push([
                'type' => 'Ajuste',
                'product' => $adjustment->product->name,
                'quantity' => $adjustment->adjustment_quantity,
                'warehouse' => $adjustment->warehouse->name,
                'date' => $adjustment->created_at
            ]);
        }

        return $recentMovements->sortByDesc('date')->take(5);
    }

    private function getMonthlyData($model, $startDate, $endDate)
    {
        $data = $model::selectRaw('MONTH(created_at) as month, COUNT(*) as count')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('count', 'month')
            ->toArray();

        $fullYearData = [];
        for ($i = 1; $i <= 12; $i++) {
            $fullYearData[$i] = $data[$i] ?? 0;
        }

        return $fullYearData;
    }
}