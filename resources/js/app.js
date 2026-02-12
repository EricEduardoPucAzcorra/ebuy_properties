import Alpine from 'alpinejs';
import './admin-app';
import './bootstrap';

window.Alpine = Alpine;

document.addEventListener('DOMContentLoaded', () => {
    setTimeout(() => {
        Alpine.start();
    }, 100);
});
