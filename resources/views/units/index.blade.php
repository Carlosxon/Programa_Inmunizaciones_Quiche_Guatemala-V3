@extends('adminlte::page')

@section('title', 'Unidades')

@section('content_header')
    <h1>Unidades</h1>
@stop

@section('content')
    <div class="row mb-3">
        <div class="col-md-6">
            <a class="btn btn-success" href="{{ route('units.create') }}">Crear Nueva Unidad</a>
        </div>
        <div class="col-md-6">
            <form action="{{ route('units.index') }}" method="GET" class="form-inline float-right">
                <input type="text" name="search" class="form-control mr-2" placeholder="Buscar unidad" value="{{ request('search') }}">
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
                    @foreach ($units as $unit)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $unit->name }}</td>
                        <td>
                            <a class="btn btn-primary btn-sm" href="{{ route('units.edit', $unit->id) }}">Editar</a>
                            <form action="{{ route('units.destroy', $unit->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro de que quieres eliminar esta unidad?')">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{ $units->links() }}
@stop
