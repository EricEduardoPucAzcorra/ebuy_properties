Vue.component('map-listings', {
    props: {
        locations: {
            type: Array,
            required: true
        },
        config: {
            type: Object,
            default: () => ({
                center: [19.4326, -99.1332],
                zoom: 12
            })
        }
    },

    template: `
        <div class="map-container">
            <div id="map-listings" :style="mapStyles"></div>
            <div class="map-controls">
                <button @click="toggleFullscreen" class="fullscreen-btn" :title="isFullscreen ? 'Salir de pantalla completa' : 'Pantalla completa'">
                    <i :class="isFullscreen ? 'fas fa-compress' : 'fas fa-expand'"></i>
                </button>
                <div class="zoom-controls">
                    <button @click="zoomIn" class="zoom-btn" title="Acercar">
                        <i class="fas fa-plus"></i>
                    </button>
                    <button @click="zoomOut" class="zoom-btn" title="Alejar">
                        <i class="fas fa-minus"></i>
                    </button>
                    <button @click="resetView" class="zoom-btn" title="Restablecer vista">
                        <i class="fas fa-home"></i>
                    </button>
                </div>
            </div>
        </div>
    `,

    data() {
        return {
            map: null,
            markers: [],
            isFullscreen: false,
            mapStyles: {
                height: this.isFullscreen ? '100vh' : '600px',
                width: '100%',
                transition: 'height 0.3s ease'
            }
        }
    },

    mounted() {
        this.initMap()
        this.setupKeyboardControls()

        // Asegurar que los controles estén activos después de la montura
        this.$nextTick(() => {
            setTimeout(() => {
                this.enableAllZoomControls()
            }, 500)
        })
    },

    watch: {
        locations: {
            handler() {
                this.updateMarkers()
            },
            deep: true
        },
        config: {
            handler() {
                if (this.map) {
                    this.map.setView(this.config.center, this.config.zoom)
                }
            },
            deep: true
        }
    },

    methods: {
        initMap() {
            // Inicializar el mapa con controles mejorados
            this.map = L.map('map-listings', {
                center: this.config.center,
                zoom: this.config.zoom,
                zoomControl: false, // Desactivar controles por defecto para usar los personalizados
                scrollWheelZoom: true, // Activar zoom con scroll
                doubleClickZoom: true,  // Activar zoom con doble clic
                dragging: true,         // Activar arrastre con mouse
                touchZoom: true,        // Activar zoom táctil
                boxZoom: true          // Activar zoom con selección de caja
            })

            // Agregar capa de tiles con mejor estilo
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap contributors',
                maxZoom: 19
            }).addTo(this.map)

            // Agregar escala
            L.control.scale({
                position: 'bottomleft',
                metric: true,
                imperial: false
            }).addTo(this.map)

            // Asegurar que el zoom con scroll esté activo
            setTimeout(() => {
                this.map.scrollWheelZoom.enable()
                this.map.doubleClickZoom.enable()
                this.map.dragging.enable()
                this.map.touchZoom.enable()
                this.map.boxZoom.enable()

                console.log('Controles de zoom activados:', {
                    scrollWheelZoom: this.map.scrollWheelZoom.enabled(),
                    doubleClickZoom: this.map.doubleClickZoom.enabled(),
                    dragging: this.map.dragging.enabled(),
                    touchZoom: this.map.touchZoom.enabled(),
                    boxZoom: this.map.boxZoom.enabled()
                })
            }, 100)

            // Actualizar marcadores iniciales
            this.updateMarkers()

            // Guardar referencia al mapa para uso externo
            this.$emit('map-ready', this.map)
        },

        updateMarkers() {
            // Limpiar marcadores existentes
            this.markers.forEach(marker => {
                this.map.removeLayer(marker)
            })
            this.markers = []

            // Agregar nuevos marcadores
            this.locations.forEach(item => {
                const popupContent = this.createPopupContent(item)

                const marker = L.marker([item.lat, item.lng])
                    .addTo(this.map)
                    .bindPopup(popupContent, {
                        maxWidth: 300,
                        className: 'property-popup'
                    })

                this.markers.push(marker)
            })

            // Ajustar vista si hay marcadores
            if (this.markers.length > 0) {
                this.fitMapToMarkers()
            }
        },

        createPopupContent(item) {
            return `
                <div class="property-popup-content">
                    <div style="position: relative;">
                        ${item.image ? `<img src="${item.image}" alt="${item.title}" style="width: 100%; height: 150px; object-fit: cover; border-radius: 8px; margin-bottom: 10px;">` : ''}
                        <button class="favorite-btn" data-property-id="${item.id}" title="Agregar a favoritos">
                            <i class="far fa-heart text-muted"></i>
                        </button>
                    </div>
                    <h5 style="margin: 0 0 10px 0; color: #2c3e50;">${item.title}</h5>
                    <p style="margin: 5px 0; font-size: 1.2em; font-weight: bold; color: #00b388;">
                        ${item.currency} ${item.price}
                    </p>
                    <p style="margin: 5px 0; color: #6c757d;">
                        <i class="fas fa-tag"></i> ${item.type_property}
                    </p>
                    <p style="margin: 5px 0; color: #6c757d;">
                        <i class="fas fa-exchange-alt"></i> ${item.type_operation}
                    </p>
                    <a href="${item.url}" class="btn btn-primary btn-sm" style="margin-top: 10px;">
                        <i class="fas fa-eye"></i> Ver detalles
                    </a>
                </div>
            `
        },

        fitMapToMarkers() {
            if (this.markers.length === 0) return

            const group = new L.featureGroup(this.markers)
            this.map.fitBounds(group.getBounds().pad(0.1))
        },

        zoomIn() {
            this.map.zoomIn()
        },

        zoomOut() {
            this.map.zoomOut()
        },

        resetView() {
            this.map.setView(this.config.center, this.config.zoom)
        },

        toggleFullscreen() {
            this.isFullscreen = !this.isFullscreen
            this.mapStyles.height = this.isFullscreen ? '100vh' : '600px'

            // Forzar redimensionamiento del mapa
            this.$nextTick(() => {
                this.map.invalidateSize()

                // Reactivar controles de zoom después del redimensionamiento
                setTimeout(() => {
                    this.enableAllZoomControls()
                }, 200)

                // Ajustar vista en pantalla completa
                if (this.isFullscreen && this.markers.length > 0) {
                    this.fitMapToMarkers()
                }
            })

            // Manejar pantalla completa del navegador
            if (this.isFullscreen) {
                this.enterBrowserFullscreen()
            } else {
                this.exitBrowserFullscreen()
            }
        },

        enableAllZoomControls() {
            if (!this.map) return

            try {
                this.map.scrollWheelZoom.enable()
                this.map.doubleClickZoom.enable()
                this.map.dragging.enable()
                this.map.touchZoom.enable()
                this.map.boxZoom.enable()

                console.log('Controles de zoom reactivados después de redimensionamiento')
            } catch (error) {
                console.error('Error al reactivar controles de zoom:', error)
            }
        },

        async enterBrowserFullscreen() {
            try {
                const mapContainer = document.getElementById('map-listings')
                if (mapContainer.requestFullscreen) {
                    await mapContainer.requestFullscreen()
                } else if (mapContainer.webkitRequestFullscreen) {
                    await mapContainer.webkitRequestFullscreen()
                } else if (mapContainer.msRequestFullscreen) {
                    await mapContainer.msRequestFullscreen()
                }
            } catch (error) {
                console.log('Pantalla completa no soportada:', error)
            }
        },

        async exitBrowserFullscreen() {
            try {
                if (document.exitFullscreen) {
                    await document.exitFullscreen()
                } else if (document.webkitExitFullscreen) {
                    await document.webkitExitFullscreen()
                } else if (document.msExitFullscreen) {
                    await document.msExitFullscreen()
                }
            } catch (error) {
                console.log('Error al salir de pantalla completa:', error)
            }
        },

        setupKeyboardControls() {
            document.addEventListener('keydown', (e) => {
                if (!this.map) return

                switch (e.key) {
                    case '+':
                    case '=':
                        if (e.ctrlKey || e.metaKey) {
                            e.preventDefault()
                            this.zoomIn()
                        }
                        break
                    case '-':
                    case '_':
                        if (e.ctrlKey || e.metaKey) {
                            e.preventDefault()
                            this.zoomOut()
                        }
                        break
                    case 'Escape':
                        if (this.isFullscreen) {
                            this.toggleFullscreen()
                        }
                        break
                }
            })

            // Escuchar cambios de pantalla completa
            document.addEventListener('fullscreenchange', () => {
                if (!document.fullscreenElement && this.isFullscreen) {
                    this.isFullscreen = false
                    this.mapStyles.height = '600px'
                    this.$nextTick(() => {
                        this.map.invalidateSize()
                    })
                }
            })
        }
    },

    beforeDestroy() {
        if (this.map) {
            this.map.remove()
        }
    }
})

// default
// locations: [
//     {
//         lat: 19.43,
//         lng: -99.13,
//         icon: '/img/house.png',
//         popup: `
//             <strong>Departamento</strong><br>
//             $12,000
//         `
//     },
//     {
//         lat: 19.45,
//         lng: -99.15,
//         icon: '/img/house.png',
//         popup: `
//             <strong>Casa</strong><br>
//             $18,500
//         `
//     }
// ]
