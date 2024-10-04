@extends('adminlte::page')

@section('title', 'Ver Bodega')

@section('content_header')
    <h1>Ver Bodega</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="form-group">
                        <label>ID:</label>
                        <p>{{ $warehouse->id }}</p>
                    </div>
                    <div class="form-group">
                        <label>Nombre:</label>
                        <p>{{ $warehouse->name }}</p>
                    </div>
                    <div class="form-group">
                        <label>Ubicación:</label>
                        <p>{{ $warehouse->location }}</p>
                    </div>
                    <div class="form-group">
                        <label>Tipo:</label>
                        <p>{{ $warehouse->type }}</p>
                    </div>
                    <div class="form-group">
                        <label>Estado:</label>
                        <p>{{ $warehouse->status }}</p>
                    </div>
                    <a href="{{ route('warehouses.index') }}" class="btn btn-secondary">Volver</a>
                </div>
            </div>
        </div>
    </div>
@stop
