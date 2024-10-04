@extends('adminlte::page')

@section('title', 'Edit Unit')

@section('content_header')
    <h1>Editar Unidad</h1>
@stop

@section('content')
    <form action="{{ route('units.update', $unit->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="name">Nombre:</label>
            <input type="text" name="name" class="form-control" id="name" value="{{ $unit->name }}">
        </div>
        <button type="submit" class="btn btn-success">Guardar</button>
    </form>
@stop

 