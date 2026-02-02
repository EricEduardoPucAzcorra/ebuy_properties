new Vue({
    el: '#UsersModule',
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
            last_name: '',
            second_last_name: '',
            phone: '',
            email: '',
            password: '',
            password_confirmation: '',
            is_active: 1,
            profile: null,
            roles: []
        },

        translations: {
            edit: '',
            create: ''
        },

        rolesMock: [
            // { id: 1, name: 'Administrador', icon: 'bi bi-shield-lock-fill', color: '#e74c3c' },
            // { id: 2, name: 'Editor', icon: 'bi bi-pencil-square', color: '#f39c12' },
            // { id: 3, name: 'Soporte', icon: 'bi bi-headset', color: '#27ae60' },
            // { id: 4, name: 'Usuario', icon: 'bi bi-person-fill', color: '#8e44ad' },
            //    { id: 1, name: 'Administrador', icon: 'bi bi-shield-lock-fill', color: '#e74c3c' },
            // { id: 2, name: 'Editor', icon: 'bi bi-pencil-square', color: '#f39c12' },
            // { id: 3, name: 'Soporte', icon: 'bi bi-headset', color: '#27ae60' },
            // { id: 4, name: 'Usuario', icon: 'bi bi-person-fill', color: '#8e44ad' },
            //    { id: 1, name: 'Administrador', icon: 'bi bi-shield-lock-fill', color: '#e74c3c' },
            // { id: 2, name: 'Editor', icon: 'bi bi-pencil-square', color: '#f39c12' },
            // { id: 3, name: 'Soporte', icon: 'bi bi-headset', color: '#27ae60' },
            // { id: 4, name: 'Usuario', icon: 'bi bi-person-fill', color: '#8e44ad' }
        ],

        roleSearch: ''
    },

    mounted() {
        const el = window.UsersModuleTranslations;
        this.translations.edit = el.edit;
        this.translations.create = el.create;
        this.initUsersTable();
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

        initUsersTable() {
            const self = this;

            self.table = new DataTableComponent({
                selector: '#table-users',
                url: '/get-users',
                extraParams: () => ({
                    is_active: self.filters.status
                }),
                columns: [
                    { data: 'id' },
                    { data: 'name' },
                    { data: 'email' },
                    {
                        data: null,
                        render: row => {
                            const img = row.profile
                                ? `/storage/${row.profile}`
                                : row.profile_url
                                    ? row.profile_url
                                    : '/images/avatar-placeholder.svg';

                            return `
                                <img src="${img}"
                                    class="img-thumbnail"
                                    style="width:45px">
                            `;
                        }
                    },
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
                                            data-user='${JSON.stringify(row)}'>
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                    ` : ''}

                                    ${canEditState ? `
                                        <button class="btn btn-sm btn-outline-${isActive ? 'warning' : 'success'} btn-toggle"
                                            data-id="${row.id}"
                                            data-status="${isActive ? 0 : 1}">
                                            <i class="bi bi-person-${isActive ? 'x' : 'check'}"></i>
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
                            const user = JSON.parse(e.currentTarget.dataset.user);
                            self.editUser(user);
                        };
                    });

                    document.querySelectorAll('.btn-toggle').forEach(btn => {
                        btn.onclick = e => {
                            self.toggleUserStatus(
                                e.currentTarget.dataset.id,
                                e.currentTarget.dataset.status
                            );
                        };
                    });
                }
            });
        },

        openCreateModal() {
            this.getRoles();
            this.resetForm();
            this.isEditing = false;
            new bootstrap.Modal('#userModal').show();
        },

        editUser(data) {
            this.getRoles();
            this.form = {
                id: data.id,
                name: data.name,
                last_name: data.last_name || '',
                second_last_name: data.second_last_name || '',
                phone: data.phone || '',
                email: data.email,
                password: '',
                password_confirmation: '',
                is_active: data.is_active,
                profile: null,
                roles: data.roles ? data.roles.map(r => r.id) : []
            };

            this.imagePreview = data.profile
                ? `/storage/${data.profile}`
                : data.profile_url
                    ? data.profile_url
                    : '/images/avatar-placeholder.svg';


            this.isEditing = true;
            new bootstrap.Modal('#userModal').show();
        },

        handleFileChange(e) {
            const file = e.target.files[0];
            if (!file) return;

            this.form.profile = file;
            this.imagePreview = URL.createObjectURL(file);
        },

        async saveUser() {
            this.loading = true;

            try {
                const fd = new FormData();
                fd.append('name', this.form.name);
                fd.append('email', this.form.email);
                fd.append('is_active', this.form.is_active);

                fd.append('last_name', this.form.last_name ?? '');
                fd.append('second_last_name', this.form.second_last_name ?? '');
                fd.append('phone', this.form.phone ?? '');
                this.form.roles.forEach(roleId => {
                    fd.append('roles[]', roleId);
                });
                fd.append('password', this.form.password);
                fd.append('password_confirmation', this.form.password_confirmation);

                if (this.form.profile instanceof File) {
                    fd.append('profile', this.form.profile);
                }

                await axios.post('/save-user', fd);

                Toast.show({
                    id: 'myToast',
                    title:  "",
                    message: UsersModuleTranslations.user_created_success,
                    type: 'success'
                });

                this.table.reload(false);

                bootstrap.Modal.getInstance(
                    document.getElementById('userModal')
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
        async updateUser() {
            let context = this;
            this.loading = true;

            try {
                const fd = new FormData();
                fd.append('id', this.form.id);
                fd.append('last_name', this.form.last_name);
                fd.append('second_last_name', this.form.second_last_name);
                fd.append('phone', this.form.phone);
                fd.append('name', this.form.name);
                fd.append('email', this.form.email);
                fd.append('is_active', this.form.is_active);
                this.form.roles.forEach(roleId => {
                    fd.append('roles[]', roleId);
                });

                if (this.form.password) {
                    fd.append('password', this.form.password);
                    fd.append('password_confirmation', this.form.password_confirmation);
                }

                if (this.form.profile instanceof File) {
                    fd.append('profile', this.form.profile);
                }

                await axios.post(`/update-user`, fd).then(function(resp){
                    context.table.reload(false);

                    bootstrap.Modal.getInstance(
                        document.getElementById('userModal')
                    ).hide();

                    context.resetForm();
                    context.isEditing = false;

                    Toast.show({
                        id: 'myToast',
                        title:  "",
                        message: UsersModuleTranslations.user_updated_success,
                        type: 'success'
                    });

                    if (resp.data.is_authenticated) {
                        location.reload();
                    }
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
                last_name: '',
                second_last_name: '',
                phone: '',
                email: '',
                password: '',
                password_confirmation: '',
                is_active: 1,
                profile: null,
                roles: []
            };
            this.imagePreview = null;
            if (this.$refs.profileInput) {
                this.$refs.profileInput.value = '';
            }
        },

        toggleUserStatus(userId, status) {
            const self = this;
            const el = window.UsersModuleTranslations;

            Swal.fire({
                title: el.are_you_sure_activate_user_title,
                text: el.are_you_sure_activate_user_info,
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: window.yes,
                cancelButtonText: window.no
                }).then((result) => {
                if (result.isConfirmed) {
                    axios.post(`update-state`, {
                        id: userId,
                        is_active: status
                    }).then((response) => {
                        this.table.reload(false);
                        Swal.fire({
                            title:el.are_you_sure_activate_user_title,
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

        getRoles(){
            let context = this;

            axios.post('/get-roles')
            .then(res => {
                context.rolesMock = res.data;
            });
        }
    }
});
