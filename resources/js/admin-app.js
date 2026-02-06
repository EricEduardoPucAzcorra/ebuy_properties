//template
import { ThemeManager } from './scripts/utils/theme-manager.js';
import { NotificationManager } from './scripts/utils/notifications.js';
import { iconManager } from './scripts/utils/icon-manager.js';
import { DataTableComponent } from './core/datatable.js';
import { Toast } from './core/toast.js';
//bootstrap, jquery, Swal
import * as bootstrap from 'bootstrap';
import $ from 'jquery';
import Swal from 'sweetalert2';
//leaflet
import L from 'leaflet';
//datatables
import 'datatables.net';
import 'datatables.net-bs5';
import 'leaflet/dist/leaflet.css'
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
//use globally
window.bootstrap = bootstrap;
window.$ = window.jQuery = $;
window.Swal = Swal;
window.DataTableComponent = DataTableComponent;
window.Toast = Toast;
window.L = L
class AdminApp {
    constructor() {
        this.isInitialized = false;
        this.currentPage = null;
        this.pageModule = null;
    }

    async init() {
        if (this.isInitialized) return;

        try {
            // 1. REGISTRAR COMPONENTES ALPINE
            this.registerAlpineComponentsNow();
            // 2. INICIALIZAR MANAGERS
            this.themeManager = new ThemeManager();
            this.notificationManager = new NotificationManager();
            this.iconManager = iconManager;
            // 3. CARGAR ÍCONOS
            this.iconManager.preloadIcons(['dashboard', 'users', 'settings', 'search', 'menu']);
            // 4. DETECTAR PÁGINA ACTUAL
            this.currentPage = this.detectCurrentPage();
            // 5. CARGAR MÓDULO DE PÁGINA
            await this.loadPageModule(this.currentPage);
            // 6. INICIALIZAR COMPONENTES BOOTSTRAP
            this.initBootstrapComponents();
            // 7. INICIALIZAR NAVEGACIÓN
            this.initNavigation();
            // 8. CONFIGURAR EVENTOS
            this.setupEventListeners();
            this.isInitialized = true;
            console.log(`AdminApp listo para: ${this.currentPage}`);
        } catch (error) {
            console.error('Error en AdminApp:', error);
        }
    }

    // Inicializar componentes Bootstrap
    initBootstrapComponents() {
        if (!window.bootstrap) {
            console.warn('Bootstrap no disponible');
            return;
        }
        const { Dropdown, Modal, Collapse, Toast, Tooltip } = window.bootstrap;
        // Inicializar solo si existen elementos
        this.initComponentIfExists('[data-bs-toggle="dropdown"]', Dropdown);
        this.initComponentIfExists('[data-bs-toggle="collapse"]', Collapse);
        this.initComponentIfExists('.modal', Modal);
        this.initComponentIfExists('.toast', Toast);
        this.initComponentIfExists('[data-bs-toggle="tooltip"]', Tooltip);
        console.log('Componentes Bootstrap inicializados');
    }

    //Helper para inicializar componentes
    initComponentIfExists(selector, ComponentClass) {
        const elements = document.querySelectorAll(selector);
        if (elements.length === 0) return;

        elements.forEach(element => {
            try {
                new ComponentClass(element);
            } catch (e) {
                console.warn(`Error inicializando ${ComponentClass.name}:`, e);
            }
        });
    }

    //Detectar página actual
    detectCurrentPage() {
        const path = window.location.pathname;
        const pageMap = {
            '/home': 'home',
            '/users': 'users',
            '/roles': 'roles',
            '/profile': 'profile',
            '/config-session': 'settings',
            '/plans_features': 'plans_features',
            '/plans': 'plans',
            '/mypropiertes': 'mypropiertes',
            '/security': 'security',
            '/files': 'files'
        };

        for (const [route, page] of Object.entries(pageMap)) {
            if (path.includes(route)) return page;
        }

        return 'dashboard';
    }

    //Cargar módulo de página
    async loadPageModule(pageName) {
        try {
            const module = await import(`./modules/${pageName}.js`);
            if (module.default) {
                this.pageModule = module.default;
                if (typeof this.pageModule.init === 'function') {
                    await this.pageModule.init(this);
                }
                console.log(`Módulo ${pageName} cargado`);
                return;
            }
        } catch (error) {
            console.error(`Error REAL cargando ${pageName}:`, error);
            console.log(`Stack:`, error.stack);
            this.pageModule = null;
        }
    }

