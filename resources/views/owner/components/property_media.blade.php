<div class="card-clean p-4 mb-4">
    <h5 class="fw-bold mb-3"> {{ auto_trans('Fotografías') }}</h5>

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

<div class="card-clean p-4 mb-4">
    <h5 class="fw-bold mb-3"> {{ auto_trans('Videos') }}</h5>
    
    <div class="mb-3">
        <small class="text-muted d-block mb-2">{{ auto_trans('Agrega enlaces de videos de YouTube, Vimeo, Facebook, Instagram, TikTok, Twitter u otras plataformas. Máximo 5 videos.') }}</small>
        
        <div class="d-flex gap-1 mb-2">
            <input type="url" 
                   v-model="newVideoUrl" 
                   class="form-control form-control-sm" 
                   placeholder="{{ auto_trans('https://youtube.com/watch?v=...') }}"
                   @keyup.enter="addVideo">
            <button type="button" 
                    class="btn btn-primary btn-sm" 
                    @click="addVideo"
                    :disabled="!newVideoUrl || propertyForm.videos.length >= 5">
                <i class="bi bi-plus-circle"></i> {{ auto_trans('Agregar') }}
            </button>
        </div>
    </div>

    <div v-if="propertyForm.videos && propertyForm.videos.length > 0" class="video-list">
        <div v-for="(video, index) in propertyForm.videos" :key="index" class="d-flex align-items-center gap-1 mb-2 p-2 border rounded">
            <i :class="'bi ' + getVideoPlatformIcon(video) + ' fs-5'"></i>
            <div class="flex-grow-1">
                <div v-if="editingVideoIndex === index">
                    <input type="url" 
                           v-model="editingVideoUrl" 
                           class="form-control form-control-sm" 
                           @keyup.enter="saveVideoEdit(index)"
                           @keyup.escape="cancelVideoEdit"
                           @blur="saveVideoEdit(index)"
                           :ref="'videoEditInput_' + index">
                    <small class="text-muted d-block mt-1">@{{ getVideoPlatformName(editingVideoUrl) }}</small>
                </div>
                <div v-else>
                    <small class="text-muted d-block" style="font-size: 0.75rem;">@{{ video }}</small>
                    <small class="text-primary d-block" style="font-size: 0.7rem;">@{{ getVideoPlatformName(video) }}</small>
                </div>
            </div>
            <div class="d-flex gap-1">
                <button v-if="editingVideoIndex === index"
                        type="button" 
                        class="btn btn-sm btn-outline-success" 
                        @click="saveVideoEdit(index)"
                        title="Guardar"
                        style="padding: 0.25rem 0.5rem;">
                    <i class="bi bi-check"></i>
                </button>
                <button v-if="editingVideoIndex === index"
                        type="button" 
                        class="btn btn-sm btn-outline-secondary" 
                        @click="cancelVideoEdit"
                        title="Cancelar"
                        style="padding: 0.25rem 0.5rem;">
                    <i class="bi bi-x"></i>
                </button>
                <button v-if="editingVideoIndex !== index"
                        type="button" 
                        class="btn btn-sm btn-outline-primary" 
                        @click="startVideoEdit(index)"
                        title="Editar"
                        style="padding: 0.25rem 0.5rem;">
                    <i class="bi bi-pencil"></i>
                </button>
                <button v-if="editingVideoIndex !== index"
                        type="button" 
                        class="btn btn-sm btn-outline-danger" 
                        @click="removeVideo(index)"
                        title="Eliminar"
                        style="padding: 0.25rem 0.5rem;">
                    <i class="bi bi-trash"></i>
                </button>
            </div>
        </div>
    </div>

    <div class="invalid-feedback d-block" v-if="errors.videos">
        <span>@{{errors.videos}}</span>
    </div>
</div>

<div class="card-clean p-4 mb-4">
    <h5 class="fw-bold mb-3"> {{ auto_trans('Recorrido 360°') }}</h5>
    
    <div class="mb-3">
        <small class="text-muted d-block mb-2">{{ auto_trans('Agrega tours virtuales 360° de Matterport, Kuula, RoundMe, Panoskin, 3DVista, Google Street View, YouTube 360°, Vimeo 360° u otras plataformas. Máximo 1 recorrido.') }}</small>
        
        <div class="d-flex gap-1 mb-2">
            <input type="url" 
                   v-model="newTour360Url" 
                   class="form-control form-control-sm" 
                   placeholder="{{ auto_trans('https://matterport.com/show/...') }}"
                   @keyup.enter="addTour360">
            <button type="button" 
                    class="btn btn-primary btn-sm" 
                    @click="addTour360"
                    :disabled="!newTour360Url || propertyForm.tours360.length >= 1">
                <i class="bi bi-plus-circle"></i> {{ auto_trans('Agregar') }}
            </button>
        </div>
    </div>

    <div v-if="propertyForm.tours360 && propertyForm.tours360.length > 0" class="tour360-list">
        <div v-for="(tour, index) in propertyForm.tours360" :key="index" class="d-flex align-items-center gap-1 mb-2 p-2 border rounded">
            <i :class="'bi ' + getTour360PlatformIcon(tour) + ' fs-5'"></i>
            <div class="flex-grow-1">
                <div v-if="editingTour360Index === index">
                    <input type="url" 
                           v-model="editingTour360Url" 
                           class="form-control form-control-sm" 
                           @keyup.enter="saveTour360Edit(index)"
                           @keyup.escape="cancelTour360Edit"
                           @blur="saveTour360Edit(index)"
                           :ref="'tour360EditInput_' + index">
                    <small class="text-muted d-block mt-1">@{{ getTour360PlatformName(editingTour360Url) }}</small>
                </div>
                <div v-else>
                    <small class="text-muted d-block" style="font-size: 0.75rem;">@{{ tour }}</small>
                    <small class="text-primary d-block" style="font-size: 0.7rem;">@{{ getTour360PlatformName(tour) }}</small>
                </div>
            </div>
            <div class="d-flex gap-1">
                <button v-if="editingTour360Index === index"
                        type="button" 
                        class="btn btn-sm btn-outline-success" 
                        @click="saveTour360Edit(index)"
                        title="Guardar"
                        style="padding: 0.25rem 0.5rem;">
                    <i class="bi bi-check"></i>
                </button>
                <button v-if="editingTour360Index === index"
                        type="button" 
                        class="btn btn-sm btn-outline-secondary" 
                        @click="cancelTour360Edit"
                        title="Cancelar"
                        style="padding: 0.25rem 0.5rem;">
                    <i class="bi bi-x"></i>
                </button>
                <button v-if="editingTour360Index !== index"
                        type="button" 
                        class="btn btn-sm btn-outline-primary" 
                        @click="startTour360Edit(index)"
                        title="Editar"
                        style="padding: 0.25rem 0.5rem;">
                    <i class="bi bi-pencil"></i>
                </button>
                <button v-if="editingTour360Index !== index"
                        type="button" 
                        class="btn btn-sm btn-outline-danger" 
                        @click="removeTour360(index)"
                        title="Eliminar"
                        style="padding: 0.25rem 0.5rem;">
                    <i class="bi bi-trash"></i>
                </button>
            </div>
        </div>
    </div>

    <div class="invalid-feedback d-block" v-if="errors.tours360">
        <span>@{{errors.tours360}}</span>
    </div>
</div>
