document.addEventListener('DOMContentLoaded', function () {
    const mapaVue = new Vue({
        el: '#mapa-app',
        data: {
            locations: [],
            loading: true,
            error: null,
            mapConfig: {
                center: [19.4326, -99.1332],
                zoom: 10
            },
            appIcon: '/images/logo-marker.png'
        },

        methods: {
            async loadProperties() {
                try {
                    this.loading = true;
                    this.error = null;

                    // Construir URL con parámetros actuales
                    const url = new URL(window.location);
                    const params = url.searchParams;

                    console.log('=== INICIO LOADPROPERTIES ===');
                    console.log('URL params:', params.toString());

                    // Si hay coordenadas directas del autocomplete, usarlas SIEMPRE
                    if (params.get('lat') && params.get('lng')) {
                        const lat = parseFloat(params.get('lat'));
                        const lng = parseFloat(params.get('lng'));

                        console.log('USANDO COORDENADAS DIRECTAS DEL AUTOCOMPLETE:', lat, lng);

                        this.mapConfig.center = [lat, lng];
                        this.mapConfig.zoom = 12;

                        // Construir URL para la API sin lat/lng
                        let apiUrl = '/properties/map';
                        const apiParams = new URLSearchParams(params.toString());
                        apiParams.delete('lat');
                        apiParams.delete('lng');

                        if (apiParams.toString()) {
                            apiUrl += '?' + apiParams.toString();
                        }

                        console.log('URL PARA CARGAR PROPIEDADES:', apiUrl);

                        const response = await fetch(apiUrl);
                        const data = await response.json();

                        console.log('RESPUESTA DEL BACKEND:', data);
                        console.log('NÚMERO DE PROPIEDADES RECIBIDAS:', data.length);

                        this.locations = data;
                        console.log('this.locations AHORA:', this.locations.length);

                        // Forzar actualización del mapa
                        this.$nextTick(() => {
                            console.log('Forzando actualización del mapa...');
                            if (this.$refs.mapComponent && this.$refs.mapComponent.map) {
                                console.log('Actualizando vista del mapa...');
                            }
                        });

                        // NO HACER RETURN AQUÍ - Continuar con el proceso normal
                    }

                    // Si hay búsqueda por texto, buscar coordenadas del lugar
                    if (params.get('q')) {
                        const searchTerm = params.get('q');
                        console.log('Buscando coordenadas para:', searchTerm);

                        // Buscar coordenadas del lugar por nombre
                        try {
                            const response = await fetch(`/location-search?q=${encodeURIComponent(searchTerm)}`);
                            const locations = await response.json();

                            if (locations.length > 0) {
                                const location = locations[0];
                                console.log('Ubicación encontrada:', location);

                                if (location.lat && location.lng) {
                                    this.mapConfig.center = [location.lat, location.lng];
                                    this.mapConfig.zoom = 12;
                                }
                            }
                        } catch (error) {
                            console.error('Error buscando coordenadas:', error);
                        }
                    }

                    // Construir URL para la API con los mismos parámetros
                    let apiUrl = '/properties/map';
                    if (params.toString()) {
                        apiUrl += '?' + params.toString();
                    }

                    console.log('Cargando propiedades con filtros:', apiUrl);

                    const response = await fetch(apiUrl);

                    if (!response.ok) {
                        throw new Error('Error al cargar las propiedades');
                    }

                    const data = await response.json();

                    console.log('Respuesta del backend:', data);

                    // Si es un error del backend
                    if (data.error) {
                        throw new Error(data.error);
                    }

                    this.locations = data;
                    console.log('Propiedades cargadas:', this.locations.length);
                    console.log('Detalles de propiedades:', this.locations);

                    if (this.locations.length > 0) {
                        this.adjustMapCenter();
                        console.log('Ajustando centro del mapa con', this.locations.length, 'propiedades');
                    } else {
                        console.log('No hay propiedades, mapa ya está centrado en la ubicación buscada');
                    }

                } catch (error) {
                    console.error('Error:', error);
                    this.error = error.message;
                } finally {
                    this.loading = false;
                }
            },

            adjustMapCenter() {
                if (this.locations.length === 0) return;

                const avgLat = this.locations.reduce((sum, loc) => sum + parseFloat(loc.lat), 0) / this.locations.length;
                const avgLng = this.locations.reduce((sum, loc) => sum + parseFloat(loc.lng), 0) / this.locations.length;

                this.mapConfig.center = [avgLat, avgLng];

                if (this.locations.length === 1) {
                    this.mapConfig.zoom = 15;
                } else if (this.locations.length <= 5) {
                    this.mapConfig.zoom = 12;
                } else {
                    this.mapConfig.zoom = 10;
                }
            },

            // Método para recargar el mapa cuando cambian los filtros
            reloadMap() {
                this.loadProperties();
            },

            // Método para centrar el mapa en la ubicación buscada cuando no hay propiedades
            async centerMapOnSearchLocation() {
                try {
                    const url = new URL(window.location);
                    const params = url.searchParams;

                    console.log('Centrando mapa en ubicación buscada:', params.toString());

                    // Si hay una ubicación específica, obtener sus coordenadas
                    if (params.get('location_type') && params.get('location_id')) {
                        const locationType = params.get('location_type');
                        const locationId = params.get('location_id');

                        console.log(`Obteniendo coordenadas para ${locationType} ID: ${locationId}`);

                        // Obtener coordenadas de la ubicación
                        const response = await fetch(`/location-coordinates?type=${locationType}&id=${locationId}`);
                        const data = await response.json();

                        console.log('Coordenadas recibidas:', data);

                        if (data.lat && data.lng) {
                            console.log('Actualizando configuración del mapa...');
                            this.mapConfig.center = [data.lat, data.lng];
                            this.mapConfig.zoom = 12;

                            // Forzar la actualización del mapa Vue
                            this.$nextTick(() => {
                                console.log('Mapa centrado en:', this.mapConfig.center);
                                // Si el mapa ya está inicializado, actualizarlo
                                if (this.$refs.mapComponent && this.$refs.mapComponent.map) {
                                    this.$refs.mapComponent.map.setView(this.mapConfig.center, this.mapConfig.zoom);
                                }
                            });
                        } else {
                            console.log('No se encontraron coordenadas válidas');
                        }
                    } else if (params.get('q')) {
                        // Si es búsqueda por texto, mantener zoom general
                        console.log('Búsqueda por texto:', params.get('q'));
                        this.mapConfig.zoom = 10;
                    }
                } catch (error) {
                    console.error('Error al centrar mapa en ubicación:', error);
                    // Mantener configuración por defecto si hay error
                }
            }
        },

        mounted() {
            this.loadProperties();

            // Guardar instancia globalmente para acceso externo
            window.mapaVueInstance = this;

            // Escuchar cambios en los filtros
            window.addEventListener('popstate', () => {
                this.reloadMap();
            });
        }
    });
});