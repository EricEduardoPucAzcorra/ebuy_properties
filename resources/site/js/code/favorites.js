class FavoriteManager {
    constructor() {
        console.log('FAVORITES: Constructor called');
        this.favorites = new Map();
        this.init();
        console.log('FAVORITES: Constructor finished');
    }

    init() {
        this.loadFavoritesFromStorage();
        this.setupEventListeners();
        this.initializeButtons();
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
        document.addEventListener('click', (e) => {
            const favoriteBtn = e.target.closest('.favorite-btn');
            if (favoriteBtn) {
                console.log('FAVORITES: Click detected on favorite button');
                e.preventDefault();
                this.toggleFavorite(favoriteBtn);
            }
        });

        document.addEventListener('mouseover', (e) => {
            const favoriteBtn = e.target.closest('.favorite-btn');
            if (favoriteBtn) {
                this.updateTooltip(favoriteBtn);
            }
        });
    }

    async toggleFavorite(button) {
        const propertyId = button.dataset.propertyId;
        if (!propertyId) {
            console.error('No property ID found on button:', button);
            return;
        }

        this.setLoadingState(button, true);

        try {
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
            const data = await response.json();

            if (data.success) {
                if (data.is_favorited) {
                    this.favorites.set(propertyId, true);
                } else {
                    this.favorites.delete(propertyId);
                }

                this.saveFavoritesToStorage();

                this.updateButtonState(button, data.is_favorited);

                this.showNotification(data.message, 'success');

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
        let token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ||
            document.querySelector('input[name="_token"]')?.value ||
            window.Laravel?.csrfToken ||
            '';
        if (!token) {
            const form = document.querySelector('form[method="POST"]');
            if (form) {
                const input = form.querySelector('input[name="_token"]');
                if (input) {
                    token = input.value;
                }
            }
        }

        if (!token) {
            const inputs = document.querySelectorAll('input[name="_token"]');
            if (inputs.length > 0) {
                token = inputs[0].value;
            }
        }

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
            button.classList.add('favorited');
            button.classList.remove('text-muted');
            icon.classList.remove('far');
            icon.classList.add('fas');
            button.title = 'Quitar de favoritos';
            button.setAttribute('aria-label', 'Quitar de favoritos');
        } else {
            button.classList.remove('favorited');
            button.classList.add('text-muted');
            icon.classList.remove('fas');
            icon.classList.add('far');
            button.title = 'Agregar a favoritos';
            button.setAttribute('aria-label', 'Agregar a favoritos');
        }

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
            button.classList.add('heart-beat');
            setTimeout(() => {
                button.classList.remove('heart-beat');
            }, 600);
        }
    }

    updateCounter(propertyId, count) {
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

    // handleAuthRequired() {
    //     // Mostrar mensaje o redirigir a login
    //     if (confirm('Debes iniciar sesión para agregar favoritos. ¿Deseas ir a la página de login?')) {
    //         window.location.href = '/login';
    //     }
    // }

    dispatchFavoriteEvent(propertyId, isFavorited) {
        const event = new CustomEvent('favoriteChanged', {
            detail: {
                propertyId: propertyId,
                isFavorited: isFavorited
            }
        });
        document.dispatchEvent(event);
    }

    isFavorited(propertyId) {
        return this.favorites.get(propertyId) || false;
    }

    getAllFavorites() {
        return Array.from(this.favorites.entries())
            .filter(([id, isFav]) => isFav)
            .map(([id]) => id);
    }
}


document.addEventListener('DOMContentLoaded', () => {
    window.favoriteManager = new FavoriteManager();
});

window.FavoriteManager = FavoriteManager;
