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
                    {{auto_trans('Nuestra plataforma permite a inmobiliarias y dueños particulares promocionar sus espacios con tecnología de punta, asegurando que cada propiedad brille ante los ojos de los inversores correctos.')}}
                </p>

                <div class="row g-4">
                    <div class="col-sm-6">
                        <div class="feature-box">
                            <div class="d-flex align-items-center mb-3">
                                <i class="fa fa-building fa-2x text-primary me-3"></i>
                                <h5 class="mb-0">{{auto_trans('Agencias')}}</h5>
                            </div>
                            <p class="small mb-0">{{auto_trans('Gestión profesional de múltiples listados con métricas en tiempo real....')}}</p>
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

<!-- Statistics Section -->
<div class="container-fluid py-5 bg-light">
    <div class="container">
        <div class="text-center mx-auto mb-5 wow fadeIn" data-wow-delay="0.1s" style="max-width: 600px;">
            <h6 class="text-primary text-uppercase fw-bold mb-2">{{auto_trans('Nuestros Logros')}}</h6>
            <h1 class="display-5 mb-4">{{auto_trans('Cifras que Hablan por Sí Solas')}}</h1>
        </div>
        <div class="row g-4">
            <div class="col-md-3 col-sm-6 wow fadeIn" data-wow-delay="0.1s">
                <div class="stat-box text-center">
                    <div class="stat-icon mb-3">
                        <i class="fa fa-home fa-3x text-primary"></i>
                    </div>
                    <h2 class="mb-2">+5,000</h2>
                    <p class="mb-0 text-muted">{{auto_trans('Propiedades Activas')}}</p>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 wow fadeIn" data-wow-delay="0.3s">
                <div class="stat-box text-center">
                    <div class="stat-icon mb-3">
                        <i class="fa fa-users fa-3x text-primary"></i>
                    </div>
                    <h2 class="mb-2">+12,000</h2>
                    <p class="mb-0 text-muted">{{auto_trans('Clientes Satisfechos')}}</p>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 wow fadeIn" data-wow-delay="0.5s">
                <div class="stat-box text-center">
                    <div class="stat-icon mb-3">
                        <i class="fa fa-building fa-3x text-primary"></i>
                    </div>
                    <h2 class="mb-2">+200</h2>
                    <p class="mb-0 text-muted">{{auto_trans('Agencias Asociadas')}}</p>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 wow fadeIn" data-wow-delay="0.7s">
                <div class="stat-box text-center">
                    <div class="stat-icon mb-3">
                        <i class="fa fa-chart-line fa-3x text-primary"></i>
                    </div>
                    <h2 class="mb-2">98%</h2>
                    <p class="mb-0 text-muted">{{auto_trans('Tasa de Éxito')}}</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Mission & Values Section -->
