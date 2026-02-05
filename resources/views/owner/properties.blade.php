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
                @click.prevent="showForm = true"
                v-if="!showForm">
                    <i class="bi bi-house-add me-2"></i> {{ auto_trans('Agregar inmueble') }}
            </a>

        </div>

        <div class="container bg-white py-5"  v-if="!showForm">

            <div class="row align-items-center mb-5">
                <div class="col-12 text-end">
                    <div class="nav nav-pills nav-pills-custom d-inline-flex justify-content-end">

                        <button v-for="type in type_operations" :key="type.id" class="nav-link"
                            :class="{ active: activeTab === type.id }"
                            @click="changeTab(type.id)">
                            @{{type.name}}
                        </button>

                    </div>
                </div>
            </div>

            <div v-if="loading" class="text-center py-5">
                <div class="spinner-border text-primary"></div>
            </div>

            <div v-if="!loading && properties.length === 0" class="text-center py-5">
                <i class="bi bi-house-x display-4 text-muted mb-3"></i>

                <h5 class="fw-bold">{{ auto_trans('No tienes propiedades registradas')}}</h5>

                <p class="text-muted mb-4">
                    {{ auto_trans('Aún no has agregado ningún inmueble.
                    Publica tu primera propiedad para comenzar.')}}'
                </p>
            </div>

            <div v-if="!loading && properties.length > 0" class="row g-4">

                <div class="col-lg-4 col-md-6"
                     v-for="property in properties"
                     :key="property.id">

                    <div class="card-clean h-100">

                        <div class="img-frame position-relative">
                            <img
                                :src="property.main_image ?? '/images/default-property.jpg'"
                                class="img-fluid">

                            <div class="floating-tag">
                                @{{ property.type_operation_id == 1 ? 'FOR SALE' : 'FOR RENT' }}
                            </div>
                        </div>

                        <div class="p-4 pt-2">
                            <div class="price-tag">$@{{ property.price }}</div>

                            <h5 class="fw-bold mb-2">@{{ property.title }}</h5>

                            <p class="text-muted small">
                                <i class="fa fa-map-marker-alt me-1"></i>
                                @{{ property.address?.city?.name }},
                                @{{ property.address?.city?.state?.name }}
                            </p>
                        </div>

                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-center mt-5"
                 v-if="!loading && properties.length > 0 && pagination.last_page > 1">

                <ul class="pagination">
                    <li class="page-item" :class="{ disabled: !pagination.prev_page_url }">
                        <a class="page-link" href="#"
                           @click.prevent="fetchProperties(pagination.current_page - 1)">
                            {{ auto_trans('Anterior')}}
                        </a>
                    </li>

                    <li class="page-item"
                        v-for="page in pagination.last_page"
                        :class="{ active: page === pagination.current_page }">
                        <a class="page-link" href="#"
                           @click.prevent="fetchProperties(page)">
                            @{{ page }}
                        </a>
                    </li>

                    <li class="page-item" :class="{ disabled: !pagination.next_page_url }">
                        <a class="page-link" href="#"
                           @click.prevent="fetchProperties(pagination.current_page + 1)">
                            {{ auto_trans('Siguiente')}}
                        </a>
                    </li>
                </ul>
            </div>

        </div>

        <div v-if="showForm" class="container bg-white py-5">

            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="fw-bold">{{ auto_trans('Nuevo inmueble') }}</h4>

                <button class="btn btn-outline-secondary"
                        @click="showForm = false">
                    <i class="bi bi-arrow-left"></i> {{ auto_trans('Volver a mis propiedades') }}
                </button>
            </div>

            <ul class="nav nav-tabs mb-4">
                <li class="nav-item">
                    <button class="nav-link"
                            :class="{ active: activeCreateTab === 'form' }"
                            @click="activeCreateTab = 'form'">
                        <i class="bi bi-ui-checks me-1"></i>
                        {{ auto_trans('Formulario') }}
                    </button>
                </li>

                <li class="nav-item">
                    <button class="nav-link"
                            :class="{ active: activeCreateTab === 'ia' }"
                            @click="activeCreateTab = 'ia'">
                        <i class="bi bi-cpu me-1"></i>
                        {{ auto_trans('Simulador IA') }}
                    </button>
                </li>
            </ul>

            <div class="tab-content">

                <div v-show="activeCreateTab === 'form'">
                    <div class="card-clean p-4">
                        <h5 class="fw-bold mb-3">{{ auto_trans('Información del inmueble') }}</h5>

                        <div class="mb-3">
                            <label class="form-label">{{ auto_trans('Título') }}</label>
                            <input type="text" class="form-control" v-model="propertyForm.title">
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label class="form-label">{{ auto_trans('Tipo de operación') }}</label>
                                <select class="form-select" v-model="propertyForm.type_operation_id">
                                    <option value="null">{{auto_trans('Selecione un tipo de operación')}}</option>
                                    <option v-for="type in type_operations"
                                            :key="type.id"
                                            :value="type.id">
                                        @{{ type.name }}
                                    </option>
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">{{ auto_trans('Tipo de propiedad') }}</label>
                                <select class="form-select" v-model="propertyForm.type_property_id">
                                    <option value="null">{{auto_trans('Selecione un tipo de propiedad')}}</option>
                                    <option v-for="type_pro in type_properties"
                                            :key="type_pro.id"
                                            :value="type_pro.id">
                                        @{{ type_pro.name }}
                                    </option>
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">{{ auto_trans('Precio') }}</label>
                                <input type="number" class="form-control" v-model="propertyForm.price">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">{{ auto_trans('Descripción') }}</label>
                            <textarea class="form-control" rows="4" v-model="propertyForm.description"></textarea>
                        </div>

                        <h5 class="fw-bold mb-3">{{ auto_trans('Domicilio') }}</h5>

                        <div class="row mb-3">
                            <div class="col-md-2">
                                <label class="form-label">{{ auto_trans('Calle') }}</label>
                                <input type="text" class="form-control" v-model="propertyForm.address.street">
                            </div>

                            <div class="col-md-2">
                                <label class="form-label">{{ auto_trans('Numero') }}</label>
                                <input type="number" class="form-control" v-model="propertyForm.address.number">
                            </div>

                            <div class="col-md-2">
                                <label class="form-label">{{ auto_trans('Codigo postal') }}</label>
                                <input type="text" class="form-control" v-model="propertyForm.address.postal_code">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">{{ auto_trans('Vecindario/Colonia') }}</label>
                                <input type="text" class="form-control" v-model="propertyForm.address.neighborhood">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">{{ auto_trans('Referencias') }}</label>
                            <textarea class="form-control" rows="2" v-model="propertyForm.address.references"></textarea>
                        </div>

                        <div class="mb-3">
                            <div class="row g-3">

                                <div class="col-md-4">
                                    <autocomplete
                                        label="{{ auto_trans('Pais') }}"
                                        placeholder="{{ auto_trans('Escribe el pais') }}"
                                        url="/countries/search"
                                        v-model="propertyForm.address.country"
                                    ></autocomplete>
                                </div>

                                <div class="col-md-4">
                                    <autocomplete
                                        label="{{ auto_trans('Estado') }}"
                                        placeholder="{{ auto_trans('Escribe el estado') }}"
                                        url="/states/search"
                                        v-model="propertyForm.address.state"
                                        :extra-params="{ country_id: propertyForm.address.country.id }"
                                    ></autocomplete>
                                </div>

                                <div class="col-md-4">
                                    <autocomplete
                                        label="{{ auto_trans('Ciudad') }}"
                                        placeholder="{{ auto_trans('Escribe la ciudad') }}"
                                        url="/cities/search"
                                        v-model="propertyForm.address.city"
                                        :extra-params="{ state_id: propertyForm.address.state.id }"
                                    ></autocomplete>
                                </div>

                            </div>
                        </div>

                        <button class="btn btn-success">
                            <i class="bi bi-check-circle me-2"></i>
                            {{ auto_trans('Guardar inmueble') }}
                        </button>
                    </div>
                </div>

                <div v-show="activeCreateTab === 'ia'">
                    <div class="card-clean p-4 bg-light">
                        <h5 class="fw-bold mb-3">🤖 {{ auto_trans('Simulador IA') }}</h5>

                        <p class="text-muted">
                            {{ auto_trans('La IA analizará los datos del inmueble y sugerirá mejoras.') }}
                        </p>

                        <div class="border rounded p-4 text-center text-muted">
                            <i class="bi bi-cpu fs-1 mb-3"></i>
                            <p class="mb-0">
                                {{ auto_trans('Simulación IA próximamente') }}
                            </p>
                        </div>
                    </div>
                </div>

            </div>
        </div>


    </div>
</div>

@include('styles.list_properties')

@endsection
