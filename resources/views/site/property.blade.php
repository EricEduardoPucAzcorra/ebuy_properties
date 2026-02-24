@extends('welcome')

@section('content')
<div class="sticky-top bg-white border-bottom py-2 shadow-sm d-none d-md-block" style="top: 0; z-index: 1030; margin-top: -1px;">
    <div class="container-fluid px-md-5 d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center gap-3">
            <h2 class="fw-bold mb-0 fs-4" style="color: #00b388;">{{ $propertie->currency }} {{ $propertie->price }}</h2>
            <span class="text-muted small border-start ps-3 d-none d-lg-inline">{{ Str::limit(auto_trans($propertie->title), 80) }}</span>
        </div>
        <div class="d-flex gap-2">
            <div class="favorite-btn-wrapper">
                @include('components.favorite-button', ['propertyId' => $propertie->id, 'absolute' => false])
            </div>
            <button class="btn btn-success btn-sm rounded-pill px-4 shadow-sm fw-bold border-0"
                    style="background-color: #00b388;"
                    onclick="scrollToContact()">
                {{auto_trans('Contactar')}}
            </button>
        </div>
    </div>
</div>

<div class="container-fluid p-0">
    <div class="row g-1 m-0" style="height: 500px;">
        <div class="col-md-8 h-100 p-0 overflow-hidden">
            <div class="gallery-item h-100 w-100" style="cursor: pointer;" onclick="openGalleryAt(0)">
                <img src="{{ $propertie->images['main'] }}" class="w-100 h-100 object-fit-cover hover-zoom">
            </div>
        </div>
        <div class="col-md-4 h-100 d-none d-md-flex flex-column gap-1 p-0">
            @php $others = collect($propertie->images['others']); @endphp
            @foreach($others->take(2) as $index => $img)
                <div class="gallery-item h-50 overflow-hidden position-relative" style="cursor: pointer;" onclick="openGalleryAt({{ $index + 1 }})">
                    <img src="{{ $img }}" class="w-100 h-100 object-fit-cover hover-zoom">
                    @if($loop->last && $others->count() > 2)
                        <div class="more-photos-overlay">
                            <span class="btn btn-light btn-sm fw-bold rounded-pill px-3 shadow">
                                <i class="fa fa-image me-1"></i> {{ auto_trans('Ver todas')}} (+{{ $others->count() - 1 }})
                            </span>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
</div>

