 <div class="card-clean p-4 mb-4">
    <h5 class="fw-bold mb-3">{{ auto_trans('Estimaciones de costo') }}</h5>
    <div class="row mb-3">
        <div class="col-md-4">
            <label class="form-label">{{ auto_trans('Precio') }}</label>
            <input type="number" class="form-control" v-model="propertyForm.price">
        </div>
        <div class="col-md-4">
            <label class="form-label">{{ auto_trans('Moneda') }}</label>
            <select class="form-select" v-model="propertyForm.currency">
                <option value="MNX" selected>MNX</option>
                <option value="USD">USD</option>
            </select>
        </div>
        <div class="col-md-4">
            <label class="form-label">{{ auto_trans('¿Precio negociable?') }}</label>
            <select class="form-select" v-model="propertyForm.price_negotiable">
                <option value="0" selected>{{ auto_trans('NO')}}</option>
                <option value="1">{{ auto_trans('SI')}}</option>
            </select>
        </div>
    </div>
</div>
