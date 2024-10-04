@extends('adminlte::page')

@section('title', 'Lista de Ajustes de Stock')

@section('content_header')
    <h1>Lista de Ajustes de Stock</h1>
@stop

@section('content')

@if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

@if (session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif

<div class="row mb-3">
    <div class="col-md-12">
        <a href="{{ route('stock-adjustments.create') }}" class="btn btn-primary">Crear Ajuste</a>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Filtros</h3>
            </div>
            <div class="card-body">
                <form action="{{ route('stock-adjustments.index') }}" method="GET">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="product_id">Producto:</label>
                                <select name="product_id" id="product_id" class="form-control">
                                    <option value="">Todos los productos</option>
                                    @foreach($products as $product)
                                        <option value="{{ $product->id }}" {{ request('product_id') == $product->id ? 'selected' : '' }}>
                                            {{ $product->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="warehouse_id">Almacén:</label>
                                <select name="warehouse_id" id="warehouse_id" class="form-control">
                                    <option value="">Todos los almacenes</option>
                                    @foreach($warehouses as $warehouse)
                                        <option value="{{ $warehouse->id }}" {{ request('warehouse_id') == $warehouse->id ? 'selected' : '' }}>
                                            {{ $warehouse->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="date_from">Fecha desde:</label>
                                <input type="date" name="date_from" id="date_from" class="form-control" value="{{ request('date_from') }}">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="date_to">Fecha hasta:</label>
                                <input type="date" name="date_to" id="date_to" class="form-control" value="{{ request('date_to') }}">
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Filtrar</button>
                    <a href="{{ route('stock-adjustments.index') }}" class="btn btn-secondary">Limpiar filtros</a>
                </form>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header">
                @can('create', App\Models\StockAdjustment::class)
                    <a href="{{ route('stock-adjustments.create') }}" class="btn btn-primary">Crear Ajuste</a>
                @endcan
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Producto</th>
                            <th>Almacén</th>
                            <th>Cantidad</th>
                            <th>Razón</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($adjustments as $adjustment)
                            <tr>
                                <td>{{ $adjustment->id }}</td>
                                <td>{{ $adjustment->product->name }}</td>
                                <td>{{ $adjustment->warehouse->name }}</td>
                                <td>{{ $adjustment->adjustment_quantity }}</td>
                                <td>{{ $adjustment->reason }}</td>
                                <td>
                                    @can('update', $adjustment)
                                        <a href="{{ route('stock-adjustments.edit', $adjustment->id) }}" class="btn btn-warning btn-sm">Editar</a>
                                    @endcan
                                    @can('delete', $adjustment)
                                        <form action="{{ route('stock-adjustments.destroy', $adjustment->id) }}" method="POST" style="display:inline-block;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm">Eliminar</button>
                                        </form>
                                    @endcan
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                {{ $adjustments->links() }}
            </div>
        </div>
    </div>
</div>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    <script> console.log('¡Hola!'); </script>
@stop
