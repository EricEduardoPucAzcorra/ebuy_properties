@extends('welcome')

@section('content')

@php
    function getAttribute($attributes, $key) {
        return optional(
            $attributes->firstWhere('key', $key)
        )->value;
    }
@endphp

<div class="container-fluid p-0">
    <img
        src="https://images.unsplash.com/photo-1512917774080-9991f1c4c750?q=80&w=1600"
        class="w-100 object-fit-cover"
        style="height: 650px"
        alt="Hero">
</div>

@include('site.components.search_site_general')

<div class="container-fluid bg-white py-5">
    <div class="container">

        <div class="row mb-5">
            <div class="col-lg-6">
                <h2 class="section-title mb-3">
                    Encuentra tu próximo lugar ideal
                </h2>
                <p class="text-muted fs-5">
                    Propiedades cuidadosamente seleccionadas para quienes buscan confort,
                    ubicación y estilo.
                </p>
            </div>

            <div class="col-lg-6 text-lg-end">
                <div class="nav nav-pills nav-pills-custom d-inline-flex">
                    <button class="nav-link active" data-bs-toggle="pill" data-bs-target="#tab-featured">
                        Featured
                    </button>
                    <button class="nav-link" data-bs-toggle="pill" data-bs-target="#tab-sell">
                        For Sale
                    </button>
                    <button class="nav-link" data-bs-toggle="pill" data-bs-target="#tab-rent">
                        For Rent
                    </button>
                </div>
            </div>
        </div>

        <div class="row g-4">

            @forelse($properties as $property)
                <div class="col-lg-4 col-md-6">

                    <div class="card-clean h-100 d-flex flex-column">

                        <div class="img-frame">

                            @if($property->images->count())
                                <div id="carousel{{ $property->id }}"
                                     class="carousel slide h-100"
                                     data-bs-ride="carousel">

                                    <div class="carousel-inner h-100">
                                        @foreach($property->images as $i => $image)
                                            <div class="carousel-item {{ $i === 0 ? 'active' : '' }}">
                                                <img
                                                    src="{{ asset('storage/'.$image->path) }}"
                                                    alt="{{ $property->title }}">
                                            </div>
                                        @endforeach
                                    </div>

                                    @if($property->images->count() > 1)
                                        <button class="carousel-control-prev"
                                                type="button"
                                                data-bs-target="#carousel{{ $property->id }}"
                                                data-bs-slide="prev">
                                            <span class="carousel-control-prev-icon"></span>
                                        </button>

                                        <button class="carousel-control-next"
                                                type="button"
                                                data-bs-target="#carousel{{ $property->id }}"
                                                data-bs-slide="next">
                                            <span class="carousel-control-next-icon"></span>
                                        </button>
                                    @endif
                                </div>
                            @else
                                <img src="{{ asset('images/ebuy_1.png') }}" alt="No image">
                            @endif

                            <div class="floating-tag">
                                {{ $property->operation->name ?? '' }}
                            </div>

                        </div>

                        <div class="p-4 pb-3 flex-grow-1">
                            <h5 class="fw-bold mb-2">
                                {{ $property->title }}
                            </h5>

                            <div class="price-tag">
                                ${{ number_format($property->price, 0) }}
                                @if(($property->operation->name ?? '') === 'Renta')
                                    <span class="price-period">{{auto_trans('/Mes')}}</span>
                                @endif
                            </div>

                            <div class="property-type mb-2">
                                {{ $property->type->name ?? 'Property' }}
                            </div>

                            <p class="text-muted small mb-0">
                                <i class="fa fa-map-marker-alt me-1"></i>
                                {{ optional($property->address)->city->cityname ?? '' }},
                                {{ optional($property->address)->state->statename ?? '' }}
                            </p>
                        </div>

                        <div class="amenity-row">
                            @if(getAttribute($property->attributes, 'Camas'))
                                <div class="amenity-box">
                                    <i class="fa fa-bed"></i>
                                    {{ getAttribute($property->attributes, 'Camas') }} Camas
                                </div>
                            @endif

                            @if(getAttribute($property->attributes, 'Baños'))
                                <div class="amenity-box">
                                    <i class="fa fa-bath"></i>
                                    {{ getAttribute($property->attributes, 'Baños') }} Baños
                                </div>
                            @endif

                            @if(getAttribute($property->attributes, 'M²'))
                                <div class="amenity-box">
                                    <i class="fa fa-ruler-combined"></i>
                                    {{ getAttribute($property->attributes, 'M²') }} m²
                                </div>
                            @endif
                        </div>

                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-5">
                    <i class="fa fa-search fa-3x text-muted mb-3"></i>
                    <h4>{{auto_trans('No se encontraron propiedades')}}</h4>
                </div>
            @endforelse

        </div>

    </div>
</div>

@include('styles.list_properties_site')

@endsection
