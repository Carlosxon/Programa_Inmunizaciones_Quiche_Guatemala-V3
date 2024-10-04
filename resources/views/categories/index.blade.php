@extends('adminlte::page')

@section('title', 'Categorías')

@section('content_header')
    <h1>Categorías</h1>
@stop

@section('content')
    <div class="row mb-3">
        <div class="col-md-6">
            <a class="btn btn-success" href="{{ route('categories.create') }}">Crear Nueva Categoría</a>
        </div>
        <div class="col-md-6">
            <form action="{{ route('categories.index') }}" method="GET" class="form-inline float-right">
                <input type="text" name="search" class="form-control mr-2" placeholder="Buscar categoría" value="{{ request('search') }}">
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
                    @foreach ($categories as $category)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $category->name }}</td>
                        <td>
                            <a class="btn btn-primary btn-sm" href="{{ route('categories.edit', $category->id) }}">Editar</a>
                            <form action="{{ route('categories.destroy', $category->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro de que quieres eliminar esta categoría?')">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{ $categories->links() }}
@stop
