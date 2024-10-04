@extends('adminlte::page')

@section('title', 'Inventario de ' . $warehouse->name)

@section('content_header')
    <h1>Inventario de {{ $warehouse->name }}</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h3>Productos en Inventario</h3>
                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Producto</th>
                                <th>Cantidad</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($products as $product)
                                <tr>
                                    <td>{{ $product->name }}</td>
                                    <td>{{ $product->pivot->quantity }}</td>
                                    <td>
                                        <!-- Botón para aceptar transferencias pendientes -->
                                        <a href="{{ route('stock-transfers.accept', ['warehouse' => $warehouse->id, 'product' => $product->id]) }}" class="btn btn-sm btn-info">Aceptar Transferencia</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center">No hay productos en inventario</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    <script> console.log('Vista de inventario cargada.'); </script>
@stop