    //Inicializar navegación
    initNavigation() {
        const currentPath = window.location.pathname;

        // Marcar enlace activo
        document.querySelectorAll('.nav-link').forEach(link => {
            const href = link.getAttribute('href');
            if (href && currentPath.includes(href)) {
                link.classList.add('active');
            }
        });

        // Restaurar estado de submenús
        document.querySelectorAll('[data-bs-toggle="collapse"]').forEach(toggle => {
            const targetId = toggle.getAttribute('data-bs-target');
            const savedState = localStorage.getItem(`submenu-${targetId}`);

            if (savedState === 'true') {
                const target = document.querySelector(targetId);
                if (target) {
                    target.classList.add('show');
                    toggle.setAttribute('aria-expanded', 'true');
                }
            }
        });

        // Guardar estado al hacer toggle
        document.addEventListener('click', (e) => {
            const toggle = e.target.closest('[data-bs-toggle="collapse"]');
            if (toggle) {
                const targetId = toggle.getAttribute('data-bs-target');
                const isExpanded = toggle.getAttribute('aria-expanded') === 'true';
                localStorage.setItem(`submenu-${targetId}`, (!isExpanded).toString());
            }
        });
    }

    //Configurar eventos globales
    setupEventListeners() {
        // Toggle de tema
        document.addEventListener('click', (e) => {
            if (e.target.matches('[data-theme-toggle]')) {
                this.themeManager.toggleTheme();
            }
        });

        document.addEventListener('click', (e) => {
            const fullscreenButton = e.target.closest('[data-fullscreen-toggle]');
            if (fullscreenButton) {
                e.preventDefault();
                this.toggleFullscreen();
            }
        });

        // Atajo de teclado para búsqueda (Ctrl+K o Cmd+K)
        document.addEventListener('keydown', (e) => {
            if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
                e.preventDefault();
                document.querySelector('[data-search-input]')?.focus();
            }
        });
    }

    //Alterna el modo de pantalla completa
    async toggleFullscreen() {
        try {
            if (!document.fullscreenElement) {
                await document.documentElement.requestFullscreen();
            } else {
                await document.exitFullscreen();
            }
        } catch (error) {
            console.error('Fullscreen toggle failed:', error);
        }
    }


    //Registrar componentes Alpine
    registerAlpineComponentsNow() {
        // Verificar si Alpine está disponible
        if (typeof Alpine === 'undefined') {
            setTimeout(() => this.registerAlpineComponentsNow(), 50);
            return;
        }

        // 1. Componente de búsqueda
        Alpine.data('searchComponent', () => ({
            query: '',
            results: [],
            isLoading: false,
            showResults: false,
            lastSearch: '',

            init() {
                console.log('SearchComponent inicializado');

                // Evento para cerrar resultados al hacer clic fuera
                document.addEventListener('click', (event) => {
                    if (!this.$el.contains(event.target)) {
                        this.showResults = false;
                    }
                });
            },

            async search() {
                if (this.query === this.lastSearch) return;

                if (this.query.length < 2) {
                    this.results = [];
                    this.showResults = false;
                    this.lastSearch = this.query;
                    return;
                }

                this.isLoading = true;
                this.lastSearch = this.query;

                try {
                    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

                    const response = await fetch(`/search?q=${encodeURIComponent(this.query)}`, {
                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': csrfToken || ''
                        }
                    });

                    if (response.ok) {
                        const data = await response.json();
                        this.results = data.results || [];
                        this.showResults = this.results.length > 0;
                    } else {
                        this.results = this.getFallbackResults();
                        this.showResults = this.results.length > 0;
                    }
                } catch (error) {
                    console.error('Búsqueda fallida:', error);
                    this.results = this.getFallbackResults();
                    this.showResults = this.results.length > 0;
                }

                this.isLoading = false;
            },

            getFallbackResults() {
                // Resultados de ejemplo si el API falla
                const fallback = [
                    {
                        id: 1,
                        title: '{{ __("menu.dashboard") }}',
                        url: '/dashboard',
                        type: 'page',
                        category: '{{ __("general.main_menu") }}',
                        icon: 'bi-speedometer2'
                    },
                    {
                        id: 2,
                        title: '{{ __("menu.users") }}',
                        url: '/users',
                        type: 'page',
                        category: '{{ __("general.main_menu") }}',
                        icon: 'bi-people'
                    },
                    {
                        id: 3,
                        title: '{{ __("menu.settings") }}',
                        url: '/settings',
                        type: 'page',
                        category: '{{ __("general.main_menu") }}',
                        icon: 'bi-gear'
                    }
                ];

                return fallback.filter(item =>
                    item.title.toLowerCase().includes(this.query.toLowerCase()) ||
                    item.category.toLowerCase().includes(this.query.toLowerCase())
                );
            },

            clearSearch() {
                this.query = '';
                this.results = [];
                this.showResults = false;
                this.lastSearch = '';
            },

            selectResult(result) {
                if (result.url && result.url !== '#') {
                    window.location.href = result.url;
                }
                this.clearSearch();
            }
        }));

        // 2. Componente de tema
        Alpine.data('themeSwitch', () => ({
            currentTheme: 'light',

            init() {
                const savedTheme = localStorage.getItem('theme');
                const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;

                this.currentTheme = savedTheme || (prefersDark ? 'dark' : 'light');
                document.documentElement.setAttribute('data-bs-theme', this.currentTheme);
                // console.log(`Tema inicial: ${this.currentTheme}`);
            },

            toggle() {
                this.currentTheme = this.currentTheme === 'light' ? 'dark' : 'light';
                document.documentElement.setAttribute('data-bs-theme', this.currentTheme);
                localStorage.setItem('theme', this.currentTheme);
                // console.log(`Tema cambiado a: ${this.currentTheme}`);
            }
        }));

        // 3. Componente de estadísticas
        Alpine.data('statsCounter', (initialValue = 0) => ({
            value: initialValue,

            init() {
                setInterval(() => {
                    this.value += Math.floor(Math.random() * 10) + 1;
                }, 5000);
            }
        }));

        // 4. Componente de formulario rápido (de tu código original)
        Alpine.data('quickAddForm', () => ({
            itemType: 'task',
            title: '',
            description: '',
            priority: 'medium',
            dateTime: '',
            assignee: '',

            init() {
                const now = new Date();
                now.setMinutes(now.getMinutes() - now.getTimezoneOffset());
                this.dateTime = now.toISOString().slice(0, 16);
            },

            resetForm() {
                this.itemType = 'task';
                this.title = '';
                this.description = '';
                this.priority = 'medium';
                this.assignee = '';
                const now = new Date();
                now.setMinutes(now.getMinutes() - now.getTimezoneOffset());
                this.dateTime = now.toISOString().slice(0, 16);
            },

            saveItem() {
                if (!this.title.trim()) {
                    window.AdminApp?.notificationManager?.warning('Por favor ingresa un título');
                    return;
                }

                const item = {
                    type: this.itemType,
                    title: this.title,
                    description: this.description,
                    priority: this.itemType === 'task' ? this.priority : null,
                    dateTime: ['event', 'reminder'].includes(this.itemType) ? this.dateTime : null,
                    assignee: this.itemType === 'task' ? this.assignee : null,
                    createdAt: new Date().toISOString()
                };

                const typeLabels = {
                    task: 'Tarea',
                    note: 'Nota',
                    event: 'Evento',
                    reminder: 'Recordatorio'
                };

                window.AdminApp?.notificationManager?.success(
                    `${typeLabels[this.itemType]} "${this.title}" creado exitosamente!`
                );

                this.resetForm();
            }
        }));

        // console.log('Componentes Alpine registrados');
    }

    //Destruir app
    destroy() {
        if (this.pageModule && typeof this.pageModule.destroy === 'function') {
            this.pageModule.destroy();
        }

        this.isInitialized = false;
        this.pageModule = null;
    }
}

// CREAR INSTANCIA GLOBAL INMEDIATAMENTE
const adminApp = new AdminApp();
window.AdminApp = adminApp;

// INICIAR CUANDO EL DOM ESTÉ LISTO
document.addEventListener('DOMContentLoaded', () => {
    adminApp.init();
});

export default adminApp;
