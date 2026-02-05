<div id="planOwner" class="container py-5">
    <!-- Encabezado -->
    <div class="text-center mb-5">
        <h2 class="fw-bold">
            Publica, Gestiona y Llena tu Condominio
        </h2>
        <p class="text-muted fs-5">
            Elige el plan que mejor se adapte a tus objetivos 🚀
        </p>
    </div>

    <!-- Botón volver a planes -->
    <div v-if="showPaymentView" class="mb-4">
        <button @click="backToPlans" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-2"></i> Volver a planes
        </button>
    </div>

    <!-- Loading -->
    <div v-if="loadingView" class="text-center py-5">
        <div class="spinner-border text-success" style="width: 3rem; height: 3rem;"></div>
        <p class="mt-3 mb-0 fs-5">Cargando...</p>
    </div>

    <!-- Lista de planes o mensaje vacío -->
    <div v-else-if="!showPaymentView" class="row g-4 justify-content-center">

        <!-- Mensaje si no hay planes -->
        <div v-if="plans.length === 0" class="text-center py-5">
            <i class="bi bi-inbox display-4 text-muted mb-3"></i>
            <h5 class="fw-bold">No hay planes disponibles</h5>
            <p class="text-muted">
                Por el momento no tenemos planes activos. Por favor, vuelve más tarde.
            </p>
        </div>

        <!-- Cards de planes -->
        <div v-else class="col-md-4" v-for="plan in plans" :key="plan.id">
            <div class="card plan-card h-100 position-relative"
                 :class="{ 'shadow-selected': selectedPlan && selectedPlan.id === plan.id }">

                <span v-if="plan.is_featured" class="badge badge-featured">
                    Recomendado
                </span>

                <div class="card-body text-center p-4 d-flex flex-column">
                    <h4 class="fw-bold mb-1">@{{ plan.name }}</h4>
                    <p class="text-muted small mb-3">@{{ plan.description }}</p>

                    <h3 class="fw-bold text-success mb-4">
                        <span v-if="plan.price == 0">Gratis</span>
                        <span v-else>
                            $@{{ plan.price.toLocaleString() }}
                            <small class="text-muted fs-6">/mes</small>
                        </span>
                    </h3>

                    <ul class="features-list list-unstyled mb-4">
                        <li v-for="feature in plan.features" :key="feature.id">
                            <i class="bi bi-check-circle-fill text-success me-2"></i>
                            <span class="fw-semibold">@{{ feature.name }}</span>
                            <span v-if="feature.pivot?.mount" class="text-muted ms-1">
                                (@{{ feature.pivot.mount }} @{{ feature.pivot.description }})
                            </span>
                        </li>
                    </ul>

                    <button class="btn btn-success fw-bold w-100 mt-4"
                            @click="selectPlan(plan)">
                        Seleccionar plan
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Vista de pago -->
    <div v-else class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card border-0 shadow-lg">
                <div class="card-body p-4 p-lg-5">

                    <!-- Breadcrumb -->
                    <div class="mb-4">
                        <h2 class="fw-bold mb-2">Configura tu plan</h2>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-0">
                                <li class="breadcrumb-item"><a href="#" @click.prevent="backToPlans">Planes</a></li>
                                <li class="breadcrumb-item active" aria-current="page">@{{ selectedPlan.name }}</li>
                            </ol>
                        </nav>
                    </div>

                    <div class="row g-4">
                        <!-- Formulario de pago -->
                        <div class="col-lg-7">
                            <div class="border rounded-3 p-4">
                                <h4 class="fw-bold mb-4">
                                    <i class="bi bi-credit-card text-success me-2"></i>
                                    Forma de pago
                                </h4>

                                <form @submit.prevent="processPayment">
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Nombre en la tarjeta</label>
                                        <input type="text" class="form-control form-control-lg"
                                               v-model="payment.name" required placeholder="Juan Pérez">
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Número de tarjeta</label>
                                        <input type="text" class="form-control form-control-lg"
                                               v-model="payment.card" required
                                               @input="formatCardNumber"
                                               placeholder="1234 5678 9012 3456">
                                    </div>

                                    <div class="row mb-4">
                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold">Expira</label>
                                            <input type="text" class="form-control form-control-lg"
                                                   v-model="payment.exp" required
                                                   @input="formatExpiration"
                                                   placeholder="MM/AA">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold">CVV</label>
                                            <input type="text" class="form-control form-control-lg"
                                                   v-model="payment.cvv" required
                                                   @input="formatCVV"
                                                   placeholder="123">
                                        </div>
                                    </div>

                                    <button type="submit"
                                            class="btn btn-success btn-lg w-100 fw-bold py-3"
                                            :disabled="processingPayment">
                                        <span v-if="processingPayment" class="spinner-border spinner-border-sm me-2"></span>
                                        Pagar $@{{ selectedPlan.price.toLocaleString() }}
                                    </button>
                                </form>
                            </div>
                        </div>

                        <!-- Resumen del plan -->
                        <div class="col-lg-5">
                            <div class="border rounded-3 p-4 h-100 bg-light">
                                <div class="text-center mb-4">
                                    <h4 class="fw-bold text-success mb-2">@{{ selectedPlan.name }}</h4>
                                    <p class="text-muted mb-0">@{{ selectedPlan.description }}</p>
                                </div>

                                <ul class="list-unstyled mb-4">
                                    <li v-for="feature in selectedPlan.features" :key="feature.id"
                                        class="mb-3 p-3 border rounded d-flex align-items-center">
                                        <i class="bi bi-check-circle-fill text-success fs-4 me-3"></i>
                                        <div>
                                            <span class="fw-semibold d-block">@{{ feature.name }}</span>
                                            <small v-if="feature.pivot?.mount" class="text-muted">
                                                (@{{ feature.pivot.mount }} @{{ feature.pivot.description }})
                                            </small>
                                        </div>
                                    </li>
                                </ul>

                                <hr class="my-4">

                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <span class="fs-5 fw-bold">Total hoy</span>
                                    <span class="fs-3 fw-bold text-success">
                                        $@{{ selectedPlan.price.toLocaleString() }}
                                    </span>
                                </div>

                                <div class="alert alert-success small mb-0">
                                    <i class="bi bi-info-circle me-2"></i>
                                    Se renueva mensualmente hasta que canceles.
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<style>
.plan-card {
    cursor: pointer;
    border-radius: 1.5rem;
    transition: all 0.3s;
    border: 2px solid #e9ecef;
    background: #f9fefb;
}

