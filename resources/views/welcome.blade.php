<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <title>Ebuy Properties</title>

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="description" content="Ebuy Properties - Bienes Raíces">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="icon" href="{{ asset('img/favicon.ico') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"/>

    <script src="{{ asset('min/vue.min.js') }}"></script>
    @vite(['resources/site/site.css', 'resources/site/site.js'])
</head>

<body>
    <div class="container-fluid bg-white p-0">
        <div id="spinner" class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
            <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>

        @include('site.components.navbar_site')

        <div class="content-wrapper">
            @yield('content')
        </div>

        <div class="container-fluid bg-dark text-white-50 footer pt-5 mt-5 wow fadeIn" data-wow-delay="0.1s">
            <div class="container py-5">
                <div class="row g-5">
                    <div class="col-lg-3 col-md-6">
                        <h5 class="text-white mb-4">Ebuy Properties</h5>
                        <p class="mb-2"><i class="fa fa-map-marker-alt me-3"></i>{{ auto_trans('Mérida, Yucatán, México') }}</p>
                        <p class="mb-2"><i class="fa fa-phone-alt me-3"></i><a class="text-white-50" href="tel:+520000000000">+52 00 00 00 00 00</a></p>
                        <p class="mb-2"><i class="fa fa-envelope me-3"></i><a class="text-white-50" href="mailto:contacto@ebuyproperties.com">contacto@ebuyproperties.com</a></p>
                        <div class="d-flex pt-2">
                            <a class="btn btn-outline-light btn-social" href="#" aria-label="Twitter"><i class="fab fa-twitter"></i></a>
                            <a class="btn btn-outline-light btn-social" href="#" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
                            <a class="btn btn-outline-light btn-social" href="#" aria-label="YouTube"><i class="fab fa-youtube"></i></a>
                            <a class="btn btn-outline-light btn-social" href="#" aria-label="LinkedIn"><i class="fab fa-linkedin-in"></i></a>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <h5 class="text-white mb-4">{{auto_trans('Enlaces rapidos')}}</h5>
                        <a class="btn btn-link text-white-50" href="{{ route('welcome.site') }}">{{ auto_trans('Inicio') }}</a>
                        <a class="btn btn-link text-white-50" href="{{ route('properties') }}">{{ auto_trans('Propiedades') }}</a>
                        <a class="btn btn-link text-white-50" href="{{ route('properties.sale') }}">{{ auto_trans('En venta') }}</a>
                        <a class="btn btn-link text-white-50" href="{{ route('properties.rent') }}">{{ auto_trans('En renta') }}</a>
                        <a class="btn btn-link text-white-50" href="{{ route('about') }}">{{ auto_trans('Acerca de') }}</a>
                    </div>
                    {{-- <div class="col-lg-3 col-md-6">
                        <h5 class="text-white mb-4">Photo Gallery</h5>
                        <div class="row g-2 pt-2">
                            <div class="col-4">
                                <img class="img-fluid rounded bg-light p-1" src="img/property-1.jpg" alt="">
                            </div>
                            <div class="col-4">
                                <img class="img-fluid rounded bg-light p-1" src="img/property-2.jpg" alt="">
                            </div>
                            <div class="col-4">
                                <img class="img-fluid rounded bg-light p-1" src="img/property-3.jpg" alt="">
                            </div>
                            <div class="col-4">
                                <img class="img-fluid rounded bg-light p-1" src="img/property-4.jpg" alt="">
                            </div>
                            <div class="col-4">
                                <img class="img-fluid rounded bg-light p-1" src="img/property-5.jpg" alt="">
                            </div>
                            <div class="col-4">
                                <img class="img-fluid rounded bg-light p-1" src="img/property-6.jpg" alt="">
                            </div>
                        </div>
                    </div> --}}
                    <div class="col-lg-3 col-md-6">
                        <h5 class="text-white mb-4">{{auto_trans('Conoce EBuy Properties')}}</h5>
                        <p>{{auto_trans('La plataforma líder para comprar, vender y rentar propiedades de forma inteligente. Conecta con las mejores oportunidades del mercado inmobiliario en un solo lugar.')}}</p>
                        <div class="position-relative mx-auto" style="max-width: 400px;">
                            <input class="form-control bg-transparent w-100 py-3 ps-4 pe-5" type="text" placeholder="Tu correo electrónico">
                            <button type="button" class="btn btn-primary py-2 position-absolute top-0 end-0 mt-2 me-2">{{auto_trans('Iniciar')}}</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="container">
                <div class="copyright">
                    <div class="row">
                        <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
                            &copy; <a class="border-bottom" href="/">Ebuy Properties</a>, {{auto_trans('Todos los derechos reservados.')}}

							<!--/*** Mis creditos a los creadores de esta plantilla mil gracias ***/-->
                            <!--/*** Son expertos e increimbles. ***/-->
                            {{auto_trans('Mis creditos a')}} <a class="border-bottom" href="https://htmlcodex.com">HTML Codex</a>
                        </div>
                        <div class="col-md-6 text-center text-md-end">
                            <div class="footer-menu">
                                <a href="{{ route('welcome.site') }}">{{auto_trans('Inicio')}}</a>
                                <a href="{{ route('properties') }}">{{ auto_trans('Propiedades') }}</a>
                                <a href="{{ route('about') }}">{{ auto_trans('Acerca de') }}</a>
                                <a href="mailto:contacto@ebuyproperties.com">{{ auto_trans('Contacto') }}</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('min/site/jquery-3.4.1.min.js') }}"></script>
    <script src="{{ asset('min/site/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('min/site/wow/wow.min.js') }}"></script>
    <script src="{{ asset('min/site/easing/easing.min.js') }}"></script>
    <script src="{{ asset('min/site/waypoints/waypoints.min.js') }}"></script>
    <script src="{{ asset('min/site/owlcarousel/owl.carousel.js') }}"></script>
    <script src="{{ asset('min/map/leaflet.js') }}"></script>
    <script src="{{ asset('min/map/swiper-bundle.min.js') }}"></script>

    <!-- JavaScript del Menú Desktop y Móvil -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // ===== MENÚ DESKTOP - MEGA MENU INTERACTIONS =====
            document.querySelectorAll('.sidebar-item').forEach(item => {
                item.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    
                    const targetId = this.getAttribute('data-target');
                    const targetPane = document.getElementById(targetId);
                    
                    if (!targetPane) {
                        console.log('No se encontró el pane con ID:', targetId);
                        return;
                    }
                    
                    // Remove active class from all sidebar items in this mega menu
                    const megaCard = this.closest('.mega-card');
                    if (megaCard) {
                        megaCard.querySelectorAll('.sidebar-item').forEach(sidebarItem => {
                            sidebarItem.classList.remove('active');
                        });
                        
                        // Hide all panes in this mega menu
                        megaCard.querySelectorAll('.mega-pane').forEach(pane => {
                            pane.classList.remove('active');
                        });
                    }
                    
                    // Add active class to clicked item
                    this.classList.add('active');
                    
                    // Show target pane
                    targetPane.classList.add('active');
                    
                    console.log('Activado pane:', targetId);
                });
            });
            
            // ===== MENÚ MÓVIL =====
            const mobileMenuToggle = document.getElementById('mobileMenuToggle');
            const mobileMenuClose = document.getElementById('mobileMenuClose');
            const mobileMenu = document.getElementById('mobileMenu');
            const body = document.body;

            // Abrir menú móvil
            if (mobileMenuToggle) {
                mobileMenuToggle.addEventListener('click', function() {
                    if (mobileMenu) {
                        mobileMenu.classList.add('active');
                        body.style.overflow = 'hidden';
                    }
                });
            }

            // Cerrar menú móvil
            if (mobileMenuClose) {
                mobileMenuClose.addEventListener('click', function() {
                    if (mobileMenu) {
                        mobileMenu.classList.remove('active');
                        body.style.overflow = '';
                    }
                });
            }

            // Inicializar submenús principales como ocultos
            document.querySelectorAll('.ebuy-mobile-submenu').forEach(submenu => {
                submenu.style.display = 'none';
                submenu.style.visibility = 'hidden';
            });
            
            // Toggle submenús principales
            document.querySelectorAll('.ebuy-mobile-menu-toggle').forEach(toggle => {
                toggle.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    
                    const targetId = this.getAttribute('data-target');
                    const submenu = document.getElementById(targetId);
                    const icon = this.querySelector('i:last-child');
                    
                    if (submenu) {
                        if (submenu.style.display === 'none') {
                            submenu.style.display = 'block';
                            submenu.style.visibility = 'visible';
                            icon.classList.remove('fa-chevron-down');
                            icon.classList.add('fa-chevron-up');
                        } else {
                            submenu.style.display = 'none';
                            submenu.style.visibility = 'hidden';
                            icon.classList.remove('fa-chevron-up');
                            icon.classList.add('fa-chevron-down');
                        }
                    }
                });
            });

            // Inicializar submenús anidados como ocultos
            document.querySelectorAll('.ebuy-mobile-submenu-nested').forEach(submenu => {
                submenu.style.display = 'none';
                submenu.style.visibility = 'hidden';
            });
            
            // Toggle submenús anidados
            document.querySelectorAll('.ebuy-mobile-submenu-toggle').forEach(toggle => {
                toggle.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    
                    const targetId = this.getAttribute('data-target');
                    const submenu = document.getElementById(targetId);
                    const icon = this.querySelector('i:last-child');
                    
                    if (submenu) {
                        if (submenu.style.display === 'none') {
                            submenu.style.display = 'block';
                            submenu.style.visibility = 'visible';
                            icon.classList.remove('fa-chevron-down');
                            icon.classList.add('fa-chevron-up');
                        } else {
                            submenu.style.display = 'none';
                            submenu.style.visibility = 'hidden';
                            icon.classList.remove('fa-chevron-up');
                            icon.classList.add('fa-chevron-down');
                        }
                    }
                });
            });

            // Toggle idioma móvil
            const mobileLangCurrent = document.querySelector('.ebuy-mobile-lang-current');
            const mobileLangOptions = document.querySelector('.ebuy-mobile-lang-options');
            
            if (mobileLangCurrent && mobileLangOptions) {
                mobileLangOptions.style.display = 'none';
                mobileLangOptions.style.visibility = 'hidden';
                
                mobileLangCurrent.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    
                    if (mobileLangOptions.style.display === 'none') {
                        mobileLangOptions.style.display = 'block';
                        mobileLangOptions.style.visibility = 'visible';
                    } else {
                        mobileLangOptions.style.display = 'none';
                        mobileLangOptions.style.visibility = 'hidden';
                    }
                });
            }

            // Cerrar menú al hacer clic fuera
            document.addEventListener('click', function(e) {
                if (mobileMenu && mobileMenuToggle) {
                    if (!mobileMenu.contains(e.target) && !mobileMenuToggle.contains(e.target)) {
                        mobileMenu.classList.remove('active');
                        body.style.overflow = '';
                    }
                }
            });

            // Cerrar menú en desktop
            window.addEventListener('resize', function() {
                if (mobileMenu && window.innerWidth > 991) {
                    mobileMenu.classList.remove('active');
                    body.style.overflow = '';
                }
            });
        });
    </script>

    @stack('scripts')
</body>

</html>
