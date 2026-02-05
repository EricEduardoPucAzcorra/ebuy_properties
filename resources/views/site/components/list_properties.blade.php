<style>
    :root {
        --primary-color: #00B98E;
        --dark-text: #1A202C;
        --body-text: #64748B;
    }

    /* Títulos limpios */
    .section-title {
        font-weight: 850;
        color: var(--dark-text);
        letter-spacing: -1.5px;
        line-height: 1.1;
    }

    /* Tabs minimalistas (Pills) */
    .nav-pills-custom .nav-link {
        color: var(--body-text);
        font-weight: 600;
        padding: 10px 25px;
        border-radius: 15px;
        transition: all 0.3s ease;
        border: 1px solid #f0f0f0;
        background: #fff;
        margin-right: 10px;
    }

    .nav-pills-custom .nav-link.active {
        background-color: var(--primary-color) !important;
        color: white !important;
        border-color: var(--primary-color);
        box-shadow: 0 10px 20px rgba(0, 185, 142, 0.2);
    }

    /* Tarjeta Estilo Premium Blanco */
    .card-clean {
        border: none;
        background: #ffffff;
        border-radius: 30px;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        border: 1px solid #f1f5f9; /* Un borde casi invisible */
    }

    .card-clean:hover {
        transform: translateY(-12px);
        box-shadow: 0 30px 60px rgba(0, 0, 0, 0.08);
        border-color: var(--primary-color);
    }

    /* Contenedor de imagen con bordes orgánicos */
    .img-frame {
        position: relative;
        padding: 12px; /* Espacio que crea el efecto de marco blanco */
    }

    .img-frame img {
        border-radius: 24px;
        width: 100%;
        object-fit: cover;
        height: 240px;
    }

    /* Etiquetas flotantes */
    .floating-tag {
        position: absolute;
        top: 25px;
        right: 25px;
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(5px);
        padding: 6px 16px;
        border-radius: 12px;
        font-weight: 700;
        font-size: 0.8rem;
        color: var(--dark-text);
        box-shadow: 0 4px 10px rgba(0,0,0,0.05);
    }

    /* Amenidades en diseño horizontal limpio */
    .amenity-row {
        padding: 15px 20px;
        border-top: 1px solid #f8fafc;
        display: flex;
        justify-content: space-between;
    }

    .amenity-box {
        font-size: 0.85rem;
        color: var(--body-text);
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .amenity-box i {
        color: var(--primary-color);
        font-size: 1rem;
    }

    /* Precio destacado */
    .price-tag {
        font-size: 1.5rem;
        font-weight: 800;
        color: var(--primary-color);
        margin-bottom: 5px;
    }
</style>

<div class="container-fluid bg-white py-5">
    <div class="container">
        <div class="row align-items-center mb-5">
            <div class="col-lg-6">
                <h1 class="section-title mb-4">Discover Your<br><span style="color: var(--primary-color);">Perfect Living</span></h1>
                <p class="text-muted fs-5">Premium listings for people who value design and comfort.</p>
            </div>
            <div class="col-lg-6 text-lg-end">
                <div class="nav nav-pills nav-pills-custom d-inline-flex">
                    <button class="nav-link active" data-bs-toggle="pill" href="#tab-1">Featured</button>
                    <button class="nav-link" data-bs-toggle="pill" href="#tab-2">For Sale</button>
                    <button class="nav-link" data-bs-toggle="pill" href="#tab-3">For Rent</button>
                </div>
            </div>
        </div>

        <div class="tab-content">
            <div id="tab-1" class="tab-pane fade show active">
                <div class="row g-4">
                    <div class="col-lg-4 col-md-6">
                        <div class="card-clean h-100">
                            <div class="img-frame">
                                <img src="img/property-1.jpg" alt="Luxury Home">
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
                            <div class="amenity-row">
                                <div class="amenity-box"><i class="fa fa-ruler-combined"></i> 1200m²</div>
                                <div class="amenity-box"><i class="fa fa-bed"></i> 3</div>
                                <div class="amenity-box"><i class="fa fa-bath"></i> 2</div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-6">
                        <div class="card-clean h-100">
                            <div class="img-frame">
                                <img src="img/property-2.jpg" alt="Luxury Home">
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
                            <div class="amenity-row">
                                <div class="amenity-box"><i class="fa fa-ruler-combined"></i> 2500m²</div>
                                <div class="amenity-box"><i class="fa fa-bed"></i> 5</div>
                                <div class="amenity-box"><i class="fa fa-bath"></i> 4</div>
                            </div>
                        </div>
                    </div>

                    </div>

                <div class="text-center mt-5">
                    <a href="#" class="btn btn-primary btn-lg px-5 py-3 rounded-pill fw-bold shadow">Explore All Properties</a>
                </div>
            </div>
        </div>
    </div>
</div>
