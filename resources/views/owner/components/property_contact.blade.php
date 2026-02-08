<div class="card-clean p-4 mb-4">
    <h5 class="fw-bold mb-3">
        {{ auto_trans('Contactos del inmueble') }}
    </h5>

    <div class="mb-4" v-if="propertyForm.contacts.length">
        <div
            class="border rounded p-3 mb-3 position-relative"
            v-for="(contact, index) in propertyForm.contacts"
            :key="index"
        >
            <button
                type="button"
                class="btn-close position-absolute top-0 end-0 m-2"
                @click="removeContact(index)"
            ></button>

            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">{{ auto_trans('Nombre') }}</label>
                    <input type="text" class="form-control" v-model="contact.name">
                </div>

                <div class="col-md-4">
                    <label class="form-label">{{ auto_trans('Teléfono') }}</label>
                    <input type="text" class="form-control" v-model="contact.phone">
                </div>

                <div class="col-md-4">
                    <label class="form-label">{{ auto_trans('Email') }}</label>
                    <input type="email" class="form-control" v-model="contact.email">
                </div>

                <div class="col-md-4">
                    <label class="form-label">{{ auto_trans('WhatsApp') }}</label>
                    <input type="text" class="form-control" v-model="contact.whatsapp">
                </div>

                <div class="col-md-4">
                    <label class="form-label">{{ auto_trans('Foto') }}</label>
                    <input
                        type="file"
                        class="form-control"
                        @change="onContactPhotoChange($event, index)"
                    >
                </div>
            </div>
        </div>
    </div>

    <div>
        <div class="row g-2 align-items-end">
            <div class="col-md-4">
                <input
                    type="text"
                    class="form-control"
                    v-model="newContact.name"
                    placeholder="Nombre"
                >
            </div>

            <div class="col-md-3">
                <input
                    type="text"
                    class="form-control"
                    v-model="newContact.phone"
                    placeholder="Teléfono"
                >
            </div>

            <div class="col-md-3">
                <input
                    type="email"
                    class="form-control"
                    v-model="newContact.email"
                    placeholder="Email"
                >
            </div>

            <div class="col-md-2">
                <button
                    type="button"
                    class="btn btn-outline-success"
                    @click="addContact"
                >
                    +
                </button>
            </div>
        </div>
    </div>
</div>
