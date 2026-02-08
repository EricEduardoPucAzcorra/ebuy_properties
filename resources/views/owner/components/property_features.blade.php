<div class="card-clean p-4 mb-4">
    <h5 class="fw-bold mb-3">
        {{ auto_trans('Características del inmueble') }}
    </h5>

    <div v-for="category in featureCategories"
         :key="category.id"
         class="mb-4">

        <div class="d-flex align-items-center gap-2 mb-3">
            <i :class="category.icon" class="text-success fs-5"></i>
            <h6 class="fw-bold mb-0">
                @{{ category.name }}
            </h6>
        </div>

        <div class="row g-3" :class="{ 'is-invalid': errors.features }">
            <div class="col-md-3 col-sm-6"
                v-for="feature in category.features"
                :key="feature.id">

                <div
                    class="border rounded p-3 h-100 d-flex align-items-center gap-2 feature-card"
                    :class="{ active: propertyForm.features.includes(feature.id) }"
                    @click="toggleFeature(feature.id)"
                >
                    <i :class="feature.icon" class="fs-5 text-success"></i>
                    <div>
                        <div class="fw-semibold">@{{ feature.name }}</div>
                        <small class="text-muted">@{{ feature.description }}</small>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <div class="invalid-feedback d-block" v-if="errors.features">
        <span>@{{ errors.features }}</span>
    </div>
</div>