<div class="container-fluid py-5 px-md-5">
    <div class="row g-4">
        <div class="col-lg-9">
            <div class="mb-4">
                <p class="text-muted fs-5"><i class="bi bi-buildings text-success me-2"></i> {{ $propertie->type_operation }} - {{ $propertie->type_property }}</p>
                <h1 class="fw-bold text-dark mb-2 h2">{{ auto_trans($propertie->title) }}</h1>
                <p class="text-muted fs-5"><i class="bi bi-geo-alt text-danger me-2"></i></i>C- {{ $propertie->address['street'] }} - {{ $propertie->address['neighborhood'] }}, # {{ $propertie->address['number'] }}, {{ $propertie->address['city']['name'] }},  {{ $propertie->address['state']['name'] }}, {{ $propertie->address['country']['name'] }}</p>
                {{-- <p class="text-muted fs-5">C- {{ $propertie->address['references'] }}</p> --}}
            </div>

            <div class="container-fluid px-0 mb-5">
                <h4 class="fw-bold mb-3 border-start border-success border-4 ps-3">{{auto_trans('Descripción')}}</h4>
                <div class="description-text fs-6 mb-5 leading-relaxed">
                    {!! nl2br(e(auto_trans($propertie->description))) !!}
                </div>
            </div>


            <div class="row g-0 py-4 border-top border-bottom mb-5 bg-white shadow-sm rounded-3">
                @foreach($propertie->attributes as $attr)
                <div class="col-3 text-center border-end last-child-border-0">
                    <span class="text-muted small text-uppercase fw-bold d-block mb-1" style="font-size: 0.7rem;">{{ auto_trans($attr['key']) }}</span>
                    <span class="fw-bold fs-4 text-dark">{{ $attr['value'] }}</span>
                </div>
                @endforeach
            </div>

            <div class="container-fluid px-0 mb-5">
                <h4 class="fw-bold mb-4 border-start border-success border-4 ps-3">{{ auto_trans('Características y Amenidades') }}</h4>

                <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                    <div class="card-header bg-white border-0 pt-4 px-4">
                        <div class="p-1 rounded-pill d-inline-block shadow-sm" style="background-color: #f8f9fa; border: 1px solid #eee;">
                            <ul class="nav nav-pills border-0 d-flex gap-1" id="featuresTab" role="tablist">
                                @foreach($propertie->features as $categoryName => $items)
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link rounded-pill fw-bold px-4 py-2 {{ $loop->first ? 'active' : '' }}"
                                                id="tab-{{ Str::slug($categoryName) }}"
                                                data-bs-toggle="pill"
                                                data-bs-target="#content-{{ Str::slug($categoryName) }}"
                                                type="button" role="tab"
                                                aria-selected="{{ $loop->first ? 'true' : 'false' }}">
                                            {{ auto_trans($categoryName) }}
                                            <span class="badge rounded-pill ms-1 {{ $loop->first ? 'bg-white text-success' : 'bg-secondary opacity-50' }}" style="font-size: 0.7rem;">
                                                {{ count($items) }}
                                            </span>
                                        </button>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>

                    <div class="card-body p-4 pt-2">
                        <div class="tab-content" id="featuresTabContent">
                            @foreach($propertie->features as $categoryName => $items)
                                <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}"
                                    id="content-{{ Str::slug($categoryName) }}"
                                    role="tabpanel"
                                    aria-labelledby="tab-{{ Str::slug($categoryName) }}">

                                    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4 mt-1">
                                        @foreach($items as $feature)
                                            <div class="col">
                                                <div class="d-flex align-items-center p-2 rounded-3 transition-all">
                                                    <div class="flex-shrink-0 me-3">
                                                        <div class="rounded-circle d-flex align-items-center justify-content-center"
                                                            style="width: 42px; height: 42px; background-color: rgba(0, 179, 136, 0.1);">
                                                            <i class="{{ $feature['icon'] ?? 'fas fa-check' }}" style="color: #00b388; font-size: 1.1rem;"></i>
                                                        </div>
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <span class="fw-semibold" style="color: #2c3e50; font-size: 0.95rem;">
                                                            {{ auto_trans($feature['name']) }}
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>


            <div class="map-container">
                <div class="map-header">
                    <h4 class="map-title">
                        <i class="bi bi-geo-alt-fill"></i>
                        {{auto_trans('Ubicación')}}
                    </h4>
                    <div class="map-actions">
                        <!-- <button class="map-btn" onclick="toggleFullscreen()">
                            <i class="bi bi-arrows-fullscreen"></i>
                            {{auto_trans('Pantalla completa')}}
                        </button> -->
                        <button class="map-btn" onclick="refreshMap()">
                            <i class="bi bi-arrow-clockwise"></i>
                            {{auto_trans('Actualizar')}}
                        </button>
                    </div>
                </div>
                <div id="mapvue"
                    data-location="{{ json_encode($propertie->address['location']) }}">
                    <div class="map-loading">
                        <div class="map-loading-spinner"></div>
                    </div>
                    <map-selector
                        v-model="location"
                        :readonly="true"
                        price="{{ $propertie->price }}"
                        currency="{{ $propertie->currency }}"
                        image="{{ $propertie->images['main'] }}"
                        title="{{ $propertie->title }}"
                        app-icon="{{ asset('images/ebuy_icon.png') }}">
                    </map-selector>
                </div>
            </div>

        </div>

        <div class="col-lg-3">
            <div class="card border-0 shadow-lg rounded-4 p-4 sticky-top" style="top: 100px; z-index: 900;" id="contact-form-sidebar">
                <h5 class="fw-bold mb-4">{{auto_trans('Contactar al anunciante')}}</h5>

                @php $contact = collect($propertie->contacts)->first(); @endphp

                @if($contact)
                <div class="d-flex align-items-center mb-4 pb-3 border-bottom">
                    <img src="{{asset('images/ebuy_1.png')}}" class="rounded-circle me-3 border shadow-sm" width="60" height="60" style="object-fit: cover;">
                    <div>
                        <h6 class="fw-bold mb-0 text-truncate" style="max-width: 140px;">{{ $contact['name'] }}</h6>
                        <small class="text-muted small">Agente Verificado</small>
                    </div>
                </div>
                @endif

                <form action="#" method="POST" id="contactForm">
                    @csrf
                    <div class="mb-2"><input type="text" name="name" class="form-control rounded-3 py-2" placeholder="Tu nombre" required></div>
                    <div class="mb-2"><input type="email" name="email" class="form-control rounded-3 py-2" placeholder="Correo" required></div>
                    <div class="mb-2"><input type="tel" name="phone" class="form-control rounded-3 py-2" placeholder="Teléfono" required></div>
                    <div class="mb-3">
                        <textarea name="message" class="form-control rounded-3" rows="3" id="messageTextarea" data-property-title="{{ $propertie->title }}">Hola, me interesa: {{ $propertie->title }}</textarea>
                    </div>
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-success btn-lg rounded-3 fw-bold py-2 border-0 shadow-sm" style="background-color: #00b388; font-size: 1rem;">
                            <i class="fas fa-paper-plane me-2"></i> Enviar Mensaje
                        </button>
                        @if(isset($contact['whatsapp']))
                        <a id="whatsappBtn" href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $contact['whatsapp']) }}?text={{ urlencode('Hola, me interesa: ' . $propertie->title) }}"
                           target="_blank" class="btn btn-success btn-lg rounded-3 fw-bold py-2 d-flex align-items-center justify-content-center whatsapp-btn" style="font-size: 1rem;">
                            <i class="fab fa-whatsapp me-2 fs-4"></i> WhatsApp
                        </a>
                        @endif
                    </div>
                </form>

                <div class="mt-4 pt-3 border-top">
                    <h6 class="fw-bold mb-3 small text-muted text-uppercase">Contacto rápido</h6>
                    <div class="row g-2">
                        @if(isset($contact['whatsapp']))
                        <div class="col-6">
                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $contact['whatsapp']) }}" target="_blank" class="btn btn-outline-success btn-sm w-100 d-flex align-items-center justify-content-center gap-1">
                                <i class="fab fa-whatsapp"></i><span>WhatsApp</span>
                            </a>
                        </div>
                        @endif
                        @if(isset($contact['phone']))
                        <div class="col-6">
                            <a href="tel:{{ $contact['phone'] }}" class="btn btn-outline-primary btn-sm w-100 d-flex align-items-center justify-content-center gap-1">
                                <i class="fas fa-phone"></i><span>Llamar</span>
                            </a>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@include('site.contents.recommendations_propety')

