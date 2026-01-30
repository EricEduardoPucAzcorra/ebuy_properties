import Alpine from 'alpinejs';
import './admin-app';
import './bootstrap';
import './scripts/utils/vue.min.js';

window.Alpine = Alpine;

document.addEventListener('DOMContentLoaded', () => {
    setTimeout(() => {
        Alpine.start();
    }, 100);
});
