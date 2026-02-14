<div class="container-fluid bg-white py-5">
    <div class="container">
        <div class="ebuy-final-cta">
            <div class="row g-4 align-items-center">
                <div class="col-lg-7">
                    <h2 class="fw-bold mb-2">{{ auto_trans('Listo para dar el siguiente paso') }}</h2>
                    <p class="text-muted mb-0">{{ auto_trans('Explora oportunidades en tu zona o publica tu propiedad y recibe contactos.') }}</p>
                </div>
                <div class="col-lg-5">
                    <div class="d-flex flex-column flex-sm-row gap-2 justify-content-lg-end">
                        <a href="{{ route('properties') }}" class="btn btn-primary py-3 px-4">
                            <i class="bi bi-search me-2"></i>{{ auto_trans('Buscar propiedades') }}
                        </a>
                        <a href="{{ route('register') }}" class="btn btn-outline-primary py-3 px-4">
                            <i class="bi bi-plus-circle me-2"></i>{{ auto_trans('Publicar propiedad') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>