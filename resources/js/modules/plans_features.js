new Vue({
    el: '#planFeatures',
    data: {
        table: null,
        loading: false,
        isEditing: false,
        imagePreview: null,
        permissionUsers:false,

        filters: {
            search: '',
            status: ''
        },

        form: {
            id: null,
            name: '',
            descripcion: '',
            is_active: null
        },

        translations: {
            edit: '',
            create: ''
        },

        rolesMock: [

        ],

        roleSearch: ''
    },

    mounted() {
        const el = window.PlanFeaturesModuleTranslations;
        this.translations.edit = el.edit;
        this.translations.create = el.create;
        this.initFeaturesTable();
    },

    computed: {
        filteredRoles() {
            let list = this.rolesMock;

            // filtro por búsqueda
            if (this.roleSearch) {
                list = list.filter(r =>
                    r.name.toLowerCase().includes(this.roleSearch.toLowerCase())
                );
            }

            return list;
        },

        selectedRoleNames() {
            return this.rolesMock
                .filter(r => this.form.roles.includes(r.id))
                .map(r => r.name);
        }
    },

    methods: {

        initFeaturesTable() {
            const self = this;

            self.table = new DataTableComponent({
                selector: '#table-features',
                url: '/plans_features_index',
                extraParams: () => ({
                    is_active: self.filters.status
                }),
                columns: [
                    { data: 'id' },
                    { data: 'name' },
                    { data: 'descripcion' },
                    {
                        data: 'is_active',
                        render: v => {
                            const active = Number(v) === 1;
                            return `
                                <span class="badge bg-${active ? 'success' : 'danger'}">
                                    ${active ? window.active : window.inactive}
                                </span>`;
                        }
                    },
                    {
                        data: null,
                        orderable: false,
                        render: function (row) {
                            const isActive = Number(row.is_active) === 1;

                            const canEdit = can('users.update');
                            const canEditState = can('users.state_update');

                            return `
                                <div class="d-flex gap-2 justify-content-center">
                                    ${canEdit ? `
                                        <button class="btn btn-sm btn-outline-primary btn-edit"
                                            data-data='${JSON.stringify(row)}'>
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                    ` : ''}

                                    ${canEditState ? `
                                        <button class="btn btn-sm btn-outline-${isActive ? 'warning' : 'success'} btn-toggle"
                                            data-id="${row.id}"
                                            data-status="${isActive ? 0 : 1}"
                                            title="${isActive ? 'Desactivar' : 'Activar'}">
                                            <i class="bi ${isActive ? 'bi-toggle-on' : 'bi-toggle-off'} fs-5"></i>
                                        </button>
                                    ` : ''}

                                </div>
                            `;
                        }
                    }
                ],

                onDraw() {
                    document.querySelectorAll('.btn-edit').forEach(btn => {
                        btn.onclick = e => {
                            const data = JSON.parse(e.currentTarget.dataset.data);
                            self.edit(data);
                        };
                    });

                    document.querySelectorAll('.btn-toggle').forEach(btn => {
                        btn.onclick = e => {
                            self.toggleStatus(
                                e.currentTarget.dataset.id,
                                e.currentTarget.dataset.status
                            );
                        };
                    });
                }
            });
        },

        openCreateModal() {
            this.resetForm();
            this.isEditing = false;
            new bootstrap.Modal('#featureModal').show();
        },

        edit(data) {
            this.form = {
                id: data.id,
                name: data.name,
                descripcion: data.descripcion,
                is_active: data.is_active
            };

            this.isEditing = true;
            new bootstrap.Modal('#featureModal').show();
        },

        async save() {
            this.loading = true;

            try {
                const fd = new FormData();
                fd.append('name', this.form.name);
                fd.append('descripcion', this.form.descripcion);
                fd.append('is_active', 1);


                await axios.post('/save-plan-feature', fd);

                Toast.show({
                    id: 'myToast',
                    title:  "",
                    message: PlanFeaturesModuleTranslations.feature_created_success,
                    type: 'success'
                });

                this.table.reload(false);

                bootstrap.Modal.getInstance(
                    document.getElementById('featureModal')
                ).hide();

                this.resetForm();

            } catch (e) {
                let er = e;

                if (er.response && er.response.data.errors) {

                    const errors = e.response.data.errors;
                    let errorHtml = '<ul style="text-align:left;">';

                    Object.keys(errors).forEach(field => {
                        errors[field].forEach(msg => {
                            errorHtml += `<li>${msg}</li>`;
                        });
                    });

                    errorHtml += '</ul>';

                    Swal.fire({
                        icon: 'error',
                        title: window.erros_validation,
                        html: errorHtml
                    });

                }
            } finally {
                this.loading = false;
            }
        },
        async update() {
            let context = this;
            this.loading = true;

            try {
                const fd = new FormData();
                fd.append('id', this.form.id);
                fd.append('name', this.form.name);
                fd.append('descripcion', this.form.descripcion);
                fd.append('is_active', this.form.is_active);

                await axios.post(`/update-plan-feature`, fd).then(function(resp){
                    context.table.reload(false);

                    bootstrap.Modal.getInstance(
                        document.getElementById('featureModal')
                    ).hide();

                    context.resetForm();
                    context.isEditing = false;

                    Toast.show({
                        id: 'myToast',
                        title:  "",
                        message: PlanFeaturesModuleTranslations.feature_updated_success,
                        type: 'success'
                    });
                });

            } catch (e) {
                let er = e;
                if (er.response && er.response.data.errors) {

                    const errors = e.response.data.errors;
                    let errorHtml = '<ul style="text-align:left;">';

                    Object.keys(errors).forEach(field => {
                        errors[field].forEach(msg => {
                            errorHtml += `<li>${msg}</li>`;
                        });
                    });

                    errorHtml += '</ul>';

                    Swal.fire({
                        icon: 'error',
                        title: window.erros_validation,
                        html: errorHtml
                    });

                }

            } finally {
                this.loading = false;
            }
        },

        resetForm() {
            this.form = {
                id: null,
                name: '',
                descripcion: ''
            };
        },

        toggleStatus(Id, status) {
            const self = this;
            const el = window.PlanFeaturesModuleTranslations;

            Swal.fire({
                title: el.change_status_title,
                text: el.change_status_info,
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: window.yes,
                cancelButtonText: window.no
                }).then((result) => {
                if (result.isConfirmed) {
                    axios.post(`update-plan-feature-state`, {
                        id: Id,
                        is_active: status
                    }).then((response) => {
                        this.table.reload(false);
                        Swal.fire({
                            title:el.change_status_title,
                            text: response.data.message,
                            icon: "success"
                        });
                    }).catch(err => {
                        console.error(err);
                    });
                }
            });
        },

        removeRole(roleName) {
            const role = this.rolesMock.find(r => r.name === roleName);
            if (role) {
                this.form.roles = this.form.roles.filter(id => id !== role.id);
            }
        },
    }
});
