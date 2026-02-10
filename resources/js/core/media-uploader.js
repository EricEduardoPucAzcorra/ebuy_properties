Vue.component('media-uploader', {
    props: {
        value: {
            type: Object,
            default: () => ({ files: [] })
        },
        label: { type: String, default: 'Medios' },
        maxImages: { type: Number, default: 10 }
    },

    template: `
    <div class="media-uploader">
        <label class="form-label fw-semibold">{{ label }}</label>

        <div class="mb-3">
            <input type="file" multiple accept="image/*,video/*" @change="handleFiles" class="form-control">
        </div>

        <div class="d-flex flex-wrap gap-2">
            <div v-for="(f, index) in localFiles" :key="index" class="position-relative preview-item">
                <img v-if="f.type==='image'" :src="f.preview" style="width:120px;height:80px;object-fit:cover;border-radius:5px;">
                <video v-else :src="f.preview" style="width:120px;height:80px;border-radius:5px; cursor:pointer;" @click="toggleVideo($event)"></video>

                <button type="button" class="btn-close remove-btn" @click="removeFile(index)"></button>

                <button
                    type="button"
                    class="btn btn-sm btn-outline-primary position-absolute bottom-0 start-0"
                    @click="setPrimary(index)"
                    :class="{'btn-primary text-white': f.isPrimary}"
                    style="font-size:10px; padding:2px 6px;"
                >
                    Principal
                </button>
            </div>
        </div>
    </div>
    `,

    data() {
        return {
            localFiles: []
        }
    },

    // 1. ESCUCHAR CAMBIOS DEL PADRE (Clave para la edición)
    watch: {
        'value.files': {
            handler(newFiles) {
                // Actualizamos localFiles solo si hay un cambio externo (como al editar)
                this.localFiles = [...newFiles];
            },
            deep: true
        }
    },

    mounted() {
        if (this.value.files) {
            this.localFiles = [...this.value.files];
        }
    },

    methods: {
        handleFiles(event) {
            const files = Array.from(event.target.files);
            files.forEach(file => {
                if (file.type.startsWith('image/') && this.countImages() >= this.maxImages) return;

                const type = file.type.startsWith('image/') ? 'image' : 'video';
                const reader = new FileReader();

                reader.onload = e => {
                    this.localFiles.push({
                        file,
                        type,
                        preview: e.target.result,
                        isPrimary: this.localFiles.length === 0,
                        existing: false // Marcamos que es nuevo
                    });
                    this.emitChange();
                };
                reader.readAsDataURL(file);
            });
            event.target.value = '';
        },

        removeFile(index) {
            this.localFiles.splice(index, 1);
            if (!this.localFiles.some(f => f.isPrimary) && this.localFiles.length) {
                this.localFiles[0].isPrimary = true;
            }
            this.emitChange();
        },

        setPrimary(index) {
            this.localFiles.forEach((f, i) => f.isPrimary = i === index);
            this.emitChange();
        },

        emitChange() {
            this.$emit('input', {
                files: [...this.localFiles] // Enviamos copia limpia
            });
        },

        toggleVideo(event) {
            const video = event.target;
            if (video.paused) video.play();
            else video.pause();
        },

        countImages() {
            return this.localFiles.filter(f => f.type === 'image').length;
        }
    }
});
