@extends('layouts.main')
@section('title','Criar evento')
@section('content')
<link rel="stylesheet" href="{{ asset('css/style.css') }}">


<div id="event-create-container" class="col-md-6 offset-md-3">

    <h1>Crie o seu evento</h1>
    <form action="{{ route('events.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="form-group">
            <label for="image">Imagem do evento:</label>
            <input type="file" id="image" name="image" class="from-control-file">
        </div>

        <div class="form-group">
            <label for="title">Evento:</label>
            <input type="text" class="form-control" name="title" id="title" placeholder="Nome do evento">
        </div>

        <div class="form-group">
            <label for="title">Cidade:</label>
            <input type="text" class="form-control" name="city" id="city" placeholder="Local do evento">
        </div>
        <div class="form-group">
            <label for="title">O evento é privado?:</label>
            <select name="private" id="private" class="form-control">
                <option value="0">Não</option>
                <option value="1">Sim</option>
            </select>
        </div>
        <div class="form-group">
            <label for="title">Descrição:</label>
            <textarea name="description" id="description" class="form-control"
                placeholder="O que vai acontecer no evento?"></textarea>
        </div>
        <div class="form-group">
            <label for="date">Data do evento:</label>
            <input type="date" class="form-control" name="date" id="date" placeholder="Data do evento">
        </div>



        <div class="form-group">
            <label for="title">Adicione itens de infraestrutura:</label>
            <div id="form-group">
                <input type="checkbox" name="items[]" value="Cadeiras"> Cadeiras
            </div>
            <div id="form-group">
                <input type="checkbox" name="items[]" value="Palco"> Palco
            </div>
            <div id="form-group">
                <input type="checkbox" name="items[]" value="Cerveja grátis"> Cerveja grátis
            </div>
            <div id="form-group">
                <input type="checkbox" name="items[]" value="Open food"> Open food
            </div>
            <div id="form-group">
                <input type="checkbox" name="items[]" value="Brindes"> Brindes
            </div>




            <input type="submit" class="btn btn-primary" value="Criar Evento">
    </form>

</div>


@endsection