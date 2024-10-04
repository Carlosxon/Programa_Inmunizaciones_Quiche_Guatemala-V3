@extends('adminlte::page')

@section('title', 'Print Barcode')

@section('content_header')
    <h1>Print Barcode</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Barcode for {{ $product->name }}</h3>
        </div>
        <div class="card-body text-center">
            {!! $product->generateBarcode() !!}
            <br>
            <strong>{{ $product->barcode }}</strong>
        </div>
        <div class="card-footer text-center">
            <a class="btn btn-primary" href="{{ route('products.index') }}">Back to Product List</a>
            
        </div>
    </div>
@stop
