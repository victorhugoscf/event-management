@extends('layouts.main')
@section('title', 'Gerenciador de eventos')

@section('content')
<link rel="stylesheet" href="{{ asset('css/style.css') }}">



<x-guest-layout>


    <div id="formloginforgot-container">

        <br>

        @if (session('status'))
        <div class="mb-4 font-medium text-sm text-green-600">
            {{ session('status') }}
            <!-- Corrigido aqui -->
        </div>
        @endif

        <x-validation-errors class="mb-4" />


        <form method="POST" id="formloginforgot" action="{{ route('password.email') }}">
            @csrf
            <label>Esqueceu sua senha? Sem problemas. Basta nos informar seu endereço de e-mail e enviaremos por e-mail
                um
                link
                de redefinição de senha que permitirá que você escolha uma nova.</label> <br>
            <br>
            <div class="block">
                <x-label for="email" value="{{ __('Email') }}" />
                <x-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required
                    autofocus autocomplete="username" />
            </div>

            <div class="flex items-center justify-end mt-4">
                <x-button id="login" class="btn btn-primary">
                    {{ __('Link de redefinição de senha de e-mail') }}
                </x-button>
            </div>
        </form>

</x-guest-layout>

</div>
@endsection