@extends('layouts.app')

@section('content')
<div class="container" id="app">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header flex space-btw">
                    <div id="module-title">
                        <i class="fas fa-database"></i>CDExtractor
                    </div>
                    <a href="{{ route('home') }}"><i class="far fa-window-close"></i></a> 
                </div>
                <div class="card-body flex wrap center-h" id="modules">
                    <div class="col-md-12">
                        <extractor csrf="{{ csrf_token() }}"></extractor>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection