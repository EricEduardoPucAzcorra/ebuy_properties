 <div class="card-clean p-4 mb-4">
    <h5 class="fw-bold mb-3"> {{ auto_trans('Atributos del inmueble') }}</h5>

    <div class="mb-4">
        <div class="row g-3">
            <div
                class="col-md-4"
                v-for="(attr, index) in propertyForm.attributes"
                :key="index"
            >
                <div class="input-group">
                    <span class="input-group-text text-capitalize">
                        @{{ attr.key }}
                    </span>
                    <input
                        type="number"
                        class="form-control"
                        v-model="attr.value"
                        placeholder="0"
                    >
                </div>
            </div>
        </div>
    </div>

    <div class="mb-4">
        <label class="form-label fw-semibold">
            {{ auto_trans('Agregar atributo personalizado') }}
        </label>

        <div class="row g-2 align-items-end">
            <div class="col-md-5">
                <input
                    type="text"
                    class="form-control"
                    v-model="newAttribute.key"
                    placeholder="Ej: frigobar"
                >
            </div>

            <div class="col-md-4">
                <input
                    type="text"
                    class="form-control"
                    v-model="newAttribute.value"
                    placeholder="Ej: 1"
                >
            </div>

            <div class="col-md-3">
                <button
                    type="button"
                    class="btn btn-outline-success w-100"
                    @click="addCustomAttribute"
                >
                    + Agregar
                </button>
            </div>
        </div>
    </div>
</div>
