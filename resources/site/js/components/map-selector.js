Vue.component('map-selector', {
    props: {
        value: { type: Object, default: () => ({ lat: 19.4326, lng: -99.1332 }) },
        readonly: { type: Boolean, default: false },
        price: { type: String, default: '' },
        currency: { type: String, default: '' },
        image: { type: String, default: '' },
        appIcon: { type: String, default: '' },
        title: { type: String, default: '' }
    },

    template: `
        <div class="map-container-wrapper" style="position: relative;">
            <div id="map-selector" ref="mapElement"></div>
            <button class="fullscreen-btn" @click="toggleFullscreen" title="Ver en grande">
                <i class="fa fa-expand"></i>
            </button>
        </div>
    `,

    methods: {
        initMap() {
            const mapOptions = {
                dragging: true,
                scrollWheelZoom: true,
                zoomControl: true,
                attributionControl: false
            };

            this.map = window.L.map('map-selector', mapOptions).setView([this.value.lat, this.value.lng], 15);

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

            const popupHtml = `
                <div class="modern-popup">
                    <div class="popup-image-wrapper">
                        <img src="${this.image}" class="popup-img">
                        <div class="popup-price-tag">${this.currency} ${this.price}</div>
                    </div>
                    <div class="popup-info">
                        <h6 class="popup-title">${this.title}</h6>
                    </div>
                </div>
            `;

            this.marker.bindPopup(popupHtml, { maxWidth: 220, className: 'custom-leaflet-popup' });

            if(this.readonly) setTimeout(() => this.marker.openPopup(), 400);
        },

        toggleFullscreen() {
            const mapElem = this.$refs.mapElement;
            if (!document.fullscreenElement) {
                mapElem.requestFullscreen().catch(err => {
                    alert(`Error al intentar modo pantalla completa: ${err.message}`);
                });
            } else {
                document.exitFullscreen();
            }
        }
    },
    mounted() {
        this.initMap();
        document.addEventListener('fullscreenchange', () => {
            if (this.map) {
                setTimeout(() => { this.map.invalidateSize(); }, 200);
            }
        });
    }
});
