@extends('adminlte::page')

@section('content')
<div class="container">
    <h1>Reporte Consolidado</h1>

    <form action="{{ route('reports.consolidated') }}" method="GET" class="mb-4">
    <div class="row">
        <div class="col-md-3">
            <label for="start_date">Fecha de inicio:</label>
            <input type="date" id="start_date" name="start_date" class="form-control" value="{{ $startDate->format('Y-m-d') }}">
        </div>
        
            <div class="col-md-3">
                <label for="end_date">Fecha de fin:</label>
                <input type="date" id="end_date" name="end_date" class="form-control" value="{{ $endDate instanceof \Carbon\Carbon ? $endDate->format('Y-m-d') : $endDate }}">
            </div>
            <div class="col-md-3">
                <label for="warehouse_id">Almacén:</label>
                <select id="warehouse_id" name="warehouse_id" class="form-control">
                    <option value="">Todos los almacenes</option>
                    @foreach($warehouses as $warehouse)
                        <option value="{{ $warehouse->id }}" {{ $warehouseId == $warehouse->id ? 'selected' : '' }}>
                            {{ $warehouse->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-primary mt-4">Filtrar</button>
            </div>
        </div>
    </form>

    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Producto</th>
                <th>Stock Inicial</th>
                <th>Entradas</th>
                <th>Salidas</th>
                <th>Ajustes</th>
                <th>Stock Final</th>
            </tr>
        </thead>
        <tbody>
            @foreach($consolidatedData as $data)
                <tr>
                    <td>{{ $data['product_name'] }}</td>
                    <td>{{ $data['initial_stock'] }}</td>
                    <td>{{ $data['incoming_transfers'] }}</td>
                    <td>{{ $data['outgoing_transfers'] }}</td>
                    <td>{{ $data['adjustments'] }}</td>
                    <td>{{ $data['final_stock'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
