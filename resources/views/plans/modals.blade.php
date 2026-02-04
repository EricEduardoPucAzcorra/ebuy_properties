<div class="modal fade" id="planModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">
                    @{{ isEditing ? 'Editar plan' : 'Crear plan' }}
                </h5>
                <button class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <div class="row g-3">

                    <div class="col-md-6">
                        <label class="form-label">Nombre</label>
                        <input v-model="form.name" class="form-control">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Precio</label>
                        <input v-model="form.price" type="number" class="form-control">
                    </div>

                    <div class="col-12">
                        <label class="form-label">Descripción</label>
                        <textarea v-model="form.description" class="form-control"></textarea>
                    </div>

                    <!-- FEATURES SELECT -->
                    <div class="col-12">
                        <label class="form-label fw-bold">Features</label>

                        <div class="border rounded-3 p-3">
                            <div v-for="feature in availableFeatures"
                                 :key="feature.id"
                                 class="border rounded-3 p-3 mb-2">

                                <div class="form-check mb-2">
                                    <input class="form-check-input"
                                           type="checkbox"
                                           v-model="feature.selected">

                                    <label class="form-check-label fw-semibold">
                                        @{{ feature.name }}
                                    </label>
                                </div>

                                <div v-if="feature.selected" class="row g-2 ps-3">
                                    <div class="col-md-4">
                                        <input class="form-control form-control-sm"
                                               type="number"
                                               v-model="feature.mount"
                                               placeholder="Cantidad">
                                    </div>

                                    <div class="col-md-4">
                                        <input class="form-control form-control-sm"
                                               type="text"
                                               v-model="feature.description"
                                               placeholder="Descripción">
                                    </div>

                                    <div class="col-md-4">
                                        <input class="form-control form-control-sm"
                                               type="text"
                                               v-model="feature.other_description"
                                               placeholder="Extra">
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-check mt-3">
                            <input class="form-check-input" type="checkbox" v-model="form.is_featured">
                            <label class="form-check-label">Recomendado</label>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-check mt-3">
                            <input class="form-check-input" type="checkbox" v-model="form.is_active">
                            <label class="form-check-label">Activo</label>
                        </div>
                    </div>

                </div>
            </div>

            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button class="btn btn-primary" @click="savePlan" :disabled="loading">
                    <span v-if="loading" class="spinner-border spinner-border-sm me-1"></span>
                    Guardar
                </button>
            </div>

        </div>
    </div>
</div>
