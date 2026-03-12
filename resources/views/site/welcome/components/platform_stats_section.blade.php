<div class="container-fluid bg-white py-5">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-4 wow fadeInUp" data-wow-delay="0.1s">
                <div class="card-clean h-100 p-4">
                    <div class="d-flex align-items-center gap-3">
                        <div class="icon" style="width: 52px; height: 52px;">
                            <i class="fa fa-home text-primary"></i>
                        </div>
                        <div>
                            <div class="fw-bold" style="font-size: 1.25rem;">{{ number_format($stats['properties'] ?? 0) }}</div>
                            <div class="text-muted">{{ auto_trans('Propiedades activas') }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 wow fadeInUp" data-wow-delay="0.2s">
                <div class="card-clean h-100 p-4">
                    <div class="d-flex align-items-center gap-3">
                        <div class="icon" style="width: 52px; height: 52px;">
                            <i class="fa fa-map-marked-alt text-primary"></i>
                        </div>
                        <div>
                            <div class="fw-bold" style="font-size: 1.25rem;">{{ number_format($stats['states'] ?? 0) }}</div>
                            <div class="text-muted">{{ auto_trans('Estados disponibles') }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 wow fadeInUp" data-wow-delay="0.3s">
                <div class="card-clean h-100 p-4">
                    <div class="d-flex align-items-center gap-3">
                        <div class="icon" style="width: 52px; height: 52px;">
                            <i class="fa fa-city text-primary"></i>
                        </div>
                        <div>
                            <div class="fw-bold" style="font-size: 1.25rem;">{{ number_format($stats['cities'] ?? 0) }}</div>
                            <div class="text-muted">{{ auto_trans('Ciudades para explorar') }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-12 wow fadeInUp" data-wow-delay="0.35s">
                <div class="bg-light rounded p-4" style="border: 1px dashed rgba(0, 185, 142, .3)">
                    <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
                        <div>
                            <h4 class="fw-bold mb-1">{{ auto_trans('Encuentra tu propiedad con confianza') }}</h4>
                            <p class="text-muted mb-0">{{ __('site.content_welcome_1') }}</p>
                        </div>
                        <div class="d-flex gap-2">
                            <a href="{{ route('properties') }}" class="btn btn-primary px-4">{{ auto_trans('Explorar') }}</a>
                            <a href="{{ route('about') }}" class="btn btn-outline-primary px-4">{{ auto_trans('Conocer más') }}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
