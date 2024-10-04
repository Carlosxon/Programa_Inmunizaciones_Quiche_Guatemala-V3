<?php

namespace App\Services;

use App\Models\Inventory;
use App\Models\Product;
use Illuminate\Support\Facades\Log;

class InventoryService
{
    public function updateInventory($warehouseId, $productId, $quantity, $isAdjustment = false)
    {
        $product = Product::findOrFail($productId);
        
        $inventory = Inventory::firstOrNew([
            'warehouse_id' => $warehouseId,
            'product_id' => $productId
        ]);

        $oldQuantity = $inventory->quantity ?? 0;

        if ($isAdjustment) {
            $inventory->quantity = $quantity;
        } else {
            $inventory->quantity = $oldQuantity + $quantity;
        }

        // Asegurarse de que la cantidad no sea negativa
        $inventory->quantity = max(0, $inventory->quantity);

        $inventory->product_name = $product->name;
        $inventory->acquisition_date = $inventory->acquisition_date ?? now();
        
        $inventory->save();

        // Registrar la actualización
        Log::info('Inventario actualizado', [
            'warehouse_id' => $warehouseId,
            'product_id' => $productId,
            'old_quantity' => $oldQuantity,
            'new_quantity' => $inventory->quantity,
            'change' => $quantity,
            'is_adjustment' => $isAdjustment
        ]);

        return $inventory;
    }

    public function getCurrentInventory($warehouseId, $productId)
    {
        $inventory = Inventory::where('warehouse_id', $warehouseId)
                              ->where('product_id', $productId)
                              ->first();

        return $inventory ? $inventory->quantity : 0;
    }
}