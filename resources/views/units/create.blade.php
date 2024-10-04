@extends('adminlte::page')

@section('title', 'Create Unit')

@section('content_header')
    <h1>Crear Unidad</h1>
@stop

@section('content')
    <form action="{{ route('units.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="name">Nombre:</label>
            <input type="text" name="name" class="form-control" id="name">
        </div>
        <button type="submit" class="btn btn-success">Guardar</button>
    </form>
@stop

    