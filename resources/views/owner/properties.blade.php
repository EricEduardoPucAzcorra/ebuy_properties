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

                <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">

                    <h4 class="fw-bold mb-0">
                        {{ auto_trans('Nuevo inmueble') }}
                    </h4>

                   <div class="d-flex align-items-center justify-content-end gap-2 ms-auto">
                        <button
                            class="btn btn-sm btn-outline-secondary px-3"
                            style="min-width: 120px;"
                            :disabled="isSubmitting"
                            @click="showForm = false; resetForm(); fetchProperties();"
                        >
                            <i class="bi bi-arrow-left me-1"></i>
                            {{ auto_trans('Volver') }}
                        </button>

                        <button
                            class="btn btn-sm btn-outline-success px-3"
                            style="min-width: 120px;"
                            @click="submitForm"
                            :disabled="isSubmitting"
                        >
                            <template v-if="isSubmitting">
                                <span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>
                                <span v-if="isEdit">{{ auto_trans('Actualizando') }}</span>
                                <span v-else>{{ auto_trans('Guardando') }}</span>
                            </template>

                            <template v-else>
                                <i class="bi bi-check-circle me-1"></i>
                                <span v-if="isEdit">{{ auto_trans('Actualizar Inmueble') }}</span>
                                <span v-else>{{ auto_trans('Guardar Inmueble') }}</span>
                            </template>
                        </button>
                    </div>
                </div>

                <ul class="nav nav-tabs mb-4" style="border-bottom: 2px solid #2e7d32;" id="propertyTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" :class="{ active: activeFormTab === 'info' }"
                                @click="activeFormTab = 'info'"
                                style="color: #2e7d32; font-weight: 500;">
                            <i class="bi bi-info-circle me-2"></i>{{auto_trans('Información')}}
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" :class="{ active: activeFormTab === 'address' }"
                                @click="activeFormTab = 'address'"
                                style="color: #2e7d32; font-weight: 500;">
                            <i class="bi bi-geo-alt me-2"></i>{{auto_trans('Ubicación')}}
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" :class="{ active: activeFormTab === 'features' }"
                                @click="activeFormTab = 'features'"
                                style="color: #2e7d32; font-weight: 500;">
                            <i class="bi bi-grid-3x3-gap-fill me-2"></i>{{auto_trans('Características')}}
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" :class="{ active: activeFormTab === 'attributes' }"
                                @click="activeFormTab = 'attributes'"
                                style="color: #2e7d32; font-weight: 500;">
                            <i class="bi bi-sliders2 me-2"></i>{{auto_trans('Atributos')}}
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" :class="{ active: activeFormTab === 'media' }"
                                @click="activeFormTab = 'media'"
                                style="color: #2e7d32; font-weight: 500;">
                            <i class="bi bi-images me-2"></i>{{auto_trans('Media')}}
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" :class="{ active: activeFormTab === 'contact' }"
                                @click="activeFormTab = 'contact'"
                                style="color: #2e7d32; font-weight: 500;">
                            <i class="bi bi-telephone me-2"></i>{{auto_trans('Contacto')}}
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" :class="{ active: activeFormTab === 'prices' }"
                                @click="activeFormTab = 'prices'"
                                style="color: #2e7d32; font-weight: 500;">
                            <i class="bi bi-cash-stack me-2"></i>{{auto_trans('Precio')}}
                        </button>
                    </li>
                </ul>

                <div class="tab-content">
                    <div v-if="activeFormTab === 'info'">
                        @include('owner.components.property_information')
                    </div>

                    <div v-if="activeFormTab === 'address'">
                        @include('owner.components.property_address')
                    </div>

                    <div v-if="activeFormTab === 'features'">
                        @include('owner.components.property_features')
                    </div>

                    <div v-if="activeFormTab === 'attributes'">
                        @include('owner.components.property_attributes')
                    </div>

                    <div v-if="activeFormTab === 'media'">
                        @include('owner.components.property_media')
                    </div>

                    <div v-if="activeFormTab === 'contact'">
                        @include('owner.components.property_contact')
                    </div>

                    <div v-if="activeFormTab === 'prices'">
                        @include('owner.components.property_prices')
                    </div>
                </div>


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
        </template>

    </div>
</div>
@endsection
