@extends('layouts.main')
@section('title', $event->title)
@section('content')
<link rel="stylesheet" href="{{ asset('css/style.css') }}">

<div class="col-md-10 offset-md-1">
    <div class="row">
        <div id="image-container" class="col-md-6">
            <img src="{{ asset('img/events/' . $event->image) }}" class="img-fluid"
                alt="Imagem do evento: {{ $event->title }}">
        </div>
        <div id="info-container" class="col-md-6">
            <h1>{{ $event->title }}</h1>
            <p class="event-city">
                <ion-icon name="location-outline"></ion-icon> {{ $event->city }}
            </p>
            <p class="events-participants">
                <ion-icon name="people-outline"></ion-icon> {{ $event->users()->count() }} Participantes
            </p>
            <p class="event-owner">
                <ion-icon name="star-outline"></ion-icon> Organizado por {{ $event->user->name }}
            </p>
            <form action="{{ route('events.join', $event->id) }}" method="POST">
                @csrf
                <a href="{{ route('events.join', $event->id) }}" class="btn btn-primary" onclick="event.preventDefault();
                    this.closest('form').submit();" id="idevent-submit">Confirmar
                    Presença</a>
            </form>

            <h3>O evento conta com:</h3>
            <ul id="items-list">
                @php
                $items = is_array($event->items) ? $event->items : json_decode($event->items, true);
                @endphp

                @if(is_array($items) && count($items) > 0)
                @foreach($items as $item)
                <li>
                    <ion-icon name="play-outline"></ion-icon> <span>{{ $item }}</span>
                </li>
                @endforeach
                @else
                <li><span>Não há itens disponíveis.</span></li>
                @endif
            </ul>

            <h3>Data do evento:</h3>
            <ul id="date-list">
                <li>
                    <ion-icon name="calendar-outline"></ion-icon>
                    <span>{{ date('d/m/Y', strtotime($event->date)) }}</span>
                </li>
            </ul>
        </div>

        <div class="col-md-12" id="description-container">
            <h3>Sobre o evento:</h3>
            <p class="event-description">{{ $event->description }}</p>
        </div>
    </div>
</div>

@endsection