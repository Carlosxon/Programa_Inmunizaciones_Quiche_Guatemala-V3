@extends('adminlte::page')

@section('title', 'Products')

@section('content_header')
    <h1>Insumos/productos</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Filtros</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('products.index') }}" method="GET">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="name">Nombre:</label>
                            <input type="text" name="name" id="name" class="form-control" value="{{ request('name') }}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="category_id">Categoría:</label>
                            <select name="category_id" id="category_id" class="form-control">
                                <option value="">Todas las categorías</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="brand_id">Marca:</label>
                            <select name="brand_id" id="brand_id" class="form-control">
                                <option value="">Todas las marcas</option>
                                @foreach($brands as $brand)
                                    <option value="{{ $brand->id }}" {{ request('brand_id') == $brand->id ? 'selected' : '' }}>
                                        {{ $brand->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="min_price">Precio mínimo:</label>
                            <input type="number" name="min_price" id="min_price" class="form-control" value="{{ request('min_price') }}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="max_price">Precio máximo:</label>
                            <input type="number" name="max_price" id="max_price" class="form-control" value="{{ request('max_price') }}">
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Filtrar</button>
                <a href="{{ route('products.index') }}" class="btn btn-secondary">Limpiar filtros</a>
            </form>
        </div>
    </div>

    <a class="btn btn-success mb-3" href="{{ route('products.create') }}">Crear nuevo producto</a>

    <div class="card">
        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nombre</th>
                        <th>Descripción</th>
                        <th>Precio/Costo</th>
                        <th>Stock</th>
                        <th>Categoria</th>
                        <th>Laboratorio/Marca</th>
                        <th>Unidad</th>
                        <th>Codigo de barras</th>
                        <th width="280px">Acción</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($products as $product)
                    <tr>
                        <td>{{ $loop->index + 1 }}</td>
                        <td>{{ $product->name }}</td>
                        <td>{{ $product->description }}</td>
                        <td>{{ $product->price }}</td>
                        <td>{{ $product->stock }}</td>
                        <td>{{ $product->category->name }}</td>
                        <td>{{ $product->brand->name }}</td>
                        <td>{{ $product->unit->name }}</td>
                        <td>
                            
                        </td>
                        <td>
                            <a class="btn btn-info" href="{{ route('products.show', $product->id) }}">Ver</a>
                            <a class="btn btn-primary" href="{{ route('products.edit', $product->id) }}">Editar</a>
                            <form action="{{ route('products.destroy', $product->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{ $products->links() }}
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    <script> console.log('Hi!'); </script>
@stop
