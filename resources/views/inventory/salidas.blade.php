@extends('adminlte::page')

@section('title', 'Salidas de Inventario')

@section('content_header')
    <h1>Salidas de Inventario</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Filtrar Salidas</h3>
        </div>
        <div class="card-body">
            <form id="salidaFilterForm">
                <div class="form-row">
                    <div class="form-group col-md-4">
                        <label for="date">Por Día</label>
                        <input type="date" class="form-control" id="date" name="date">
                    </div>
                    <div class="form-group col-md-4">
                        <label for="month">Por Mes</label>
                        <input type="month" class="form-control" id="month" name="month">
                    </div>
                    <div class="form-group col-md-4">
                        <label for="year">Por Año</label>
                        <input type="number" class="form-control" id="year" name="year" min="2000" max="2099" step="1">
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Filtrar</button>
            </form>
        </div>
    </div>

    <div class="card mt-4">
        <div class="card-header">
            <h3 class="card-title">Resultados</h3>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-striped" id="salidasTable">
                <thead>
                    <tr>
                        <th>Número de Salida</th>
                        <th>Fecha</th>
                        <th>Usuario</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($salidas as $salida)
                        <tr>
                            <td>{{ $salida->exit_number }}</td>
                            <td>{{ $salida->exit_date }}</td>
                            <td>{{ $salida->user->name }}</td>
                            <td>
                                <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#salidaModal{{ $salida->id }}">
                                    Ver Detalles
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    @foreach($salidas as $salida)
        <div class="modal fade" id="salidaModal{{ $salida->id }}" tabindex="-1" role="dialog" aria-labelledby="salidaModalLabel{{ $salida->id }}" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="salidaModalLabel{{ $salida->id }}">Detalles de Salida #{{ $salida->exit_number }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p><strong>Fecha:</strong> {{ $salida->exit_date }}</p>
                        <p><strong>Usuario:</strong> {{ $salida->user->name }}</p>
                        <p><strong>Descripción:</strong> {{ $salida->description }}</p>
                        <h6>Productos:</h6>
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Producto</th>
                                    <th>Cantidad</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($salida->items as $item)
                                    <tr>
                                        <td>{{ $item->inventoryItem->product->name }}</td>
                                        <td>{{ $item->quantity }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
@stop

@section('js')
<script>
    $(document).ready(function() {
        $('#salidaFilterForm').submit(function(e) {
            e.preventDefault();
            var date = $('#date').val();
            var month = $('#month').val();
            var year = $('#year').val();

            $.ajax({
                url: '{{ route("inventory.filtrarSalidas") }}',
                method: 'GET',
                data: {
                    date: date,
                    month: month,
                    year: year
                },
                success: function(response) {
                    var tbody = $('#salidasTable tbody');
                    tbody.empty();
                    response.forEach(function(salida) {
                        var row = `
                            <tr>
                                <td>${salida.exit_number}</td>
                                <td>${salida.exit_date}</td>
                                <td>${salida.user.name}</td>
                                <td>
                                    <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#salidaModal${salida.id}">
                                        Ver Detalles
                                    </button>
                                </td>
                            </tr>
                        `;
                        tbody.append(row);
                    });
                },
                error: function(xhr) {
                    console.log(xhr.responseText);
                }
            });
        });
    });
</script>
@stop