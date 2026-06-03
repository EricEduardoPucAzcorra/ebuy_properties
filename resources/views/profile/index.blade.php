@extends('layouts.app')

@section('content')
<div id="profileModule" class="container py-4">

    <div class="row justify-content-center">
        <div class="col-xl-10 col-lg-11">

            <div class="card shadow-sm">
                <div class="card-header d-flex align-items-center gap-2">
                    <i class="bi bi-person-circle"></i>
                    <h5 class="mb-0">{{ __('general.users.profile') }}</h5>
                </div>

                <div class="card-body">

                    <div v-if="loading" class="text-center py-5">
                        <div class="spinner-border text-primary"></div>
                    </div>

                    <form v-else @submit.prevent="updateProfile">

                        <div class="row g-4">

                            <div class="col-lg-8">
                                <div class="row g-3">

                                    <div class="col-md-6">
                                        <label class="form-label">{{ __('general.users.name') }}</label>
                                        <input type="text" class="form-control" v-model="form.name" required>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">{{ __('general.users.last_name') }}</label>
                                        <input type="text" class="form-control" v-model="form.last_name" required>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">{{ __('general.users.second_last_name') }}</label>
                                        <input type="text" class="form-control" v-model="form.second_last_name">
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">{{ __('general.users.phone') }}</label>
                                        <input type="text" class="form-control" v-model="form.phone">
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">{{ __('general.users.email') }}</label>
                                        <input type="email" class="form-control" v-model="form.email" required>
                                    </div>

                                    <div class="col-md-12">
                                        <label class="form-label">{{ __('general.users.role') }}</label>
                                        <div>
                                            <span class="badge bg-primary me-1"
                                                  v-for="role in form.roles"
                                                  :key="role.id">
                                                @{{ role.name }}
                                            </span>
                                        </div>
                                    </div>

                                    <div class="col-md-6 mt-3">
                                        <label class="form-label">{{ __('general.users.password') }}</label>
                                        <input type="password"
                                               class="form-control"
                                               v-model="form.password"
                                               autocomplete="new-password">
                                    </div>

                                    <div class="col-md-6 mt-3">
                                        <label class="form-label">{{ __('general.users.confirm_password') }}</label>
                                        <input type="password"
                                               class="form-control"
                                               v-model="form.password_confirmation"
                                               autocomplete="new-password">
                                    </div>

                                </div>
                            </div>

                            <div class="col-lg-4">
                                <label class="form-label">{{ __('general.users.profile') }}</label>

                                <div class="d-flex flex-column align-items-center gap-3">
                                    <img
                                        :src="imagePreview || '/images/avatar-placeholder.svg'"
                                        class="img-thumbnail rounded-circle"
                                        style="width:140px;height:140px;object-fit:cover">

                                    <input type="file"
                                           class="form-control"
                                           accept="image/*"
                                           @change="handleFileChange">
                                </div>
                            </div>

                        </div>

                        <div class="d-flex justify-content-end mt-4">
                            <button class="btn btn-primary" :disabled="loading">
                                <span v-if="loading" class="spinner-border spinner-border-sm me-1"></span>
                                <i class="bi bi-save me-1"></i>
                                {{ __('general.users.edit') }}
                            </button>
                        </div>

                    </form>

                </div>
            </div>

            <!-- Tarjetas del usuario -->
            @if(auth()->user()->hasRoleName('Owner'))
                <div class="card shadow-sm mt-4">
                    <div class="card-header d-flex align-items-center gap-2">
                        <i class="bi bi-credit-card"></i>
                        <h5 class="mb-0">{{ __('Mis Tarjetas') }}</h5>
                    </div>

                    <div class="card-body">
                        <div v-if="loadingCards" class="text-center py-3">
                            <div class="spinner-border spinner-border-sm text-primary"></div>
                        </div>

                        <div v-else-if="cards.length === 0" class="text-center py-4 text-muted">
                            <i class="bi bi-credit-card fs-1 mb-2 d-block"></i>
                            <p>{{ __('No tienes tarjetas registradas') }}</p>
                        </div>

                        <div v-else class="row g-3">
                            <div v-for="card in cards" :key="card.id" class="col-md-6 col-lg-4">
                                <div class="card h-100 border shadow-sm position-relative">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-start mb-3">
                                            <div>
                                                <span class="badge bg-primary bg-opacity-10 text-primary mb-2 fw-normal">@{{ (card.brand || 'Card').toUpperCase() }}</span>
                                                <h5 class="mb-0 fw-bold">**** @{{ card.card_number ? card.card_number.slice(-4) : '****' }}</h5>
                                            </div>
                                            <i :class="getCardIcon(card.brand)" class="fs-3 text-primary opacity-50"></i>
                                        </div>
                                        <p class="text-muted small mb-3">@{{ card.card_holder_name || 'Titular' }}</p>
                                        <div class="row g-2 small text-muted">
                                            <div class="col-6">
                                                <span class="d-block" style="font-size: 0.65rem; text-transform: uppercase; letter-spacing: 0.5px;">{{ __('Expira') }}</span>
                                                <span class="fw-semibold text-dark">@{{ card.card_expiry_month }}/@{{ card.card_expiry_year }}</span>
                                            </div>
                                            <div class="col-6">
                                                <span class="d-block" style="font-size: 0.65rem; text-transform: uppercase; letter-spacing: 0.5px;">{{ __('Banco') }}</span>
                                                <span class="fw-semibold text-dark text-truncate d-block" style="max-width: 100px;">@{{ card.bank_name || 'Bank' }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-footer bg-white border-top-0 pt-0">
                                        <button
                                            class="btn btn-sm btn-outline-danger w-100"
                                            @click="confirmDeleteCard(card)"
                                            :disabled="deletingCard">
                                            <i class="bi bi-trash me-1"></i>
                                            {{ __('Eliminar') }}
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @else
            
            @endif
        </div>
    </div>

</div>

{{-- LOGOUT FORM reutilizado --}}
<form id="logout-form"
      action="{{ route('logout') }}"
      method="POST"
      class="d-none">
    @csrf
</form>

@endsection