<div class="modal fade" id="galleryModal" tabindex="-1" style="z-index: 9999;">
    <div class="modal-dialog modal-fullscreen modal-dialog-centered">
        <div class="modal-content bg-white border-0">
            <div class="modal-header border-bottom position-absolute w-100" style="top: 0; z-index: 10010; background-color: white;">
                <h5 class="modal-title text-dark fw-bold mb-0">{{ Str::limit(auto_trans($propertie->title), 40) }}</h5>
                <button type="button" class="btn btn-outline-danger btn-sm rounded-circle p-2 ms-auto close-gallery-btn shadow-sm" data-bs-dismiss="modal" style="width: 40px; height: 40px;">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body p-0 d-flex align-items-center justify-content-center mt-5">
                <div id="propCarousel" class="carousel slide w-100 h-100 d-flex align-items-center" data-bs-interval="false">
                    <div class="carousel-inner">
                        <div class="carousel-item active text-center">
                            <img src="{{ $propertie->images['main'] }}" class="img-fluid" style="max-height: 80vh; object-fit: contain;">
                        </div>
                        @foreach($propertie->images['others'] as $img)
                        <div class="carousel-item text-center">
                            <img src="{{ $img }}" class="img-fluid" style="max-height: 80vh; object-fit: contain;">
                        </div>
                        @endforeach
                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#propCarousel" data-bs-slide="prev">
                        <span class="bg-success bg-opacity-90 rounded-circle p-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                            <i class="fas fa-chevron-left text-white fs-4"></i>
                        </span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#propCarousel" data-bs-slide="next">
                        <span class="bg-success bg-opacity-90 rounded-circle p-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                            <i class="fas fa-chevron-right text-white fs-4"></i>
                        </span>
                    </button>
                </div>
                <div class="position-absolute bottom-0 start-50 translate-middle-x mb-4">
                    <span class="badge bg-success px-3 py-2 fs-6 fw-normal shadow-sm" id="imageCounter" data-total="{{ count($propertie->images['others']) + 1 }}">
                        1 / {{ count($propertie->images['others']) + 1 }}
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Funciones para el mapa
function toggleFullscreen() {
    const mapContainer = document.querySelector('#mapvue map-selector, #mapvue .map-selector, #mapvue');
    if (!mapContainer) return;
    
    if (!document.fullscreenElement) {
        // Crear un contenedor temporal para el modo fullscreen
        const fsContainer = document.createElement('div');
        fsContainer.id = 'map-fullscreen-container';
        fsContainer.style.cssText = `
            position: fixed !important;
            top: 0 !important;
            left: 0 !important;
            width: 100vw !important;
            height: 100vh !important;
            z-index: 9999 !important;
            background: white !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
        `;
        
        // Clonar el contenido del mapa
        const mapClone = mapContainer.cloneNode(true);
        mapClone.style.cssText = `
            width: 100% !important;
            height: 100% !important;
            border-radius: 0 !important;
        `;
        
        fsContainer.appendChild(mapClone);
        document.body.appendChild(fsContainer);
        
        // Intentar entrar en fullscreen
        if (fsContainer.requestFullscreen) {
            fsContainer.requestFullscreen();
        } else if (fsContainer.webkitRequestFullscreen) {
            fsContainer.webkitRequestFullscreen();
        } else if (fsContainer.msRequestFullscreen) {
            fsContainer.msRequestFullscreen();
        }
        
        // Agregar botón de salir
        const exitBtn = document.createElement('button');
        exitBtn.innerHTML = '<i class="bi bi-x-lg"></i> Salir';
        exitBtn.style.cssText = `
            position: absolute !important;
            top: 20px !important;
            right: 20px !important;
            z-index: 10000 !important;
            background: #00b388 !important;
            color: white !important;
            border: none !important;
            border-radius: 8px !important;
            padding: 10px 20px !important;
            cursor: pointer !important;
            font-size: 16px !important;
            box-shadow: 0 4px 15px rgba(0, 179, 136, 0.3) !important;
        `;
        exitBtn.onclick = exitFullscreen;
        fsContainer.appendChild(exitBtn);
        
    } else {
        exitFullscreen();
    }
}

