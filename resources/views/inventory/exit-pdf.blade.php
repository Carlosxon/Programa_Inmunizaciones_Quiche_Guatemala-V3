<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Comprobante de Salida de Inventario - {{ $exit->id }}</title>
    <style>
        body { font-family: Arial, sans-serif; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h1>Comprobante de Salida de Inventario</h1>
    <p><strong>Número de Salida:</strong> {{ $exit->id }}</p>
    <p><strong>Fecha y Hora:</strong> {{ $exit->created_at->format('d/m/Y H:i:s') }}</p>
    <p><strong>Usuario:</strong> {{ $exit->user->name }}</p>
    <p><strong>Justificación:</strong> {{ $exit->justification }}</p>

    <table>
        <thead>
            <tr>
                <th>Producto</th>
                <th>Cantidad</th>
                <th>Almacén</th>
            </tr>
        </thead>
        <tbody>
            @foreach($exit->items as $item)
                <tr>
                    <td>{{ $item->inventoryItem->product->name }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>{{ $item->inventoryItem->warehouse->name }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div style="margin-top: 50px;">
        <p>_______________________________</p>
        <p>Firma del Responsable</p>
    </div>
</body>
</html>



