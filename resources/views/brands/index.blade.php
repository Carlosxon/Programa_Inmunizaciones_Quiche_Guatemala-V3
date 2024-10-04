@extends('adminlte::page')

@section('title', 'Laboratorios/Marcas')

@section('content_header')
    <h1>Laboratorios/Marcas</h1>
@stop

@section('content')
    <div class="row mb-3">
        <div class="col-md-6">
            <a class="btn btn-success" href="{{ route('brands.create') }}">Crear Nuevo Laboratorio/Marca</a>
        </div>
        <div class="col-md-6">
            <form action="{{ route('brands.index') }}" method="GET" class="form-inline float-right">
                <input type="text" name="search" class="form-control mr-2" placeholder="Buscar laboratorio/marca" value="{{ request('search') }}">
                <button type="submit" class="btn btn-primary">Buscar</button>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nombre</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($brands as $brand)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $brand->name }}</td>
                        <td>
                            <a class="btn btn-primary btn-sm" href="{{ route('brands.edit', $brand->id) }}">Editar</a>
                            <form action="{{ route('brands.destroy', $brand->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro de que quieres eliminar este laboratorio/marca?')">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{ $brands->links() }}
@stop

