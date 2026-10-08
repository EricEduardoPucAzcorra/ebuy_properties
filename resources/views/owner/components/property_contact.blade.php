<div class="card-clean p-4 mb-4">
    <h5 class="fw-bold mb-3">
        {{ auto_trans('Contacto del inmueble') }}
    </h5>

    <div class="border rounded p-3">
        <div class="row g-3">
            <div class="col-md-4">
                <label class="form-label">{{ auto_trans('Nombre') }}</label>
                <input type="text" class="form-control" v-model="propertyForm.contact.name">
            </div>

            <div class="col-md-4">
                <label class="form-label">{{ auto_trans('Teléfono') }}</label>
                <input type="text" class="form-control" v-model="propertyForm.contact.phone">
            </div>

            <div class="col-md-4">
                <label class="form-label">{{ auto_trans('Email') }}</label>
                <input type="email" class="form-control" v-model="propertyForm.contact.email">
            </div>

            <div class="col-md-4">
                <label class="form-label">{{ auto_trans('WhatsApp') }}</label>
                <input type="text" class="form-control" v-model="propertyForm.contact.whatsapp">
            </div>
        </div>
    </div>
</div>
