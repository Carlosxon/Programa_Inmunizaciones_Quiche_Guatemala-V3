@extends('adminlte::page')

@section('title', 'Transferencias Pendientes e Inventario' . ($warehouse ? ' de ' . $warehouse->name : ''))

@section('content_header')
    <h1>Transferencias Pendientes e Inventario{{ $warehouse ? ' para ' . $warehouse->name : '' }}</h1>
@stop

@section('content')
    

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h3>Transferencias Pendientes</h3>

                    <!-- Mensaje de éxito cuando se acepta la transferencia -->
                    @if (session('success'))
                        <div class="alert alert-success">
                            {!! session('success') !!}
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if ($transferencias->isEmpty())
                        <p>No hay transferencias pendientes{{ $warehouse ? ' para esta bodega' : '' }}.</p>
                    @else
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Producto</th>
                                    <th>Cantidad</th>
                                    <th>Fecha de Transferencia</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($transferencias as $transferencia)
                                    @foreach ($transferencia->transfers as $transfer)
                                        <tr>
                                            <td>{{ $transfer->product->name ?? 'Producto no disponible' }}</td>
                                            <td>{{ $transfer->quantity }}</td>
                                            <td>{{ \Carbon\Carbon::parse($transferencia->transfer_date)->format('d/m/Y') }}</td>
                                            <td>
                                                @if($transferencia->is_received)
                                                    <span class="badge badge-success">Aceptada</span>
                                                @else
                                                    <span class="badge badge-warning">Pendiente</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if(!$transferencia->is_received)
                                                    <form action="{{ route('transferencias.aceptar', $transferencia->id) }}" method="POST">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit" class="btn btn-success">Aceptar Transferencia</button>
                                                    </form>
                                                @else
                                                    <button class="btn btn-secondary" disabled>Aceptada</button>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Inventario de la sucursal -->
    @if($warehouse)
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h3>Inventario Actual de {{ $warehouse->name }}</h3>
                    
                    
                    
                    <form action="{{ route('inventory.realizar-salida') }}" method="POST" id="bulk-exit-form" class="mb-3">
                        @csrf
                        <input type="hidden" name="warehouse_id" value="{{ $warehouse->id }}">
                        <div class="form-group">
                            <label for="justification">Justificación</label>
                            <textarea name="justification" id="justification" class="form-control" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-warning">Registrar Salida</button>
                        <div class="mb-3">
                        <a href="{{ route('inventory.salidas', $warehouse->id) }}" class="btn btn-primary">Ver Salidas de Inventario</a>
                    </div>
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th><input type="checkbox" id="select-all"></th>
                                    <th>Producto</th>
                                    <th>Cantidad Disponible</th>
                                    <th>Cantidad a Retirar</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($inventario as $item)
                                    <tr>
                                        <td>
                                            <input type="checkbox" name="selected_items[]" value="{{ $item->id }}">
                                        </td>
                                        <td>{{ $item->product->name }}</td>
                                        <td>{{ $item->quantity }}</td>
                                        <td>
                                            <input type="number" name="quantities[{{ $item->id }}]" min="1" max="{{ $item->quantity }}" class="form-control" disabled>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endif
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
<script>
    $(document).ready(function() {
        $('#select-all').click(function(event) {
            $(':checkbox').prop('checked', this.checked);
            $('input[type="number"]').prop('disabled', !this.checked);
        });

        $('input[type="checkbox"]').change(function() {
            var $quantityInput = $(this).closest('tr').find('input[type="number"]');
            $quantityInput.prop('disabled', !this.checked);
            if (!this.checked) {
                $quantityInput.val('');
            }
        });

    });
</script>
@stop