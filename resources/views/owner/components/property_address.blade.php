 <div class="card-clean p-4 mb-4">
    <h5 class="fw-bold mb-3">{{ auto_trans('Domicilio') }}</h5>

    <div class="row mb-3">
        <div class="col-md-2">
            <label class="form-label">{{ auto_trans('Calle') }} <span class="text-danger">*</span></label>
            <input type="text" class="form-control" v-model="propertyForm.address.street" :class="{ 'is-invalid': errors.street }">
             <div class="invalid-feedback">
                <span>@{{errors.street}}</span>
            </div>
        </div>

        <div class="col-md-3">
            <label class="form-label">{{ auto_trans('Numero') }} <span class="text-danger">*</span> </label>
            <input type="number" class="form-control" v-model="propertyForm.address.number" :class="{ 'is-invalid': errors.number }">
            <div class="invalid-feedback">
                 <span>@{{errors.number}}</span>
            </div>
        </div>

        <div class="col-md-3">
            <label class="form-label">{{ auto_trans('Codigo postal') }} <span class="text-danger">*</span> </label>
            <input type="text" class="form-control" v-model="propertyForm.address.postal_code" :class="{ 'is-invalid': errors.postal_code }">
            <div class="invalid-feedback">
                 <span>@{{errors.postal_code}}</span>
            </div>
        </div>

        <div class="col-md-4">
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
                    :class="{ 'is-invalid': errors.country }"
                ></autocomplete>
                <div class="invalid-feedback">
                    <span>@{{errors.country}}</span>
                </div>
            </div>

            <div class="col-md-4">
                <autocomplete
                    label="{{ auto_trans('Estado') }}"
                    placeholder="{{ auto_trans('Escribe el estado') }}"
                    url="/states/search"
                    v-model="propertyForm.address.state"
                    :extra-params="{ country_id: propertyForm.address.country.id }"
                    :class="{ 'is-invalid': errors.state }"
                ></autocomplete>
                <div class="invalid-feedback">
                      <span>@{{errors.state}}</span>
                </div>
            </div>

            <div class="col-md-4">
                <autocomplete
                    label="{{ auto_trans('Ciudad') }}"
                    placeholder="{{ auto_trans('Escribe la ciudad') }}"
                    url="/cities/search"
                    v-model="propertyForm.address.city"
                    :extra-params="{ state_id: propertyForm.address.state.id }"
                    :class="{ 'is-invalid': errors.city }"
                ></autocomplete>
                <div class="invalid-feedback">
                      <span>@{{errors.city}}</span>
                </div>
            </div>

        </div>
    </div>
</div>

<div class="card-clean p-4 mb-4">
    <div class="mb-3">
        <div class="col-md-12">
            <map-selector
                v-model="propertyForm.address.location"
                app-icon="{{asset('images/ebuy_icon.png')}}"
                title="{{auto_trans('Seleciona tu propiedad')}}"
                @location-selected="handleLocationSelection">
            </map-selector>
        </div>
    </div>
</div>
