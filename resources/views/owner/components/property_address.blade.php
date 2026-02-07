 <div class="card-clean p-4 mb-4">
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
</div>

<div class="card-clean p-4 mb-4">
    <div class="mb-3">
        <div class="col-md-12">
            <map-selector v-model="propertyForm.address.location"></map-selector>
        </div>
    </div>
</div>
