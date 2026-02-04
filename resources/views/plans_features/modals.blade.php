<div class="modal fade" id="featureModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">
                    @{{ isEditing ? translations.edit : translations.create }}
                </h5>
                <button type="button" class="btn-close"  @click="resetForm()" data-bs-dismiss="modal"></button>
            </div>

            <form @submit.prevent="save">
                <div class="modal-body">
                    <div class="row g-3">

                        <div class="col-lg-7">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">{{__('general.plan_feature.name')}}</label>
                                    <input type="text" class="form-control" v-model="form.name" required>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">{{__('general.plan_feature.description')}}</label>
                                    <input type="text" class="form-control" v-model="form.descripcion" required>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" @click="resetForm()" data-bs-dismiss="modal">
                        {{__('general.plan_feature.cancel')}}
                    </button>

                    <button v-if="!isEditing" class="btn btn-primary" :disabled="loading">
                        <span v-if="loading" class="spinner-border spinner-border-sm me-1"></span>
                        {{__('general.plan_feature.save')}}
                    </button>

                    <button type="button" v-if="isEditing" class="btn btn-primary" :disabled="loading" @click="update()">
                        <span v-if="loading" class="spinner-border spinner-border-sm me-1"></span>
                        {{__('general.plan_feature.edit')}}
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>


<style>
.role-item {
    background: rgba(255,255,255,0.03);
    border: 1px solid rgba(255,255,255,0.08);
}

.role-item:hover {
    background: rgba(255,255,255,0.06);
}

.role-icon {
    font-size: 18px;
    color: var(--bs-primary);
}

.role-name {
    font-weight: 600;
}

.role-module {
    font-size: 12px;
    opacity: 0.7;
}

</style>
