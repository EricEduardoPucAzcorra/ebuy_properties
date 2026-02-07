 <div class="card-clean p-4 mb-4">
    <h5 class="fw-bold mb-3">{{ auto_trans('Información del inmueble') }}</h5>

    <div class="mb-3">
        <label class="form-label">{{ auto_trans('Título') }}</label>
        <input type="text" class="form-control" v-model="propertyForm.title"   :class="{ 'is-invalid': errors.title }">
        <div class="invalid-feedback">
            @{{ auto_trans(errors.title) }}
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-md-6">
            <label class="form-label">{{ auto_trans('Tipo de operación') }}</label>
            <select class="form-select" v-model="propertyForm.type_operation_id">
                <option value="null">{{ auto_trans('Seleccione') }}</option>
                <option v-for="type in type_operations"
                        :key="type.id"
                        :value="type.id">
                    @{{ type.name }}
                </option>
            </select>
        </div>

        <div class="col-md-6">
            <label class="form-label">{{ auto_trans('Tipo de propiedad') }}</label>
            <select class="form-select" v-model="propertyForm.type_property_id">
                <option value="null">{{ auto_trans('Seleccione') }}</option>
                <option v-for="type_pro in type_properties"
                        :key="type_pro.id"
                        :value="type_pro.id">
                    @{{ type_pro.name }}
                </option>
            </select>
        </div>
    </div>

    <div class="mb-3">
        <label class="form-label">{{ auto_trans('Descripción') }}</label>
        <textarea class="form-control" rows="4" v-model="propertyForm.description"></textarea>
    </div>
</div>
