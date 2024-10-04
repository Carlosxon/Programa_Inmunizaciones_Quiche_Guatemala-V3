@extends('adminlte::page')

@section('title', 'Transferencias de Stock')

@section('content_header')
    <h1>Transferencias de Stock</h1>
@stop

@section('content')
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <a href="{{ route('stock-transfers.create') }}" class="btn btn-primary">Crear Nueva Transferencia</a>
                <form action="{{ route('stock-transfers.index') }}" method="GET" class="form-inline">
                    <input type="text" name="search" class="form-control mr-sm-2" placeholder="Buscar transferencias..." value="{{ request('search') }}">
                    <button type="submit" class="btn btn-outline-success my-2 my-sm-0">Buscar</button>
                </form>
            </div>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Desde Almacén</th>
                        <th>Hacia Almacén</th>
                        <th>Producto</th>
                        <th>Cantidad</th>
                        <th>Fecha</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($stockTransfers as $transfer)
                        <tr>
                            <td>{{ $transfer->id }}</td>
                            <td>{{ $transfer->fromWarehouse->name }}</td>
                            <td>{{ $transfer->toWarehouse->name }}</td>
                            <td>{{ $transfer->product->name }}</td>
                            <td>{{ $transfer->quantity }}</td>
                            <td>{{ $transfer->transfer_date }}</td>
                            <td>
                                @if($transfer->is_received)
                                    <span class="badge badge-success">Recibido</span>
                                @elseif($transfer->status == 'cancelled')
                                    <span class="badge badge-danger">Cancelado</span>
                                @else
                                    <span class="badge badge-warning">Pendiente</span>
                                @endif
                            </td>
                            <td>
                                <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#transferModal{{ $transfer->id }}">
                                    Ver Detalles
                                </button>
                                @if(!$transfer->is_received && $transfer->status != 'cancelled')
                                    <form action="{{ route('stock-transfers.cancel', $transfer->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="btn btn-warning btn-sm" onclick="return confirm('¿Estás seguro de que quieres cancelar esta transferencia?')">
                                            Cancelar
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center">No se encontraron transferencias</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            {{ $stockTransfers->appends(request()->query())->links() }}
        </div>
    </div>

    @foreach($stockTransfers as $transfer)
        <div class="modal fade" id="transferModal{{ $transfer->id }}" tabindex="-1" role="dialog" aria-labelledby="transferModalLabel{{ $transfer->id }}" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="transferModalLabel{{ $transfer->id }}">Detalles de la Transferencia</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p><strong>ID:</strong> {{ $transfer->id }}</p>
                        <p><strong>Desde:</strong> {{ $transfer->fromWarehouse->name }}</p>
                        <p><strong>Hacia:</strong> {{ $transfer->toWarehouse->name }}</p>
                        <p><strong>Producto:</strong> {{ $transfer->product->name }}</p>
                        <p><strong>Cantidad:</strong> {{ $transfer->quantity }}</p>
                        <p><strong>Fecha:</strong> {{ $transfer->transfer_date }}</p>
                        <p><strong>Estado:</strong> 
                            @if($transfer->is_received)
                                <span class="badge badge-success">Recibido</span>
                            @elseif($transfer->status == 'cancelled')
                                <span class="badge badge-danger">Cancelado</span>
                            @else
                                <span class="badge badge-warning">Pendiente</span>
                            @endif
                        </p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    <script>
        $(document).ready(function() {
            // Cerrar automáticamente las alertas después de 5 segundos
            setTimeout(function() {
                $('.alert').alert('close');
            }, 5000);
        });
    </script>
@stop