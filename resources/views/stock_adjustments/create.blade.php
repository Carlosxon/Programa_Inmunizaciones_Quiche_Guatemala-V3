@extends('adminlte::page')

@section('title', 'Crear Ajuste de Stock')

@section('content_header')
    <h1>Crear Ajuste de Stock</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('stock-adjustments.store') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="product_id">Producto</label>
                            <select name="product_id" id="product_id" class="form-control" required>
                                @foreach ($products as $product)
                                    <option value="{{ $product->id }}">{{ $product->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="warehouse_id">Almacén</label>
                            <select name="warehouse_id" id="warehouse_id" class="form-control" required>
                                @foreach ($warehouses as $warehouse)
                                    <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="adjustment_quantity">Cantidad</label>
                            <input type="number" name="adjustment_quantity" id="adjustment_quantity" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="reason">Razón</label>
                            <textarea name="reason" id="reason" class="form-control"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Crear Ajuste</button>
                    </form>
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
