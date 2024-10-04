@extends('adminlte::page')

@section('title', 'Reporte de Almacenes')

@section('content_header')
    <h1>Reporte de Almacenes</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <h5>Resumen de Almacenes</h5>
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Ubicación</th>
                        <th>Capacidad</th>
                        <th>Total de Productos</th>
                        <th>Valor Total del Inventario</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($warehouses as $warehouse)
                        <tr>
                            <td>{{ $warehouse->name }}</td>
                            <td>{{ $warehouse->location }}</td>
                            <td>{{ $warehouse->capacity }}</td>
                            <td>{{ $warehouse->total_products }}</td>
                            <td>{{ number_format($warehouse->total_inventory_value, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="card mt-4">
        <div class="card-body">
            <h5>Inventario por Almacén</h5>
            @foreach($warehouses as $warehouse)
                <h6 class="mt-4">{{ $warehouse->name }}</h6>
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Producto</th>
                            <th>Cantidad</th>
                            <th>Valor Unitario</th>
                            <th>Valor Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($warehouse->inventories as $inventory)
                            <tr>
                                <td>{{ $inventory->product->name }}</td>
                                <td>{{ $inventory->quantity }}</td>
                                <td>{{ number_format($inventory->product->unit_price, 2) }}</td>
                                <td>{{ number_format($inventory->quantity * $inventory->product->unit_price, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endforeach
        </div>
    </div>

    <div class="card mt-4">
        <div class="card-body">
            <h5>Movimientos Recientes</h5>
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Tipo</th>
                        <th>Producto</th>
                        <th>Cantidad</th>
                        <th>Almacén Origen</th>
                        <th>Almacén Destino</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentMovements as $movement)
                        <tr>
                            <td>{{ $movement->created_at }}</td>
                            <td>{{ $movement->type }}</td>
                            <td>{{ $movement->product_name }}</td>
                            <td>{{ $movement->quantity }}</td>
                            <td>{{ $movement->from_warehouse_name ?? 'N/A' }}</td>
                            <td>{{ $movement->to_warehouse_name ?? 'N/A' }}</td>
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