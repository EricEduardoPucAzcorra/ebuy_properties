<div class="modal fade" id="configModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    @{{ isEditing ? translations.edit : translations.create }}
                </h5>
                <button type="button" class="btn-close"  @click="resetForm()" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
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
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" @click="resetForm()" data-bs-dismiss="modal">
                    {{__('general.users.cancel')}}
                </button>

                <button type="button" v-if="!isEditing" class="btn btn-primary" :disabled="loading" @click="saveSettings(activeSection)">
                    <span v-if="loading" class="spinner-border spinner-border-sm me-1"></span>
                    {{__('general.settings.save')}}
                </button>

                <button type="button" v-if="isEditing" class="btn btn-primary" :disabled="loading" @click="saveSettings(activeSection)">
                    <span v-if="loading" class="spinner-border spinner-border-sm me-1"></span>
                    {{__('general.settings.edit')}}
                </button>
            </div>
        </div>
    </div>
</div>
