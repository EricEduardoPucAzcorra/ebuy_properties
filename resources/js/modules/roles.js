new Vue({
    el: '#RolesModule',

    data: {
        roles: [],
        role_selected: null,
        loading: false,

        modules: modules_roles || [],
        selectedPermissions: new Set(),

        form: {
            id: null,
            name: '',
            description: ''
        },

        translations: {
            edit: '',
            create: ''
        },

        isEditing: false
    },

    mounted() {
        this.initGetRoles();

        this.sortPermissions();

        if (RolesModuleTranslations) {
            this.translations.edit   = RolesModuleTranslations.edit;
            this.translations.create = RolesModuleTranslations.create;
        }
    },

    methods: {

        initGetRoles() {
            axios.post('/get-roles')
                .then(res => {
                    this.roles = res.data;
                });
        },

        openCreateModal() {
            this.resetForm();
            this.isEditing = false;
            new bootstrap.Modal('#ModalRole').show();
        },

        edit_role() {
            if (!this.role_selected) return;

            this.isEditing = true;
            this.form = {
                id: this.role_selected.id,
                name: this.role_selected.name,
                description: this.role_selected.description || ''
            };

            new bootstrap.Modal('#ModalRole').show();
        },

        async saveRole() {
            this.loading = true;

            const fd = new FormData();
            Object.keys(this.form).forEach(k => {
                if (this.form[k] !== null) {
                    fd.append(k, this.form[k]);
                }
            });

            await axios.post('/save-role', fd);

            this.role_selected = null;

            this.initGetRoles();

            bootstrap.Modal.getInstance(
                document.getElementById('ModalRole')
            ).hide();

            this.loading = false;
        },

        async updateRole() {
            this.loading = true;

            const fd = new FormData();
            fd.append('id', this.form.id);
            fd.append('name', this.form.name);
            fd.append('description', this.form.description);

            await axios.post('/update-role', fd).then(res => {
                this.role_selected = res.data.role;
            });

            this.initGetRoles();

            bootstrap.Modal.getInstance(
                document.getElementById('ModalRole')
            ).hide();

            this.resetForm();
            this.isEditing = false;
            this.loading = false;
        },

        resetForm() {
            this.form = {
                id: null,
                name: '',
                description: ''
            };
        },

        loadPermissions() {
            if (!this.role_selected) {
                this.selectedPermissions.clear();
                return;
            }

            this.loading = true;

            axios.get(`/roles/${this.role_selected.id}/permissions`)
                .then(res => {
                    this.selectedPermissions = new Set(res.data);
                })
                .finally(() => {
                    this.loading = false;
                });
        },

        togglePermission(permissionId) {
            if (this.selectedPermissions.has(permissionId)) {
                this.selectedPermissions.delete(permissionId);
            } else {
                this.selectedPermissions.add(permissionId);
            }
        },

        savePermissions() {
            if (!this.role_selected) return;

            this.loading = true;

            axios.post(`/roles/${this.role_selected.id}/permissions`, {
                permissions: Array.from(this.selectedPermissions)
            })
            .then((response) => {
                Toast.show({
                    id: 'myToast',
                    title:  RolesModuleTranslations.role_permission_updated_title,
                    message: RolesModuleTranslations.role_permission_updated,
                    type: 'success'
                });

                if (response.data.hasrole) {
                    location.reload();
                }
            })
            .finally(() => {
                this.loading = false;
            });
        },

        formatPermissionLabel(name) {
            const parts = name.split('.');
            return parts[1] ? parts[1].replace(/_/g, ' ') : name;
        },

        sortPermissions() {
            const fixedOrder = ['view', 'create', 'update', 'destroy'];

            this.modules.forEach(module => {
                module.permissions.sort((a, b) => {
                    const keyA = a.name.split('.').pop();
                    const keyB = b.name.split('.').pop();

                    const idxA = fixedOrder.indexOf(keyA);
                    const idxB = fixedOrder.indexOf(keyB);

                    if (idxA !== -1 && idxB !== -1) {
                        return idxA - idxB;
                    }
                    if (idxA !== -1) return -1;
                    if (idxB !== -1) return 1;

                    return keyA.localeCompare(keyB);
                });
            });
        }
    }
});
