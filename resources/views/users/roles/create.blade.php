@extends('adminlte::page')

@section('title', 'Crear Rol')

@section('content_header')
    <h1>Crear Rol</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('roles.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="name">Nombre del Rol</label>
                    <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}" required>
                    @error('name')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <button type="submit" class="btn btn-primary">Guardar</button>
                <a href="{{ route('roles.index') }}" class="btn btn-secondary">Cancelar</a>
                 <a href="{{ route('roles.index') }}" class="btn btn-primary">Ver Roles</a>
                 <a href="{{ route('roles.create') }}" class="btn btn-primary">Crear Rol</a>

                 

            </form>
        </div>
    </div>
@stop


