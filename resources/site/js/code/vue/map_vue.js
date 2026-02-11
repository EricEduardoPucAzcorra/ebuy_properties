import '../../components/map-selector';

new Vue({
    el: '#mapvue',
    data() {
        const el = document.getElementById('mapvue');
        const rawLocation = JSON.parse(el.getAttribute('data-location'));

        return {
            location: {
                lat: rawLocation && rawLocation.latitude ? parseFloat(rawLocation.latitude) : 19.4326,
                lng: rawLocation && rawLocation.longitude ? parseFloat(rawLocation.longitude) : -99.1332
            }
        }
    }
});
