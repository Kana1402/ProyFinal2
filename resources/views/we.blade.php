@extends('layouts.app')


<!--Titulo de la pestaña del navegador y el logo-->
@section('title', 'ASOPESCAHUITA')

<!--Contenido -->
@section('content')

    <div style="padding-top: 100px;"> <!-- Espacio para el navbar fijo -->
        @include('partials.hero-about-us')
        @include('partials.member')
    </div>
    
@endsection