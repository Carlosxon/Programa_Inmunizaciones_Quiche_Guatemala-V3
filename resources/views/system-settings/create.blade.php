@extends('adminlte::page')

@section('title', 'Crear Configuración')

@section('content_header')
    <h1>Crear Configuración</h1>
@stop

@section('content')
    <form action="{{ route('system-settings.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="key">Clave</label>
            <input type="text" name="key" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="value">Valor</label>
            <textarea name="value" class="form-control" required></textarea>
        </div>

        <button type="submit" class="btn btn-success">Guardar</button>
    </form>
@stop
