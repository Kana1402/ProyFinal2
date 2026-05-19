@extends('layouts.app')


<!--Titulo de la pestaña del navegador y el logo-->
@section('title', 'ASOPESCAHUITA')

<!--Contenido -->
@section('content')
    @include('partials.hero-about-us')
    @include('partials.member')
@endsection