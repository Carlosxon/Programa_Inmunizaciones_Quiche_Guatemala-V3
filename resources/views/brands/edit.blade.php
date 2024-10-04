@extends('adminlte::page')

@section('title', 'Edit Brand')

@section('content_header')
    <h1>Editar Laboratorio/Marca</h1>
@stop

@section('content')
    <form action="{{ route('brands.update', $brand->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="name">Nombre:</label>
            <input type="text" name="name" class="form-control" id="name" value="{{ $brand->name }}">
        </div>
        <button type="submit" class="btn btn-success">Guardar</button>
    </form>
@stop



 