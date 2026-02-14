<div class="container-fluid py-5">
    <div class="container">
        <div class="row g-4 align-items-start">
            <div class="col-lg-5">
                <h2 class="section-title fw-bold mb-3">{{ auto_trans('Preguntas frecuentes') }}</h2>
                <p class="text-muted mb-4">{{ auto_trans('Resolvemos lo más común para que tomes decisiones con confianza.') }}</p>
                <div class="ebuy-faq-note">
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-shield-check text-success"></i>
                        <span class="fw-semibold">{{ auto_trans('Transparencia y control') }}</span>
                    </div>
                    <div class="text-muted small mt-2">{{ auto_trans('Nuestra prioridad es que encuentres información clara y anuncios de calidad.') }}</div>
                </div>
            </div>

            <div class="col-lg-7">
                <div class="accordion ebuy-accordion" id="ebuyFaq">
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="faqOne">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faqOneCollapse" aria-expanded="true" aria-controls="faqOneCollapse">
                                {{ auto_trans('¿Publicar una propiedad tiene costo?') }}
                            </button>
                        </h2>
                        <div id="faqOneCollapse" class="accordion-collapse collapse show" aria-labelledby="faqOne" data-bs-parent="#ebuyFaq">
                            <div class="accordion-body text-muted">
                                {{ auto_trans('Puedes comenzar con opciones accesibles según tu plan. Si eres propietario, crea tu cuenta y revisa los planes disponibles.') }}
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item">
                        <h2 class="accordion-header" id="faqTwo">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faqTwoCollapse" aria-expanded="false" aria-controls="faqTwoCollapse">
                                {{ auto_trans('¿Cómo contacto al anunciante?') }}
                            </button>
                        </h2>
                        <div id="faqTwoCollapse" class="accordion-collapse collapse" aria-labelledby="faqTwo" data-bs-parent="#ebuyFaq">
                            <div class="accordion-body text-muted">
                                {{ auto_trans('Entra al detalle de la propiedad y utiliza el formulario de contacto o los datos disponibles para coordinar una visita.') }}
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item">
                        <h2 class="accordion-header" id="faqThree">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faqThreeCollapse" aria-expanded="false" aria-controls="faqThreeCollapse">
                                {{ auto_trans('¿Las propiedades están verificadas?') }}
                            </button>
                        </h2>
                        <div id="faqThreeCollapse" class="accordion-collapse collapse" aria-labelledby="faqThree" data-bs-parent="#ebuyFaq">
                            <div class="accordion-body text-muted">
                                {{ auto_trans('Los anuncios los publican nuestros usuarios. Te recomendamos verificar información y visitar antes de concretar. Trabajamos para elevar la calidad de los anuncios.') }}
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item">
                        <h2 class="accordion-header" id="faqFour">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faqFourCollapse" aria-expanded="false" aria-controls="faqFourCollapse">
                                {{ auto_trans('¿Puedo filtrar por ciudad o estado?') }}
                            </button>
                        </h2>
                        <div id="faqFourCollapse" class="accordion-collapse collapse" aria-labelledby="faqFour" data-bs-parent="#ebuyFaq">
                            <div class="accordion-body text-muted">
                                {{ auto_trans('Sí. Escribe tu ubicación en el buscador y selecciona una opción sugerida. Eso aplicará el filtro automáticamente.') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>