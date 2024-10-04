<?php


namespace App\Http\Controllers;
use App\Models\StockTransfer;
use App\Models\Transfer;
use App\Models\Inventory;
use App\Models\Product;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\PDF;

class TransferController extends Controller
{
    public function listarTransferenciasPendientes($warehouseId)
    {
        $warehouse = Warehouse::findOrFail($warehouseId);

        $transferencias = StockTransfer::where('to_warehouse_id', $warehouseId)
            ->where('is_received', false)
            ->with(['transfers.product']) // Carga eager los productos
            ->get();

        $inventario = Inventory::where('warehouse_id', $warehouseId)
            ->with('product') // Carga eager los productos
            ->get();

        return view('transferencias.pendientes', compact('warehouse', 'transferencias', 'inventario'));
    }

    public function aceptarTransferencia(StockTransfer $transferencia)
    {
        DB::beginTransaction();

        try {
            foreach ($transferencia->transfers as $transfer) {
                $inventory = Inventory::firstOrNew([
                    'warehouse_id' => $transferencia->to_warehouse_id,
                    'product_id' => $transfer->product_id
                ]);

                $inventory->quantity = ($inventory->quantity ?? 0) + $transfer->quantity;
                $inventory->product_name = $transfer->product->name; // Asegúrate de que el nombre del producto se guarde
                $inventory->acquisition_date = $inventory->acquisition_date ?? now();
                $inventory->save();

                $transfer->is_received = true;
                $transfer->save();
            }

            $transferencia->is_received = true;
            $transferencia->save();

            DB::commit();
            return redirect()->route('transferencias.listar', $transferencia->to_warehouse_id)
                             ->with('success', 'Transferencia aceptada y el inventario ha sido actualizado.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error al aceptar la transferencia: ' . $e->getMessage());
        }
    }

    public function listarTransferencia(Warehouse $warehouse = null)
    {
        if ($warehouse) {
            $transferencias = Transfer::where('to_warehouse_id', $warehouse->id)
                                      ->where('is_received', false)
                                      ->get();
            $inventario = Inventory::where('warehouse_id', $warehouse->id)->get();
        } else {
            $transferencias = Transfer::where('is_received', false)->get();
            $inventario = collect(); // Colección vacía si no hay bodega específica
        }
        
        return view('transferencias.pendientes', compact('transferencias', 'warehouse', 'inventario'));
    }

    public function pendientes()
    {
        $transferencias = Transfer::with('transferItems')->where('is_received', false)->get();
        return view('transferencias.pendientes', compact('transferencias'));
    }

    public function store(Request $request)
    {
        \Log::info('Datos recibidos:', $request->all());

        $validatedData = $request->validate([
            'from_warehouse_id' => 'required|exists:warehouses,id',
            'to_warehouse_id' => 'required|exists:warehouses,id',
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'transfer_date' => 'required|date',
            'status' => 'required|in:pending,completed,cancelled',
        ]);

        \Log::info('Datos validados:', $validatedData);

        $transfer = Transfer::create($validatedData);

        return response()->json($transfer, 201);
    }

   

    
    
}

