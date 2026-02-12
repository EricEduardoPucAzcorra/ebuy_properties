@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

<style>
    :root {
        --brand-green: #00B98E;
        --text-dark: #1A2B49;
        --text-muted: #7A869A;
        --bg-light: #F8FAFF;
    }

    html { scroll-behavior: smooth; }
    body { background-color: var(--bg-light); font-family: 'Inter', sans-serif; }

    /* Estilo de Tarjetas (Calco de tu imagen) */
    .help-card {
        border: none;
        border-radius: 24px;
        background: #ffffff;
        padding: 45px 30px;
        transition: all 0.4s ease;
        height: 100%;
        box-shadow: 0 10px 30px rgba(0,0,0,0.02);
    }

    .help-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 20px 40px rgba(0,0,0,0.06) !important;
    }

    .icon-wrapper {
        width: 80px;
        height: 80px;
        border-radius: 18px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 30px;
        font-size: 2.2rem;
    }

    /* Colores de Iconos basados en tus fotos */
    .bg-blue-soft { background-color: #E9F2FF; color: #448AFF; }
    .bg-green-soft { background-color: #E9F7F2; color: var(--brand-green); }
    .bg-yellow-soft { background-color: #FFF8E6; color: #FBC02D; }

    .card-title { color: var(--text-dark); font-weight: 800; font-size: 1.4rem; margin-bottom: 15px; }
    .card-text { color: var(--text-muted); font-size: 0.95rem; line-height: 1.6; margin-bottom: 25px; }

    .card-divider { width: 60px; height: 1px; background-color: #E0E0E0; margin: 0 auto 25px; }

    /* Enlaces con Anclas */
    .btn-link-action {
        text-decoration: none;
        font-weight: 700;
        font-size: 1rem;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: gap 0.3s;
    }
    .btn-link-action:hover { gap: 12px; }

    /* Banner Final (Verde #00B98E) */
    .cta-container {
        background-color: #1A2238; /* Fondo oscuro del banner original */
        border-radius: 25px;
        padding: 40px 60px;
        color: white;
        margin-top: 80px;
        position: relative;
        overflow: hidden;
    }

    .btn-whatsapp {
        background-color: var(--brand-green);
        color: white;
        border: none;
        padding: 12px 30px;
        border-radius: 50px;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        gap: 10px;
        transition: transform 0.3s;
    }
    .btn-whatsapp:hover {
        background-color: #009673;
        color: white;
        transform: scale(1.05);
    }

    /* Secciones de Contenido */
    .content-section {
        padding: 100px 0;
        border-bottom: 1px solid #E0E0E0;
    }
    .section-tag {
        color: var(--brand-green);
        text-transform: uppercase;
        font-weight: 800;
        letter-spacing: 1px;
        font-size: 0.85rem;
        display: block;
        margin-bottom: 10px;
    }
</style>

<div class="container py-5">
    <div class="text-center mb-5">
        <h1 class="fw-bold display-5">Centro de Ayuda para <span style="color: var(--brand-green)">Propietarios</span></h1>
        <p class="text-muted lead">Haz clic en nuestras guías especializadas para gestionar tu inmueble.</p>
    </div>

    <div class="row g-4 mb-5">
        <div class="col-md-4">
            <div class="card help-card shadow-sm">
                <div class="card-body">
                    <div class="icon-wrapper bg-blue-soft"><i class="bi bi-camera-video"></i></div>
                    <h4 class="card-title">Marketing Visual</h4>
                    <p class="card-text">Descubre cómo iluminar tus espacios y usar ángulos que dupliquen tus clics.</p>
                    <div class="card-divider"></div>
                    <a href="#marketing" class="btn-link-action" style="color: #5C67F2;">Ver Tutorial <i class="bi bi-arrow-right"></i></a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card help-card shadow-sm">
                <div class="card-body border-top border-4" style="border-top-color: var(--brand-green) !important; border-radius: 24px 24px 0 0;">
                    <div class="icon-wrapper bg-green-soft"><i class="bi bi-shield-check"></i></div>
                    <h4 class="card-title">Blindaje Legal</h4>
                    <p class="card-text">Lista de documentos y contratos actualizados para tu seguridad jurídica.</p>
                    <div class="card-divider"></div>
                    <a href="#legal" class="btn-link-action" style="color: var(--brand-green);">Descargar Kit <i class="bi bi-download"></i></a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card help-card shadow-sm">
                <div class="card-body">
                    <div class="icon-wrapper bg-yellow-soft"><i class="bi bi-lightning-charge"></i></div>
                    <h4 class="card-title">Plan de Visibilidad</h4>
                    <p class="card-text">¿Urgencia por vender? Aprende cómo funcionan nuestros anuncios destacados.</p>
                    <div class="card-divider"></div>
                    <a href="#visibilidad" class="btn-link-action" style="color: #F39C12;">Subir Nivel <i class="bi bi-graph-up-arrow"></i></a>
                </div>
            </div>
        </div>
    </div>

    <section id="marketing" class="content-section">
        <div class="row align-items-center">
            <div class="col-md-6">
                <span class="section-tag">Fotografía Profesional</span>
                <h2 class="fw-bold mb-4">Domina el Marketing Visual</h2>
                <p class="text-muted">El 90% de los compradores deciden basándose en las fotos. Aquí aprenderás a usar la luz a tu favor...</p>
                <ul class="list-unstyled">
                    <li class="mb-2"><i class="bi bi-check2-circle text-success me-2"></i> Fotos en horizontal siempre.</li>
                    <li class="mb-2"><i class="bi bi-check2-circle text-success me-2"></i> Limpieza y orden (Home Staging).</li>
                </ul>
            </div>
            <div class="col-md-6">
                <img src="https://images.unsplash.com/photo-1512917774080-9991f1c4c750?auto=format&fit=crop&w=600&q=80" class="img-fluid rounded-4 shadow" alt="Marketing">
            </div>
        </div>
    </section>

    <section id="legal" class="content-section">
        <div class="row align-items-center">
            <div class="col-md-6 order-md-2">
                <span class="section-tag">Documentación Legal</span>
                <h2 class="fw-bold mb-4">Tu Seguridad es Primero</h2>
                <p class="text-muted">Evita fraudes y problemas legales con nuestra documentación certificada.</p>
                <div class="d-grid gap-2 d-md-flex">
                    <button class="btn btn-outline-success">Descargar Contrato Renta</button>
                    <button class="btn btn-outline-success">Descargar Contrato Venta</button>
                </div>
            </div>
            <div class="col-md-6 order-md-1 text-center">
                <i class="bi bi-file-earmark-pdf" style="font-size: 8rem; color: var(--brand-green);"></i>
            </div>
        </div>
    </section>

    <section id="visibilidad" class="content-section">
        <div class="row align-items-center">
            <div class="col-md-12 text-center">
                <span class="section-tag">Impulsa tu Anuncio</span>
                <h2 class="fw-bold mb-4">Planes de Visibilidad Pro</h2>
                <div class="row justify-content-center">
                    <div class="col-md-4 p-3">
                        <div class="p-4 bg-white rounded-4 shadow-sm border border-warning">
                            <h4 class="fw-bold">Plan Gold</h4>
                            <p>3x más vistas que un anuncio normal.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="cta-container shadow-lg">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h2 class="fw-bold mb-3" style="font-size: 2.5rem;">¿Aún tienes dudas?</h2>
                <p class="mb-0 opacity-75 fs-5">Nuestros expertos están listos para ayudarte con la valoración gratuita de tu inmueble.</p>
            </div>
            <div class="col-lg-4 text-center text-lg-end mt-4 mt-lg-0">
                <a href="https://wa.me/tu_numero" class="btn-whatsapp text-decoration-none shadow">
                    <i class="bi bi-whatsapp fs-4"></i> WhatsApp
                </a>
            </div>
        </div>
        <div style="position: absolute; right: -20px; bottom: -20px; font-size: 10rem; opacity: 0.1; color: white;">
            <i class="bi bi-house"></i>
        </div>
    </div>
</div>
@endsection