function exitFullscreen() {
    const fsContainer = document.getElementById('map-fullscreen-container');
    if (fsContainer) {
        fsContainer.remove();
    }
    
    if (document.exitFullscreen) {
        document.exitFullscreen();
    } else if (document.webkitExitFullscreen) {
        document.webkitExitFullscreen();
    } else if (document.msExitFullscreen) {
        document.msExitFullscreen();
    }
}

function refreshMap() {
    const mapLoading = document.querySelector('.map-loading');
    if (mapLoading) {
        mapLoading.style.display = 'flex';
        // Simular recarga del mapa (aquí podrías agregar lógica real)
        setTimeout(() => {
            mapLoading.style.display = 'none';
            // Disparar evento de recarga si el mapa lo necesita
            window.dispatchEvent(new Event('resize'));
        }, 1000);
    }
}

// Ocultar loading cuando el mapa esté listo
document.addEventListener('DOMContentLoaded', function() {
    setTimeout(() => {
        const mapLoading = document.querySelector('.map-loading');
        if (mapLoading) {
            mapLoading.style.display = 'none';
        }
    }, 2000);
});

// Escuchar cambios de fullscreen
document.addEventListener('fullscreenchange', function() {
    if (!document.fullscreenElement) {
        const fsContainer = document.getElementById('map-fullscreen-container');
        if (fsContainer) {
            fsContainer.remove();
        }
    }
});
</script>
@endpush
