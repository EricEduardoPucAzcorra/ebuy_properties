@extends('welcome')

@section('content')

<div class="container-fluid header bg-white p-0">
    <div class="row g-0">
        <div class="col-12 animated fadeIn">
            <img class="img-fluid w-100 object-fit-cover"
                 src="https://images.unsplash.com/photo-1512917774080-9991f1c4c750?q=80&w=1170&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D"
                 alt="Header"
                 style="height: 650px;"> </div>
    </div>
</div>

@include('site.components.search_site_general')

<!-- Property List Start -->
<div class="container-fluid">
    <div class="container">
        <!-- Header y Tabs -->
        <div class="row g-0 gx-5 align-items-end">
            <div class="col-lg-6">
                <div class="text-start mx-auto mb-5 wow slideInLeft" data-wow-delay="0.1s">
                    <h1 class="mb-3">Property Listing</h1>
                    <p>Explora nuestra selección de propiedades cuidadosamente seleccionadas para ti.</p>
                </div>
            </div>
            <div class="col-lg-6 text-start text-lg-end wow slideInRight" data-wow-delay="0.1s">
                <ul class="nav nav-pills d-inline-flex justify-content-end mb-5">
                    <li class="nav-item me-2">
                        <a class="btn btn-outline-primary active" data-bs-toggle="pill" href="#tab-featured">Featured</a>
                    </li>
                    <li class="nav-item me-2">
                        <a class="btn btn-outline-primary" data-bs-toggle="pill" href="#tab-sell">For Sell</a>
                    </li>
                    <li class="nav-item me-0">
                        <a class="btn btn-outline-primary" data-bs-toggle="pill" href="#tab-rent">For Rent</a>
                    </li>
                </ul>
            </div>
        </div>

        @php
            $defaultImage = asset('img/default-property.jpg');
        @endphp

        <div class="tab-content">

            {{-- Featured --}}
            <div id="tab-featured" class="tab-pane fade show p-0 active">
                <div class="row g-4">
                    @forelse($properties as $property)
                        <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.1s">
                            <div class="property-item rounded overflow-hidden">
                                <div class="position-relative overflow-hidden">
                                    <a href="#">
                                        <img class="img-fluid" src="https://img10.naventcdn.com/avisos/resize/18/01/48/22/02/08/1200x1200/1580752714.jpg?isFirstImage=true" alt="{{ $property->title }}">
                                    </a>
                                    <div class="bg-primary rounded text-white position-absolute start-0 top-0 m-4 py-1 px-3">
                                        {{ $property->typeOperation->name ?? 'Venta' }}
                                    </div>
                                    <div class="bg-white rounded-top text-primary position-absolute start-0 bottom-0 mx-4 pt-1 px-3">
                                        {{ $property->typeProperty->name ?? 'Propiedad' }}
                                    </div>
                                </div>
                                <div class="p-4 pb-0">
                                    <h5 class="text-primary mb-3">${{ number_format($property->price, 0) }}</h5>
                                    <a class="d-block h5 mb-2" href="#">{{ $property->title }}</a>
                                    <p><i class="fa fa-map-marker-alt text-primary me-2"></i>
                                        {{ optional($property->address)->street ?? '' }}, {{ optional($property->address)->city ?? '' }}
                                    </p>
                                </div>
                                <div class="d-flex border-top">
                                    <small class="flex-fill text-center border-end py-2">
                                        <i class="fa fa-ruler-combined text-primary me-2"></i>
                                        {{ $property->square_meters ?? 'N/A' }} Sqft
                                    </small>
                                    <small class="flex-fill text-center border-end py-2">
                                        <i class="fa fa-bed text-primary me-2"></i>
                                        {{ $property->bedrooms ?? 'N/A' }} Bed
                                    </small>
                                    <small class="flex-fill text-center py-2">
                                        <i class="fa fa-bath text-primary me-2"></i>
                                        {{ $property->bathrooms ?? 'N/A' }} Bath
                                    </small>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12 text-center py-5">
                            <i class="fa fa-search fa-3x text-muted mb-3"></i>
                            <h4>No se encontraron propiedades</h4>
                            <p class="text-muted">Intenta con otros criterios de búsqueda</p>
                        </div>
                    @endforelse

                    <div class="col-12 text-center wow fadeInUp" data-wow-delay="0.1s">
                        <a class="btn btn-primary py-3 px-5" href="#">Browse More Property</a>
                    </div>
                </div>
            </div>

            {{-- For Sell --}}
            <div id="tab-sell" class="tab-pane fade show p-0">
                <div class="row g-4">
                    @foreach($properties->where('typeOperation.name', 'Venta') as $property)
                        @include('partials.property-card', ['property' => $property, 'defaultImage' => $defaultImage])
                    @endforeach
                </div>
            </div>

            {{-- For Rent --}}
            <div id="tab-rent" class="tab-pane fade show p-0">
                <div class="row g-4">
                    @foreach($properties->where('typeOperation.name', 'Renta') as $property)
                        @include('partials.property-card', ['property' => $property, 'defaultImage' => $defaultImage])
                    @endforeach
                </div>
            </div>

        </div>
    </div>
</div>
<!-- Property List End -->

@endsection
