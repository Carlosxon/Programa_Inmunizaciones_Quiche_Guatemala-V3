@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h2>Barcode for {{ $product->name }}</h2>
                {!! $barcodeHtml !!}
            </div>
        </div>
    </div>
@endsection
