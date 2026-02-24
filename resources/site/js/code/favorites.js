class FavoriteManager {
    constructor() {
        console.log('FAVORITES: Constructor called');
        this.favorites = new Map(); // Usamos localStorage
        this.init();
        console.log('FAVORITES: Constructor finished');
    }

    init() {
        console.log('FAVORITES: Initializing...');

        // Cargar favoritos desde localStorage
        this.loadFavoritesFromStorage();

        // Configurar event listeners - ESTO FALTABA
        this.setupEventListeners();

        // Inicializar botones
        this.initializeButtons();

        console.log('FAVORITES: Initialization complete');
    }

    loadFavoritesFromStorage() {
        try {
            const stored = localStorage.getItem('property_favorites');
            if (stored) {
                const favoritesArray = JSON.parse(stored);
                favoritesArray.forEach(propertyId => {
                    this.favorites.set(propertyId, true);
                });
            }
        } catch (error) {
            console.error('Error loading favorites from localStorage:', error);
        }
    }

    saveFavoritesToStorage() {
        try {
            const favoritesArray = Array.from(this.favorites.keys());
            localStorage.setItem('property_favorites', JSON.stringify(favoritesArray));
        } catch (error) {
            console.error('Error saving favorites to localStorage:', error);
        }
    }

    setupEventListeners() {
        console.log('FAVORITES: Setting up event listeners...');

        // Event listener para botones de favoritos
        document.addEventListener('click', (e) => {
            const favoriteBtn = e.target.closest('.favorite-btn');
            if (favoriteBtn) {
                console.log('FAVORITES: Click detected on favorite button');
                e.preventDefault();
                this.toggleFavorite(favoriteBtn);
            }
        });

        // Event listener para hover
        document.addEventListener('mouseover', (e) => {
            const favoriteBtn = e.target.closest('.favorite-btn');
            if (favoriteBtn) {
                this.updateTooltip(favoriteBtn);
            }
        });

        console.log('FAVORITES: Event listeners setup complete');
    }

    async toggleFavorite(button) {
        const propertyId = button.dataset.propertyId;
        if (!propertyId) {
            console.error('No property ID found on button:', button);
            return;
        }

        console.log('Toggle favorite clicked:', propertyId);

        // Mostrar estado de carga
        this.setLoadingState(button, true);

        try {
            // Enviar petición al servidor para alternar favorito
            const response = await fetch('/favorites/toggle', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': this.getCSRFToken()
                },
                body: JSON.stringify({
                    property_id: propertyId,
                    _token: this.getCSRFToken()
                })
            });

            console.log('Response received:', response);

            const data = await response.json();
            console.log('Data parsed:', data);

            if (data.success) {
                // Actualizar estado local en localStorage
                if (data.is_favorited) {
                    this.favorites.set(propertyId, true);
                } else {
                    this.favorites.delete(propertyId);
                }

                // Guardar en localStorage
                this.saveFavoritesToStorage();

                // Actualizar UI con el estado correcto
                this.updateButtonState(button, data.is_favorited);

                // Mostrar notificación
                this.showNotification(data.message, 'success');

                // Disparar evento personalizado
                this.dispatchFavoriteEvent(propertyId, data.is_favorited);
            } else {
                this.showNotification(data.message || 'Error al actualizar favoritos', 'error');
            }
        } catch (error) {
            console.error('Error al toggle favorite:', error);
            this.showNotification('Error de conexión. Intenta nuevamente.', 'error');
        } finally {
            this.setLoadingState(button, false);
        }
    }

    getCSRFToken() {
        // Múltiples formas de obtener el token CSRF
        let token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ||
            document.querySelector('input[name="_token"]')?.value ||
            window.Laravel?.csrfToken ||
            '';

        console.log('CSRF Token found:', token ? 'YES' : 'NO');

        // Si no hay token, intentar obtener del formulario
        if (!token) {
            const form = document.querySelector('form[method="POST"]');
            if (form) {
                const input = form.querySelector('input[name="_token"]');
                if (input) {
                    token = input.value;
                }
            }
        }

        // Si todavía no hay token, buscar en cualquier parte
        if (!token) {
            const inputs = document.querySelectorAll('input[name="_token"]');
            if (inputs.length > 0) {
                token = inputs[0].value;
            }
        }

        // Último intento: obtener de las cookies de Laravel
        if (!token) {
            const cookies = document.cookie.split(';');
            for (let cookie of cookies) {
                const [name, value] = cookie.trim().split('=');
                if (name === 'XSRF-TOKEN') {
                    token = decodeURIComponent(value);
                    break;
                }
            }
        }

        if (!token) {
            console.warn('CSRF token not found');
        } else {
            console.log('Final CSRF Token:', token.substring(0, 20) + '...');
        }

        return token;
    }

    async checkFavoriteStatus(propertyId) {
        try {
            const response = await fetch(`/favorites/check/${propertyId}`);
            const data = await response.json();

            this.favorites.set(propertyId, data.is_favorited);
            return data;
        } catch (error) {
            console.error('Error checking favorite status:', error);
            return { is_favorited: false, favorites_count: 0 };
        }
    }

    async loadFavoriteStates() {
        // Cargar estado de todos los botones de favoritos en la página
        const favoriteButtons = document.querySelectorAll('.favorite-btn');

        const promises = Array.from(favoriteButtons).map(async (button) => {
            const propertyId = button.dataset.propertyId;
            if (propertyId && !this.favorites.has(propertyId)) {
                const data = await this.checkFavoriteStatus(propertyId);
                this.updateButtonState(button, data.is_favorited);
                this.updateCounter(propertyId, data.favorites_count);
            }
        });

        await Promise.all(promises);
    }

    initializeButtons() {
        // Actualizar todos los botones existentes basados en localStorage
        document.querySelectorAll('.favorite-btn').forEach(button => {
            const propertyId = button.dataset.propertyId;
            if (propertyId && this.favorites.has(propertyId)) {
                this.updateButtonState(button, true);
            }
        });
    }

    updateButtonState(button, isFavorited) {
        const icon = button.querySelector('i');

        if (isFavorited) {
            // Botón VERDE + Corazón ROJO cuando está activo
            button.classList.add('favorited');
            button.classList.remove('text-muted');
            icon.classList.remove('far');
            icon.classList.add('fas');
            button.title = 'Quitar de favoritos';
            button.setAttribute('aria-label', 'Quitar de favoritos');
        } else {
            // Botón VERDE + Corazón BLANCO cuando no está activo
            button.classList.remove('favorited');
            button.classList.add('text-muted');
            icon.classList.remove('fas');
            icon.classList.add('far');
            button.title = 'Agregar a favoritos';
            button.setAttribute('aria-label', 'Agregar a favoritos');
        }

        // Agregar animación de corazón latiendo
        icon.classList.add('heart-beat');
        setTimeout(() => {
            icon.classList.remove('heart-beat');
        }, 1000);
    }

    animateButton(button, action) {
        button.style.transform = 'scale(1.2)';
        button.style.transition = 'transform 0.2s ease';

        setTimeout(() => {
            button.style.transform = 'scale(1)';
        }, 200);

        if (action === 'added') {
            // Efecto de corazón latiendo
            button.classList.add('heart-beat');
            setTimeout(() => {
                button.classList.remove('heart-beat');
            }, 600);
        }
    }

    updateCounter(propertyId, count) {
        // Actualizar contadores si existen
        const counters = document.querySelectorAll(`[data-favorites-count="${propertyId}"]`);
        counters.forEach(counter => {
            counter.textContent = count;
        });
    }

    setLoadingState(button, isLoading) {
        const icon = button.querySelector('i');
        if (!icon) return;

        if (isLoading) {
            icon.classList.add('fa-spin');
            button.disabled = true;
            button.style.opacity = '0.6';
        } else {
            icon.classList.remove('fa-spin');
            button.disabled = false;
            button.style.opacity = '1';
        }
    }

    updateTooltip(button) {
        const propertyId = button.dataset.propertyId;
        const isFavorited = this.favorites.get(propertyId);

        if (isFavorited) {
            button.setAttribute('title', 'Quitar de favoritos');
        } else {
            button.setAttribute('title', 'Agregar a favoritos');
        }
    }

    showNotification(message, type = 'info') {
        // Usar el sistema de notificaciones existente si está disponible
        if (window.AdminApp?.notificationManager) {
            switch (type) {
                case 'success':
                    window.AdminApp.notificationManager.success(message);
                    break;
                case 'error':
                    window.AdminApp.notificationManager.error(message);
                    break;
                default:
                    window.AdminApp.notificationManager.info(message);
            }
        } else {
            // Fallback a alert simple
            console.log(`${type.toUpperCase()}: ${message}`);
        }
    }

    handleAuthRequired() {
        // Mostrar mensaje o redirigir a login
        if (confirm('Debes iniciar sesión para agregar favoritos. ¿Deseas ir a la página de login?')) {
            window.location.href = '/login';
        }
    }

    dispatchFavoriteEvent(propertyId, isFavorited) {
        const event = new CustomEvent('favoriteChanged', {
            detail: {
                propertyId: propertyId,
                isFavorited: isFavorited
            }
        });
        document.dispatchEvent(event);
    }

    // Método público para verificar si una propiedad es favorita
    isFavorited(propertyId) {
        return this.favorites.get(propertyId) || false;
    }

    // Método público para obtener todos los favoritos
    getAllFavorites() {
        return Array.from(this.favorites.entries())
            .filter(([id, isFav]) => isFav)
            .map(([id]) => id);
    }
}

// Inicializar el gestor de favoritos
document.addEventListener('DOMContentLoaded', () => {
    console.log('FAVORITES: DOM loaded, initializing...');
    window.favoriteManager = new FavoriteManager();
    console.log('FAVORITES: FavoriteManager initialized');
});

// Exportar para uso global
window.FavoriteManager = FavoriteManager;
