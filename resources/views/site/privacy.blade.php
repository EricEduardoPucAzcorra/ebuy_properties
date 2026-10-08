@extends('welcome')

@section('content')

<div class="container-fluid bg-light py-5">
    <div class="container">
        <div class="text-center mx-auto mb-5 wow fadeIn" data-wow-delay="0.1s" style="max-width: 600px;">
            <h1 class="display-5 mb-4">{{auto_trans('Aviso de privacidad')}}</h1>
            <h6 class="text-primary text-uppercase fw-bold mb-2">{{auto_trans('Grupo Menbol Multimedia, S.A.P.I. de C.V. — “Ebuy Properties”')}}</h6>
        </div>
        <p class="text-muted">
            {{auto_trans('Grupo Menbol Multimedia, Sociedad Anónima Promotora de Inversión de Capital Variable (en adelante, “Ebuy Properties” o “el Responsable”), con domicilio en Calle 27 número 494, entre las calles 56 y 56-A, Colonia Itzimná, C.P.97100, Mérida, Yucatán, México, teléfono +52 999 908 3332 y correo electrónico contacto@menbol.com, es responsable del uso, protección y tratamiento de sus datos personales, y pone a su disposición el presente Aviso de Privacidad Integral en cumplimiento de la Ley Federal de Protección de Datos Personales en Posesión de los Particulares (en adelante, “la Ley”), publicada en el Diario Oficial de la Federación el 20 de marzo de 2025.
                Ebuy Properties opera la plataforma tecnológica accesible en www.ebuyproperties.com, dedicada a la publicación, difusión y comercialización de anuncios de bienes inmuebles, poniendo en contacto a las personas que ofrecen inmuebles con las personas interesadas en adquirirlos, arrendarlos o utilizarlos.
            ')}}
        </p>
    </div>
</div>

<div class="container-xxl py-5">
    <div class="container">
        <div class="row g-5">
            <div class="col-lg-12">
                <div class="mb-5">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-3 mb-4">
                        <h4 class="mb-0">{{ auto_trans('Última actualización') }}: {{ auto_trans('05 de agosto de 2026.') }}</h4>
                        <a href="{{ asset('ebuy/Aviso de privacidad Ebuy.pdf') }}" target="_blank" class="btn btn-primary d-inline-flex align-items-center gap-2">
                            <i class="fas fa-file-pdf"></i>
                            {{ auto_trans('Descargar PDF') }}
                        </a>
                    </div>
                    
                    <div class="privacy-section mb-4">
                        <h5 class="text-primary mb-3">{{ auto_trans('1. Datos personales que tratamos') }}</h5>
                        <p class="mb-3">{{ auto_trans('Para las finalidades descritas en este Aviso, el Responsable podrá tratar las siguientes categorías de datos personales:') }}</p>
                        <ul class="list-group list-group-flush mb-3">
                            <li class="list-group-item">{{ auto_trans('Datos de identificación y contacto del usuario: nombre, razón social o denominación; nombre del representante en el caso de personas morales; teléfono; correo electrónico; y, en general, cualquier dato útil para la comunicación entre el usuario y las personas interesadas.') }}</li>
                            <li class="list-group-item">{{ auto_trans('Datos del inmueble objeto de difusión: dirección y ubicación geográfica; dimensiones; fotografías; descripción de amenidades; precio de oferta; y datos catastrales.') }}</li>
                            <li class="list-group-item">{{ auto_trans('Datos financieros o patrimoniales: limitados exclusivamente a las formas y cuentas de las que provienen los pagos de las cuotas o contraprestaciones que el usuario realiza a Ebuy Properties por los servicios de la plataforma.') }}</li>
                            <li class="list-group-item">{{ auto_trans('Datos fiscales: en caso de requerir la emisión de comprobantes fiscales digitales (CFDI), los datos del contribuyente a cuyo favor se emitan.') }}</li>
                        </ul>
                        <p class="mb-3">{{ auto_trans('El Responsable no recaba ni trata datos personales sensibles. Tratándose de los datos financieros o patrimoniales antes señalados, su tratamiento requiere el consentimiento expreso del titular, el cual se otorga al momento de contratar y pagar los servicios de la plataforma.') }}</p>
                    </div>

                    <div class="privacy-section mb-4">
                        <h5 class="text-primary mb-3">{{ auto_trans('2. Finalidades del tratamiento') }}</h5>
                        
                        <h6 class="fw-bold mb-3 text-dark">{{ auto_trans('Finalidades necesarias (no requieren de su consentimiento adicional, pues dan origen y son indispensables para la relación con el Responsable):') }}</h6>
                        <ul class="list-group list-group-flush mb-4">
                            <li class="list-group-item">{{ auto_trans('Exhibir, difundir y comercializar en la plataforma el inmueble cuya promoción solicita el usuario.') }}</li>
                            <li class="list-group-item">{{ auto_trans('Poner en contacto al usuario anunciante con las personas interesadas en el inmueble.') }}</li>
                            <li class="list-group-item">{{ auto_trans('Crear, gestionar y dar acceso a su cuenta de usuario.') }}</li>
                            <li class="list-group-item">{{ auto_trans('Procesar los pagos de los planes, suscripciones y anuncios contratados.') }}</li>
                            <li class="list-group-item">{{ auto_trans('Emitir los comprobantes fiscales y cumplir con las obligaciones fiscales, contables y legales aplicables.') }}</li>
                            <li class="list-group-item">{{ auto_trans('Atender sus solicitudes, aclaraciones y brindar soporte.') }}</li>
                        </ul>

                        <h6 class="fw-bold mb-3 text-dark">{{ auto_trans('Finalidades que requieren su consentimiento (secundarias; no son necesarias para el servicio y usted puede negarse a ellas):') }}</h6>
                        <ul class="list-group list-group-flush mb-4">
                            <li class="list-group-item">{{ auto_trans('Envío de promociones, boletines, novedades y comunicaciones de mercadotecnia sobre la plataforma.') }}</li>
                            <li class="list-group-item">{{ auto_trans('Realización de encuestas, estudios y evaluaciones de calidad.') }}</li>
                            <li class="list-group-item">{{ auto_trans('Ofrecimiento de productos o servicios propios o de socios comerciales.') }}</li>
                        </ul>

                        <p class="mb-0 text-muted fst-italic">{{ auto_trans('Si usted no desea que sus datos se traten para las finalidades secundarias, puede manifestarlo enviando un correo a contacto@menbol.com. Su negativa no será motivo para que se le niegue el servicio contratado.') }}</p>
                    </div>

                    <div class="privacy-section mb-4">
                        <h5 class="text-primary mb-3">{{ auto_trans('3. Fundamento y consentimiento') }}</h5>
                        <p>{{ auto_trans('Conforme a la Ley, como regla general el consentimiento para el tratamiento de datos personales podrá ser tácito cuando, puesto a su disposición este Aviso, usted no manifieste oposición. Tratándose de datos financieros o patrimoniales, el consentimiento debe ser expreso. Al proporcionar sus datos, utilizar la plataforma o marcar la casilla de aceptación correspondiente, usted consiente el tratamiento de sus datos en los términos de este Aviso.') }}</p>
                    </div>

                    <div class="privacy-section mb-4">
                        <h5 class="text-primary mb-3">{{ auto_trans('4. Medios para limitar el uso o divulgación de sus datos') }}</h5>
                        <p>{{ auto_trans('Usted puede limitar el uso o divulgación de sus datos personales presentando una solicitud por escrito al correo contacto@menbol.com, en la que exponga las limitaciones que desea establecer y las causas que las justifican.') }}</p>
                    </div>

                    <div class="privacy-section mb-4">
                        <h5 class="text-primary mb-3">{{ auto_trans('5. Transferencias y remisiones') }}</h5>
                        <p class="mb-3">{{ auto_trans('Sus datos personales serán tratados con estricta confidencialidad por el personal de Ebuy Properties. El Responsable no realiza transferencias de datos personales que requieran de su consentimiento. Únicamente se hacen públicos, a través de la plataforma, los datos del anuncio que usted decide publicar.') }}</p>
                        <p>{{ auto_trans('Para operar el servicio, el Responsable puede apoyarse en proveedores (por ejemplo, servicios de alojamiento en la nube, pasarelas de pago y facturación) que tratan datos personales por cuenta y bajo las instrucciones del Responsable, en su carácter de encargados, lo cual no constituye una transferencia. El Responsable podrá revelar datos cuando lo exija una disposición legal o una resolución de autoridad competente.') }}</p>
                    </div>

                    <div class="privacy-section mb-4">
                        <h5 class="text-primary mb-3">{{ auto_trans('6. Derechos ARCO y revocación del consentimiento') }}</h5>
                        <p class="mb-3">{{ auto_trans('Usted tiene derecho a acceder, rectificar y cancelar sus datos personales, así como a oponerse a su tratamiento (derechos ARCO) y a revocar el consentimiento que haya otorgado. El ejercicio de estos derechos es gratuito. Para ello, deberá presentar una solicitud al correo contacto@menbol.com que contenga:') }}</p>
                        <ul class="list-group list-group-flush mb-3">
                            <li class="list-group-item">{{ auto_trans('Nombre, domicilio y medios electrónicos para recibir la respuesta.') }}</li>
                            <li class="list-group-item">{{ auto_trans('Documentos que acrediten su identidad y, en su caso, la personalidad de su representante.') }}</li>
                            <li class="list-group-item">{{ auto_trans('La descripción clara de los datos y del derecho que desea ejercer.') }}</li>
                            <li class="list-group-item">{{ auto_trans('Las razones que motivan su solicitud, cuando ello aplique.') }}</li>
                        </ul>
                        <p>{{ auto_trans('El Responsable dará respuesta a su solicitud dentro de los plazos previstos por la Ley, a través del medio de contacto que usted haya señalado.') }}</p>
                    </div>

                    <div class="privacy-section mb-4">
                        <h5 class="text-primary mb-3">{{ auto_trans('7. Uso de cookies') }}</h5>
                        <p>{{ auto_trans('La plataforma utiliza cookies para personalizar su navegación, simplificar el inicio de sesión, recordar sus preferencias y analizar el uso del sitio. Las cookies son pequeños archivos de texto que su navegador almacena en su dispositivo. Usted puede configurar su navegador para no aceptarlas; sin embargo, ello podría limitar el uso de ciertas funciones (como "favoritos", "alertas por correo" o "contactar al anunciante"). Al utilizar el sitio sin desactivar las cookies, usted consiente su uso para los fines indicados.') }}</p>
                    </div>

                    <div class="privacy-section mb-4">
                        <h5 class="text-primary mb-3">{{ auto_trans('8. Enlaces a otros sitios') }}</h5>
                        <p>{{ auto_trans('La plataforma puede incluir enlaces a sitios web, aplicaciones o plataformas de terceros. El Responsable no controla ni se hace responsable del contenido, servicios ni de las prácticas de privacidad de dichos terceros, por lo que le recomendamos revisar sus respectivos avisos y políticas.') }}</p>
                    </div>

                    <div class="privacy-section mb-4">
                        <h5 class="text-primary mb-3">{{ auto_trans('9. Seguridad de la información') }}</h5>
                        <p class="mb-3">{{ auto_trans('El Responsable ha implementado y mantiene medidas de seguridad administrativas, técnicas y físicas razonables para proteger sus datos personales contra daño, pérdida, alteración, destrucción o el uso, acceso o tratamiento no autorizados, incluyendo el cifrado de las comunicaciones en la captura de datos bancarios. Su contraseña es la clave de su cuenta; le recomendamos utilizar una contraseña robusta y no compartirla. En caso de que su contraseña se vea comprometida, deberá notificarlo de inmediato al correo contacto@menbol.com. Ningún medio de transmisión por Internet es completamente seguro, por lo que el Responsable no puede garantizar seguridad absoluta frente a intercepciones ilícitas por parte de terceros.') }}</p>
                    </div>

                    <div class="privacy-section mb-4">
                        <h5 class="text-primary mb-3">{{ auto_trans('10. Autoridad en materia de protección de datos') }}</h5>
                        <p>{{ auto_trans('Si usted considera que su derecho a la protección de datos personales ha sido vulnerado, o presume alguna infracción a la Ley, podrá acudir a la Secretaría Anticorrupción y Buen Gobierno, autoridad competente en la materia.') }}</p>
                    </div>

                    <div class="privacy-section mb-4">
                        <h5 class="text-primary mb-3">{{ auto_trans('11. Modificaciones al presente Aviso') }}</h5>
                        <p>{{ auto_trans('El Responsable podrá modificar o actualizar este Aviso de Privacidad en cualquier momento. Cualquier cambio se dará a conocer a través de la propia plataforma www.ebuyproperties.com y/o por los medios de contacto que usted haya proporcionado.') }}</p>
                    </div>

                    <div class="privacy-section mb-4">
                        <h5 class="text-primary mb-3">{{ auto_trans('12. Aceptación') }}</h5>
                        <p>{{ auto_trans('Al proporcionar sus datos personales, utilizar la plataforma o marcar la casilla de aceptación, usted manifiesta haber leído, entendido y aceptado los términos de este Aviso de Privacidad, y otorga su consentimiento para el tratamiento de sus datos en los términos aquí descritos y conforme a la Ley.') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Privacy Policy End -->

<style>
    .privacy-section {
        background: #f8f9fa;
        padding: 20px;
        border-radius: 8px;
        border-left: 4px solid #00B98E;
    }

    .privacy-section h5 {
        color: #0E2E50;
        font-weight: 600;
    }

    .privacy-section p {
        color: #4a5568;
        line-height: 1.6;
    }

    .list-group-item {
        border: none;
        padding-left: 20px;
        color: #4a5568;
    }

    .list-group-item::before {
        content: "•";
        color: #00B98E;
        font-weight: bold;
        display: inline-block;
        width: 1em;
        margin-left: -1em;
    }

    .hover-lift {
        transition: transform 0.3s ease, opacity 0.3s ease;
    }

    .hover-lift:hover {
        transform: translateY(-2px);
        opacity: 1;
    }

    @media (max-width: 768px) {
        .display-3 {
            font-size: 2rem;
        }
        
        .fa-3x {
            font-size: 2rem;
        }
    }
</style>
@endsection
