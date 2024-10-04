@extends('adminlte::page')

@section('title', 'Crear Permiso')

@section('content_header')
    <h1>Crear Permiso</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('permissions.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="name">Nombre del Permiso</label>
                    <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}" required>
                    @error('name')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <button type="submit" class="btn btn-primary">Guardar</button>
                <a href="{{ route('permissions.index') }}" class="btn btn-secondary">Cancelar</a>
                <a href="{{ route('permissions.index') }}" class="btn btn-primary">Ver Permisos</a>
                <a href="{{ route('permissions.create') }}" class="btn btn-primary">Crear Permiso</a>

            </form>
        </div>
    </div>
@stop


