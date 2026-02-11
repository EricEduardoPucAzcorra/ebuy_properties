Vue.component('map-selector', {
    props: {
        value: {
            type: Object,
            default: () => ({ lat: 19.4326, lng: -99.1332 })
        },
        readonly: { type: Boolean, default: false },
        title: { type: String, default: '' },
        appIcon: { type: String, default: '' },
        image: { type: String, default: '' },
        price: { type: String, default: '' },
        currency: { type: String, default: '' }
    },

    template: `
        <div class="map-container-wrapper" style="position: relative;">
            <div id="map-selector" ref="mapElement" style="height:400px; width:100%;"></div>
            <button v-if="!readonly" class="fullscreen-btn" @click="toggleFullscreen">
               <i class="bi bi-arrows-fullscreen"></i>
            </button>
        </div>
    `,

    data() {
        return {
            map: null,
            marker: null
        }
    },

    mounted() {
        this.initMap();
        document.addEventListener('fullscreenchange', this.handleResize);
    },

    beforeDestroy() {
        document.removeEventListener('fullscreenchange', this.handleResize);
    },

    watch: {
        'value.lat': function(newVal) { this.updateMarkerPos(); },
        'value.lng': function(newVal) { this.updateMarkerPos(); }
    },

    methods: {
        initMap() {
            const mapOptions = {
                dragging: !this.readonly,
                scrollWheelZoom: true,
                zoomControl: true,
                attributionControl: false
            };

            this.map = window.L.map(this.$refs.mapElement, mapOptions)
                .setView([this.value.lat, this.value.lng], 15);

            window.L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(this.map);

            const customIcon = window.L.divIcon({
                className: 'custom-div-icon',
                html: `
                    <div class="marker-wrapper">
                        <div class="marker-pin">
                            <div class="marker-logo" style="background-image: url('${this.appIcon}');"></div>
                        </div>
                        <div class="marker-shadow-floor"></div>
                    </div>
                `,
                iconSize: [60, 60],
                iconAnchor: [30, 60],
                popupAnchor: [0, -65]
            });

            this.marker = window.L.marker([this.value.lat, this.value.lng], {
                draggable: !this.readonly,
                icon: customIcon
            }).addTo(this.map);

            // this.updatePopupContent();

            this.map.on('click', e => {
                if (this.readonly) return;
                this.marker.setLatLng(e.latlng);
                this.emitLocation(e.latlng);
            });

            this.marker.on('dragend', e => {
                this.emitLocation(e.target.getLatLng());
            });
        },

        updatePopupContent() {
            const popupHtml = `
                <div class="modern-popup">
                    <div class="popup-image-wrapper">
                        <img src="${this.image}" class="popup-img" onerror="this.style.display='none'">
                        <div class="popup-price-tag" v-if="${this.price}">${this.currency} ${this.price}</div>
                    </div>
                    <div class="popup-info">
                        <h6 class="popup-title">${this.title}</h6>
                        <small class="text-muted">Arrastra para ajustar</small>
                    </div>
                </div>
            `;
            this.marker.bindPopup(popupHtml, { maxWidth: 220, className: 'custom-leaflet-popup' });
        },

        updateMarkerPos() {
            if (this.map && this.marker) {
                const pos = [this.value.lat, this.value.lng];
                this.marker.setLatLng(pos);
                this.map.panTo(pos);
            }
        },

        emitLocation(latlng) {
            this.$emit('input', {
                lat: latlng.lat,
                lng: latlng.lng
            });
        },

        toggleFullscreen() {
            const mapElem = this.$refs.mapElement;
            if (!document.fullscreenElement) {
                mapElem.requestFullscreen();
            } else {
                document.exitFullscreen();
            }
        },

        handleResize() {
            if (this.map) {
                setTimeout(() => { this.map.invalidateSize(); }, 200);
            }
        }
    }
});
