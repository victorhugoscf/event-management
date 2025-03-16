@extends('layouts.main')
@section('title', 'Gerenciador de eventos')

@section('content')
<x-guest-layout>



    <div class="mb-4 text-sm text-gray-600">
        {{ __('Esta é uma área segura do aplicativo. Por favor, confirme sua senha antes de continuar.') }}
    </div>

    <x-validation-errors class="mb-4" />

    <form method="POST" action="{{ route('password.confirm') }}">
        @csrf

        <div>
            <x-label for="password" value="{{ __('Password') }}" />
            <x-input id="password" class="block mt-1 w-full" type="password" name="password" required
                autocomplete="current-password" autofocus />
        </div>

        <div class="flex justify-end mt-4">
            <x-button class="ms-4">
                {{ __('Confirmar') }}
            </x-button>
        </div>
    </form>
    </x-authentication-card>
</x-guest-layout>
@endsection