.plan-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 1rem 2rem rgba(25, 135, 84, 0.15);
    border-color: #20c997;
}

.shadow-selected {
    box-shadow: 0 0 1.5rem rgba(25, 135, 84, 0.25);
    border: 2px solid #198754 !important;
}

.badge-featured {
    position: absolute;
    top: 15px;
    right: 15px;
    padding: 0.5rem 1rem;
    font-size: 0.8rem;
    font-weight: 600;
    background: linear-gradient(90deg, #28a745, #20c997);
    color: white;
    border-radius: 2rem;
}

.form-control-lg {
    padding: 1rem 1.25rem;
    border-radius: 0.75rem;
    border: 2px solid #dee2e6;
}

.form-control-lg:focus {
    border-color: #198754;
    box-shadow: 0 0 0 0.25rem rgba(25, 135, 84, 0.25);
}

.btn-success {
    background: linear-gradient(135deg, #198754, #20c997);
    border: none;
    border-radius: 0.75rem;
    transition: all 0.3s;
}

.btn-success:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(25, 135, 84, 0.3);
}

.btn-success:disabled {
    opacity: 0.7;
    transform: none;
    box-shadow: none;
}

.btn-outline-secondary {
    border-radius: 0.75rem;
    padding: 0.5rem 1.5rem;
    font-weight: 600;
}

.spinner-border {
    width: 3rem;
    height: 3rem;
}

@media (max-width: 768px) {
    .card-body {
        padding: 1.5rem !important;
    }

    .form-control-lg {
        padding: 0.75rem 1rem;
    }
}
</style>
