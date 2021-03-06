@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Bem vindo, {{ $user->name }}.</h1>
@stop

@section('content')
    @if(session()->has('message'))
        <div class="alert {{ session('saved') ? 'alert-success' : 'alert-error' }} alert-dismissible"
             role="alert">
            {{ session()->get('message') }}

            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <p>Você está conectado como {{ $user->roles->pluck('friendly_name')[0] }}.</p>

    @if($user->isCoordinator())
        @include('coordinator.home')
    @elseif($user->isAdmin())
        @include('admin.home')
    @elseif($user->isCompany())
        @include('company.home')
    @elseif($user->isStudent())
        @include('student.home')
    @endif
@stop
