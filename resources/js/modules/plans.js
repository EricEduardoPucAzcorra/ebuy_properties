new Vue({
    el: '#plansModule',

    data: {
        loading: false,
        plans: [],
        isEditing: false,

        // FEATURES DISPONIBLES
        availableFeatures: [],

        // FORM PLAN
        form: {
            id: null,
            name: '',
            price: '',
            description: '',
            is_featured: false,
            is_active: true,
            features: []
        }
    },

    mounted() {
        this.loadPlans();
        this.loadAvailableFeatures();
    },

    methods: {

        /* =========================
         * LOADERS
         * ========================= */

        async loadPlans() {
            try {
                const res = await axios.get('/plans-index');
                this.plans = res.data;
            } catch (e) {
                console.error(e);
            }
        },

        async loadAvailableFeatures() {
            try {
                const res = await axios.get('/feature-plans');

                this.availableFeatures = res.data.map(f => ({
                    id: f.id,
                    name: f.name,
                    selected: false,
                    mount: null,
                    description: null,
                    other_description: null
                }));
            } catch (e) {
                console.error(e);
            }
        },

        /* =========================
         * MODALS
         * ========================= */

        openCreate() {
            this.resetForm();
            this.isEditing = false;

            this.availableFeatures.forEach(f => {
                f.selected = false;
                f.mount = null;
                f.description = null;
                f.other_description = null;
            });

            new bootstrap.Modal(
                document.getElementById('planModal')
            ).show();
        },

        openEdit(plan) {
            this.resetForm();
            this.isEditing = true;

            this.form = {
                id: plan.id,
                name: plan.name,
                price: plan.price,
                description: plan.description,
                is_featured: plan.is_featured,
                is_active: plan.is_active,
                features: []
            };

            this.availableFeatures.forEach(f => {
                const found = plan.features.find(pf => pf.id === f.id);

                if (found) {
                    f.selected = true;
                    f.mount = found.pivot.mount;
                    f.description = found.pivot.description;
                    f.other_description = found.pivot.other_description;
                } else {
                    f.selected = false;
                    f.mount = null;
                    f.description = null;
                    f.other_description = null;
                }
            });

            new bootstrap.Modal(
                document.getElementById('planModal')
            ).show();
        },

        resetForm() {
            this.form = {
                id: null,
                name: '',
                price: '',
                description: '',
                is_featured: false,
                is_active: true,
                features: []
            };
        },

        /* =========================
         * SAVE
         * ========================= */

        async savePlan() {
            this.loading = true;

            // ARMAR FEATURES PARA BACKEND
            this.form.features = this.availableFeatures
                .filter(f => f.selected)
                .map(f => ({
                    feature_plan_id: f.id,
                    mount: f.mount,
                    description: f.description,
                    other_description: f.other_description
                }));

            try {
                if (this.isEditing) {
                    await axios.put(
                        `/plans-index/${this.form.id}`,
                        this.form
                    );
                } else {
                    await axios.post('/plans-index', this.form);
                }

                await this.loadPlans();

                bootstrap.Modal
                    .getInstance(document.getElementById('planModal'))
                    .hide();

                Swal.fire('Listo', 'Plan guardado correctamente', 'success');

            } catch (e) {
                console.error(e);
                Swal.fire('Error', 'Revisa los datos', 'error');
            } finally {
                this.loading = false;
            }
        },

        /* =========================
         * DELETE
         * ========================= */

        async deletePlan(id) {
            const confirm = await Swal.fire({
                icon: 'warning',
                title: 'Eliminar plan',
                text: '¿Seguro?',
                showCancelButton: true,
                confirmButtonText: 'Sí'
            });

            if (!confirm.isConfirmed) return;

            await axios.delete(`/plans-index/${id}`);
            this.loadPlans();
        }
    }
});
