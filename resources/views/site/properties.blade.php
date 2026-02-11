@extends('welcome')

@section('content')

<section class="hero-custom">
    <div class="container">
        <div class="row justify-content-center text-center">
            <div class="col-lg-10">
                <h1 class="hero-title">{{auto_trans('Encuentra tu próximo lugar ideal')}}</h1>
                <p class="hero-subtitle">{{auto_trans('Propiedades seleccionadas para quienes buscan confort y estilo.')}}</p>
                @include('site.components.search_site_general')
            </div>
        </div>
    </div>
</section>

<section class="py-5 bg-white">
    <div class="container">
        @include('site.components.list_properties')
    </div>
</section>

@endsection
