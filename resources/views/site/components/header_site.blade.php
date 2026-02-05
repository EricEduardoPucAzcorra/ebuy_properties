<div class="container-fluid header bg-white p-0">
    <div class="row g-0 align-items-center flex-column-reverse flex-md-row">

        <div class="col-md-6 p-5 mt-lg-5">
            <h1 class="display-5 animated fadeIn mb-4">
                {{ auto_trans('Descubre el lugar ideal para') }}
                <span class="text-success">{{ auto_trans('construir momentos') }}</span>
                {{ auto_trans('con tu familia.') }}
            </h1>

            <p class="animated fadeIn mb-4 pb-2 text-muted">
                 {{ auto_trans('Encuentra un hogar diseñado para brindar comodidad, seguridad y bienestar.
                Un espacio donde cada momento se convierte en un recuerdo especial.') }}
            </p>

            <a href="{{ route('register') }}"
               class="btn btn-success btn-lg py-3 px-5 animated fadeIn">
                 {{ auto_trans('Iniciar') }}
            </a>
        </div>

        <div class="col-md-6 animated fadeIn">
            <div class="owl-carousel header-carousel">
                <div class="owl-carousel-item">
                    <img class="img-fluid" style="height: 700px;" src="https://images.unsplash.com/photo-1570129477492-45c003edd2be?q=80&w=1170&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D" alt="">
                </div>
                <div class="owl-carousel-item">
                    <img class="img-fluid" style="height: 700px;" src="https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?q=80&w=1175&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D" alt="">
                </div>
            </div>
        </div>

    </div>
</div>
