<div class="container-fluid header bg-white p-0">
    <div class="row g-0 align-items-center flex-column-reverse flex-md-row">

        <div class="col-md-6 p-5 mt-lg-5">
            <h1 class="display-5 animated fadeIn mb-4">
                {{ auto_trans('Descubre el lugar ideal para') }}
                <span class="text-success">{{ auto_trans('construir momentos') }}</span>
                {{ auto_trans('con tu familia.') }}
            </h1>

            <p class="animated fadeIn mb-4 pb-2 text-muted">
                Encuentra un hogar diseñado para brindar comodidad, seguridad y bienestar.
                Un espacio donde cada momento se convierte en un recuerdo especial.
            </p>

            <a href="{{ route('register') }}"
               class="btn btn-success btn-lg py-3 px-5 animated fadeIn">
                Registrarse
            </a>
        </div>

        <div class="col-md-6 animated fadeIn">
            <div class="owl-carousel header-carousel">
                <div class="owl-carousel-item">
                    <img class="img-fluid" src="img/carousel-1.jpg" alt="">
                </div>
                <div class="owl-carousel-item">
                    <img class="img-fluid" src="img/carousel-2.jpg" alt="">
                </div>
            </div>
        </div>

    </div>
</div>
