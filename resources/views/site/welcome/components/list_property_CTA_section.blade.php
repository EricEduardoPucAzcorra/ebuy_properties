<div class="container-fluid py-5">
    <div class="container">
        <div class="bg-light rounded p-3">
            <div class="bg-white rounded p-4" style="border: 1px dashed rgba(0, 185, 142, .3)">
                <div class="row g-4 align-items-center">
                    <div class="col-lg-7">
                        <h2 class="fw-bold mb-2">{{ auto_trans('¿Tienes una propiedad para vender o rentar?') }}</h2>
                        <p class="text-muted mb-0">{{ auto_trans('Publica tu anuncio en minutos y llega a compradores y arrendatarios interesados en tu zona.') }}</p>
                    </div>
                    <div class="col-lg-5 text-lg-end">
                        <div class="d-flex flex-column flex-sm-row gap-2 justify-content-lg-end">
                            <a href="{{ route('register') }}" class="btn btn-primary py-3 px-4">{{ auto_trans('Publicar ahora') }}</a>
                            <a href="{{ route('login') }}" class="btn btn-outline-primary py-3 px-4">{{ auto_trans('Ya tengo cuenta') }}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>