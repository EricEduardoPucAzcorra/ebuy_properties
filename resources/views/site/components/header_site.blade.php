<div class="container-fluid header bg-white p-0 ebuy-hero">
    <div class="container-fluid px-4 px-xl-5">
        <div class="row g-4 align-items-center">

            <div class="col-lg-5 py-5 py-lg-0">
                <div class="ebuy-hero-copy">
                    <div class="ebuy-hero-pill mb-3 animated fadeIn">
                        <i class="fa fa-shield-alt me-2"></i>
                        {{ auto_trans('Compra, renta o publica en un solo lugar') }}
                    </div>

                    <h1 class="ebuy-hero-title animated fadeIn mb-3">
                        {{ auto_trans('Descubre el lugar ideal para') }}
                        <span class="text-success">{{ auto_trans('construir momentos') }}</span>
                        {{ auto_trans('con tu familia.') }}
                    </h1>

                    <p class="ebuy-hero-subtitle animated fadeIn mb-4">
                        <p class="ebuy-hero-subtitle animated fadeIn mb-4">
                            {{ auto_trans('Encuentra un hogar diseñado para brindar comodidad, seguridad y bienestar.') }}
                            {{ auto_trans('Un espacio donde cada momento se convierte en un recuerdo especial.') }}
                        </p>
                    </p>

                    <div class="d-flex flex-column flex-sm-row gap-2 animated fadeIn">
                        <a href="{{ route('properties') }}" class="btn btn-primary btn-lg px-5 py-3">
                            {{ auto_trans('Explorar') }}
                        </a>
                        <a href="{{ route('register') }}" class="btn btn-outline-primary btn-lg px-5 py-3">
                            {{ auto_trans('Publicar') }}
                        </a>
                    </div>

                    <div class="row g-3 mt-4 animated fadeIn">
                        <div class="col-4">
                            <div class="ebuy-hero-mini">
                                <div class="value">{{ auto_trans('Búsqueda') }}</div>
                                <div class="label">{{ auto_trans('Inteligente') }}</div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="ebuy-hero-mini">
                                <div class="value">{{ auto_trans('Zonas') }}</div>
                                <div class="label">{{ auto_trans('Destacadas') }}</div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="ebuy-hero-mini">
                                <div class="value">{{ auto_trans('Anuncios') }}</div>
                                <div class="label">{{ auto_trans('Rápidos') }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-7">
                <div class="ebuy-hero-carousel-card animated fadeIn">
                    <div class="owl-carousel header-carousel ebuy-hero-carousel">
                        <div class="owl-carousel-item">
                            <div class="ebuy-hero-slide">
                                <img class="img-fluid" src="https://images.unsplash.com/photo-1570129477492-45c003edd2be?q=80&w=1170&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D" alt="">
                                <div class="ebuy-hero-overlay"></div>
                                <div class="ebuy-hero-slide-content">
                                    <div class="tag">{{ auto_trans('Tendencia') }}</div>
                                    <div class="title">{{ auto_trans('Casas con gran ubicación') }}</div>
                                    <div class="meta">
                                        <i class="fa fa-map-marker-alt me-2"></i>
                                        {{ auto_trans('Explora las mejores zonas cerca de ti') }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="owl-carousel-item">
                            <div class="ebuy-hero-slide">
                                <img class="img-fluid" src="https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?q=80&w=1175&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D" alt="">
                                <div class="ebuy-hero-overlay"></div>
                                <div class="ebuy-hero-slide-content">
                                    <div class="tag">{{ auto_trans('Nuevo') }}</div>
                                    <div class="title">{{ auto_trans('Departamentos modernos') }}</div>
                                    <div class="meta">
                                        <i class="fa fa-key me-2"></i>
                                        {{ auto_trans('Filtra por precio, zona y amenidades') }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
