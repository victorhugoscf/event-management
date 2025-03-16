    @extends('layouts.main')

    @section('title', 'Meus eventos')
    @section('content')
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

    <div class="col-md-10 offset-md-1 dashboard-title-container">
        <h1>Meus eventos: </h1>
    </div>

    <div class="col-md-10 offset-md-1 dashboard-container">
        @if(count(events) > 0)
        @else
        <p>VocÊ ainda não tem eventos, <a href="/events/create">Criar evento</a></p>
        @endif
    </div>

    @endsection