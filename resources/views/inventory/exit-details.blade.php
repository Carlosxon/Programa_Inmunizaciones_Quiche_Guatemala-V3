@extends('adminlte::page')

@section('title', 'Detalles de Salida de Inventario')

@section('content_header')
    <h1>Detalles de Salida de Inventario #{{ $exit->id }}</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <h5>Información General</h5>
            <p><strong>Fecha:</strong> {{ $exit->exit_date }}</p>
            <p><strong>Bodega:</strong> {{ $exit->warehouse->name }}</p>
            <p><strong>Usuario:</strong> {{ $exit->user->name }}</p>
            <p><strong>Justificación:</strong> {{ $exit->justification }}</p>

            <h5>Productos Retirados</h5>
            <table class="table">
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Cantidad</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($exit->items as $item)
                        <tr>
                            <td>{{ $item->product->name }}</td>
                            <td>{{ $item->quantity }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@stop