<div class="modal fade" id="planModal" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 1rem;">

            <div class="modal-header border-bottom-0 pt-4 px-4">
                <div>
                    <h5 class="modal-title fw-bold text-dark mb-0">
                        @{{ isEditing ? 'Editar Plan' : 'Crear Nuevo Plan' }}
                    </h5>
                    <p class="text-muted small mb-0">Gestione los costos y beneficios de este nivel de suscripción.</p>
                </div>
                <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body p-0">
                <div class="row g-0 border-top mt-3">

                    <div class="col-lg-4 border-end bg-light p-4">
                        <label class="form-label fw-bold text-primary small text-uppercase mb-3" style="letter-spacing: 0.5px;">Datos Generales</label>

                        <div class="mb-3">
                            <label class="form-label small fw-semibold text-secondary">Nombre del Plan</label>
                            <input v-model="form.name" class="form-control border-light-subtle shadow-sm" placeholder="Ej: Platinum Elite">
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-semibold text-secondary">Precio Sugerido (USD)</label>
                            <div class="input-group shadow-sm">
                                <span class="input-group-text bg-white border-light-subtle text-muted">$</span>
                                <input v-model="form.price" type="number" class="form-control border-light-subtle" placeholder="0.00">
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label small fw-semibold text-secondary">Descripción</label>
                            <textarea v-model="form.description" class="form-control border-light-subtle shadow-sm" rows="5" placeholder="¿Qué incluye este plan?" style="resize: none;"></textarea>
                        </div>

                        <div class="bg-white border rounded-3 p-3 shadow-sm">
                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" type="checkbox" v-model="form.is_featured" id="swRecomendado">
                                <label class="form-check-label small fw-bold" for="swRecomendado">Marcar como recomendado</label>
                            </div>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" v-model="form.is_active" id="swActivo">
                                <label class="form-check-label small fw-bold" for="swActivo">Activar plan ahora</label>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-8 p-4 bg-white" style="max-height: 60vh; overflow-y: auto;">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <label class="form-label fw-bold text-primary small text-uppercase mb-0" style="letter-spacing: 0.5px;">Características del Plan</label>
                            <span class="badge rounded-pill bg-light text-dark border fw-normal">Total: @{{ availableFeatures.length }}</span>
                        </div>

                        <div class="row g-3">
                            <div v-for="feature in availableFeatures" :key="feature.id" class="col-12">
                                <div class="card border-light-subtle shadow-sm">
                                    <div class="card-body p-3">
                                        <div class="row align-items-center">

                                            <div class="col-md-5">
                                                <div class="form-check form-switch d-flex align-items-center mb-0">
                                                    <input class="form-check-input fs-5 me-3" type="checkbox" role="switch" :id="'f-' + feature.id" v-model="feature.selected">
                                                    <label class="form-check-label fw-bold text-dark" :for="'f-' + feature.id">
                                                        @{{ feature.name }}
                                                    </label>
                                                </div>
                                            </div>

                                            <div class="col-md-7 border-start" v-if="feature.selected">
                                                <div class="row g-2">
                                                    <div class="col-4 text-center">
                                                        <input class="form-control form-control-sm bg-light text-center" type="number" v-model="feature.mount">
                                                        <span class="text-muted fw-bold" style="font-size: 0.65rem;">CANTIDAD</span>
                                                    </div>
                                                    <div class="col-8">
                                                        <input class="form-control form-control-sm bg-light" type="text" v-model="feature.description" placeholder="Descripción comercial">
                                                        <span class="text-muted fw-bold" style="font-size: 0.65rem;">DESCRIPCIÓN EN PLAN</span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-7 text-end text-muted small px-4" v-else>
                                                <span class="badge bg-light text-secondary border-0 fw-normal italic">Desactivado</span>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <div class="modal-footer bg-light border-top-0 p-4 justify-content-end">
                <button class="btn btn-outline-secondary fw-bold px-4 me-2 rounded-3" data-bs-dismiss="modal" style="border-color: #dee2e6;">
                    Cancelar
                </button>
                <button class="btn btn-primary fw-bold px-5 rounded-3 shadow" @click="savePlan" :disabled="loading">
                    <span v-if="loading" class="spinner-border spinner-border-sm me-2"></span>
                    Guardar Configuración
                </button>
            </div>

        </div>
    </div>
</div>
