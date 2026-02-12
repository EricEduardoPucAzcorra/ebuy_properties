import '../../components/map-selector';

new Vue({
    el: '#mapvue',
    data() {
        const el = document.getElementById('mapvue');

        let rawLocation = null;
        if (el && el.getAttribute('data-location')) {
            try {
                rawLocation = JSON.parse(el.getAttribute('data-location'));
            } catch (e) {
                rawLocation = null;
            }
        }

        return {
            location: {
                lat: rawLocation?.latitude ? parseFloat(rawLocation.latitude) : 19.4326,
                lng: rawLocation?.longitude ? parseFloat(rawLocation.longitude) : -99.1332
            }
        }
    }
});
