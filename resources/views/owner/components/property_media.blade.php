<div class="card-clean p-4 mb-4">
    <h5 class="fw-bold mb-3"> {{ auto_trans('Fotografías y videos') }}</h5>

    <media-uploader
        v-model="propertyForm.media"
        label=""
        :class="{ 'is-invalid': errors.media || errors.media_primary }">
    </media-uploader>

    <div class="invalid-feedback d-block" v-if="errors.media || errors.media_primary">

        <span v-if="errors.media"><span>@{{errors.media}}</span></span>

        <span v-else-if="errors.media_primary">@{{errors.media_primary}}</span>

    </div>
</div>
