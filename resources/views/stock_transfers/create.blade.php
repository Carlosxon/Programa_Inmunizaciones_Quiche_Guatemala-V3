@extends('adminlte::page')

@section('title', 'Crear Transferencia de Stock')

@section('content_header')
    <h1>Crear Transferencia de Stock</h1>
@stop

@section('content')
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('stock-transfers.store') }}" method="POST">
        @csrf
        <!-- Campos del formulario -->
        <div class="form-group">
            <label for="from_warehouse_id">Desde Bodega</label>
            <select name="from_warehouse_id" id="from_warehouse_id" class="form-control" required>
                <option value="">Seleccione una bodega</option>
                @foreach($warehouses as $warehouse)
                    <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="to_warehouse_id">Hacia Bodega</label>
            <select name="to_warehouse_id" id="to_warehouse_id" class="form-control" required>
                <option value="">Seleccione una bodega</option>
                @foreach($warehouses as $warehouse)
                    <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="transfer_date">Fecha de Transferencia</label>
            <input type="date" name="transfer_date" id="transfer_date" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Productos</label>
            @foreach($products as $product)
                <div class="form-check">
                    <input type="checkbox" class="form-check-input product-checkbox" id="product-{{ $product->id }}" name="selected_products[]" value="{{ $product->id }}">
                    <label class="form-check-label" for="product-{{ $product->id }}">
                        {{ $product->name }}
                        (Inventario disponible: <span id="inventory-{{ $product->id }}">Cargando...</span>)
                    </label>
                    <!-- Aquí es donde debes agregar o modificar el campo de cantidad -->
                    <input type="number" name="quantities[{{ $product->id }}]" class="form-control quantity-input" placeholder="Cantidad" min="1" style="display: none;">
                </div>
            @endforeach
        </div>
        <button type="submit" class="btn btn-primary">Crear Transferencia</button>
    </form>
@stop

@section('js')
<script>
$(document).ready(function() {
    $('#from_warehouse_id').change(function() {
        var warehouseId = $(this).val();
        if(warehouseId) {
            $.ajax({
                url: '{{ route("get-warehouse-inventory") }}',
                type: 'GET',
                data: { warehouse_id: warehouseId },
                success: function(data) {
                    $.each(data, function(productId, quantity) {
                        $('#inventory-' + productId).text(quantity);
                    });
                },
                error: function() {
                    $('[id^=inventory-]').text('Error al cargar');
                }
            });
        } else {
            $('[id^=inventory-]').text('Cargando...');
        }
    });

    $('.product-checkbox').change(function() {
        var quantityInput = $(this).closest('.form-check').find('.quantity-input');
        if(this.checked) {
            quantityInput.show().prop('required', true);
        } else {
            quantityInput.hide().val('').prop('required', false);
        }
    });
});
</script>
@stop
