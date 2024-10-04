@extends('adminlte::page')

@section('title', 'Formulario de Salida de Inventario')

@section('content_header')
    <h1>Formulario de Salida de Inventario</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('inventory.process-exit') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="justification">Justificación</label>
                    <textarea name="justification" id="justification" rows="3" class="form-control" required></textarea>
                </div>
                
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Producto</th>
                            <th>Cantidad Disponible</th>
                            <th>Cantidad a Retirar</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($items as $item)
                            <tr>
                                <td>{{ $item->product->name }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td>
                                    <input type="number" name="quantities[{{ $item->id }}]" 
                                           value="{{ $quantities[$item->id] ?? 0 }}" 
                                           min="1" max="{{ $item->quantity }}" 
                                           class="form-control" required>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                
                <button type="submit" class="btn btn-primary">Procesar Salida</button>
            </form>
        </div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    <script>
        // Aquí puedes agregar cualquier JavaScript necesario
    </script>
@stop