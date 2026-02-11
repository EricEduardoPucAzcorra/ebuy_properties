@extends('layouts.app')

@section('content')
<div class="container py-5" id="plansModule">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-0">{{ __('general.plans.title') }}</h2>
            <small class="text-muted">{{ __('general.plans.manage_plans') }}</small>
        </div>

        @can('plans.create')
            <button class="btn btn-primary rounded-pill px-4" @click="openCreate">
                + {{ __('general.plans.create') }}
            </button>
        @endcan
    </div>

    <div v-if="plans.length === 0" class="text-center py-5">
        <i class="bi bi-inbox display-4 text-muted mb-3"></i>
        <h5 class="fw-bold">{{ __('general.plans.empty_title') }}</h5>
        <p class="text-muted">
            {{ __('general.plans.empty_description') }}
        </p>
    </div>

    <div class="row g-4" v-else>
        <div class="col-lg-4 col-md-6" v-for="plan in plans" :key="plan.id">
            <div class="card h-100 border-0 rounded-4 plan-card position-relative overflow-hidden">

                <div class="plan-bg"></div>

                <div v-if="plan.is_featured"
                     class="badge bg-gradient-success position-absolute top-0 end-0 m-3 px-3 py-2 rounded-pill">
                    <i class="bi bi-star-fill me-1"></i>
                    {{ __('general.plans.recommended') }}
                </div>

                <div class="card-body position-relative d-flex flex-column">

                    <h5 class="fw-bold mb-1">@{{ plan.name }}</h5>

                    <small class="text-muted mb-3">
                        @{{ plan.description }}
                    </small>

                    <div class="price-box mb-4">
                        <span class="price">$@{{ plan.price }}</span>
                        <span class="period">/{{ __('general.mounth') }}</span>
                    </div>

                    <ul class="features-list mb-4">
                        <li v-for="feature in plan.features" :key="feature.id">
                            <i class="bi bi-check-circle-fill text-success me-2"></i>

                            <span class="fw-semibold">
                                @{{ feature.name }}
                            </span>

                            <span v-if="feature.pivot?.mount" class="text-muted ms-1">
                                (@{{ feature.pivot.mount }} @{{ feature.pivot.description }})
                            </span>
                        </li>
                    </ul>

                    <div class="mt-auto">
                        @can('plans.update')
                            <button class="btn btn-outline-primary w-100 btn-sm"
                                    @click="openEdit(plan)">
                                {{ __('general.plans.edit') }}
                            </button>
                        @endcan
                    </div>

                </div>
            </div>
        </div>
    </div>

    @include('admin.plans.modals')
</div>

@endsection
