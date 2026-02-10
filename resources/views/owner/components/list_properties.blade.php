<div class="container bg-white py-5">
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

            <div class="card-clean h-100 d-flex flex-column">

                <div class="img-frame position-relative">
                    <img
                        :src="property.images.main ?? '/images/ebuy_1.png'"
                        class="img-fluid">

                    <div class="floating-tag">
                        @{{ property.type_operation }}
                    </div>
                </div>

                <div class="p-4 pt-2 flex-grow-1">
                    <div class="price-tag">$@{{ property.price }}</div>

                    <h5 class="fw-bold mb-2">@{{ property.title }}</h5>

                    <p class="text-muted small mb-1">
                        <i class="fa fa-map-marker-alt me-1"></i>
                        @{{ property.address?.city_name }},
                        @{{ property.address?.state_name }}
                    </p>

                    <div class="d-flex align-items-center justify-content-between mt-2">
                        <span
                            class="badge d-flex align-items-center gap-1"
                            :class="getStatusBadge(property.status)">
                            <i class="bi bi-circle-fill small"></i>
                            @{{ property.status }}
                        </span>

                        <div class="d-flex gap-2 property-mini-actions">
                            <button
                                class="icon-btn"
                                title="{{auto_trans('Editar propiedad')}}"
                                @click="editProperty(property)">
                                <i class="bi bi-pencil"></i>
                            </button>

                            <button
                                class="icon-btn"
                                title="Vista previa"
                                @click="previewProperty(property.id)">
                                <i class="bi bi-eye"></i>
                            </button>

                            <button
                                class="icon-btn"
                                title="Cambiar estado"
                                @click="openStateModal(property)">
                                <i class="bi bi-sliders"></i>
                            </button>

                        </div>
                    </div>

                </div>

                <div class="amenity-row px-4 pb-3">
                    <div class="amenity-box" v-if="getAttribute(property, 'Camas')">
                        <i class="bi bi-door-open"></i>
                        <span>@{{ getAttribute(property, 'Camas') }} Camas</span>
                    </div>

                    <div class="amenity-box" v-if="getAttribute(property, 'Baños')">
                        <i class="bi bi-droplet"></i>
                        <span>@{{ getAttribute(property, 'Baños') }} Baños</span>
                    </div>

                    <div class="amenity-box" v-if="getAttribute(property, 'M²')">
                        <i class="bi bi-rulers"></i>
                        <span>@{{ getAttribute(property, 'M²') }} m²</span>
                    </div>
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

<div class="modal fade"
     id="changeStatusModal"
     tabindex="-1"
     aria-hidden="true">

    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">
                    {{ auto_trans('Cambiar estado') }}
                </h5>
                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <select class="form-select" v-model="selectedState">
                    <option disabled value="">
                        {{ auto_trans('Select status') }}
                    </option>

                    <option v-for="state in states"
                            :key="state.id"
                            :value="state.id">
                        @{{ state.name }}
                    </option>
                </select>
            </div>

            <div class="modal-footer">
                <button class="btn btn-light"
                        data-bs-dismiss="modal">
                    {{ auto_trans('Cancel') }}
                </button>

                <button class="btn btn-primary"
                        @click="saveStatus">
                    {{ auto_trans('Guardar') }}
                </button>
            </div>

        </div>
    </div>
</div>


