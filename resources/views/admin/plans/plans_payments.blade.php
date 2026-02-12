<div id="planOwner">

    <div class="text-center mb-5">
        <h1 class="fw-bold">
           {{ auto_trans('Publica tus inmuebles con Ebuy Properties')}}
        </h1>
        <p class="text-muted fs-5">
            {{ auto_trans('Sigue estos 3 simples pasos para activar tu plan')}}
        </p>
    </div>

    <div v-if="loadingView" class="text-center py-5">
        <div class="spinner-border text-success" style="width: 3rem; height: 3rem;"></div>
        <p class="mt-3 mb-0 fs-5">Cargando información...</p>
    </div>

    <div v-else>
        <div class="stepper-wrapper mb-5">
            <div class="stepper-item" :class="{ 'active': currentStep >= 1, 'completed': currentStep > 1 }">
                <div class="step-counter">
                    <i v-if="currentStep > 1" class="bi bi-check-lg"></i>
                    <span v-else>1</span>
                </div>
                <div class="step-name">Elige tu plan</div>
            </div>
            <div class="stepper-item" :class="{ 'active': currentStep >= 2, 'completed': currentStep > 2 }">
                <div class="step-counter">
                    <i v-if="currentStep > 2" class="bi bi-check-lg"></i>
                    <span v-else>2</span>
                </div>
                <div class="step-name">Detalles del plan</div>
            </div>
            <div class="stepper-item" :class="{ 'active': currentStep >= 3 }">
                <div class="step-counter">
                    <span>3</span>
                </div>
                <div class="step-name">Realizar pago</div>
            </div>
        </div>

        <!-- PASO 1: Lista de planes -->
        <div v-if="currentStep === 1" class="step-content">
            <!-- Mensaje si no hay planes -->
            <div v-if="plans.length === 0" class="text-center py-5">
                <i class="bi bi-inbox display-4 text-muted mb-3"></i>
                <h5 class="fw-bold">No hay planes disponibles</h5>
                <p class="text-muted">
                    Por el momento no tenemos planes activos. Por favor, vuelve más tarde.
                </p>
            </div>

            <!-- Cards de planes -->
            <div v-else class="row g-4 justify-content-center">
                <div class="col-md-6 col-lg-4" v-for="plan in plans" :key="plan.id">
                    <div class="card plan-card h-100 position-relative"
                         :class="{ 'selected-card': selectedPlan && selectedPlan.id === plan.id }">

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

                            <div class="features-preview mb-4">
                                <div v-for="feature in plan.features.slice(0, 3)" :key="feature.id"
                                     class="d-flex align-items-center mb-2">
                                    <i class="bi bi-check-circle-fill text-success me-2"></i>
                                    <span class="small">@{{ feature.name }}</span>
                                </div>
                                <div v-if="plan.features.length > 3" class="text-muted small mt-2">
                                    @{{ plan.features.length - 3 }} características más
                                </div>
                            </div>

                            <button class="btn btn-success fw-bold w-100 mt-auto"
                                    @click="selectPlan(plan)">
                                Seleccionar plan
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div v-if="currentStep === 2 && selectedPlan" class="step-content">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="card border-0 shadow-lg">
                        <div class="card-body p-4 p-lg-5">
                            <div class="row">

                                <div class="col-lg-7">
                                    <div class="d-flex align-items-center mb-4">

                                        <div>
                                            <h2 class="fw-bold mb-1">@{{ selectedPlan.name }}</h2>
                                            <p class="text-muted mb-0">@{{ selectedPlan.description }}</p>
                                        </div>
                                    </div>

                                    <div class="price-box bg-light rounded-4 p-4 mb-4">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="fs-5 fw-semibold">Precio del plan:</span>
                                            <span class="fs-2 fw-bold text-success">
                                                $@{{ selectedPlan.price.toLocaleString() }}
                                                <small class="text-muted fs-6">/mes</small>
                                            </span>
                                        </div>
                                    </div>

                                    <h5 class="fw-bold mb-3">
                                        <i class="bi bi-list-check text-success me-2"></i>
                                        Todas las características incluidas:
                                    </h5>

                                    <div class="features-grid">
                                        <div v-for="feature in selectedPlan.features" :key="feature.id"
                                             class="feature-item d-flex p-3 border rounded-3 mb-2">
                                            <i class="bi bi-check-circle-fill text-success fs-5 me-3 mt-1"></i>
                                            <div>
                                                <span class="fw-semibold d-block">@{{ feature.name }}</span>
                                                <span v-if="feature.pivot?.mount" class="text-muted small">
                                                    @{{ feature.pivot.mount }} @{{ feature.pivot.description }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-5">
                                    <div class="border rounded-4 p-4 bg-light position-sticky" style="top: 20px;">
                                        <h5 class="fw-bold mb-4">Resumen</h5>

                                        <div class="d-flex justify-content-between mb-3">
                                            <span>Plan @{{ selectedPlan.name }}</span>
                                            <span class="fw-bold">$@{{ selectedPlan.price.toLocaleString() }}/mes</span>
                                        </div>

                                        <hr>

                                        <div class="d-flex justify-content-between mb-4">
                                            <span class="fs-5 fw-bold">Total mensual</span>
                                            <span class="fs-3 fw-bold text-success">
                                                $@{{ selectedPlan.price.toLocaleString() }}
                                            </span>
                                        </div>

                                        <div class="alert alert-info mb-4">
                                            <i class="bi bi-info-circle me-2"></i>
                                            Puedes cancelar o cambiar tu plan en cualquier momento.
                                        </div>

                                        <div class="d-grid gap-2">
                                            <button class="btn btn-success btn-lg fw-bold py-3"
                                                    @click="goToPayment">
                                                <i class="bi bi-credit-card me-2"></i>
                                                Continuar al pago
                                            </button>
                                            <button class="btn btn-outline-secondary"
                                                    @click="goToStep1">
                                                <i class="bi bi-arrow-left me-2"></i>
                                                Elegir otro plan
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- PASO 3: Formulario de pago -->
        <div v-if="currentStep === 3 && selectedPlan" class="step-content">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="card border-0 shadow-lg">
                        <div class="card-body p-4 p-lg-5">
                            <div class="row g-4">
                                <!-- Formulario de pago -->
                                <div class="col-lg-7">
                                    <div class="d-flex align-items-center mb-4">
                                        <div class="bg-success bg-opacity-10 p-3 rounded-circle me-3">
                                            <i class="bi bi-credit-card text-success fs-3"></i>
                                        </div>
                                        <div>
                                            <h4 class="fw-bold mb-1">Información de pago</h4>
                                            <p class="text-muted mb-0">Completa los datos de tu tarjeta</p>
                                        </div>
                                    </div>

                                    <form @submit.prevent="processPayment">
                                        <div class="mb-3">
                                            <label class="form-label fw-semibold">
                                                <i class="bi bi-person me-2"></i>Nombre en la tarjeta
                                            </label>
                                            <input type="text" class="form-control form-control-lg"
                                                   v-model="payment.name" required
                                                   placeholder="Como aparece en la tarjeta">
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label fw-semibold">
                                                <i class="bi bi-credit-card me-2"></i>Número de tarjeta
                                            </label>
                                            <input type="text" class="form-control form-control-lg"
                                                   v-model="payment.card" required
                                                   @input="formatCardNumber"
                                                   placeholder="1234 5678 9012 3456"
                                                   maxlength="19">
                                        </div>

                                        <div class="row mb-4">
                                            <div class="col-md-6">
                                                <label class="form-label fw-semibold">
                                                    <i class="bi bi-calendar me-2"></i>Fecha expiración
                                                </label>
                                                <input type="text" class="form-control form-control-lg"
                                                       v-model="payment.exp" required
                                                       @input="formatExpiration"
                                                       placeholder="MM/AA"
                                                       maxlength="5">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label fw-semibold">
                                                    <i class="bi bi-lock me-2"></i>CVV
                                                </label>
                                                <input type="text" class="form-control form-control-lg"
                                                       v-model="payment.cvv" required
                                                       @input="formatCVV"
                                                       placeholder="123"
                                                       maxlength="4">
                                            </div>
                                        </div>

                                        <div class="d-flex gap-3">
                                            <button type="button"
                                                    class="btn btn-outline-secondary btn-lg px-4"
                                                    @click="goToStep2">
                                                <i class="bi bi-arrow-left me-2"></i>
                                                Atrás
                                            </button>
                                            <button type="submit"
                                                    class="btn btn-success btn-lg fw-bold py-3 flex-grow-1"
                                                    :disabled="processingPayment">
                                                <span v-if="processingPayment"
                                                      class="spinner-border spinner-border-sm me-2"></span>
                                                <i v-else class="bi bi-shield-check me-2"></i>
                                                Pagar $@{{ selectedPlan.price.toLocaleString() }}
                                            </button>
                                        </div>
                                    </form>

                                    <div class="mt-4 text-center">
                                        <small class="text-muted">
                                            <i class="bi bi-shield-lock me-1"></i>
                                            Tus datos están seguros y encriptados
                                        </small>
                                    </div>
                                </div>

                                <!-- Resumen del plan -->
                                <div class="col-lg-5">
                                    <div class="border rounded-4 p-4 bg-light">
                                        <h5 class="fw-bold mb-4">
                                            <i class="bi bi-receipt me-2"></i>
                                            Resumen de tu compra
                                        </h5>

                                        <div class="d-flex align-items-center mb-4">
                                            {{-- <div class="plan-icon-small me-3">
                                                <i :class="getPlanIcon(selectedPlan.name)"
                                                   class="fs-3 text-success"></i>
                                            </div> --}}
                                            <div>
                                                <h6 class="fw-bold mb-1">@{{ selectedPlan.name }}</h6>
                                                <small class="text-muted">@{{ selectedPlan.description }}</small>
                                            </div>
                                        </div>

                                        <div class="border-top border-bottom py-3 mb-3">
                                            <div v-for="feature in selectedPlan.features.slice(0, 3)"
                                                 :key="feature.id"
                                                 class="d-flex align-items-center mb-2">
                                                <i class="bi bi-check-circle text-success me-2"></i>
                                                <small>@{{ feature.name }}</small>
                                            </div>
                                            <div v-if="selectedPlan.features.length > 3"
                                                 class="text-muted small mt-2">
                                                @{{ selectedPlan.features.length - 3 }} características adicionales
                                            </div>
                                        </div>

                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <span class="fw-semibold">Subtotal mensual:</span>
                                            <span class="fw-bold">$@{{ selectedPlan.price.toLocaleString() }}</span>
                                        </div>

                                        <hr>

                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <span class="fs-5 fw-bold">Total a pagar:</span>
                                            <span class="fs-3 fw-bold text-success">
                                                $@{{ selectedPlan.price.toLocaleString() }}
                                            </span>
                                        </div>

                                        <div class="alert alert-success small mb-0 mt-3">
                                            <i class="bi bi-arrow-repeat me-2"></i>
                                            Pago mensual, puedes cancelar cuando quieras
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
