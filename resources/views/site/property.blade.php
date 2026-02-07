@extends('welcome')
@section('content')
    <div class="container py-4">
        <div class="row g-4 align-items-stretch">

            <div class="col-lg-8">
                <div class="position-relative shadow-sm rounded overflow-hidden" style="height: 500px; background-color: #000;">
                    <img src="https://images.homify.com/v1440015283/p/photo/image/832767/JLF_6309.jpg"
                        class="w-100 h-100 object-fit-cover"
                        style="cursor: zoom-in;"
                        onclick="openGalleryAt(0)">

                    <span class="position-absolute top-0 start-0 m-3 badge bg-primary px-3 py-2" style="z-index: 5;">Destacado</span>

                    <div class="position-absolute bottom-0 start-50 translate-middle-x mb-3 d-flex gap-2 p-2 rounded-3" style="background: rgba(0,0,0,0.3); backdrop-filter: blur(8px); z-index: 10;">
                        <img src="https://images.homify.com/v1440015283/p/photo/image/832767/JLF_6309.jpg" class="img-thumbnail thumb-preview" onclick="openGalleryAt(0)">
                        <img src="https://images.homify.com/v1440015283/p/photo/image/832767/JLF_6309.jpg" class="img-thumbnail thumb-preview" onclick="openGalleryAt(1)">
                        <div class="thumb-preview more-counter" onclick="openGalleryAt(2)">+3</div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card border-0 shadow-sm h-100 p-4">
                    <h2 class="fw-bold h3">Villa Moderna en la Playa</h2>
                    <p class="text-primary fw-bold fs-4">$15,000,000 MXN</p>
                    <hr>
                    <p class="text-muted small">Hermosa villa con vistas al mar y acabados de lujo.</p>
                    <div class="d-grid gap-2 mt-auto">
                        <button class="btn btn-primary btn-lg py-3">Contactar ahora</button>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <div class="modal fade" id="inmuebleGalleryModal" tabindex="-1" aria-hidden="true" style="z-index: 999999 !important;">
        <div class="modal-dialog modal-fullscreen">
            <div class="modal-content border-0 bg-transparent">

                <div class="gallery-overlay">
                    <button type="button" class="btn-close-gallery" data-bs-dismiss="modal" onclick="closeManual()">
                        <i class="fa fa-times"></i>
                    </button>

                    <div class="d-flex flex-column h-100">
                        <div id="carouselInmueble" class="carousel slide flex-grow-1 d-flex align-items-center" data-bs-interval="false">
                            <div class="carousel-inner">
                                <div class="carousel-item active">
                                    <img src="https://images.homify.com/v1440015283/p/photo/image/832767/JLF_6309.jpg" class="img-gallery-main">
                                </div>
                                <div class="carousel-item">
                                    <img src="https://images.homify.com/v1440015283/p/photo/image/832767/JLF_6309.jpg" class="img-gallery-main">
                                </div>
                                <div class="carousel-item">
                                    <img src="https://images.homify.com/v1440015283/p/photo/image/832767/JLF_6309.jpg" class="img-gallery-main">
                                </div>
                            </div>

                            <button class="carousel-control-prev" type="button" data-bs-target="#carouselInmueble" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon"></span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#carouselInmueble" data-bs-slide="next">
                                <span class="carousel-control-next-icon"></span>
                            </button>
                        </div>

                        <div class="gallery-footer-nav p-3 text-center">
                            <div class="d-flex gap-2 justify-content-center py-2">
                                <img src="https://images.homify.com/v1440015283/p/photo/image/832767/JLF_6309.jpg" class="thumb-gallery active" onclick="slideTo(0)">
                                <img src="https://images.homify.com/v1440015283/p/photo/image/832767/JLF_6309.jpg" class="thumb-gallery" onclick="slideTo(1)">
                                <img src="https://images.homify.com/v1440015283/p/photo/image/832767/JLF_6309.jpg" class="thumb-gallery" onclick="slideTo(2)">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .thumb-preview { width: 65px; height: 48px; object-fit: cover; cursor: pointer; border: 1px solid #fff; border-radius: 4px; }
        .more-counter { background: rgba(255, 255, 255, 0.2); color: white; display: flex; align-items: center; justify-content: center; font-size: 14px; font-weight: bold; }

        .gallery-overlay {
            position: fixed;
            top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(0, 0, 0, 0.88);
            backdrop-filter: blur(15px);
            z-index: 999999;
        }

        .btn-close-gallery {
            position: absolute;
            right: 25px;
            top: 25px;
            z-index: 1000000;
            background: rgba(255,255,255,0.2);
            border: none;
            color: white;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            cursor: pointer;
            font-size: 20px;
        }

        .img-gallery-main {
            max-width: 90%;
            max-height: 75vh;
            margin: auto;
            display: block;
            object-fit: contain;
        }

        .thumb-gallery {
            width: 70px; height: 50px; object-fit: cover; cursor: pointer;
            opacity: 0.4; border: 2px solid transparent; transition: 0.3s;
        }
        .thumb-gallery.active { opacity: 1; border-color: #0d6efd; transform: scale(1.1); }
        .object-fit-cover { object-fit: cover; }
    </style>

    <script>
        function openGalleryAt(index) {
            if (window.jQuery) {
                $('#inmuebleGalleryModal').modal('show');
            } else {
                var myModal = new bootstrap.Modal(document.getElementById('inmuebleGalleryModal'));
                myModal.show();
            }
            slideTo(index);
        }

        function slideTo(index) {
            if (window.jQuery) {
                $('#carouselInmueble').carousel(index);
            } else {
                var carousel = new bootstrap.Carousel(document.getElementById('carouselInmueble'));
                carousel.to(index);
            }
            updateThumbs(index);
        }

        function closeManual() {
            if (window.jQuery) {
                $('#inmuebleGalleryModal').modal('hide');
            }
        }

        function updateThumbs(index) {
            document.querySelectorAll('.thumb-gallery').forEach((thumb, i) => {
                thumb.classList.toggle('active', i === index);
            });
        }

        document.addEventListener('DOMContentLoaded', function() {
            var carouselEl = document.getElementById('carouselInmueble');

            carouselEl.addEventListener('slide.bs.carousel', function (e) {
                updateThumbs(e.to);
            });

            if (window.jQuery) {
                $(carouselEl).on('slide.bs.carousel', function (e) {
                    updateThumbs(e.to);
                });
            }
        });
    </script>
@endsection
