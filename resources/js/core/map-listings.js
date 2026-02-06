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
        <div id="map-listings" style="height:500px;width:100%;"></div>
    `,

    mounted() {
        this.initMap()
    },

    methods: {
        initMap() {
            const map = L.map('map-listings')
                .setView(this.config.center, this.config.zoom)

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap contributors'
            }).addTo(map)

            this.locations.forEach(item => {
                const icon = item.icon
                    ? L.icon({
                        iconUrl: item.icon,
                        iconSize: [36, 36]
                    })
                    : null

                const marker = L.marker(
                    [item.lat, item.lng],
                    icon ? { icon } : {}
                ).addTo(map)

                if (item.popup) {
                    marker.bindPopup(item.popup)
                }
            })
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
