@extends('layouts.app')

@section('content')
<div id="UsersModule">
    <div>
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-0">{{ __('general.users.title') }}</h1>
                <p class="text-muted mb-0">{{ __('general.users.manage_users_roles_permissions') }}</p>
            </div>
            @can('users.create')
                <button class="btn btn-primary" @click="openCreateModal">
                    <i class="bi bi-person-plus me-2"></i>{{ __('general.users.create') }}
                </button>
            @endcan
        </div>

        <div class="card">
            <div class="card-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h5 class="card-title mb-0">{{ __('general.users.list') }}</h5>
                    </div>
                    <div class="col-auto d-flex gap-2">
                        <input type="search"
                               class="form-control form-control-sm"
                               placeholder="{{ __('general.users.search_users') }}"
                               v-model="filters.search"
                               @input="table.search(filters.search).draw()">

                        <select class="form-select form-select-sm"
                                v-model="filters.status"
                                @change="table.draw()">
                            <option value="">{{ __('general.status') }}</option>
                            <option value="1">{{ __('general.active') }}</option>
                            <option value="0">{{ __('general.inactive') }}</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="card-body">
                <table id="table-users" class="table table-hover align-middle w-100">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>{{ __('general.users.name') }}</th>
                            <th>{{ __('general.users.email') }}</th>
                            <th>{{ __('general.users.profile') }}</th>
                            <th>{{ __('general.users.status') }}</th>
                            <th>{{ __('general.users.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
    @include('users.modals')
</div>
@endsection
