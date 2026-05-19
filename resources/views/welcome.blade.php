@extends('layouts.app')


<!--Titulo de la pestaña del navegador y el logo-->
@section('title', 'ASOPESCAHUITA')

<!--Contenido -->
@section('content')
    
    @include('partials.hero-main')<!--Contenido de la pagina -->
    @include('partials.about-us-main')
    @include('partials.last-news-publications')
@endsection