import L from 'leaflet';
//Config Leaflet
import icon from 'leaflet/dist/images/marker-icon.png'
import icon2x from 'leaflet/dist/images/marker-icon-2x.png'
import shadow from 'leaflet/dist/images/marker-shadow.png'
delete L.Icon.Default.prototype._getIconUrl

L.Icon.Default.mergeOptions({
    iconRetinaUrl: icon2x,
    iconUrl: icon,
    shadowUrl: shadow,
})

window.L = L

import './js/main.js';
import './js/code/property.js';
import './js/code/mapa.js';
import './js/code/favorites.js';


