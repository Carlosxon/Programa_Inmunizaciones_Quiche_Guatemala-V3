@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Dashboard</h1>
    <h1 class="text-center"><b>DISTRIBUCIÓN DE INSUMOS / PROGRAMA DE INMUNIZACIONES / QUICHÉ</b></h1>
@stop

@section('content')
    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    <h5 class="text-center">¡Bienvenido! <b>{{ Auth::user()->name }}</b> desde aquí puedes administrar tus consultas y pendientes.</h5>

    <div class="row">
        <div class="col-lg-3 col-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ $productCount }}</h3>
                    <p>vacunas/Insumos</p>
                </div>
                <div class="icon">
                    <i class="fas fa-syringe"></i>
                </div>
                <a href="{{ route('products.index') }}" class="small-box-footer">Más info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ $adjustmentCount }}</h3>
                    <p>Ajustes de Inventario</p>
                </div>
                <div class="icon">
                    <i class="fas fa-vials"></i>
                </div>
                <a href="{{ route('stock-adjustments.index') }}" class="small-box-footer">Más info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-wite">
                <div class="inner">
                    <h3>{{ $transferCount }}</h3>
                    <p>Transferencias</p>
                </div>
                <div class="icon">
                    <i class="fas fa-hospital"></i>
                </div>
                <a href="{{ route('stock-transfers.index') }}" class="small-box-footer">Más info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-wite">
                <div class="inner">
                    <h3>{{ $warehouseCount }}</h3>
                    <p>Centros de Salud</p>
                </div>
                <div class="icon">
                    <i class="fas fa-warehouse"></i>
                </div>
                <a href="{{ route('warehouses.index') }}" class="small-box-footer">Más info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
    </div>
    

    <div class="col-lg-3 col-6">
        <div class="small-box bg-wite">
            <div class="inner">
                <h3>{{ $userCount }}</h3>
                <p>Personal Médico/Usuarios</p>
            </div>
            <div class="icon">
                <i class="fas fa-user-md"></i>
            </div>
            <a href="{{ route('users.index') }}" class="small-box-footer">Más info <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>


    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Estadísticas de Stock</h3>
                </div>
                <div class="card-body">
                    <canvas id="stockChart"></canvas>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Movimientos Recientes</h3>
                </div>
                <div class="card-body">
                    <ul class="list-group">
                        @foreach($recentMovements as $movement)
                            <li class="list-group-item">
                                @if($movement['type'] == 'Transferencia')
                                    {{ $movement['product'] }} - {{ $movement['quantity'] }} Unidades Transferidas
                                    <small class="text-muted">
                                        (De {{ $movement['from'] }} a {{ $movement['to'] }})
                                    </small>
                                @else
                                    {{ $movement['product'] }} - {{ $movement['quantity'] }} Unidades Ajustadas
                                    <small class="text-muted">
                                        (En {{ $movement['warehouse'] }})
                                    </small>
                                @endif
                                <br>
                                <small class="text-muted">{{ $movement['date']->format('d/m/Y H:i') }}</small>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>




    

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Filtros</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('dashboard') }}" method="GET">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="warehouse_id">Bodega</label>
                            <select name="warehouse_id" id="warehouse_id" class="form-control">
                                <option value="">Todas las bodegas</option>
                                @foreach($warehouses as $warehouse)
                                    <option value="{{ $warehouse->id }}" {{ $selectedWarehouse && $selectedWarehouse->id == $warehouse->id ? 'selected' : '' }}>
                                        {{ $warehouse->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="product_id">Producto</label>
                            <select name="product_id" id="product_id" class="form-control">
                                <option value="">Todos los productos</option>
                                @foreach($products as $product)
                                    <option value="{{ $product->id }}" {{ $selectedProduct && $selectedProduct->id == $product->id ? 'selected' : '' }}>
                                        {{ $product->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="start_date">Fecha de inicio</label>
                            <input type="date" name="start_date" id="start_date" class="form-control" value="{{ $startDate->format('Y-m-d') }}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="end_date">Fecha de fin</label>
                            <input type="date" name="end_date" id="end_date" class="form-control" value="{{ $endDate->format('Y-m-d') }}">
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Filtrar</button>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Consolidado de Inventario</h3>
        </div>
        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Bodega</th>
                        <th>Salidas</th>
                        <th>Entradas</th>
                        <th>Cantidad inicial</th>
                        <th>Ajustes</th>
                        <th>inventario actual</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($consolidado as $productData)
                        @foreach($productData as $warehouseData)
                            <tr>
                                <td>{{ $warehouseData['product_name'] }}</td>
                                <td>{{ $warehouseData['warehouse_name'] }}</td>
                                <td>{{ $warehouseData['initial_quantity'] }}</td>
                                <td>{{ $warehouseData['entries'] }}</td>
                                <td>{{ $warehouseData['exits'] }}</td>
                                <td>{{ $warehouseData['adjustments'] }}</td>
                                <td>{{ $warehouseData['final_quantity'] }}</td>
                            </tr>
                        @endforeach
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    
@stop

@php
    $monthlyAdjustments = $monthlyAdjustments ?? [];
    $monthlyTransfers = $monthlyTransfers ?? [];
@endphp



    
@section('js')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        var monthlyAdjustments = @json($monthlyAdjustments);
        var monthlyTransfers = @json($monthlyTransfers);

        var months = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
        var adjustmentData = new Array(12).fill(0);
        var transferData = new Array(12).fill(0);

        for (var month in monthlyAdjustments) {
            adjustmentData[month - 1] = monthlyAdjustments[month];
        }

        for (var month in monthlyTransfers) {
            transferData[month - 1] = monthlyTransfers[month];
        }

        var adjustmentCtx = document.getElementById('stockChart').getContext('2d');
        var adjustmentChart = new Chart(adjustmentCtx, {
            type: 'bar',
            data: {
                labels: months,
                datasets: [{
                    label: 'Ajustes de Stock',
                    data: adjustmentData,
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }, {
                    label: 'Transferencias de Stock',
                    data: transferData,
                    backgroundColor: 'rgba(153, 102, 255, 0.2)',
                    borderColor: 'rgba(153, 102, 255, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    });
    </script>
@stop