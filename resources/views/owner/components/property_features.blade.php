<div class="card-clean p-4 mb-4">
    <h5 class="fw-bold mb-3">{{ auto_trans('Características del inmueble') }}</h5>

    <div class="row g-3">
        <div class="col-md-3 col-sm-6"
            v-for="feature in features"
            :key="feature.id">
            <div
                class="border rounded p-3 h-100 d-flex align-items-center gap-2 feature-card"
                :class="{ active: propertyForm.features.includes(feature.id) }"
                @click="toggleFeature(feature.id)"
            >
                <i :class="feature.icon" class="fs-5 text-primary"></i>
                <div>
                    <div class="fw-semibold">@{{ feature.name }}</div>
                    <small class="text-muted">@{{ feature.description }}</small>
                </div>
            </div>
        </div>
    </div>
</div>