<div class="container-fluid bg-white py-5">
    <div class="container">
        <div class="row g-5">
            <div class="col-lg-6 wow fadeInLeft" data-wow-delay="0.1s">
                <h6 class="text-primary text-uppercase fw-bold mb-2">{{auto_trans('Nuestra Misión')}}</h6>
                <h2 class="mb-4">{{auto_trans('Revolutionizar el Mercado Inmobiliario')}}</h2>
                <p class="text-muted mb-4">
                    {{auto_trans('Transformamos la forma en que las personas compran, venden y rentan propiedades mediante tecnología innovadora que simplifica procesos y maximiza oportunidades.')}}
                </p>
                <div class="values-list">
                    <div class="value-item mb-3">
                        <div class="d-flex align-items-start">
                            <i class="fa fa-check-circle text-primary me-3 mt-1"></i>
                            <div>
                                <h5 class="mb-1">{{auto_trans('Innovación Constante')}}</h5>
                                <p class="text-muted mb-0 small">{{auto_trans('Siempre a la vanguardia tecnológica para ofrecer la mejor experiencia.')}}</p>
                            </div>
                        </div>
                    </div>
                    <div class="value-item mb-3">
                        <div class="d-flex align-items-start">
                            <i class="fa fa-check-circle text-primary me-3 mt-1"></i>
                            <div>
                                <h5 class="mb-1">{{auto_trans('Confianza y Transparencia')}}</h5>
                                <p class="text-muted mb-0 small">{{auto_trans('Cada transacción segura y transparente para todos nuestros usuarios.')}}</p>
                            </div>
                        </div>
                    </div>
                    <div class="value-item mb-3">
                        <div class="d-flex align-items-start">
                            <i class="fa fa-check-circle text-primary me-3 mt-1"></i>
                            <div>
                                <h5 class="mb-1">{{auto_trans('Excelencia en Servicio')}}</h5>
                                <p class="text-muted mb-0 small">{{auto_trans('Soporte excepcional para garantizar el éxito de cada cliente.')}}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 wow fadeInRight" data-wow-delay="0.3s">
                <h6 class="text-primary text-uppercase fw-bold mb-2">{{auto_trans('Nuestra Visión')}}</h6>
                <h2 class="mb-4">{{auto_trans('Ser la Plataforma Líder en América Latina')}}</h2>
                <p class="text-muted mb-4">
                    {{auto_trans('Aspiramos a conectar a millones de personas con sus hogares ideales, democratizando el acceso al mercado inmobiliario con herramientas poderosas y accesibles.')}}
                </p>
                <div class="tech-showcase">
                    <h5 class="mb-3">{{auto_trans('Tecnología que Impulsa')}}</h5>
                    <div class="row g-3">
                        <div class="col-6">
                            <div class="tech-item text-center p-3 bg-light rounded">
                                <i class="fa fa-robot fa-2x text-primary mb-2"></i>
                                <p class="small mb-0">{{auto_trans('IA y Machine Learning')}}</p>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="tech-item text-center p-3 bg-light rounded">
                                <i class="fa fa-map-marked-alt fa-2x text-primary mb-2"></i>
                                <p class="small mb-0">{{auto_trans('Mapas Interactivos')}}</p>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="tech-item text-center p-3 bg-light rounded">
                                <i class="fa fa-mobile-alt fa-2x text-primary mb-2"></i>
                                <p class="small mb-0">{{auto_trans('App Móvil')}}</p>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="tech-item text-center p-3 bg-light rounded">
                                <i class="fa fa-shield-alt fa-2x text-primary mb-2"></i>
                                <p class="small mb-0">{{auto_trans('Seguridad Avanzada')}}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Team Section -->
<div class="container-fluid bg-light py-5">
    <div class="container">
        <div class="text-center mx-auto mb-5 wow fadeIn" data-wow-delay="0.1s" style="max-width: 600px;">
            <h6 class="text-primary text-uppercase fw-bold mb-2">{{auto_trans('Nuestro Equipo')}}</h6>
            <h1 class="display-5 mb-4">{{auto_trans('Apasionados por la Innovación')}}</h1>
            <p class="text-muted">{{auto_trans('Un equipo diverso de expertos comprometidos con transformar el mercado inmobiliario.')}}</p>
        </div>
        <div class="row g-4">
            <div class="col-lg-3 col-md-6 wow fadeIn" data-wow-delay="0.1s">
                <div class="team-card text-center">
                    <div class="team-img mb-3">
                        <img src="{{asset('images/avatar-placeholder.svg')}}" class="img-fluid rounded-circle" alt="Team Member">
                    </div>
                    <h5 class="mb-1">{{auto_trans('CEO Fundador')}}</h5>
                    <p class="text-muted small mb-2">{{auto_trans('Liderazgo Estratégico')}}</p>
                    <div class="social-links">
                        <a href="#" class="text-primary me-2"><i class="fab fa-linkedin"></i></a>
                        <a href="#" class="text-primary me-2"><i class="fab fa-twitter"></i></a>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 wow fadeIn" data-wow-delay="0.3s">
                <div class="team-card text-center">
                    <div class="team-img mb-3">
                        <img src="{{asset('images/avatar-placeholder.svg')}}" class="img-fluid rounded-circle" alt="Team Member">
                    </div>
                    <h5 class="mb-1">{{auto_trans('CTO')}}</h5>
                    <p class="text-muted small mb-2">{{auto_trans('Innovación Tecnológica')}}</p>
                    <div class="social-links">
                        <a href="#" class="text-primary me-2"><i class="fab fa-linkedin"></i></a>
                        <a href="#" class="text-primary me-2"><i class="fab fa-github"></i></a>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 wow fadeIn" data-wow-delay="0.5s">
                <div class="team-card text-center">
                    <div class="team-img mb-3">
                        <img src="{{asset('images/avatar-placeholder.svg')}}" class="img-fluid rounded-circle" alt="Team Member">
                    </div>
                    <h5 class="mb-1">{{auto_trans('Directora Comercial')}}</h5>
                    <p class="text-muted small mb-2">{{auto_trans('Relaciones con Clientes')}}</p>
                    <div class="social-links">
                        <a href="#" class="text-primary me-2"><i class="fab fa-linkedin"></i></a>
                        <a href="#" class="text-primary me-2"><i class="fab fa-twitter"></i></a>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 wow fadeIn" data-wow-delay="0.7s">
                <div class="team-card text-center">
                    <div class="team-img mb-3">
                        <img src="{{asset('images/avatar-placeholder.svg')}}" class="img-fluid rounded-circle" alt="Team Member">
                    </div>
                    <h5 class="mb-1">{{auto_trans('Head de Producto')}}</h5>
                    <p class="text-muted small mb-2">{{auto_trans('Experiencia de Usuario')}}</p>
                    <div class="social-links">
                        <a href="#" class="text-primary me-2"><i class="fab fa-linkedin"></i></a>
                        <a href="#" class="text-primary me-2"><i class="fab fa-dribbble"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Testimonials Section -->
