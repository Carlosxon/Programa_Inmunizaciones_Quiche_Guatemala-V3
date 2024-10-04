<?php

// app/Http/Controllers/InventoryController.php

namespace App\Http\Controllers;
use App\Services\InventoryService;
use App\Models\Inventory;
use App\Models\Product;
use App\Models\Warehouse;
use App\Models\InventoryExit;
use App\Models\InventoryexitItem;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class InventoryController extends Controller
{    
    
    public function index(Request $request, $warehouseId = null)
    {
        $query = Inventory::with(['product', 'warehouse']);
    
        if ($warehouseId) {
            $query->where('warehouse_id', $warehouseId);
            $warehouse = Warehouse::findOrFail($warehouseId);
        } else {
            $warehouse = null;
        }
    
        $inventories = $query->get();
    
        $salidas = InventoryExit::where('warehouse_id', $warehouseId)
            ->with(['items.inventoryItem.product', 'user'])
            ->orderBy('exit_date', 'desc')
            ->get();
    
        return view('inventories.salidas', compact('warehouse', 'inventories', 'salidas'));
    }



    public function create()
    {
        $warehouses = Warehouse::all();
        $products = Product::all();
        return view('inventories.create', compact('warehouses', 'products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'warehouse_id' => 'required|exists:warehouses,id',
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:0',
            'acquisition_date' => 'required|date',
        ]);

        $product = Product::find($request->product_id);

        Inventory::create([
            'warehouse_id' => $request->warehouse_id,
            'product_id' => $request->product_id,
            'product_name' => $product->name,
            'quantity' => $request->quantity,
            'acquisition_date' => $request->acquisition_date,
        ]);

        return redirect()->route('inventories.index')->with('success', 'Inventario creado con éxito.');
    }

    public function edit(Inventory $inventory)
    {
        $warehouses = Warehouse::all();
        return view('inventories.edit', compact('inventory', 'warehouses'));
    }

    public function update(Request $request, Inventory $inventory)
    {
        $request->validate([
            'warehouse_id' => 'required|exists:warehouses,id',
            'item_name' => 'required|string|max:255',
            'quantity' => 'required|integer|min:0',
        ]);

        $inventory->update($request->all());

        return redirect()->route('inventories.index')->with('success', 'Inventario actualizado con éxito.');
    }

    public function destroy(Inventory $inventory)
    {
        $inventory->delete();
        return redirect()->route('inventories.index')->with('success', 'Inventario eliminado con éxito.');
    }

    public function exitForm(Request $request)
    {
        // Validación
        $request->validate([
            'warehouse_id' => 'required|exists:warehouses,id',
            'selected_items' => 'required|array',
            'selected_items.*' => 'exists:inventories,id',
            'quantities' => 'required|array',
            'quantities.*' => 'required|integer|min:1',
            'justification' => 'required|string',
        ]);

        DB::beginTransaction();

        try {
            // Crear la salida de inventario
            $salida = InventoryExit::create([
                'exit_number' => 'EX-' . Str::random(8),
                'exit_date' => now(),
                'user_id' => auth()->id(),
                'destination' => $request->destination ?? 'No especificado',
                'description' => $request->justification,
                'status' => 'completed',
            ]);

            foreach ($request->selected_items as $itemId) {
                $quantity = $request->quantities[$itemId];
                $inventory = Inventory::findOrFail($itemId);
                
                if ($inventory->quantity < $quantity) {
                    throw new \Exception("Cantidad insuficiente para el producto {$inventory->product_name}");
                }

                // Actualizar el inventario usando el servicio
                app(InventoryService::class)->updateInventory(
                    $inventory->warehouse_id,
                    $inventory->product_id,
                    -$quantity // Usamos cantidad negativa para indicar una salida
                );

                // Registrar el detalle de la salida
                InventoryExitItem::create([
                    'inventory_exit_id' => $salida->id,
                    'inventory_item_id' => $itemId,
                    'quantity' => $quantity,
                ]);
            }

            DB::commit();

            // Generar PDF...
            // Código para generar el PDF aquí...

            // Redireccionar a la vista de transferencias pendientes (que incluye el inventario)
            return redirect()->route('transferencias.pendientes', ['warehouseId' => $request->warehouse_id])
                ->with('success', 'La salida se ha realizado correctamente. El comprobante ha sido generado e impreso.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al procesar la salida: ' . $e->getMessage());
        }
    }

    public function processExit(Request $request)
    {
        $request->validate([
            'justification' => 'required|string',
            'quantities' => 'required|array',
            'quantities.*' => 'required|numeric|min:1',
        ]);

        DB::beginTransaction();

        try {
            $exit = InventoryExit::create([
                'user_id' => auth()->id(),
                'justification' => $request->justification,
            ]);

            foreach ($request->quantities as $itemId => $quantity) {
                $item = Inventory::findOrFail($itemId);
                
                if ($quantity > $item->quantity) {
                    throw new \Exception("Cantidad insuficiente para {$item->product->name}");
                }

                // Actualizar el inventario
                $item->quantity -= $quantity;
                $item->save();

                // Crear el item de salida
                $exit->items()->create([
                    'inventory_item_id' => $itemId,
                    'quantity' => $quantity,
                ]);
            }

            DB::commit();

            // Cargar las relaciones necesarias para el PDF
            $exit->load(['items.inventoryItem.product', 'items.inventoryItem.warehouse', 'user']);

            // Generar PDF
            $pdf = PDF::loadView('inventory.exit-pdf', compact('exit'));
            
            // Guardar el PDF en el servidor
            $pdfPath = storage_path('app/public/pdfs/salida_inventario_' . $exit->id . '.pdf');
            $pdf->save($pdfPath);

            // Preparar el mensaje de éxito
            $successMessage = 'Salida de inventario procesada con éxito. ';
            $successMessage .= '<a href="' . route('inventory.exit-pdf', $exit->id) . '" target="_blank">Descargar PDF</a>';

            // Redirigir a la vista de pendientes con el mensaje de éxito
            return redirect()->route('transferencias.pendientes', ['warehouseId' => $item->warehouse_id])
                             ->with('success', $successMessage);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    public function exitPdf($id)
    {
        $exit = InventoryExit::with(['items.inventoryItem.product', 'items.inventoryItem.warehouse', 'user'])->findOrFail($id);
        
        $pdf = PDF::loadView('inventory.exit-pdf', compact('exit'));
        
        return $pdf->download('salida_inventario_' . $exit->id . '.pdf');
    }

    public function storeExit(Request $request)
    {
        // Aquí va la lógica para procesar la salida de inventario
         //Por ejemplo:
         $exit = new InventoryExit();
         $exit->product_id = $request->product_id;
        $exit->quantity = $request->quantity;
        $exit->date = $request->date;
         $exit->reason = $request->reason;
         $exit->save();

        return redirect()->route('inventory.index')->with('success', 'Salida de inventario registrada con éxito');
    }

    public function realizarSalida(Request $request)
    {
        \Log::info('Iniciando proceso de salida de inventario', $request->all());
    
        try {
            $request->validate([
                'warehouse_id' => 'required|exists:warehouses,id',
                'selected_items' => 'required|array',
                'selected_items.*' => 'exists:inventories,id',
                'quantities' => 'required|array',
                'quantities.*' => 'required|integer|min:1',
                'justification' => 'required|string|max:255',
            ]);
    
            DB::beginTransaction();
    
            $salida = InventoryExit::create([
                'exit_number' => 'EX-' . Str::random(8),
                'exit_date' => now(),
                'user_id' => auth()->id(),
                'destination' => 'No especificado', // Puedes ajustar esto según tus necesidades
                'description' => $request->justification,
                'status' => 'completed',
                'warehouse_id' => $request->warehouse_id,
            ]);
    
            $itemsProcessed = [];
            $inventoryService = app(InventoryService::class);
    
            foreach ($request->selected_items as $itemId) {
                $quantity = $request->quantities[$itemId];
                $inventory = Inventory::findOrFail($itemId);
                
                if ($inventory->quantity < $quantity) {
                    throw new \Exception("Cantidad insuficiente para el producto {$inventory->product->name}");
                }
    
                $inventoryService->updateInventory(
                    $inventory->warehouse_id,
                    $inventory->product_id,
                    -$quantity
                );
    
                InventoryExitItem::create([
                    'inventory_exit_id' => $salida->id,
                    'inventory_item_id' => $itemId,
                    'quantity' => $quantity,
                ]);
    
                $itemsProcessed[] = [
                    'product' => $inventory->product->name,
                    'quantity' => $quantity,
                ];
            }
    
            DB::commit();
    
            \Log::info('Salida de inventario procesada correctamente', ['salida_id' => $salida->id]);
    
            return redirect()->route('transferencias.pendientes', ['warehouseId' => $request->warehouse_id])
                ->with('success', 'Salida de inventario registrada correctamente. Número de salida: ' . $salida->exit_number);
    
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error al procesar la salida: ' . $e->getMessage());
            return back()->with('error', 'Error al procesar la salida: ' . $e->getMessage());
        }
    }

    public function getSalidas(Request $request)
{
    $query = InventoryExit::with(['items', 'warehouse', 'user']);

    if ($request->filled('date')) {
        $query->whereDate('exit_date', $request->date);
    } elseif ($request->filled('month')) {
        $query->whereYear('exit_date', substr($request->month, 0, 4))
              ->whereMonth('exit_date', substr($request->month, 5, 2));
    } elseif ($request->filled('year')) {
        $query->whereYear('exit_date', $request->year);
    }

    $salidas = $query->get();

    return view('inventory.salidas', compact('salidas'));
}

public function filtrarSalidas(Request $request)
{
    $query = InventoryExit::with(['user', 'items.inventoryItem.product']);

    if ($request->filled('date')) {
        $query->whereDate('exit_date', $request->date);
    } elseif ($request->filled('month')) {
        $query->whereYear('exit_date', substr($request->month, 0, 4))
              ->whereMonth('exit_date', substr($request->month, 5, 2));
    } elseif ($request->filled('year')) {
        $query->whereYear('exit_date', $request->year);
    }

    $salidas = $query->get();

    return response()->json($salidas);
}


public function salidas($warehouseId)
{
    $warehouse = Warehouse::findOrFail($warehouseId);
    $salidas = InventoryExit::with(['items.inventoryItem.product', 'user'])
        ->orderBy('exit_date', 'desc')
        ->get();

    return view('inventory.salidas', compact('warehouse', 'salidas'));
}

}