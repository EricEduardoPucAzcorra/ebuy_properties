@extends('welcome')

@section('content')

<!-- Hero Section para Favoritos -->
<section class="map-hero">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10 text-center">
                <div class="map-hero-content">
                    <h1 class="display-4 fw-bold mb-3">
                        <i class="fas fa-heart me-3"></i>{{auto_trans('Mis Propiedades Favoritas')}}
                    </h1>
                    <p class="lead mb-4">{{auto_trans('Guarda las propiedades que más te gusten y accede a ellas fácilmente cuando quieras.')}}</p>
                    <div class="d-flex justify-content-center gap-3">
                        <div class="stat-item text-center">
                            <div class="h3 mb-0">{{ $favorites->count() }}</div>
                            <small>{{auto_trans('Propiedades guardadas')}}</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Favorites Section -->
<section class="py-5 bg-light">
    <div class="container">
        @if($favorites->count() > 0)
            <div class="favorites-grid">
                @foreach($favorites as $property)
                    <div class="favorite-property-card">
                        @php
                            $mainImage = $property->images->firstWhere('is_main', true);
                            $price = number_format($property->price, 2);
                            $location = $property->address->city->cityname . ', ' . $property->address->state->statename;
                        @endphp
                        
                        <div class="position-relative">
                            @if($mainImage)
                                <img src="{{ asset('storage/' . $mainImage->path) }}" class="card-img-top" style="height: 200px; object-fit: cover;" alt="{{ $property->title }}">
                            @else
                                <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                                    <i class="fas fa-home fa-3x text-muted"></i>
                                </div>
                            @endif
                            
                            <div class="favorite-badge">
                                <i class="fas fa-heart me-1"></i> {{auto_trans('Favorito')}}
                            </div>
                            
                            <button class="favorite-btn favorited" data-property-id="{{ $property->id }}" title="Quitar de favoritos">
                                <i class="fas fa-heart text-danger"></i>
                            </button>
                        </div>
                        
                        <div class="card-body">
                            <h5 class="card-title">{{ Str::limit($property->title, 50) }}</h5>
                            <p class="property-location mb-2">
                                <i class="fas fa-map-marker-alt me-1"></i>{{ $location }}
                            </p>
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="property-price">
                                    {{ $property->currency ?? '$' }} {{ $price }}
                                </div>
                                <small class="text-muted">{{ $property->type->name ?? '' }}</small>
                            </div>
                        </div>
                        
                        <div class="card-footer bg-transparent border-0">
                            <a href="{{ route('property', $property->id) }}" class="btn btn-primary w-100">
                                <i class="fas fa-eye me-2"></i>{{auto_trans('Ver detalles')}}
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <!-- Paginación -->
            <div class="d-flex justify-content-center mt-5">
                {{ $favorites->links() }}
            </div>
        @else
            <div class="favorites-empty">
                <i class="fas fa-heart"></i>
                <h3>{{auto_trans('Aún no tienes propiedades favoritas')}}</h3>
                <p class="mb-4">{{auto_trans('Explora nuestro catálogo y guarda las propiedades que más te gusten.')}}</p>
                <a href="{{ route('properties') }}" class="btn btn-primary btn-lg">
                    <i class="fas fa-search me-2"></i>{{auto_trans('Explorar propiedades')}}
                </a>
            </div>
        @endif
    </div>
</section>

<!-- Call to Action -->
<section class="py-5 bg-primary text-white">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h3 class="fw-bold mb-3">{{auto_trans('¿Listo para encontrar más propiedades?')}}</h3>
                <p class="mb-0">{{auto_trans('Sigue explorando y guarda tus favoritas para encontrar la opción perfecta.')}}</p>
            </div>
            <div class="col-lg-4 text-end">
                <a href="{{ route('properties') }}" class="btn btn-light btn-lg">
                    <i class="fas fa-search me-2"></i>{{auto_trans('Buscar ahora')}}
                </a>
            </div>
        </div>
    </div>
</section>

@endsection
