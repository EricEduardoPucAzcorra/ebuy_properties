@extends('layouts.app')

@section('content')
<div id="RolesModule">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">{{__('general.roles.title')}}</h1>
            <p class="text-muted mb-0">{{__('general.roles.manage_roles_permissions')}}</p>
        </div>
        @can('roles.create')
            <button type="button" class="btn btn-primary" @click="openCreateModal">
                <i class="bi bi-plus-circle me-2"></i>{{__('general.roles.create')}}
            </button>
        @endcan
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <div class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label class="form-label">{{__('general.roles.select_role')}}</label>
                    <select class="form-select" v-model="role_selected" @change="loadPermissions">
                        <option :value="null">{{__('general.roles.select_role')}}</option>
                        <option v-for="role in roles" :key="role.id" :value="role">
                            @{{ role.name }}
                        </option>
                    </select>
                </div>

                <div class="col-md-8 text-md-end">
                    @can('roles.update')
                        <button class="btn btn-outline-primary"
                                :disabled="!role_selected"
                                @click="edit_role">
                            <i class="bi bi-pencil-square"></i>
                        </button>
                    @endcan
                </div>
            </div>
        </div>
    </div>

    <div v-if="loading" class="text-center py-5">
        <div class="spinner-border text-primary"></div>
    </div>

    <div v-else class="accordion" id="permissionsAccordion">

        <div class="accordion-item" v-for="module in modules" :key="module.id">

            <h2 class="accordion-header">
                <button class="accordion-button"
                        type="button"
                        data-bs-toggle="collapse"
                        :data-bs-target="'#module_' + module.id">
                    <i :class="module.icon ?? 'bi bi-grid me-2'"></i>
                   <span v-text="module.title_translated"></span>
                </button>
            </h2>

            <div class="accordion-collapse collapse show"
                 :id="'module_' + module.id">

                <div class="accordion-body">

                    <div class="row">

                        <div class="col-md-3" v-for="perm in module.permissions" :key="perm.id">
                            <div class="form-check">
                                <input class="form-check-input"
                                       type="checkbox"
                                       :id="perm.name"
                                       :checked="selectedPermissions.has(perm.id)"
                                       @change="togglePermission(perm.id)"
                                       :disabled="!role_selected">

                                <label class="form-check-label" :for="perm.title_translated">
                                    @{{ formatPermissionLabel(perm.title_translated) }}
                                </label>
                            </div>
                        </div>

                    </div>

                </div>
            </div>
        </div>
    </div>
    @can('roles.permissions')
        <div class="d-flex justify-content-end mt-4">
            <button class="btn btn-success"
                    :disabled="!role_selected || loading"
                    @click="savePermissions">
                <i class="bi bi-save me-2"></i>{{__('general.roles.save.permissions')}}
            </button>
        </div>
    @endcan

    @include('roles.modals')
</div>

<script>
    window.modules_roles = @json($modules);
</script>

@endsection
