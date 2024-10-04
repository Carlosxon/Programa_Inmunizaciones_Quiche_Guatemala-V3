@extends('adminlte::page')

@section('title', 'Editar Configuración')

@section('content_header')
    <h1>Editar Configuración</h1>
@stop

@section('content')
    <form action="{{ route('system-settings.update', $setting->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="key">Clave</label>
            <input type="text" name="key" class="form-control" value="{{ $setting->key }}" disabled>
        </div>

        <div class="form-group">
            <label for="value">Valor</label>
            <textarea name="value" class="form-control">{{ $setting->value }}</textarea>
        </div>

        <button type="submit" class="btn btn-success">Actualizar</button>
    </form>
@stop
