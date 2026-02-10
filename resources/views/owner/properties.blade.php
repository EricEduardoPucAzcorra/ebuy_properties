@extends('layouts.app')

@section('content')
<div id="mypropiertes">
    <div class="container-fluid py-5">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-0">{{ auto_trans('Mis propiedades') }}</h1>
                <p class="text-muted mb-0">{{ auto_trans('Gestionar mis propiedades y servicios.')}}</p>
            </div>

            <a href="#"
                class="btn btn-primary rounded-pill px-4"
                @click.prevent="showForm = true; resetForm();"
                v-if="!showForm">
                    <i class="bi bi-house-add me-2"></i> {{ auto_trans('Agregar inmueble') }}
            </a>

        </div>

        <template v-if="!showForm">

        @include('owner.components.list_properties')

        </template>

        <template v-else>
            <div class="container-fluid bg-white py-5">

                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="fw-bold">{{ auto_trans('Nuevo inmueble') }}</h4>

                    <button class="btn btn-outline-secondary" :disabled="isSubmitting"
                            @click="showForm = false; resetForm(); fetchProperties();">
                        <i class="bi bi-arrow-left"></i>
                        {{ auto_trans('Volver a mis propiedades') }}
                    </button>
                </div>

                <div class="row g-4">

                    <div class="col-lg-12">

                        @include('owner.components.property_information')

                        @include('owner.components.property_address')

                        @include('owner.components.property_contact')

                        @include('owner.components.property_features')

                        @include('owner.components.property_attributes')

                        @include('owner.components.property_media')

                        @include('owner.components.property_prices')

                        <button
                            class="btn btn-success btn-lg"
                            @click="submitForm"
                            :disabled="isSubmitting"
                        >
                            <template v-if="isSubmitting">
                                <span
                                    class="spinner-border spinner-border-sm me-2"
                                    role="status"
                                    aria-hidden="true">
                                </span>

                                <template v-if="isEdit">
                                    {{auto_trans('Actualizando...')}}
                                </template>
                                <template v-else>
                                    {{auto_trans('Guardando...')}}
                                </template>
                            </template>

                            <template v-else>
                                <i class="bi bi-check-circle me-2"></i>
                                <template v-if="isEdit">
                                    {{auto_trans('Actualizar inmueble')}}
                                </template>
                                <template v-else>
                                    {{auto_trans('Guardar inmueble')}}
                                </template>
                            </template>
                        </button>

                    </div>

                    {{-- <div class="col-lg-4">

                        <div class="card-clean p-4 ia-sticky">
                            <h5 class="fw-bold mb-3">
                                🤖 {{ auto_trans('Asistente IA') }}
                            </h5>

                            <p class="text-muted small">
                                {{ auto_trans('La IA analizará la información mientras completas el formulario y te sugerirá mejoras.') }}
                            </p>

                            <div class="border rounded p-3 text-center text-muted mb-3">
                                <i class="bi bi-cpu fs-1 mb-2"></i>
                                <p class="mb-0">
                                    {{ auto_trans('Análisis en tiempo real próximamente') }}
                                </p>
                            </div>

                            <ul class="list-unstyled small text-muted">
                                <li>✔ Mejora del título</li>
                                <li>✔ Precio recomendado</li>
                                <li>✔ Descripción optimizada</li>
                                <li>✔ Detección de faltantes</li>
                            </ul>
                        </div>

                    </div> --}}

                    <button v-if="showForm"
                            class="btn-ai-float"
                            data-bs-toggle="modal"
                            data-bs-target="#modalIA">
                        <span class="fs-4"><i class="bi bi-robot"></i></span>
                        <span>{{ auto_trans('Ebuy Asistente') }}</span>
                    </button>

                    <div class="modal fade modal-ai" id="modalIA" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header d-flex justify-content-between align-items-center">
                                    <h5 class="modal-title fw-bold">
                                        <i class="bi bi-robot"></i> {{ auto_trans('Ebuy Asistente') }}
                                    </h5>
                                    <span class="ai-badge-status">{{ auto_trans('En línea') }}</span>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body p-4">
                                    <p class="text-muted small mb-4">
                                        {{ auto_trans('La IA analizará la información mientras completas el formulario y te sugerirá mejoras.') }}
                                    </p>

                                    <div class="border rounded-4 p-4 text-center text-muted mb-4 bg-light">
                                        <i class="bi bi-cpu fs-1 mb-2 d-block text-primary"></i>
                                        <p class="mb-0 fw-semibold">
                                            {{ auto_trans('Análisis en tiempo real próximamente') }}
                                        </p>
                                    </div>

                                    <ul class="list-unstyled small">
                                        <li class="mb-2"><i class="bi bi-check2-circle text-success me-2"></i>Mejora del título</li>
                                        <li class="mb-2"><i class="bi bi-check2-circle text-success me-2"></i>Precio recomendado</li>
                                        <li class="mb-2"><i class="bi bi-check2-circle text-success me-2"></i>Descripción optimizada</li>
                                        <li class="mb-2"><i class="bi bi-check2-circle text-success me-2"></i>Detección de faltantes</li>
                                    </ul>
                                </div>
                                <div class="modal-footer border-0 p-4 pt-0">
                                    <button type="button" class="btn btn-light rounded-pill w-100" data-bs-dismiss="modal">
                                        {{ auto_trans('Entendido, seguir editando') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </template>

    </div>
</div>

@include('styles.list_properties')

@endsection
