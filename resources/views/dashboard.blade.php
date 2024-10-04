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
            <div class="small-box bg-white">
                <div class="inner">
                    <h3>{{ $productCount }}</h3>
                    <p>Productos</p>
                </div>
                <div class="icon">
                    <i class="fas fa-box"></i>
                </div>
                <a href="{{ route('products.index') }}" class="small-box-footer">Más información <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box bg-white">
                <div class="inner">
                    <h3>{{ $adjustmentCount }}</h3>
                    <p>Ajustes de Stock</p>
                </div>
                <div class="icon">
                    <i class="fas fa-adjust"></i>
                </div>
                <a href="{{ route('stock-adjustments.index') }}" class="small-box-footer">Más información <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box bg-white">
                <div class="inner">
                    <h3>{{ $transferCount }}</h3>
                    <p>Transferencias de Stock</p>
                </div>
                <div class="icon">
                    <i class="fas fa-exchange-alt"></i>
                </div>
                <a href="{{ route('stock-transfers.index') }}" class="small-box-footer">Más información <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box bg-white">
                <div class="inner">
                    <h3>{{ $warehouseCount }}</h3>
                    <p>Almacenes</p>
                </div>
                <div class="icon">
                    <i class="fas fa-warehouse"></i>
                </div>
                <a href="{{ route('warehouses.index') }}" class="small-box-footer">Más información <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box bg-white">
                <div class="inner">
                    <h3>{{ $userCount }}</h3>
                    <p>Usuarios</p>
                </div>
                <div class="icon">
                    <i class="fas fa-users"></i>
                </div>
                <a href="{{ route('users.index') }}" class="small-box-footer">Más información <i class="fas fa-arrow-circle-right"></i></a>
            </div>
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
                    <ul>
                        <li>Producto A - 10 Unidades Transferidas</li>
                        <li>Producto B - 5 Unidades Ajustadas</li>
                        <li>Producto C - 20 Unidades Transferidas</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="{{ asset('css/admin_custom.css') }}">
@stop
<canvas id="stockChart"></canvas>
    
@section('js')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="{{ asset('js/stock-chart.js') }}"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        var monthlyAdjustments = ($monthlyAdjustments);
        var monthlyTransfers = ($monthlyTransfers);

        var adjustmentCtx = document.getElementById('stockChart').getContext('2d');
        var adjustmentChart = new Chart(adjustmentCtx, {
            type: 'bar',
            data: {
                labels: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
                datasets: [{
                    label: 'Ajustes de Stock',
                    data: monthlyAdjustments,
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }, {
                    label: 'Transferencias de Stock',
                    data: monthlyTransfers,
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
