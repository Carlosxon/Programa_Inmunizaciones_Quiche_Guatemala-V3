@extends('adminlte::page')

@section('title', 'Reporte de Productos')

@section('content_header')
    <h1>Reporte de Productos</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <h5>Resumen de Productos</h5>
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Código</th>
                        <th>Categoría</th>
                        <th>Stock Total</th>
                        <th>Precio Unitario</th>
                        <th>Valor Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($products as $product)
                        <tr>
                            <td>{{ $product->name }}</td>
                            <td>{{ $product->code }}</td>
                            <td>{{ $product->category->name ?? 'N/A' }}</td>
                            <td>{{ $product->total_stock }}</td>
                            <td>{{ number_format($product->unit_price, 2) }}</td>
                            <td>{{ number_format($product->total_stock * $product->unit_price, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="card mt-4">
        <div class="card-body">
            <h5>Stock por Almacén</h5>
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Producto</th>
                        @foreach($warehouses as $warehouse)
                            <th>{{ $warehouse->name }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach($products as $product)
                        <tr>
                            <td>{{ $product->name }}</td>
                            @foreach($warehouses as $warehouse)
                                <td>
                                    {{ $product->inventories->where('warehouse_id', $warehouse->id)->first()->quantity ?? 0 }}
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="card mt-4">
        <div class="card-body">
            <h5>Movimientos Recientes</h5>
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Producto</th>
                        <th>Tipo</th>
                        <th>Cantidad</th>
                        <th>Almacén Origen</th>
                        <th>Almacén Destino</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentMovements as $movement)
                        <tr>
                            <td>{{ $movement->created_at }}</td>
                            <td>{{ $movement->product->name }}</td>
                            <td>{{ $movement->type }}</td>
                            <td>{{ $movement->quantity }}</td>
                            <td>{{ $movement->from_warehouse->name ?? 'N/A' }}</td>
                            <td>{{ $movement->to_warehouse->name ?? 'N/A' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    <script>
        console.log('Hi!');
    </script>
@stop