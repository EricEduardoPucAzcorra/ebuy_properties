<transition name="fade">
    <div v-if="showPlanModal" class="modal-backdrop">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content p-4">

                <div v-if="loadingModal" class="d-flex flex-column justify-content-center align-items-center" style="height:250px;">
                    <div class="spinner-border text-success" role="status"></div>
                    <p class="mt-3">Preparando plan</p>
                </div>

                <div v-else>
                    <div class="modal-header border-0 pb-0">
                        <h5 class="modal-title fw-bold text-success">@{{ selectedPlan.name }}</h5>
                        <button type="button" class="btn-close" @click="closeModal"></button>
                    </div>
                    <div class="modal-body pt-3">
                        <p class="text-muted">@{{ selectedPlan.description }}</p>
                        {{-- <ul class="list-unstyled mb-3 plan-features">
                            <li v-for="(feature,index) in selectedPlan.features" :key="index">
                                <i class="bi bi-check-circle-fill text-success me-2"></i> @{{ feature }}
                            </li>
                        </ul> --}}

                        <hr class="my-3">

                        <div class="d-flex justify-content-between align-items-center mb-3 fw-bold fs-5">
                            <span>Total a pagar</span>
                            <span v-if="selectedPlan.price==0" class="text-success">Gratis</span>
                            <span v-else class="text-success">$@{{ selectedPlan.price.toLocaleString() }}</span>
                        </div>

                        <h6 class="fw-bold mb-2">Datos de pago</h6>
                        <form @submit.prevent="processPayment">
                            <div class="mb-3">
                                <label class="form-label">Nombre en la tarjeta</label>
                                <input type="text" class="form-control" v-model="payment.name" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Número de tarjeta</label>
                                <input type="text" class="form-control" v-model="payment.card" required>
                            </div>
                            <div class="row mb-3">
                                <div class="col">
                                    <label class="form-label">Expira</label>
                                    <input type="text" class="form-control" v-model="payment.exp" placeholder="MM/AA" required>
                                </div>
                                <div class="col">
                                    <label class="form-label">CVV</label>
                                    <input type="text" class="form-control" v-model="payment.cvv" required>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-success w-100 fw-bold">
                                Pagar $@{{ selectedPlan.price.toLocaleString() }}
                            </button>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
</transition>
