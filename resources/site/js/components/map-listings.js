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
        },
        appIcon: { type: String, default: '' }
    },

    template: `
        <div class="map-container-wrapper" style="position: relative;">
            <div id="map-listings" ref="mapElement" style="height:500px; width:100%;"></div>
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

            this.map = window.L.map(this.$refs.mapElement, mapOptions)
                .setView(this.config.center, this.config.zoom);

            window.L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(this.map);

            this.locations.forEach(item => {
                const customIcon = window.L.divIcon({
                    className: 'custom-div-icon',
                    html: `
                        <div class="marker-wrapper">
                            <div class="marker-pin">
                                <div class="marker-logo" style="background-image: url('${item.appIcon || this.appIcon}');"></div>
                            </div>
                            <div class="marker-shadow-floor"></div>
                        </div>
                    `,
                    iconSize: [60, 60],
                    iconAnchor: [30, 60],
                    popupAnchor: [0, -65]
                });

                const marker = window.L.marker([item.lat, item.lng], {
                    icon: customIcon
                }).addTo(this.map);

                const popupHtml = `
                    <div class="modern-popup">
                        <div class="popup-image-wrapper">
                            <img src="${item.image || ''}" class="popup-img">
                            <div class="popup-price-tag">${item.currency || '$'} ${item.price || '0'}</div>
                        </div>
                        <div class="popup-info">
                            <h6 class="popup-title">${item.title || 'Sin título'}</h6>
                        </div>
                    </div>
                `;

                marker.bindPopup(popupHtml, {
                    maxWidth: 220,
                    className: 'custom-leaflet-popup'
                });
            });
        },

        toggleFullscreen() {
            const mapElem = this.$refs.mapElement;
            if (!document.fullscreenElement) {
                mapElem.requestFullscreen().catch(err => {
                    alert(`Error: ${err.message}`);
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
