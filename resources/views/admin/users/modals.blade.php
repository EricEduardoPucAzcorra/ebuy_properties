<div class="modal fade" id="userModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">
                    @{{ isEditing ? translations.edit : translations.create }}
                </h5>
                <button type="button" class="btn-close"  @click="resetForm()" data-bs-dismiss="modal"></button>
            </div>

            <form @submit.prevent="saveUser">
                <div class="modal-body">
                    <div class="row g-3">

                        <!-- LEFT: FORM FIELDS -->
                        <div class="col-lg-7">
                            <div class="row g-3">

                                <div class="col-md-6">
                                    <label class="form-label">{{__('general.users.name')}}</label>
                                    <input type="text" class="form-control" v-model="form.name" required>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">{{__('general.users.last_name')}}</label>
                                    <input type="text" class="form-control" v-model="form.last_name" required>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">{{__('general.users.second_last_name')}}</label>
                                    <input type="text" class="form-control" v-model="form.second_last_name" required>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">{{__('general.users.phone')}}</label>
                                    <input type="text" class="form-control" v-model="form.phone" required>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">{{__('general.users.email')}}</label>
                                    <input type="email" class="form-control" v-model="form.email" required>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">{{__('general.users.password')}}</label>
                                    <input type="password" class="form-control"
                                           v-model="form.password"
                                           :required="!isEditing">
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">{{__('general.users.confirm_password')}}</label>
                                    <input type="password" class="form-control"
                                           v-model="form.password_confirmation"
                                           :required="!isEditing">
                                </div>

                                <div class="col-md-12">
                                    <label class="form-label">{{__('general.users.profile')}}</label>

                                    <div class="d-flex gap-3 align-items-center">
                                        <img
                                            :src="imagePreview || '/images/avatar-placeholder.svg'"
                                            class="img-thumbnail"
                                            style="width:80px;height:80px;object-fit:cover"
                                        >

                                        <input type="file"
                                               class="form-control"
                                               accept="image/*"
                                               ref="profileInput"
                                               title="{{__('general.select_image_file')}}"
                                               @change="handleFileChange">
                                    </div>

                                    <small v-if="isEditing" class="text-muted">
                                        {{__('general.select_image_file')}}
                                    </small>
                                </div>

                            </div>
                        </div>

                        <div class="col-lg-5">
                            <label class="form-label">{{__('general.users.role')}}</label>

                            <input type="text"
                                class="form-control mb-2"
                                placeholder="{{__('general.roles.search')}}"
                                v-model="roleSearch">

                            <div class="roles-list border rounded p-2" style="max-height: 320px; overflow-y: auto;">
                                <div v-for="role in filteredRoles" :key="role.id"
                                    class="role-item d-flex align-items-center justify-content-between p-2 mb-2 rounded">

                                    <div class="d-flex align-items-center gap-2">
                                        <i :class="role.icon" class="role-icon"></i>
                                        <div>
                                            <div class="role-name">@{{ role.name }}</div>
                                            <div class="role-module">@{{ role.module }}</div>
                                        </div>
                                    </div>

                                    <input class="form-check-input" type="checkbox"
                                        :id="'role-'+role.id"
                                        :value="role.id"
                                        v-model="form.roles">
                                </div>
                            </div>

                           <div class="mt-2">
                                <span class="badge bg-primary me-1"
                                    v-for="role in selectedRoleNames" :key="role">
                                    @{{ role }}
                                </span>
                            </div>

                        </div>

                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" @click="resetForm()" data-bs-dismiss="modal">
                        {{__('general.users.cancel')}}
                    </button>

                    <button v-if="!isEditing" class="btn btn-primary" :disabled="loading">
                        <span v-if="loading" class="spinner-border spinner-border-sm me-1"></span>
                        {{__('general.users.save')}}
                    </button>

                    <button type="button" v-if="isEditing" class="btn btn-primary" :disabled="loading" @click="updateUser()">
                        <span v-if="loading" class="spinner-border spinner-border-sm me-1"></span>
                        {{__('general.users.edit')}}
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
