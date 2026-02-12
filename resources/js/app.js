import Alpine from 'alpinejs';
import './scripts/utils/vue-global';
import './admin-app';
import './bootstrap';

window.Alpine = Alpine;

document.addEventListener('DOMContentLoaded', () => {
    setTimeout(() => {
        Alpine.start();
    }, 100);
});
