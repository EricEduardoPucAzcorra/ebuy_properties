export default {
    async init(adminApp) {
        console.log('Inicializando Dashboard');

        // Cargar gráficos solo si existen
        const chartElements = document.querySelectorAll('[data-chart]');
        if (chartElements.length > 0) {
            await this.loadCharts();
        }

        // Widgets
        this.initWidgets();

        return this;
    },

    async loadCharts() {
        // Cargar Chart.js solo cuando sea necesario
        const { default: Chart } = await import('chart.js/auto');

        document.querySelectorAll('[data-chart]').forEach(canvas => {
            const type = canvas.dataset.chart || 'line';
            new Chart(canvas, {
                type: type,
                data: JSON.parse(canvas.dataset.chartData || '{}'),
                options: JSON.parse(canvas.dataset.chartOptions || '{}')
            });
        });
    },

    initWidgets() {
        // Widgets específicos del dashboard
        document.querySelectorAll('[data-widget="stats"]').forEach(widget => {
            // Actualizar estadísticas cada 30 segundos
            setInterval(() => {
                this.updateWidgetStats(widget);
            }, 30000);
        });
    },

    search(query) {
        // Búsqueda específica del dashboard
        return [
            { title: 'Ventas del día', url: '#sales', type: 'dashboard' },
            { title: 'Usuarios activos', url: '#users', type: 'dashboard' }
        ].filter(item => item.title.toLowerCase().includes(query.toLowerCase()));
    },

    destroy() {
        // Limpiar intervalos, event listeners, etc.
        console.log('Dashboard module destroyed');
    }
};
