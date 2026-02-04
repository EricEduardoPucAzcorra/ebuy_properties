<!-- MODAL DE PAGO - CORREGIDO -->
<div class="modal fade"
     id="modal_home_owner"
     tabindex="-1"
     data-bs-focus="false"  data-bs-backdrop="static" aria-hidden="true">

    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">

            <!-- LOADING -->
            <div v-if="loadingModal"
                 class="modal-body d-flex flex-column justify-content-center align-items-center"
                 style="min-height:300px">
                <div class="spinner-border text-success"></div>
                <p class="mt-3 mb-0">Preparando plan...</p>
            </div>

            <!-- CONTENIDO DEL PAGO -->
            <div v-else>
                <div class="modal-header bg-light">
                    <h5 class="modal-title fw-bold" id="paymentModalLabel">
                        Configura tu plan
                    </h5>
                    <button type="button"
                            class="btn-close"
                            data-bs-dismiss="modal"
                            aria-label="Cerrar"></button>
                </div>

                <div class="modal-body">
                    <div class="row g-4">

                        <!-- COLUMNA IZQUIERDA: FORMULARIO -->
                        <div class="col-md-7">
                            <h6 class="fw-bold mb-3 text-dark">Forma de pago</h6>

                            <form @submit.prevent="processPayment" id="paymentForm">
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Nombre en la tarjeta</label>
                                    <input type="text"
                                           class="form-control border-secondary"
                                           v-model="payment.name"
                                           required
                                           placeholder="Juan Pérez">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Número de tarjeta</label>
                                    <input type="text"
                                           class="form-control border-secondary"
                                           v-model="payment.card"
                                           required
                                           @input="formatCardNumber"
                                           placeholder="1234 5678 9012 3456">
                                </div>

                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Expira</label>
                                        <input type="text"
                                               class="form-control border-secondary"
                                               v-model="payment.exp"
                                               required
                                               @input="formatExpiration"
                                               placeholder="MM/AA">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">CVV</label>
                                        <input type="text"
                                               class="form-control border-secondary"
                                               v-model="payment.cvv"
                                               required
                                               @input="formatCVV"
                                               placeholder="123">
                                    </div>
                                </div>

                                <button type="submit"
                                        class="btn btn-success w-100 fw-bold py-3"
                                        :disabled="processingPayment">
                                    <span v-if="processingPayment"
                                          class="spinner-border spinner-border-sm me-2"></span>
                                    Pagar $@{{ selectedPlan ? selectedPlan.price.toLocaleString() : '0' }}
                                </button>
                            </form>
                        </div>

                        <!-- COLUMNA DERECHA: RESUMEN -->
                        <div class="col-md-5">
                            <div class="border rounded-3 p-4 h-100 bg-light">
                                <h5 class="fw-bold text-success mb-3">
                                    @{{ selectedPlan ? selectedPlan.name : 'Condominio' }}
                                </h5>

                                <p class="text-muted mb-3">
                                    @{{ selectedPlan ? selectedPlan.description : 'prueba dev' }}
                                </p>

                                <ul class="list-unstyled mb-4">
                                    <li v-for="(feature, index) in selectedPlanFeatures"
                                        :key="index"
                                        class="mb-2 d-flex align-items-center">
                                        <i class="bi bi-check-circle-fill text-success me-2"></i>
                                        <span class="small">@{{ feature.name || feature }}</span>
                                    </li>
                                </ul>

                                <hr class="my-4">

                                <div class="d-flex justify-content-between fw-bold fs-5 mb-3">
                                    <span>Total hoy</span>
                                    <span class="text-success">
                                        $@{{ selectedPlan ? selectedPlan.price.toLocaleString() : '0' }}
                                    </span>
                                </div>

                                <small class="text-muted d-block">
                                    Se renueva mensualmente hasta que canceles.
                                </small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- FOOTER DEL MODAL -->
                <div class="modal-footer border-top bg-light py-3">
                    <small class="text-muted text-center w-100">
                        © 2026 Desarrollando Ideas<br>
                        <span class="fw-semibold">Pos Finance</span>
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* 1. Asegura que el modal siempre esté sobre el fondo */
.modal {
    z-index: 1060 !important;
}
.modal-backdrop {
    z-index: 1050 !important;
}

/* 2. Si el fondo se queda "pegado" al cerrar */
body.modal-open {
    overflow: auto !important;
    padding-right: 0 !important;
}

</style>
