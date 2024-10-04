@extends('adminlte::page')

@section('title', 'Edit Category')

@section('content_header')
    <h1>Editar Categorias</h1>
@stop

@section('content')
    <form action="{{ route('categories.update', $category->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="name">Nombre:</label>
            <input type="text" name="name" class="form-control" id="name" value="{{ $category->name }}">
        </div>
        <button type="submit" class="btn btn-success">Guardar</button>
    </form>
@stop

 