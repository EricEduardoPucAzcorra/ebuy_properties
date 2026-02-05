@extends('layouts.app')

@section('content')
<div id="mypropiertes">
    <div class="container-fluid py-5">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-0">{{ auto_trans('Mis propiedades') }}</h1>
                <p class="text-muted mb-0">{{auto_trans('Gestionar mis propiedades y servicios.')}}</p>
            </div>

            <button class="btn btn-primary">
               <i class="bi bi-house-add me-2"></i> {{auto_trans('Agregar inmueble')}}
            </button>
        </div>

        <div class="container-fluid bg-white py-5">
            <div class="container">
                <div class="row align-items-center mb-5">
                    <div class="col-12 text-end">
                        <div class="nav nav-pills nav-pills-custom d-inline-flex justify-content-end">
                            <button class="nav-link active" data-bs-toggle="pill" data-bs-target="#tab-1" type="button">Featured</button>
                            <button class="nav-link" data-bs-toggle="pill" data-bs-target="#tab-2" type="button">For Sale</button>
                            <button class="nav-link" data-bs-toggle="pill" data-bs-target="#tab-3" type="button">For Rent</button>
                        </div>
                    </div>
                </div>

                <div class="tab-content">
                    <div id="tab-1" class="tab-pane fade show active">
                        <div class="row g-4">
                            <div class="col-lg-4 col-md-6">
                                <div class="card-clean h-100">
                                    <div class="img-frame position-relative">
                                        <img src="img/property-1.jpg" alt="Luxury Home" class="img-fluid">
                                        <div class="floating-tag">FOR SALE</div>
                                        <div class="position-absolute bottom-0 start-0 m-4">
                                            <span class="badge bg-primary px-3 py-2 rounded-pill">Apartment</span>
                                        </div>
                                    </div>
                                    <div class="p-4 pt-2">
                                        <div class="price-tag">$1,250,000</div>
                                        <h5 class="fw-bold text-dark mb-2">The Glass Pavilion Loft</h5>
                                        <p class="text-muted small mb-3">
                                            <i class="fa fa-map-marker-alt me-1"></i> 5th Ave, Manhattan, NY
                                        </p>
                                    </div>
                                    <div class="amenity-row d-flex justify-content-between">
                                        <div class="amenity-box"><i class="fa fa-ruler-combined"></i> 1200m²</div>
                                        <div class="amenity-box"><i class="fa fa-bed"></i> 3</div>
                                        <div class="amenity-box"><i class="fa fa-bath"></i> 2</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Property Card 2 -->
                            <div class="col-lg-4 col-md-6">
                                <div class="card-clean h-100">
                                    <div class="img-frame position-relative">
                                        <img src="img/property-2.jpg" alt="Luxury Home" class="img-fluid">
                                        <div class="floating-tag text-primary">FOR RENT</div>
                                        <div class="position-absolute bottom-0 start-0 m-4">
                                            <span class="badge bg-primary px-3 py-2 rounded-pill">Villa</span>
                                        </div>
                                    </div>
                                    <div class="p-4 pt-2">
                                        <div class="price-tag">$4,500<span class="fs-6 text-muted fw-normal">/mo</span></div>
                                        <h5 class="fw-bold text-dark mb-2">Sunset Infinity Villa</h5>
                                        <p class="text-muted small mb-3">
                                            <i class="fa fa-map-marker-alt me-1"></i> Malibu Coast, CA
                                        </p>
                                    </div>
                                    <div class="amenity-row d-flex justify-content-between">
                                        <div class="amenity-box"><i class="fa fa-ruler-combined"></i> 2500m²</div>
                                        <div class="amenity-box"><i class="fa fa-bed"></i> 5</div>
                                        <div class="amenity-box"><i class="fa fa-bath"></i> 4</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Explore Button -->
                        <div class="text-center mt-5">
                            <a href="#" class="btn btn-primary btn-lg px-5 py-3 rounded-pill fw-bold shadow">Explore All Properties</a>
                        </div>
                    </div>

                    <!-- Puedes agregar tab-2 y tab-3 aquí -->
                </div>
            </div>
        </div>
    </div>
</div>
@include('styles.list_properties')
@endsection
