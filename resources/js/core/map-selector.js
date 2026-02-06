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

    mounted() {
        this.initMap()
    },

    methods: {
        initMap() {
            const map = L.map('map-selector')
                .setView([this.value.lat, this.value.lng], 13)

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap contributors'
            }).addTo(map)

            const marker = L.marker([this.value.lat, this.value.lng], {
                draggable: true
            }).addTo(map)

            map.on('click', e => {
                marker.setLatLng(e.latlng)
                this.emitLocation(e.latlng)
            })

            marker.on('dragend', e => {
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
// default
// location: {
//     lat: 19.4326,
//     lng: -99.1332
// }
