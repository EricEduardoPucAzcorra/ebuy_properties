@extends('layouts.app')

@section('content')
<div class="container py-4">
    
    <div class="text-center mb-5">
        <span class="section-tag">Guía Definitiva</span>
        <h1 class="fw-bold display-5 mb-3">Cómo crear una <span style="color: var(--brand-green)">publicación</span> que vende</h1>
        <p class="text-muted lead mx-auto" style="max-width: 700px;">Sigue estos pasos y convierte tu publicación atractiva e interesante.</p>
    </div>

    <div class="row g-4 mb-5">
        <div class="col-md-6 col-lg-4">
            <div class="process-step">
                <div class="step-number">1</div>
                <div class="step-icon bg-step1"><i class="bi bi-camera-fill"></i></div>
                <h4 class="step-title">Fotografía Profesional</h4>
                <p class="step-text">Las imágenes son lo primero que ven. Invierte en calidad visual.</p>
                <div class="mt-3">
                    <div class="checklist-item"><i class="bi bi-check-circle-fill"></i> <small>Usa luz natural (mañana o atardecer)</small></div>
                    <div class="checklist-item"><i class="bi bi-check-circle-fill"></i> <small>Fotos en horizontal</small></div>
                    <div class="checklist-item"><i class="bi bi-check-circle-fill"></i> <small>Muestra espacios clave: sala, cocina, recámaras, baños</small></div>
                    <div class="checklist-item"><i class="bi bi-check-circle-fill"></i> <small>Incluye foto de fachada y amenidades</small></div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-4">
            <div class="process-step">
                <div class="step-number">2</div>
                <div class="step-icon bg-step2"><i class="bi bi-pencil-square"></i></div>
                <h4 class="step-title">Título y Descripción</h4>
                <p class="step-text">El texto correcto marca la diferencia entre un "clic" y un "ignorar".</p>
                <div class="mt-3">
                    <div class="checklist-item"><i class="bi bi-check-circle-fill"></i> <small>Título: atractivo + datos clave (ej. "Espectacular casa 3 hab + alberca")</small></div>
                    <div class="checklist-item"><i class="bi bi-check-circle-fill"></i> <small>Destaca beneficios, no solo características</small></div>
                    <div class="checklist-item"><i class="bi bi-check-circle-fill"></i> <small>Incluye medidas, acabados y ubicación exacta</small></div>
                    <div class="checklist-item"><i class="bi bi-check-circle-fill"></i> <small>Usa emojis estratégicos (🏠 🌳 🔑)</small></div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-4">
            <div class="process-step">
                <div class="step-number">3</div>
                <div class="step-icon bg-step3"><i class="bi bi-tags"></i></div>
                <h4 class="step-title">Precio y Condiciones</h4>
                <p class="step-text">La transparencia genera confianza y filtra clientes reales.</p>
                <div class="mt-3">
                    <div class="checklist-item"><i class="bi bi-check-circle-fill"></i> <small>Pon precio realista (investiga el mercado)</small></div>
                    <div class="checklist-item"><i class="bi bi-check-circle-fill"></i> <small>Especifica si aceptas crédito/inversión</small></div>
                    <div class="checklist-item"><i class="bi bi-check-circle-fill"></i> <small>Menciona gastos de mantenimiento (si aplica)</small></div>
                </div>
            </div>
        </div>
    </div>

    <section class="content-section">
        <div class="row align-items-center">
            <div class="col-md-5">
                <h2 class="fw-bold mb-4">El secreto que usan los top agentes</h2>
                <p class="text-muted">No vendes paredes, vendes un <strong>estilo de vida</strong>. Describe cómo se siente vivir ahí: tranquilidad, seguridad, cerca de colegios, áreas verdes.</p>
                <!-- <div class="tip-card">
                    <i class="bi bi-star-fill" style="color: var(--brand-green);"></i>
                    <p class="mt-2 mb-0 fst-italic">"Una casa se ve con los ojos, pero un hogar se siente con las palabras."</p>
                </div> -->
            </div>
            <div class="col-md-7">
                <div class="row g-3">
                    <div class="col-6">
                        <div class="bg-white p-3 rounded-4 text-center shadow-sm">
                            <i class="bi bi-camera-reels-fill fs-1" style="color: var(--brand-green);"></i>
                            <p class="mt-2 fw-bold mb-0">+40% interacción</p>
                            <small class="text-muted">con video tour</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="bg-white p-3 rounded-4 text-center shadow-sm">
                            <i class="bi bi-whatsapp fs-1" style="color: #25D366;"></i>
                            <p class="mt-2 fw-bold mb-0">Respuesta rápida</p>
                            <small class="text-muted">convierte 3x más</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="cta-container shadow-lg">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h2 class="fw-bold mb-3" style="font-size: 2rem;">¿Necesitas asesoría personalizada?</h2>
                <p class="mb-0 opacity-75">Contáctanos y te ayudamos a vender o rentar tu propiedad más rápido.</p>
            </div>
            <div class="col-lg-4 text-center text-lg-end mt-4 mt-lg-0">
                <a href="https://wa.me/529997255903" class="btn-whatsapp text-decoration-none shadow">
                    <i class="bi bi-whatsapp fs-4"></i> WhatsApp
                </a>
            </div>
        </div>
        <div style="position: absolute; right: -20px; bottom: -20px; font-size: 8rem; opacity: 0.08; color: white;">
            <i class="bi bi-chat-dots-fill"></i>
        </div>
    </div>

    <div class="company-info-simple">
        <div class="company-name">
            <i class="bi bi-building me-2"></i> EBuy Properties
        </div>
        <p class="text-muted mb-2">La plataforma líder para comprar, vender y rentar propiedades de forma inteligente. Conecta con las mejores oportunidades del mercado inmobiliario en un solo lugar.</p>
        
        <div class="company-detail">
            <span><i class="bi bi-geo-alt-fill"></i> Calle 10 #317 x 25B y 25B1, Colonia Benito Juárez Oriente, Mérida, Yucatán.</span>
            <span><i class="bi bi-telephone-fill"></i> +52 999 725 5903</span>
            <span><i class="bi bi-envelope-fill"></i> contacto@ebuyproperties.com</span>
            <span><i class="bi bi-file-earmark-text"></i><a href="{{ asset('ebuy/Aviso de privacidad Ebuy.pdf') }}" target="_blank">{{auto_trans('Aviso de Privacidad')}}</a></span>
        </div>

        <div class="quick-link-text">
            <i class="bi bi-link-45deg"></i> <a href="#">Conoce EBuy Properties</a>
        </div>
    </div>

</div>
@endsection