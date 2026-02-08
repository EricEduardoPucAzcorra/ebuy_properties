Vue.component('map-selector', {
    props: {
        value: {
            type: Object,
            default: () => ({
                lat: 19.4326,
                lng: -99.1332
            })
        }
    },

    template: `
        <div id="map-selector" style="height:400px;width:100%;"></div>
    `,

    data() {
        return {
            map: null,
            marker: null
        }
    },

    mounted() {
        this.initMap()
    },

    watch: {
        value: {
            deep: true,
            handler(newVal) {
                if (!this.map || !this.marker) return

                this.map.setView([newVal.lat, newVal.lng], 15)
                this.marker.setLatLng([newVal.lat, newVal.lng])
            }
        }
    },

    methods: {
        initMap() {
            this.map = L.map('map-selector')
                .setView([this.value.lat, this.value.lng], 13)

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap contributors'
            }).addTo(this.map)

            this.marker = L.marker([this.value.lat, this.value.lng], {
                draggable: true
            }).addTo(this.map)

            // Click en el mapa
            this.map.on('click', e => {
                this.marker.setLatLng(e.latlng)
                this.emitLocation(e.latlng)
            })

            // Arrastrar marcador
            this.marker.on('dragend', e => {
                this.emitLocation(e.target.getLatLng())
            })
        },

        emitLocation(latlng) {
            this.$emit('input', {
                lat: latlng.lat,
                lng: latlng.lng
            })
        }
    }
})
