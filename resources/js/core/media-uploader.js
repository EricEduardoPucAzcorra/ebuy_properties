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
        <small class="text-muted d-block mb-2">Carga entre 5 y 50 imágenes. Formatos JPG, JPEG, PNG. Tamaño: 500x500px a 6000x6000px.</small>

        <div class="mb-3">
            <input type="file" multiple accept="image/jpeg,image/jpg,image/png" @change="handleFiles" class="form-control">
        </div>

        <div class="sortable-images d-flex flex-wrap gap-2" @dragover.prevent @drop.prevent="handleDrop">
            <div v-for="(f, index) in localFiles" 
                 :key="index" 
                 class="position-relative preview-item sortable-item"
                 draggable="true"
                 @dragstart="handleDragStart(index, $event)"
                 @dragover.prevent="handleDragOver($event)"
                 @drop.prevent="handleDrop(index, $event)">
                
                <img :src="f.preview" 
                     style="width:120px;height:80px;object-fit:cover;border-radius:5px;cursor:move;">
                
                <div class="image-order">{{ index + 1 }}</div>
                
                <div class="remove-btn" 
                     @click="removeFile(index)" 
                     @mouseover="$event.target.style.background='#c82333'; $event.target.style.transform='scale(1.15)'; $event.target.style.boxShadow='0 4px 12px rgba(220, 53, 69, 0.6)'"
                     @mouseout="$event.target.style.background='#dc3545'; $event.target.style.transform='scale(1)'; $event.target.style.boxShadow='0 2px 8px rgba(220, 53, 69, 0.4)'"
                     style="background: #dc3545; color: white; border: 2px solid white; border-radius: 50%; width: 26px; height: 26px; display: flex; align-items: center; justify-content: center; font-size: 16px; font-weight: bold; cursor: pointer; position: absolute; top: 2px; left: 2px; z-index: 15; box-shadow: 0 2px 8px rgba(220, 53, 69, 0.4); padding: 0; margin: 0; line-height: 1; font-family: Arial, sans-serif; user-select: none; transition: all 0.2s ease;">
                    ×
                </div>

                <button
                    type="button"
                    class="btn btn-sm btn-outline-primary position-absolute bottom-0 end-0"
                    @click="setPrimary(index)"
                    :class="{'btn-primary text-white': f.isPrimary}"
                    style="font-size:9px; padding:2px 4px; margin: 0 5px 5px 0;"
                >
                    Principal
                </button>
            </div>
        </div>
    </div>
    
    <style>
    .sortable-item {
        transition: transform 0.2s ease;
    }
    
    .sortable-item:hover {
        transform: translateY(-2px);
    }
    
    .image-order {
        position: absolute;
        top: 5px;
        right: 5px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 50%;
        width: 22px;
        height: 22px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 11px;
        font-weight: bold;
        border: 2px solid white;
        box-shadow: 0 2px 6px rgba(0,0,0,0.2);
        z-index: 5;
        transition: transform 0.2s ease;
    }
    
    .image-order:hover {
        transform: scale(1.1);
    }
    
    .preview-item {
        transition: opacity 0.2s ease;
    }
    
    .preview-item.dragging {
        opacity: 0.5;
    }
    
    .remove-btn {
        position: absolute !important;
        top: 2px !important;
        left: 2px !important;
        background: #dc3545 !important;
        color: white !important;
        border: 2px solid white !important;
        border-radius: 50% !important;
        width: 26px !important;
        height: 26px !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        font-size: 16px !important;
        font-weight: bold !important;
        cursor: pointer !important;
        transition: all 0.2s ease !important;
        z-index: 15 !important;
        box-shadow: 0 2px 8px rgba(220, 53, 69, 0.4) !important;
        opacity: 0.95 !important;
        padding: 0 !important;
        margin: 0 !important;
        line-height: 1 !important;
    }
    
    .remove-btn:hover {
        background: #c82333 !important;
        transform: scale(1.15) !important;
        box-shadow: 0 4px 12px rgba(220, 53, 69, 0.6) !important;
        opacity: 1 !important;
    }
    
    .remove-btn:active {
        transform: scale(0.95) !important;
    }
    
    .remove-btn span {
        font-family: Arial, sans-serif !important;
        user-select: none !important;
    }
    </style>
    `,

    data() {
        return {
            localFiles: [],
            draggedIndex: undefined
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
            const currentImageCount = this.countImages();
            const newImageCount = files.filter(file => file.type.startsWith('image/')).length;
            const totalImageCount = currentImageCount + newImageCount;

            // Validar límite máximo de 50 imágenes
            if (totalImageCount > 50) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Límite excedido',
                    text: 'Solo puedes subir un máximo de 50 imágenes. Ya tienes ' + currentImageCount + ' imágenes.',
                    confirmButtonColor: '#3085d6'
                });
                event.target.value = '';
                return;
            }

            files.forEach(file => {
                // Solo permitir imágenes
                if (!file.type.startsWith('image/')) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Formato no permitido',
                        text: 'Solo se permiten imágenes en formato JPG, JPEG o PNG.',
                        confirmButtonColor: '#3085d6'
                    });
                    return;
                }

                // Validar formato específico
                const allowedFormats = ['image/jpeg', 'image/jpg', 'image/png'];
                if (!allowedFormats.includes(file.type)) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Formato no válido',
                        text: 'Solo se admiten los formatos JPG, JPEG y PNG.',
                        confirmButtonColor: '#3085d6'
                    });
                    return;
                }

                if (this.countImages() >= 50) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Límite alcanzado',
                        text: 'Has alcanzado el máximo de 50 imágenes permitidas.',
                        confirmButtonColor: '#3085d6'
                    });
                    return;
                }

                // Validar tamaño mínimo y máximo (500x500px a 6000x6000px aprox)
                const maxImageSize = 10 * 1024 * 1024; // 10MB
                const minImageSize = 100 * 1024; // 100KB mínimo (aprox 500x500px)

                if (file.size > maxImageSize) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Imagen muy grande',
                        text: 'Las imágenes no pueden exceder 10MB.',
                        confirmButtonColor: '#3085d6'
                    });
                    return;
                }

                if (file.size < minImageSize) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Imagen muy pequeña',
                        text: 'La imagen es demasiado pequeña. El tamaño mínimo recomendado es de 500x500px.',
                        confirmButtonColor: '#3085d6'
                    });
                    return;
                }

                const type = 'image'; // Solo imágenes ahora
                const reader = new FileReader();

                reader.onload = e => {
                    this.localFiles.push({
                        file,
                        type,
                        preview: e.target.result,
                        isPrimary: this.localFiles.length === 0,
                        existing: false,
                        order: this.localFiles.length // Para drag and drop
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

        // Drag and Drop methods
        handleDragStart(index, event) {
            this.draggedIndex = index;
            event.dataTransfer.effectAllowed = 'move';
            event.target.style.opacity = '0.5';
        },

        handleDragOver(event) {
            event.preventDefault();
            event.dataTransfer.dropEffect = 'move';
        },

        handleDrop(index, event) {
            event.preventDefault();

            if (this.draggedIndex === undefined) return;

            // Mover el elemento
            const draggedItem = this.localFiles[this.draggedIndex];
            this.localFiles.splice(this.draggedIndex, 1);
            this.localFiles.splice(index, 0, draggedItem);

            // Resetear
            this.draggedIndex = undefined;
            event.target.style.opacity = '1';

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