<div class="container-fluid bg-white py-5">
    <div class="container">
        <div class="text-center mx-auto mb-5 wow fadeIn" data-wow-delay="0.1s" style="max-width: 600px;">
            <h6 class="text-primary text-uppercase fw-bold mb-2">{{auto_trans('Testimonios')}}</h6>
            <h1 class="display-5 mb-4">{{auto_trans('Historias de Éxito')}}</h1>
            <p class="text-muted">{{auto_trans('Conoce las experiencias de nuestros clientes satisfechos.')}}</p>
        </div>
        <div class="row g-4">
            <div class="col-lg-4 wow fadeIn" data-wow-delay="0.1s">
                <div class="testimonial-card">
                    <div class="testimonial-content mb-3">
                        <i class="fas fa-quote-left text-primary mb-3"></i>
                        <p class="text-muted">{{auto_trans('Gracias a Ebuy Properties vendí mi departamento en menos de 15 días. El proceso fue increíblemente fácil y seguro.')}}</p>
                    </div>
                    <div class="testimonial-author">
                        <div class="d-flex align-items-center">
                            <img src="{{asset('images/avatar-placeholder.svg')}}" class="img-fluid rounded-circle me-3" width="50" alt="Author">
                            <div>
                                <h6 class="mb-0">{{auto_trans('María González')}}</h6>
                                <p class="text-muted small mb-0">{{auto_trans('Vendedora Particular')}}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 wow fadeIn" data-wow-delay="0.3s">
                <div class="testimonial-card">
                    <div class="testimonial-content mb-3">
                        <i class="fas fa-quote-left text-primary mb-3"></i>
                        <p class="text-muted">{{auto_trans('Como agencia inmobiliaria, Ebuy Properties ha multiplicado nuestra visibilidad y nos ha ayudado a cerrar más ventas.')}}</p>
                    </div>
                    <div class="testimonial-author">
                        <div class="d-flex align-items-center">
                            <img src="{{asset('images/avatar-placeholder.svg')}}" class="img-fluid rounded-circle me-3" width="50" alt="Author">
                            <div>
                                <h6 class="mb-0">{{auto_trans('Roberto Méndez')}}</h6>
                                <p class="text-muted small mb-0">{{auto_trans('Agente Inmobiliario')}}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 wow fadeIn" data-wow-delay="0.5s">
                <div class="testimonial-card">
                    <div class="testimonial-content mb-3">
                        <i class="fas fa-quote-left text-primary mb-3"></i>
                        <p class="text-muted">{{auto_trans('Encontré el hogar de mis sueños gracias a las herramientas de búsqueda y las recomendaciones personalizadas.')}}</p>
                    </div>
                    <div class="testimonial-author">
                        <div class="d-flex align-items-center">
                            <img src="{{asset('images/avatar-placeholder.svg')}}" class="img-fluid rounded-circle me-3" width="50" alt="Author">
                            <div>
                                <h6 class="mb-0">{{auto_trans('Carlos Rodríguez')}}</h6>
                                <p class="text-muted small mb-0">{{auto_trans('Comprador')}}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Timeline Section -->
