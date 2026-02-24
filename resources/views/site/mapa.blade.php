@extends('welcome')

@section('content')
<!-- <section class="map-hero">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10 text-center">
                <div class="map-hero-content">
                    <h1 class="display-4 fw-bold mb-3">
                        <i class="fas fa-map-marked-alt me-3"></i>{{auto_trans('Mapa de Propiedades')}}
                    </h1>
                    <p class="lead mb-4">{{auto_trans('Explora todas las propiedades disponibles en nuestro mapa interactivo. Encuentra tu próximo hogar en la ubicación perfecta.')}}</p>
                    <div class="d-flex justify-content-center gap-3">
                        <div class="stat-item text-center">
                            <div class="h3 mb-0">{{ $recommendedProperties->count() }}+</div>
                            <small>{{auto_trans('Propiedades en mapa')}}</small>
                        </div>
                        <div class="stat-item text-center">
                            <div class="h3 mb-0">{{ $mostViewed->count() }}+</div>
                            <small>{{auto_trans('Destacadas')}}</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section> -->

<!-- Filtros del Mapa -->
<section class="py-4 bg-light">
    <div class="container">
        <div class="search-wrapper">
            <div class="search-card">
                <form class="row g-3 align-items-center search-form" method="GET" action="{{ route('site.mapa') }}" id="map-search-form">

                    <div class="col-lg-3 col-md-6 search-group">
                        <select class="form-select search-select" name="operation">
                            <option value="">{{auto_trans('Tipo de Operación')}}</option>
                            @foreach ($type_operations as $operation)
                                <option value="{{ $operation->id }}" {{ request('operation') == $operation->id ? 'selected' : '' }}>
                                    {{ auto_trans($operation->name) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-lg-3 col-md-6 search-group">
                        <select class="form-select search-select" name="type">
                            <option value="">{{auto_trans('Tipo de Propiedad')}}</option>
                            @foreach ($type_properties as $type)
                                <option value="{{ $type->id }}" {{ request('type') == $type->id ? 'selected' : '' }}>
                                    {{ auto_trans($type->name) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-lg-4 col-md-12 position-relative search-group">
                        <input
                            type="text"
                            name="q"
                            id="location-search"
                            class="form-control search-input"
                            placeholder="{{ auto_trans('Buscar por lugar, característica, atributo etc...') }}"
                            value="{{ request('q') }}"
                            autocomplete="off">

                        <input type="hidden" name="location_type" id="location_type" value="{{ request('location_type') }}">
                        <input type="hidden" name="location_id" id="location_id" value="{{ request('location_id') }}">

                        <div id="location-results" class="autocomplete d-none"></div>
                    </div>

                    <div class="col-lg-2 col-md-12 d-grid">
                        <button type="submit" class="btn btn-primary btn-search">
                            <i class="fas fa-search me-2"></i>
                            {{ auto_trans('Buscar') }}
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</section>

<!-- Map Section -->
<section class="py-3 bg-light">
    <div class="container-fluid px-3">
        <div class="row">
            <div class="col-12">
                <div class="text-center mb-3">
                    <h2 class="fw-bold mb-2">
                        <i class="fas fa-globe-americas text-primary me-2"></i>
                        {{auto_trans('Descubre Propiedades en el Mapa')}}
                    </h2>
                    <p class="text-muted">{{auto_trans('Navega por el mapa interactivo y encuentra propiedades en tu área de interés. Haz clic en los marcadores para ver detalles.')}}</p>
                </div>
            </div>
        </div>
        
        <div id="mapa-app">
            <div v-if="loading" class="text-center py-5">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">{{auto_trans('Cargando...')}}</span>
                </div>
                <p class="mt-2">{{auto_trans('Cargando propiedades en el mapa...')}}</p>
            </div>
            
            <div v-else-if="error" class="alert alert-danger text-center">
                <i class="fas fa-exclamation-triangle"></i>
                {{auto_trans('Error al cargar las propiedades:')}} <span v-text="error"></span>
            </div>
            
            <div v-else>
                <div v-if="locations.length === 0" class="alert alert-warning text-center mb-3">
                    {{auto_trans('No se encontraron propiedades en esta ubicación')}}
                </div>
                
                <div class="map-container-wrapper">
                    <map-listings 
                        ref="mapComponent"
                        :locations="locations" 
                        :config="mapConfig" 
                        :app-icon="appIcon">
                    </map-listings>
                </div>
            </div>
        </div>
    </div>
</section>

@if($recommendedProperties->count() > 0)
<section class="py-5 bg-white">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">
                <i class="fas fa-star text-warning me-2"></i>{{auto_trans('Propiedades Recomendadas')}}
            </h2>
            <p class="section-subtitle">{{auto_trans('Descubre las propiedades más recomendadas que coinciden con tus preferencias')}}</p>
        </div>
        
        <div class="row g-4">
            @foreach($recommendedProperties->take(6) as $property)
                <div class="col-lg-4 col-md-6">
                    <div class="property-card card h-100">
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
                            
                            <div class="property-badge">
                                <span class="badge bg-primary">{{ $property->operation->name ?? '' }}</span>
                            </div>
                            
                            <button class="favorite-btn" data-property-id="{{ $property->id }}" title="Agregar a favoritos">
                                <i class="far fa-heart text-muted"></i>
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
                </div>
            @endforeach
        </div>
        
        <div class="text-center mt-4">
            <a href="{{ route('properties') }}" class="btn btn-outline-primary btn-lg">
                <i class="fas fa-th me-2"></i>{{auto_trans('Ver todas las propiedades')}}
            </a>
        </div>
    </div>
</section>
@endif

<!-- @if($mostViewed->count() > 0)
<section class="py-5 bg-light">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">
                <i class="fas fa-fire text-danger me-2"></i>{{auto_trans('Propiedades Populares')}}
            </h2>
            <p class="section-subtitle">{{auto_trans('Las propiedades más vistas y solicitadas de la plataforma')}}</p>
        </div>
        
        <div class="row g-4">
            @foreach($mostViewed->take(4) as $property)
                <div class="col-lg-3 col-md-6">
                    <div class="property-card card h-100">
                        @php
                            $mainImage = $property->images->firstWhere('is_main', true);
                            $price = number_format($property->price, 2);
                            $location = $property->address->city->cityname;
                        @endphp
                        
                        <div class="position-relative">
                            @if($mainImage)
                                <img src="{{ asset('storage/' . $mainImage->path) }}" class="card-img-top" style="height: 180px; object-fit: cover;" alt="{{ $property->title }}">
                            @else
                                <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 180px;">
                                    <i class="fas fa-home fa-2x text-muted"></i>
                                </div>
                            @endif
                            
                            <div class="property-badge">
                                <span class="badge bg-danger">{{auto_trans('Popular')}}</span>
                            </div>
                        </div>
                        
                        <div class="card-body">
                            <h6 class="card-title">{{ Str::limit($property->title, 40) }}</h6>
                            <p class="property-location mb-2">
                                <i class="fas fa-map-marker-alt me-1"></i>{{ $location }}
                            </p>
                            <div class="property-price">
                                {{ $property->currency ?? '$' }} {{ $price }}
                            </div>
                        </div>
                        
                        <div class="card-footer bg-transparent border-0">
                            <a href="{{ route('property', $property->id) }}" class="btn btn-sm btn-outline-primary w-100">
                                {{auto_trans('Ver más')}}
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif -->


<section class="py-5 bg-primary text-white">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h3 class="fw-bold mb-3">{{auto_trans('¿Listo para encontrar tu próximo hogar?')}}</h3>
                <p class="mb-0">{{auto_trans('Explora nuestro catálogo completo de propiedades y encuentra la opción perfecta para ti.')}}</p>
            </div>
            <div class="col-lg-4 text-end">
                <a href="{{ route('properties') }}" class="btn btn-light btn-lg">
                    <i class="fas fa-search me-2"></i>{{auto_trans('Buscar ahora')}}
                </a>
            </div>
        </div>
    </div>
</section>

@push('scripts')
<script>
let mapaVueInstance = null;

const input = document.getElementById('location-search');
const results = document.getElementById('location-results');
const form = document.getElementById('map-search-form');
let controller;
let isAutocompleteActive = false;

const locationType = document.getElementById('location_type');
const locationId = document.getElementById('location_id');

input.addEventListener('input', async () => {
    const q = input.value.trim();

    if (q === '') {
        locationType.value = '';
        locationId.value = '';

        results.classList.add('d-none');
        results.innerHTML = '';
        isAutocompleteActive = false;
        return;
    }

    if (q.length < 2) {
        results.classList.add('d-none');
        results.innerHTML = '';
        isAutocompleteActive = false;
        return;
    }

    if (controller) controller.abort();
    controller = new AbortController();

    try {
        const res = await fetch(`/location-search?q=${encodeURIComponent(q)}`, {
            signal: controller.signal
        });
        const data = await res.json();

        results.innerHTML = '';
        data.forEach(item => {
            const div = document.createElement('div');
            div.className = 'autocomplete-item';
            div.dataset.type = item.type;
            div.dataset.id = item.id;
            div.dataset.cityid = item.cityid || item.stateid || '';  
            div.dataset.lat = item.lat || '';
            div.dataset.lng = item.lng || '';
            div.textContent = item.label;
            results.appendChild(div);
        });

        results.classList.toggle('d-none', data.length === 0);
        isAutocompleteActive = data.length > 0;
    } catch (err) {
        if (err.name === 'AbortError') {
            return;
        }
        console.error("Error en la búsqueda:", err);
        isAutocompleteActive = false;
    }
});

results.addEventListener('click', e => {
    const item = e.target.closest('.autocomplete-item');
    if (!item) return;

    const operation = document.querySelector('[name="operation"]').value;
    const type = document.querySelector('[name="type"]').value;

    locationType.value = item.dataset.type;
    locationId.value = item.dataset.cityid || item.dataset.id;  

    const params = new URLSearchParams({
        operation,
        type,
        location_type: item.dataset.type,
        location_id: item.dataset.cityid || item.dataset.id,  
        q: item.textContent.trim()
    });

    if (item.dataset.lat && item.dataset.lng) {
        params.append('lat', item.dataset.lat);
        params.append('lng', item.dataset.lng);
        console.log('Usando coordenadas del autocomplete:', item.dataset.lat, item.dataset.lng);
        console.log('Usando cityid para filtrar:', item.dataset.cityid || item.dataset.id);
    }

    window.location.href = `/mapa?${params.toString()}`;
});

document.addEventListener('click', e => {
    if (!e.target.closest('.position-relative')) {
        results.classList.add('d-none');
        results.innerHTML = '';
        isAutocompleteActive = false;
    }
});

input.addEventListener('keypress', (e) => {
    if (e.key === 'Enter') {
        e.preventDefault();

        if (!isAutocompleteActive) {
            submitSearchForm();
        }
    }
});

form.addEventListener('submit', (e) => {
    e.preventDefault();
    
    if (!isAutocompleteActive) {
        submitSearchForm();
    }
});

function submitSearchForm() {
    const formData = new FormData(form);
    const params = new URLSearchParams();
    
    for (let [key, value] of formData.entries()) {
        if (value && value.trim() !== '') {
            params.append(key, value);
        }
    }
    
    const newUrl = params.toString() ? `/mapa?${params.toString()}` : '/mapa';
    window.location.href = newUrl;
}

window.reloadMapa = function() {
    if (mapaVueInstance) {
        mapaVueInstance.reloadMap();
    }
};
</script>
@endpush

@endsection
