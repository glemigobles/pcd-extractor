@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Wybierz moduł</div>

                <div class="card-body flex wrap center-h" id="modules">
                    @hasrole('administrator')
                    <a href="{{ route('uzytkownicy') }}">
                    <i class="fas fa-users big flex column center-v center-h">
                    <p>użytkownicy</p>    
                    </i>
                    </a>
                    @endhasrole
                    <a href="{{ route('extractor') }}">
                    <i class="fas fa-database big flex column center-v center-h">
                    <p>CDExtractor</p>    
                    </i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