<div class="container-fluid bg-light py-5">
    <div class="container">
        <div class="text-center mx-auto mb-5 wow fadeIn" data-wow-delay="0.1s" style="max-width: 600px;">
            <h6 class="text-primary text-uppercase fw-bold mb-2">{{auto_trans('Nuestra Historia')}}</h6>
            <h1 class="display-5 mb-4">{{auto_trans('Creciendo con Cada Paso')}}</h1>
            <p class="text-muted">{{auto_trans('El viaje que nos ha convertido en líderes del mercado.')}}</p>
        </div>
        <div class="timeline">
            <div class="timeline-item wow fadeIn" data-wow-delay="0.1s">
                <div class="timeline-dot"></div>
                <div class="timeline-content">
                    <h5>2020</h5>
                    <h6>{{auto_trans('El Comienzo')}}</h6>
                    <p class="text-muted">{{auto_trans('Nacimos con la misión de digitalizar el mercado inmobiliario.')}}</p>
                </div>
            </div>
            <div class="timeline-item wow fadeIn" data-wow-delay="0.3s">
                <div class="timeline-dot"></div>
                <div class="timeline-content">
                    <h5>2021</h5>
                    <h6>{{auto_trans('Primera Ronda de Inversión')}}</h6>
                    <p class="text-muted">{{auto_trans('Recibimos funding para expandir nuestra tecnología.')}}</p>
                </div>
            </div>
            <div class="timeline-item wow fadeIn" data-wow-delay="0.5s">
                <div class="timeline-dot"></div>
                <div class="timeline-content">
                    <h5>2022</h5>
                    <h6>{{auto_trans('Expansión Nacional')}}</h6>
                    <p class="text-muted">{{auto_trans('Llegamos a más de 20 ciudades en el país.')}}</p>
                </div>
            </div>
            <div class="timeline-item wow fadeIn" data-wow-delay="0.7s">
                <div class="timeline-dot"></div>
                <div class="timeline-content">
                    <h5>2023</h5>
                    <h6>{{auto_trans('Lanzamiento de App Móvil')}}</h6>
                    <p class="text-muted">{{auto_trans('Revolutionamos la experiencia móvil para nuestros usuarios.')}}</p>
                </div>
            </div>
            <div class="timeline-item wow fadeIn" data-wow-delay="0.9s">
                <div class="timeline-dot"></div>
                <div class="timeline-content">
                    <h5>2024</h5>
                    <h6>{{auto_trans('Líderes del Mercado')}}</h6>
                    <p class="text-muted">{{auto_trans('Nos consolidamos como la plataforma preferida por miles de usuarios.')}}</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- FAQ Section -->
<div class="container-fluid bg-white py-5">
    <div class="container">
        <div class="text-center mx-auto mb-5 wow fadeIn" data-wow-delay="0.1s" style="max-width: 600px;">
            <h6 class="text-primary text-uppercase fw-bold mb-2">{{auto_trans('Preguntas Frecuentes')}}</h6>
            <h1 class="display-5 mb-4">{{auto_trans('Todo lo que Necesitas Saber')}}</h1>
        </div>
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="accordion" id="faqAccordion">
                    <div class="accordion-item mb-3 wow fadeIn" data-wow-delay="0.1s">
                        <h2 class="accordion-header">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
                                {{auto_trans('¿Es seguro publicar mi propiedad en Ebuy Properties?')}}
                            </button>
                        </h2>
                        <div id="faq1" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                <p class="text-muted">{{auto_trans('Sí, contamos con sistemas de verificación avanzados y protocolos de seguridad que protegen tanto a propietarios como a compradores.')}}</p>
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item mb-3 wow fadeIn" data-wow-delay="0.2s">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
                                {{auto_trans('¿Cuánto cuesta publicar una propiedad?')}}
                            </button>
                        </h2>
                        <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                <p class="text-muted">{{auto_trans('Ofrecemos planes flexibles desde publicaciones gratuitas hasta paquetes premium con características avanzadas.')}}</p>
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item mb-3 wow fadeIn" data-wow-delay="0.3s">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq3">
                                {{auto_trans('¿Puedo editar mi publicación después de publicarla?')}}
                            </button>
                        </h2>
                        <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                <p class="text-muted">{{auto_trans('Sí, puedes actualizar información, fotos y precios en cualquier momento desde tu panel de control.')}}</p>
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item mb-3 wow fadeIn" data-wow-delay="0.4s">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq4">
                                {{auto_trans('¿Cómo funciona el proceso de compra-venta?')}}
                            </button>
                        </h2>
                        <div id="faq4" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                <p class="text-muted">{{auto_trans('Te conectamos directamente con vendedores o compradores, proporcionando herramientas para gestionar negociaciones, visitas y documentación legal.')}}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
