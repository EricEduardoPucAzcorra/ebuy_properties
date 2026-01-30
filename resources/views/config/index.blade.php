@extends('layouts.app')

@section('content')
<div id="SettingsModule" class="container-fluid mt-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">{{ __('general.settings.title') }}</h1>
            <p class="text-muted mb-0">{{ __('general.settings.subtitle') }}</p>
        </div>
        @can('config.set_up_global')
            <button class="btn btn-primary" v-if="activeSection!=='business_units'" @click="saveSettings(activeSection)">
                <i class="bi bi-check-lg me-2"></i>
                {{ __('general.settings.save') }}
            </button>
        @endcan
    </div>

    <div class="row g-4">
        <div class="col-lg-3">
            <div class="card shadow-sm">
                <div class="list-group list-group-flush">
                    <button
                        v-for="section in sections"
                        :key="section.id"
                        class="list-group-item list-group-item-action d-flex align-items-center gap-2"
                        :class="{ active: activeSection === section.id }"
                        @click="activesSection(section.id)"
                    >
                        <i :class="section.icon"></i>
                        @{{ section.name }}
                    </button>
                </div>
            </div>
        </div>

        <div class="col-lg-9">
            {{-- @{{tenantPrincipalIsPrincipal}}
            @{{hasData}} --}}
            <div v-if="loading" class="text-center py-5">
                <div class="spinner-border text-primary"></div>
            </div>

            <div class="card shadow-sm mb-4" v-if="activeSection === 'business' && !loading">
                <div class="card-header d-flex align-items-center gap-2">
                    <h5 class="mb-0">{{ __('general.settings.business') }}</h5>
                </div>

                <div class="card-body">
                    <div class="row g-4">

                        <div class="col-md-4 text-center">
                            <div class="company-logo-upload">
                                <div class="logo-preview mb-3">
                                    <img :src="settings.company.logoPreview || defaultLogo">
                                </div>

                                <label class="btn btn-outline-primary btn-sm w-100">
                                    <i class="bi bi-upload me-2"></i>
                                    {{__('general.select_file')}}
                                    <input type="file" class="d-none" accept="image/*" @change="onLogoChange">
                                </label>

                                <small class="text-muted d-block mt-2">
                                    PNG, JPG o SVG · Máx 2MB
                                </small>
                            </div>
                        </div>

                        <div class="col-md-8">
                            <div class="row g-3">

                                <div class="col-md-6">
                                    <label class="form-label">{{ __('general.settings.business.name') }}</label>
                                    <input class="form-control" v-model="settings.company.name">
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">{{ __('general.settings.business.legalName') }}</label>
                                    <input class="form-control" v-model="settings.company.legalName">
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">{{ __('general.settings.business.taxId') }}</label>
                                    <input class="form-control" v-model="settings.company.taxId">
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">{{ __('general.settings.business.email') }}</label>
                                    <input type="email" class="form-control" v-model="settings.company.email">
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">{{ __('general.settings.business.phone') }}</label>
                                    <input class="form-control" v-model="settings.company.phone">
                                </div>

                                <div class="col-md-6 position-relative">
                                    <label class="form-label">{{ __('general.settings.business.country') }}</label>

                                    <input
                                        class="form-control"
                                        v-model="settings.company.country_name"
                                        @input="onCountryInput"
                                        @blur="validateCountry"
                                        placeholder="{{ __('general.autocomplete.search.countrie') }}"
                                    >

                                    <ul v-if="cities.length"
                                        class="list-group position-absolute w-100"
                                        style="z-index:1000; max-height:200px; overflow:auto;">
                                        <li
                                            v-for="city in cities"
                                            :key="city.id"
                                            class="list-group-item list-group-item-action"
                                            @click="selectCountry(city)"
                                            style="cursor:pointer">
                                            @{{ city.name }}
                                        </li>
                                    </ul>
                                </div>

                                <div class="col-12">
                                    <label class="form-label">{{ __('general.settings.business.address') }}</label>
                                    <textarea class="form-control" rows="2"
                                              v-model="settings.company.address"></textarea>
                                </div>

                                <div class="col-12">
                                    <label class="form-label">{{__('general.settings.business.location')}}</label>

                                    <div id="map" style="height:300px;border-radius:8px;"></div>

                                    <small class="text-muted d-block mt-2">
                                       {{__('general.settings.business.click.location')}}
                                    </small>
                                </div>


                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <div class="card shadow-sm" v-if="activeSection === 'appearance' && !loading">
                <div class="card-header">
                    <h5 class="mb-0">Apariencia</h5>
                </div>
                <div class="card-body">
                    <select class="form-select" v-model="settings.theme">
                        <option value="light">Claro</option>
                        <option value="dark">Oscuro</option>
                        <option value="auto">Automático</option>
                    </select>
                </div>
            </div>

            <div class="card shadow-sm" v-if="activeSection === 'business_units' && !loading">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-0">{{__('general.settings.business_units')}}</h5>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row g-3">

                        <div
                            v-if="!settings.tenants.length"
                            class="col-12 col-md-6 col-lg-4"
                        >
                            <div class="card h-100 border border-2 border-dashed">
                                <div class="card-body d-flex align-items-center gap-3">

                                    <div
                                        class="rounded-circle bg-primary bg-opacity-10 d-flex align-items-center justify-content-center flex-shrink-0"
                                        style="width:64px;height:64px;"
                                    >
                                        <i class="bi bi-plus-lg text-primary fs-4"></i>
                                    </div>

                                    <div class="flex-grow-1">
                                        <h6 class="mb-1 fw-semibold">{{__('general.settings.business.new')}}</h6>
                                        <small class="text-muted">{{__('general.settings.business.new.unit')}}</small>
                                    </div>
                                    @can('config.set_up_global')
                                        <button
                                            class="btn btn-primary btn-sm rounded-pill px-3"
                                            @click="createBussine()"
                                        >
                                        {{__('general.settings.business.create')}}
                                        </button>
                                    @endcan

                                </div>
                            </div>
                        </div>

                        <div
                            v-for="tenant in settings.tenants"
                            :key="tenant.id"
                            class="col-12 col-md-6 col-lg-4"
                        >
                            <div class="card h-100 shadow-sm">
                                <div class="card-body d-flex align-items-center gap-3">

                                    <div
                                        class="rounded-circle bg-light d-flex align-items-center justify-content-center flex-shrink-0"
                                        style="width:64px;height:64px;"
                                    >
                                        <img
                                            :src="tenant.logo"
                                            class="img-fluid rounded-circle"
                                            alt="Logo"
                                        >
                                    </div>

                                    <div class="flex-grow-1">
                                        <h6 class="mb-1 fw-semibold">
                                            @{{ tenant.name }}
                                        </h6>
                                        <small class="text-muted">
                                            @{{ tenant.country_name || '—' }}
                                        </small>
                                    </div>
                                    @can('config.set_up_global')
                                        <button
                                            class="btn btn-light btn-sm rounded-circle"
                                            @click="editBussine(tenant)"
                                        >
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                    @endcan
                                </div>
                            </div>
                        </div>

                        <div
                            v-if="settings.tenants.length"
                            class="col-12 col-md-6 col-lg-4"
                        >
                            <div class="card h-100 border border-2 border-dashed">
                                <div class="card-body d-flex align-items-center gap-3">

                                    <div
                                        class="rounded-circle bg-primary bg-opacity-10 d-flex align-items-center justify-content-center flex-shrink-0"
                                        style="width:64px;height:64px;"
                                    >
                                        <i class="bi bi-plus-lg text-primary fs-4"></i>
                                    </div>

                                    <div class="flex-grow-1">
                                        <h6 class="mb-1 fw-semibold">{{__('general.settings.business.new')}}</h6>
                                        <small class="text-muted">{{__('general.settings.business.new.unit')}}</small>
                                    </div>
                                    @can('config.set_up_global')
                                        <button
                                            class="btn btn-outline-primary btn-sm rounded-pill px-3"
                                            @click="createBussine()"
                                        >
                                            {{__('general.settings.business.create')}}
                                        </button>
                                    @endcan
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>
    @include('config.modals')
</div>

<style>
.company-logo-upload { padding: 1.5rem }
.logo-preview {
    width:140px;height:140px;margin:auto;
    border-radius: 50%;
    display:flex;align-items:center;justify-content:center;
    box-shadow:0 4px 12px rgba(0,0,0,.1)
}
.logo-preview img { max-width:100%;max-height:100%;object-fit:contain; border-radius: 50%; }
</style>
@endsection
