@extends('welcome')

@section('content')

<div class="container-fluid bg-light py-5">
    <div class="container">
        <div class="text-center mx-auto mb-5 wow fadeIn" data-wow-delay="0.1s" style="max-width: 600px;">
            <h1 class="display-5 mb-4">{{auto_trans('Términos y Condiciones de Uso')}}</h1>
            <h6 class="text-primary text-uppercase fw-bold mb-2">{{auto_trans('Plataforma www.ebuyproperties.com — Ebuy Properties')}}</h6>
        </div>
        <p class="text-muted">
            {{auto_trans('Los presentes Términos y Condiciones de Uso (los "Términos") regulan el acceso y uso del sitio web www.ebuyproperties.com y de la plataforma en él alojada (la "Plataforma"), operada por Grupo Menbol Multimedia, S.A.P.I. de C.V. ("Ebuy Properties", "nosotros"), con domicilio en Calle 27 número 494, entre las calles 56 y 56-A, Colonia Itzimná, C.P. 97100, Mérida, Yucatán. Al acceder o utilizar la Plataforma, usted (el "Usuario") declara haber leído, entendido y aceptado estos Términos. Si no está de acuerdo, deberá abstenerse de usar la Plataforma.')}}
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
                        <a href="{{ asset('ebuy/Terminos y Condiciones Ebuy.pdf') }}" target="_blank" class="btn btn-primary d-inline-flex align-items-center gap-2">
                            <i class="fas fa-file-pdf"></i>
                            {{ auto_trans('Descargar PDF') }}
                        </a>
                    </div>
                    
                    <div class="terms-section mb-4">
                        <h5 class="text-primary mb-3">{{ auto_trans('1. Definiciones') }}</h5>
                        <ul class="list-group list-group-flush mb-3">
                            <li class="list-group-item">{{ auto_trans('Plataforma: el sitio web y los servicios digitales operados por Ebuy Properties para la publicación y difusión de anuncios inmobiliarios.') }}</li>
                            <li class="list-group-item">{{ auto_trans('Usuario: toda persona que accede o utiliza la Plataforma, ya sea para publicar anuncios ("Anunciante") o para consultar o contactar respecto de inmuebles ("Usuario Interesado").') }}</li>
                            <li class="list-group-item">{{ auto_trans('Anuncio: la publicación mediante la cual un Anunciante ofrece un inmueble para su venta, arrendamiento o cualquier otra forma de utilización.') }}</li>
                            <li class="list-group-item">{{ auto_trans('Contenido: textos, imágenes, datos y demás información que el Usuario incorpora a la Plataforma.') }}</li>
                        </ul>
                    </div>

                    <div class="terms-section mb-4">
                        <h5 class="text-primary mb-3">{{ auto_trans('2. Objeto y naturaleza del servicio') }}</h5>
                        <p class="mb-3">{{ auto_trans('Ebuy Properties es única y exclusivamente una plataforma tecnológica de intermediación que permite la publicación y difusión de anuncios inmobiliarios y facilita el contacto entre Anunciantes y Usuarios Interesados.') }}</p>
                        <p>{{ auto_trans('Ebuy Properties no es propietaria, agente, corredora, asesora ni parte de ninguna operación de compraventa, arrendamiento o utilización de inmuebles que se pacte entre los Usuarios. En consecuencia, Ebuy Properties no interviene en la negociación de dichas operaciones, no recibe, custodia ni administra el dinero de las mismas, y no garantiza su celebración, perfeccionamiento ni cumplimiento. Toda operación se pacta directa y exclusivamente entre los Usuarios, bajo su propia responsabilidad.') }}</p>
                    </div>

                    <div class="terms-section mb-4">
                        <h5 class="text-primary mb-3">{{ auto_trans('3. Aceptación y modificaciones a los Términos') }}</h5>
                        <p>{{ auto_trans('El uso de la Plataforma implica la aceptación plena de estos Términos. Ebuy Properties podrá modificarlos en cualquier momento; las modificaciones surtirán efecto a partir de su publicación en la Plataforma. El uso continuado de la Plataforma con posterioridad a dichas modificaciones constituye su aceptación.') }}</p>
                    </div>

                    <div class="terms-section mb-4">
                        <h5 class="text-primary mb-3">{{ auto_trans('4. Registro y cuenta de Usuario') }}</h5>
                        <p>{{ auto_trans('Para acceder a determinadas funciones, el Usuario deberá crear una cuenta y proporcionar información veraz, completa y actualizada. El Usuario es responsable de la confidencialidad de su contraseña y de toda actividad realizada desde su cuenta, y deberá notificar a Ebuy Properties cualquier uso no autorizado. Ebuy Properties podrá suspender o cancelar cuentas que incumplan estos Términos.') }}</p>
                    </div>

                    <div class="terms-section mb-4">
                        <h5 class="text-primary mb-3">{{ auto_trans('5. Obligaciones y declaraciones del Anunciante') }}</h5>
                        <p class="mb-3">{{ auto_trans('Al publicar un Anuncio, el Anunciante declara y garantiza que:') }}</p>
                        <ul class="list-group list-group-flush mb-3">
                            <li class="list-group-item">{{ auto_trans('Es el propietario del inmueble o cuenta con la facultad o autorización suficiente para promocionarlo.') }}</li>
                            <li class="list-group-item">{{ auto_trans('La información y las imágenes del Anuncio son veraces, exactas, lícitas, propias o cuenta con los derechos para su uso, y no inducen a error.') }}</li>
                            <li class="list-group-item">{{ auto_trans('El Anuncio y la operación que en su caso derive cumplen con la legislación aplicable.') }}</li>
                            <li class="list-group-item">{{ auto_trans('La publicación no infringe derechos de terceros ni disposición legal alguna.') }}</li>
                        </ul>
                        <p>{{ auto_trans('El Anunciante es el único responsable del Contenido que publica y de las operaciones que celebre con los Usuarios Interesados.') }}</p>
                    </div>

                    <div class="terms-section mb-4">
                        <h5 class="text-primary mb-3">{{ auto_trans('6. Ausencia de verificación') }}</h5>
                        <p>{{ auto_trans('Ebuy Properties no verifica la titularidad de los inmuebles, la veracidad de los Anuncios ni la identidad o solvencia de los Usuarios. La responsabilidad sobre la exactitud, legalidad y legitimidad del Contenido y de las operaciones corresponde exclusivamente a los Usuarios. Ebuy Properties recomienda a los Usuarios Interesados verificar de manera independiente la información y la situación jurídica de cualquier inmueble antes de contratar.') }}</p>
                    </div>

                    <div class="terms-section mb-4">
                        <h5 class="text-primary mb-3">{{ auto_trans('7. Conducta prohibida') }}</h5>
                        <p class="mb-3">{{ auto_trans('El Usuario se obliga a no utilizar la Plataforma para:') }}</p>
                        <ul class="list-group list-group-flush mb-3">
                            <li class="list-group-item">{{ auto_trans('Publicar Anuncios o Contenido falso, engañoso, ilícito, difamatorio, discriminatorio u ofensivo.') }}</li>
                            <li class="list-group-item">{{ auto_trans('Promocionar inmuebles sobre los que carece de derechos o facultades.') }}</li>
                            <li class="list-group-item">{{ auto_trans('Suplantar la identidad de terceros o proporcionar datos falsos.') }}</li>
                            <li class="list-group-item">{{ auto_trans('Enviar comunicaciones no solicitadas (spam), código malicioso o realizar extracción masiva de datos (scraping).') }}</li>
                            <li class="list-group-item">{{ auto_trans('Vulnerar la seguridad de la Plataforma o infringir derechos de propiedad intelectual.') }}</li>
                        </ul>
                        <p>{{ auto_trans('Ebuy Properties podrá retirar cualquier Anuncio o Contenido, y suspender o cancelar cuentas, cuando presuma el incumplimiento de estos Términos, sin responsabilidad alguna.') }}</p>
                    </div>

                    <div class="terms-section mb-4">
                        <h5 class="text-primary mb-3">{{ auto_trans('8. Planes, precios y pagos') }}</h5>
                        <p class="mb-3">{{ auto_trans('Ebuy Properties ofrece servicios de pago consistentes en la publicación de anuncios y en planes de suscripción. Las características, vigencia y precios de cada plan se indican en la Plataforma al momento de la contratación.') }}</p>
                        <ul class="list-group list-group-flush mb-3">
                            <li class="list-group-item">{{ auto_trans('Los precios se expresan en pesos mexicanos (MXN) e incluyen o adicionan el Impuesto al Valor Agregado (IVA) según se indique.') }}</li>
                            <li class="list-group-item">{{ auto_trans('Las suscripciones podrán renovarse de forma automática por periodos iguales, salvo que el Usuario cancele antes de la fecha de renovación.') }}</li>
                            <li class="list-group-item">{{ auto_trans('El Usuario podrá cancelar su plan o suscripción en cualquier momento; la cancelación surte efectos al término del periodo ya pagado y no genera devolución proporcional, salvo disposición legal en contrario.') }}</li>
                            <li class="list-group-item">{{ auto_trans('Salvo que la ley disponga lo contrario, los pagos por servicios ya prestados o anuncios ya publicados no son reembolsables.') }}</li>
                            <li class="list-group-item">{{ auto_trans('A solicitud del Usuario se emitirá el CFDI correspondiente, con los datos fiscales que éste proporcione.') }}</li>
                            <li class="list-group-item">{{ auto_trans('Ebuy Properties podrá modificar sus precios; los cambios no afectarán periodos ya contratados y se comunicarán con antelación razonable.') }}</li>
                        </ul>
                    </div>

                    <div class="terms-section mb-4">
                        <h5 class="text-primary mb-3">{{ auto_trans('9. Propiedad intelectual') }}</h5>
                        <p>{{ auto_trans('La Plataforma, su software, diseño, marcas, logotipos y demás elementos son propiedad de Ebuy Properties o de sus licenciantes y están protegidos por la legislación aplicable. El Usuario no adquiere derecho alguno sobre ellos. Al publicar Contenido, el Usuario otorga a Ebuy Properties una licencia no exclusiva, gratuita y para el territorio en que opera la Plataforma, a fin de alojar, reproducir y exhibir dicho Contenido con el objeto de prestar el servicio; el Usuario conserva la titularidad de su Contenido y declara contar con los derechos para otorgar dicha licencia.') }}</p>
                    </div>

                    <div class="terms-section mb-4">
                        <h5 class="text-primary mb-3">{{ auto_trans('10. Limitación de responsabilidad') }}</h5>
                        <p class="mb-3">{{ auto_trans('La Plataforma se proporciona "tal cual" y "según disponibilidad". Ebuy Properties no garantiza que el servicio sea ininterrumpido o libre de errores. En la máxima medida permitida por la ley, Ebuy Properties no será responsable por:') }}</p>
                        <ul class="list-group list-group-flush mb-3">
                            <li class="list-group-item">{{ auto_trans('La veracidad, legalidad, calidad o situación jurídica de los inmuebles anunciados;') }}</li>
                            <li class="list-group-item">{{ auto_trans('Los tratos, negociaciones u operaciones celebradas entre Usuarios;') }}</li>
                            <li class="list-group-item">{{ auto_trans('Los daños o perjuicios derivados de dichas operaciones; ni') }}</li>
                            <li class="list-group-item">{{ auto_trans('Daños indirectos, incidentales o consecuenciales.') }}</li>
                        </ul>
                        <p>{{ auto_trans('La relación entre Anunciantes y Usuarios Interesados es directa y ajena a Ebuy Properties.') }}</p>
                    </div>

                    <div class="terms-section mb-4">
                        <h5 class="text-primary mb-3">{{ auto_trans('11. Indemnización') }}</h5>
                        <p>{{ auto_trans('El Usuario se obliga a sacar en paz y a salvo e indemnizar a Ebuy Properties, sus socios, representantes y empleados, respecto de cualquier reclamación, daño, pérdida o gasto (incluidos honorarios legales razonables) que derive del Contenido que publique, del uso que haga de la Plataforma o del incumplimiento de estos Términos o de la ley.') }}</p>
                    </div>

                    <div class="terms-section mb-4">
                        <h5 class="text-primary mb-3">{{ auto_trans('12. Suspensión y terminación') }}</h5>
                        <p>{{ auto_trans('Ebuy Properties podrá suspender, limitar o cancelar el acceso del Usuario a la Plataforma, retirar Anuncios y dar por terminada la relación, en cualquier momento y sin responsabilidad, cuando exista incumplimiento a estos Términos o a la ley. El Usuario podrá dar de baja su cuenta cuando lo desee.') }}</p>
                    </div>

                    <div class="terms-section mb-4">
                        <h5 class="text-primary mb-3">{{ auto_trans('13. Enlaces y servicios de terceros') }}</h5>
                        <p>{{ auto_trans('La Plataforma puede contener enlaces a sitios o servicios de terceros. Ebuy Properties no controla ni respalda dicho contenido y no es responsable de él ni de sus políticas.') }}</p>
                    </div>

                    <div class="terms-section mb-4">
                        <h5 class="text-primary mb-3">{{ auto_trans('14. Privacidad') }}</h5>
                        <p>{{ auto_trans('El tratamiento de los datos personales de los Usuarios se rige por el Aviso de Privacidad de Ebuy Properties, disponible en la Plataforma, que forma parte integrante de estos Términos.') }}</p>
                    </div>

                    <div class="terms-section mb-4">
                        <h5 class="text-primary mb-3">{{ auto_trans('15. Notificaciones') }}</h5>
                        <p>{{ auto_trans('Las comunicaciones al Usuario podrán realizarse a través de la Plataforma o del correo electrónico registrado. Las comunicaciones a Ebuy Properties deberán dirigirse a contacto@menbol.com.') }}</p>
                    </div>

                    <div class="terms-section mb-4">
                        <h5 class="text-primary mb-3">{{ auto_trans('16. Legislación aplicable y jurisdicción') }}</h5>
                        <p>{{ auto_trans('Estos Términos se rigen por las leyes de los Estados Unidos Mexicanos. Para la interpretación y cumplimiento de los mismos, las partes se someten a la jurisdicción de los tribunales competentes de la ciudad de Mérida, Yucatán, renunciando a cualquier otro fuero que pudiera corresponderles por razón de sus domicilios presentes o futuros. Lo anterior sin perjuicio de los derechos que la legislación en materia de protección al consumidor conceda a los Usuarios.') }}</p>
                    </div>

                    <div class="terms-section mb-4">
                        <h5 class="text-primary mb-3">{{ auto_trans('17. Divisibilidad') }}</h5>
                        <p>{{ auto_trans('Si alguna disposición de estos Términos fuere declarada nula o inaplicable, las demás conservarán plena validez.') }}</p>
                    </div>

                    <div class="terms-section mb-4">
                        <h5 class="text-primary mb-3">{{ auto_trans('18. Acuerdo íntegro') }}</h5>
                        <p>{{ auto_trans('Estos Términos, junto con el Aviso de Privacidad, constituyen el acuerdo íntegro entre el Usuario y Ebuy Properties respecto del uso de la Plataforma.') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Terms and Conditions End -->

<style>
    .terms-section {
        background: #f8f9fa;
        padding: 20px;
        border-radius: 8px;
        border-left: 4px solid #00B98E;
    }

    .terms-section h5 {
        color: #0E2E50;
        font-weight: 600;
    }

    .terms-section p {
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
</style>
@endsection
