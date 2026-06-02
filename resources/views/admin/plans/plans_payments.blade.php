<div id="planOwner">
    <div class="benefits-section mt-5 pt-3">
        <div class="text-center mb-4"  v-if="currentStep === 1" >
            <h5 class="fw-bold">{{ auto_trans('¿Por qué activar tu plan?') }}</h5>
        </div>

        <div class="row g-3 mb-4"  v-if="currentStep === 1" >
            <div class="col-md-3 col-6">
                <div class="benefit-card text-center p-3 rounded-4 border bg-white h-100">
                    <div class="benefit-icon bg-success bg-opacity-10 rounded-3 d-inline-flex align-items-center justify-content-center mb-2" style="width: 48px; height: 48px;">
                        <i class="bi bi-eye-fill text-success fs-4"></i>
                    </div>
                    <div class="fw-bold fs-3 text-success">3x</div>
                    <span class="small text-muted">{{ auto_trans('Más visitas') }}</span>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="benefit-card text-center p-3 rounded-4 border bg-white h-100">
                    <div class="benefit-icon bg-success bg-opacity-10 rounded-3 d-inline-flex align-items-center justify-content-center mb-2" style="width: 48px; height: 48px;">
                        <i class="bi bi-clock-fill text-success fs-4"></i>
                    </div>
                    <div class="fw-bold fs-3 text-success">-50%</div>
                    <span class="small text-muted">{{ auto_trans('Tiempo de venta') }}</span>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="benefit-card text-center p-3 rounded-4 border bg-white h-100">
                    <div class="benefit-icon bg-success bg-opacity-10 rounded-3 d-inline-flex align-items-center justify-content-center mb-2" style="width: 48px; height: 48px;">
                        <i class="bi bi-chat-dots-fill text-success fs-4"></i>
                    </div>
                    <div class="fw-bold fs-3 text-success">2.5x</div>
                    <span class="small text-muted">{{ auto_trans('Más contactos') }}</span>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="benefit-card text-center p-3 rounded-4 border bg-white h-100">
                    <div class="benefit-icon bg-success bg-opacity-10 rounded-3 d-inline-flex align-items-center justify-content-center mb-2" style="width: 48px; height: 48px;">
                        <i class="bi bi-heart-fill text-success fs-4"></i>
                    </div>
                    <div class="fw-bold fs-3 text-success">#1</div>
                    <span class="small text-muted">{{ auto_trans('Propiedad destacada') }}</span>
                </div>
            </div>
        </div>

        <div class="text-center">
            <div class="p-3 bg-light">
                <span class="small fw-semibold">
                    {{ auto_trans('Publica tus inmuebles con Ebuy Properties')}}
                </span>
            </div>
        </div>
    </div>
    <div class="text-center mb-5">
        <!-- <h1 class="fw-bold">
           {{ auto_trans('Publica tus inmuebles con Ebuy Properties')}}
        </h1> -->
        <p class="text-muted fs-5">
            {{ auto_trans('Sigue estos 3 simples pasos para activar tu plan')}}
        </p>
    </div>

    <div v-if="loadingView" class="text-center py-5">
        <div class="spinner-border text-success" style="width: 3rem; height: 3rem;"></div>
        <p class="mt-3 mb-0 fs-5">{{ auto_trans('Cargando información...')}}</p>
    </div>

    <div v-else>
        <div class="stepper-wrapper mb-5">
            <div class="stepper-item" :class="{ 'active': currentStep >= 1, 'completed': currentStep > 1 }">
                <div class="step-counter">
                    <i v-if="currentStep > 1" class="bi bi-check-lg"></i>
                    <span v-else>1</span>
                </div>
                <div class="step-name">{{ auto_trans('Elige tu plan')}}</div>
            </div>
            <div class="stepper-item" :class="{ 'active': currentStep >= 2, 'completed': currentStep > 2 }">
                <div class="step-counter">
                    <i v-if="currentStep > 2" class="bi bi-check-lg"></i>
                    <span v-else>2</span>
                </div>
                <div class="step-name">{{ auto_trans('Detalles del plan')}}</div>
            </div>
            <div class="stepper-item" :class="{ 'active': currentStep >= 3 }">
                <div class="step-counter">
                    <span>3</span>
                </div>
                <div class="step-name">{{ auto_trans('Realizar pago')}}</div>
            </div>
        </div>

        <!-- Lista de planes -->
        <div v-if="currentStep === 1" class="step-content">
            <div v-if="plans.length === 0" class="text-center py-5">
                <i class="bi bi-inbox display-4 text-muted mb-3"></i>
                <h5 class="fw-bold">{{ auto_trans('No hay planes disponibles')}}</h5>
                <p class="text-muted">
                    {{ auto_trans('Por el momento no tenemos planes activos. Por favor, vuelve más tarde.')}}
                </p>
            </div>

            <div v-else class="row g-4 justify-content-center">
                <div class="col-md-6 col-lg-4" v-for="plan in plans" :key="plan.id">
                    <div class="card plan-card h-100 position-relative"
                         :class="{ 
                             'selected-card': selectedPlan && selectedPlan.id === plan.id,
                             'current-plan-card': hasActiveSubscription && currentPlan && currentPlan.id === plan.id
                         }">

                        <span v-if="plan.is_featured" class="badge-featured">
                            {{ auto_trans('Recomendado')}}
                        </span>

                        <!-- <div v-if="hasActiveSubscription && currentPlan && currentPlan.id === plan.id" 
                             class="current-plan-badge">
                            <i class="bi bi-check-circle-fill"></i> {{ auto_trans('Plan Actual')}}
                        </div> -->

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

                            <button v-if="getPlanStatus(plan.id) === 'Activa' && currentPlan && currentPlan.id === plan.id"
                                    class="btn btn-outline-danger fw-bold w-100 mt-auto"
                                    @click="cancelSubscription"
                                    :disabled="processingAction">
                                <i class="bi bi-x-circle me-2"></i>
                                {{ auto_trans('Cancelar Plan')}}
                            </button>

                            <button v-else-if="hasActiveSubscription && currentPlan"
                                    class="btn btn-outline-success fw-bold w-100 mt-auto"
                                    @click="selectPlan(plan)"
                                    :disabled="processingAction">
                                <i class="bi bi-arrow-repeat me-2"></i>
                                {{ auto_trans('Cambiar a este plan')}}
                            </button>

                            <!-- <button v-else-if="getPlanStatus(plan.id) === 'Activa' && currentPlan"
                                    class="btn btn-outline-success fw-bold w-100 mt-auto"
                                    @click="selectPlan(plan)"
                                    :disabled="processingAction">
                                <i class="bi bi-arrow-repeat me-2"></i>
                                Cambiar a este plan
                            </button> -->
                            
                            <!-- 
                            <button v-else-if="getPlanStatus(plan.id) === 'Suspendida'"
                                    class="btn btn-outline-success fw-bold w-100 mt-auto"
                                    @click="selectPlan(plan)"
                                    :disabled="processingAction">
                                <i class="bi bi-arrow-repeat me-2"></i>
                                Reanudar plan
                            </button> -->

                            <button v-else
                                    class="btn btn-success fw-bold w-100 mt-auto"
                                    @click="selectPlan(plan)">
                                {{ auto_trans('Seleccionar plan')}}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Detalles del plan -->
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
                                            <span class="fs-5 fw-semibold">{{ auto_trans('Precio del plan')}}</span>
                                            <span class="fs-2 fw-bold text-success">
                                                $@{{ selectedPlan.price.toLocaleString() }}
                                                <small class="text-muted fs-6">/mes</small>
                                            </span>
                                        </div>
                                    </div>

                                    <h5 class="fw-bold mb-3">
                                        <i class="bi bi-list-check text-success me-2"></i>
                                        {{ auto_trans('Todas las características incluidas:')}}
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
                                        <h5 class="fw-bold mb-4">{{ auto_trans('Resumen')}}</h5>

                                        <div class="d-flex justify-content-between mb-3">
                                            <span>{{ auto_trans('Plan')}} @{{ selectedPlan.name }}</span>
                                            <span class="fw-bold">$@{{ selectedPlan.price.toLocaleString() }}/mes</span>
                                        </div>

                                        <hr>

                                        <div class="d-flex justify-content-between mb-4">
                                            <span class="fs-5 fw-bold">Total mensual</span>
                                            <span class="fs-3 fw-bold text-success">
                                                $@{{ selectedPlan.price.toLocaleString() }}
                                            </span>
                                        </div>

                                        <!-- <div class="alert alert-info mb-4">
                                            <i class="bi bi-info-circle me-2"></i>
                                            {{ auto_trans('Puedes cancelar o cambiar tu plan en cualquier momento.')}}
                                        </div> -->

                                        <div class="d-grid gap-2">
                                            <button class="btn btn-success btn-lg fw-bold py-3"
                                                    @click="goToPayment">
                                                <i class="bi bi-credit-card me-2"></i>
                                                {{ auto_trans('Continuar al pago')}}
                                            </button>
                                            <button class="btn btn-outline-secondary"
                                                    @click="goToStep1">
                                                <i class="bi bi-arrow-left me-2"></i>
                                                {{ auto_trans('Elegir otro plan')}}
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

        <!-- Pago -->
        <div v-if="currentStep === 3 && selectedPlan" class="step-content">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
                        <div class="card-body p-4 p-lg-5">
                            <div class="row g-5">
                                <div class="col-lg-7">
                                    <div class="d-flex align-items-center mb-4">
                                        <div>
                                            <h4 class="fw-bold mb-1">
                                                {{ auto_trans('Información de pago')}}
                                            </h4>
                                            <p class="text-muted mb-0">
                                                {{ auto_trans('Selecciona una tarjeta existente o agrega una nueva')}}
                                            </p>
                                        </div>
                                    </div>

                                    <!-- Tarjetas existentes -->
                                    <!-- <div v-if="userCards.length > 0 && !useNewCard" class="mb-3">
                                        <h6 class="fw-bold mb-2 small">
                                            <i class="bi bi-credit-card me-1"></i>
                                            {{ auto_trans('Tarjetas guardadas')}}
                                        </h6>
                                        <div class="row g-2">
                                            <div class="col-md-6" v-for="card in userCards" :key="card.id">
                                                <div class="card border-2"
                                                     :class="{ 'border-success bg-light': selectedCard && selectedCard.id === card.id }"
                                                     @click="selectExistingCard(card)"
                                                     style="cursor: pointer;">
                                                    <div class="card-body p-2">
                                                        <div class="d-flex align-items-center justify-content-between">
                                                            <div class="small">
                                                                <div class="fw-bold mb-0">
                                                                    @{{ card.brand }} •••• @{{ card.card_number ? card.card_number.slice(-4) : '****' }}
                                                                </div>
                                                                <small class="text-muted" style="font-size: 11px;">
                                                                    @{{ card.card_holder_name }}
                                                                </small>
                                                            </div>
                                                            <div v-if="selectedCard && selectedCard.id === card.id">
                                                                <i class="bi bi-check-circle-fill text-success fs-5"></i>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div> -->

                                    <div v-if="userCards.length > 0 && !useNewCard" class="mb-3">
                                        <h6 class="fw-bold mb-2 small d-flex align-items-center">
                                            <i class="bi bi-credit-card me-2"></i>
                                            {{ auto_trans('Tarjetas guardadas') }}
                                        </h6>

                                        <div class="row g-2">
                                            <div class="col-md-6" v-for="card in userCards" :key="card.id">

                                                <div class="card card-select h-100 border-0 shadow-sm"
                                                    :class="{
                                                        'border-success selected': selectedCard && selectedCard.id === card.id
                                                    }"
                                                    @click="selectExistingCard(card)">

                                                    <div class="card-body p-3 d-flex justify-content-between align-items-center">

                                                        <!-- INFO -->
                                                        <div>
                                                            <div class="fw-semibold small mb-1">
                                                                @{{ card.brand }} •••• @{{ card.card_number ? card.card_number.slice(-4) : '****' }}
                                                            </div>

                                                            <div class="text-muted" style="font-size: 12px;">
                                                                @{{ card.card_holder_name }}
                                                            </div>
                                                        </div>

                                                        <!-- ICON -->
                                                        <div class="ms-2">
                                                            <i v-if="selectedCard && selectedCard.id === card.id"
                                                            class="bi bi-check-circle-fill text-success fs-5"></i>

                                                            <i v-else class="bi bi-circle text-muted fs-5"></i>
                                                        </div>

                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>

                                    <!-- Opción agregar nueva tarjeta -->
                                   <div v-if="!useNewCard" class="mb-3">
                                        <label class="form-check w-100 d-flex align-items-center gap-2 p-2 border rounded"
                                            for="newCardOption"
                                            style="cursor:pointer;">

                                            <input class="form-check-input m-0" type="radio"
                                                name="cardOption"
                                                id="newCardOption"
                                                v-model="useNewCard"
                                                :value="true"
                                                @change="clearSelectedCard">

                                            <span class="small">
                                                {{ auto_trans('Agregar nueva tarjeta') }}
                                            </span>
                                        </label>
                                    </div>

                                    <!-- Opción volver a tarjetas guardadas -->
                                    <div v-if="useNewCard && userCards.length > 0" class="mb-3">
                                        <button type="button" class="btn btn-outline-secondary btn-sm small"
                                                @click="useNewCard = false; selectedCard = userCards[0]">
                                            <i class="bi bi-arrow-left me-1"></i>
                                            {{ auto_trans('Tarjetas guardadas')}}
                                        </button>
                                    </div>

                                    <!-- Formulario nueva tarjeta -->
                                    <form v-if="useNewCard" id="payment-form" autocomplete="off" @submit.prevent="processPayment">
                                        <input type="hidden" id="deviceIdHiddenFieldName" name="deviceIdHiddenFieldName">

                                        <div class="mb-4">
                                            <label class="form-label fw-semibold">
                                                <i class="bi bi-person me-2"></i>
                                                {{ auto_trans('Nombre en la tarjeta')}}
                                            </label>
                                            <input type="text" class="form-control form-control-lg"
                                                   v-model="payment.name" data-openpay-card="holder_name"
                                                   autocomplete="off" required
                                                   placeholder="{{ auto_trans('Como aparece en la tarjeta')}}">
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label fw-semibold">
                                                <i class="bi bi-credit-card me-2"></i>
                                                {{ auto_trans('Número de tarjeta')}}
                                            </label>
                                            <input type="text" class="form-control form-control-lg"
                                                   v-model="payment.card" data-openpay-card="card_number"
                                                   @input="formatCardNumber" autocomplete="off" required
                                                   inputmode="numeric" placeholder="xxxx xxxx xxxx xxxx"
                                                   maxlength="19">
                                        </div>

                                        <div class="d-flex align-items-center gap-3 mb-4">
                                            <img src="{{ asset('openpay/cards1.png') }}" height="25">
                                        </div>

                                        <div class="row mb-4">
                                            <div class="col-md-3">
                                                <label class="form-label fw-semibold">{{ auto_trans('Month')}}</label>
                                                <input type="text" class="form-control form-control-lg"
                                                       v-model="payment.expMonth" data-openpay-card="expiration_month"
                                                       inputmode="numeric" autocomplete="off" placeholder="MM"
                                                       maxlength="2">
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label fw-semibold">{{ auto_trans('Año')}}</label>
                                                <input type="text" class="form-control form-control-lg"
                                                       v-model="payment.expYear" data-openpay-card="expiration_year"
                                                       inputmode="numeric" autocomplete="off" placeholder="AA"
                                                       maxlength="2">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label fw-semibold">
                                                    <!-- <img src="{{ asset('openpay/cvv.png') }}" height="25"> -->
                                                    {{ auto_trans('CVV')}}
                                                </label>
                                                <input type="password" class="form-control form-control-lg"
                                                       v-model="payment.cvv" data-openpay-card="cvv2"
                                                       autocomplete="off" inputmode="numeric" placeholder="***"
                                                       maxlength="4">
                                            </div>
                                        </div>
                                    </form>

                                    <div class="d-flex gap-3 mt-4">
                                        <button type="button" class="btn btn-outline-secondary btn-lg px-4"
                                                @click="goToStep2">
                                            <i class="bi bi-arrow-left me-2"></i>{{ auto_trans('Atrás')}}
                                        </button>
                                        <button type="button" class="btn btn-success btn-lg fw-bold py-3 flex-grow-1"
                                                @click="processPayment"
                                                :disabled="processingPayment || (!selectedCard && !useNewCard)">
                                            {{ auto_trans('Pagar')}} $@{{ selectedPlan.price.toLocaleString() }}
                                        </button>
                                    </div>

                                    <div class="alert alert-light border rounded-4 mt-4">
                                        <div class="d-flex align-items-center">
                                            <img src="{{ asset('openpay/openpay.png') }}" height="30" class="me-3">
                                            <small class="text-muted">
                                                {{ auto_trans('Tus pagos se procesan de forma segura')}}
                                                {{ auto_trans('mediante OpenPay y cifrado SSL de 256 bits')}}
                                            </small>
                                        </div>
                                    </div>

                                    <div class="mt-4 text-center">
                                        <small class="text-muted">
                                            <img src="{{ asset('openpay/security.png') }}" height="30" class="me-3">
                                            {{ auto_trans('Tus datos están seguros y encriptados')}}
                                        </small>
                                    </div>
                                </div>

                                <div class="col-lg-5">
                                    <div class="border rounded-4 p-4 bg-light h-100">
                                        <h5 class="fw-bold mb-4">
                                            <i class="bi bi-receipt me-2"></i>
                                            {{ auto_trans('Resumen de tu compra')}}
                                        </h5>

                                        <div class="mb-4">
                                            <h6 class="fw-bold mb-1">
                                                @{{ selectedPlan.name }}
                                            </h6>
                                            <small class="text-muted">
                                                @{{ selectedPlan.description }}
                                            </small>
                                        </div>

                                        <div class="border-top border-bottom py-3 mb-3">
                                            <div v-for="feature in selectedPlan.features.slice(0,3)" :key="feature.id"
                                                 class="d-flex align-items-center mb-2">
                                                <i class="bi bi-check-circle text-success me-2"></i>
                                                <small>@{{ feature.name }}</small>
                                            </div>
                                            <div v-if="selectedPlan.features.length > 3" class="text-muted small mt-2">
                                                @{{ selectedPlan.features.length - 3 }} {{ auto_trans('características adicionales')}}
                                            </div>
                                        </div>

                                        <div class="d-flex justify-content-between mb-3">
                                            <span class="fw-semibold">{{ auto_trans('Subtotal mensual')}}</span>
                                            <span class="fw-bold">$@{{ selectedPlan.price.toLocaleString() }}</span>
                                        </div>

                                        <hr>

                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="fs-5 fw-bold">{{ auto_trans('Total')}}</span>
                                            <span class="fs-3 fw-bold text-success">
                                                $@{{ selectedPlan.price.toLocaleString() }}
                                            </span>
                                        </div>

                                        <div class="alert alert-success small mt-4 mb-0">
                                            <i class="bi bi-arrow-repeat me-2"></i>
                                            {{ auto_trans('Pago mensual, puedes cancelar cuando quieras')}}
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
