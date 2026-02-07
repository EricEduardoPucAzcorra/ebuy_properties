@extends('welcome')
@section('content')
<div class="container-fluid bg-white py-5">
    <div class="container">
        <div class="row g-5 align-items-center">
            <div class="col-lg-6 wow zoomIn" data-wow-delay="0.1s">
                <div class="img-stack">
                    <img class="img-fluid w-100" src="{{asset('images/ebuy_1.png')}}" alt="Propiedades">
                    <div class="floating-card">
                        <h5 class="mb-1 text-primary">+1,200</h5>
                        <p class="mb-0 small text-muted">{{auto_trans('Propiedades publicadas este mes')}}</p>
                    </div>
                </div>
            </div>

            <div class="col-lg-6 wow fadeInRight" data-wow-delay="0.3s">
                <h6 class="text-primary text-uppercase fw-bold mb-2">{{auto_trans('Sobre Nosotros')}}</h6>
                <h1 class="display-5 mb-4">{{auto_trans('La Nueva Era del Real Estate en')}} <span class="ebuy-gradient-text">Ebuy Properties</span></h1>
                <p class="lead text-dark mb-4">{{auto_trans('No somos solo un listado. Somos el puente digital entre quienes buscan un hogar y quienes ofrecen oportunidades.')}}</p>

                <p class="mb-4 text-muted">
                    {{auto_trans('Nuestra plataforma permite a inmobiliarias y dueños particulares promocionar sus espacios con tecnología de punta, asegurando que cada propiedad brille ante los ojos de los inversores correctos.')}}'
                </p>

                <div class="row g-4">
                    <div class="col-sm-6">
                        <div class="feature-box">
                            <div class="d-flex align-items-center mb-3">
                                <i class="fa fa-building fa-2x text-primary me-3"></i>
                                <h5 class="mb-0">{{auto_trans('Agencias')}}</h5>
                            </div>
                            <p class="small mb-0">{{auto_trans('Gestión profesional de múltiples listados con métricas en tiempo real.')}}</p>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="feature-box">
                            <div class="d-flex align-items-center mb-3">
                                <i class="fa fa-user-check fa-2x text-primary me-3"></i>
                                <h5 class="mb-0">{{auto_trans('Dueños')}}</h5>
                            </div>
                            <p class="small mb-0">{{auto_trans('Publica en minutos y mantén el control total de tu negociación.')}}</p>
                        </div>
                    </div>
                </div>

                <div class="mt-5">
                    <a href="{{route('login')}}" class="btn btn-primary rounded-pill py-3 px-5 shadow">{{auto_trans('Empezar a Publicar')}}</a>
                </div>
            </div>
        </div>
    </div>
</div>
@include('styles.about')
@endsection
