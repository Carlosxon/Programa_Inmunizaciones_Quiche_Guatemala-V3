@extends('adminlte::page')

@section('title', 'Editar Permiso')

@section('content_header')
    <h1>Editar Permiso</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('permissions.update', $permission->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label for="name">Nombre del Permiso</label>
                    <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $permission->name) }}" required>
                    @error('name')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <button type="submit" class="btn btn-primary">Actualizar</button>
                <a href="{{ route('permissions.index') }}" class="btn btn-secondary">Cancelar</a>
            </form>
        </div>
    </div>
@stop
    