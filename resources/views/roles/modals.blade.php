<div class="modal fade" id="ModalRole" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">
                    @{{ isEditing ? translations.edit : translations.create }}
                </h5>
                <button type="button" class="btn-close"  @click="resetForm()" data-bs-dismiss="modal"></button>
            </div>

            <form @submit.prevent="saveRole">
                <div class="modal-body">
                    <div class="row g-3">

                        <div class="col-md-6">
                            <label class="form-label">{{__('general.roles.name')}}</label>
                            <input type="text" class="form-control" v-model="form.name" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">{{__('general.roles.description')}}</label>
                            <input type="text" class="form-control" v-model="form.description">
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" @click="resetForm()" data-bs-dismiss="modal">
                        {{__('general.roles.cancel')}}
                    </button>

                    <button  v-if="!isEditing"  class="btn btn-primary" :disabled="loading">
                        <span v-if="loading" class="spinner-border spinner-border-sm me-1"></span>
                        {{__('general.roles.save')}}
                    </button>

                    <button type="button" v-if="isEditing" class="btn btn-primary" :disabled="loading" @click="updateRole()">
                        <span v-if="loading" class="spinner-border spinner-border-sm me-1"></span>
                        {{__('general.roles.edit')}}
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>
