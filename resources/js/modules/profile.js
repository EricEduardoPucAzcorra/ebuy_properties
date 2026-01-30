new Vue({
    el: '#profileModule',

    data: {
        loading: false,
        imagePreview: null,

        form: {
            id: null,
            name: '',
            last_name: '',
            second_last_name: '',
            phone: '',
            email: '',
            password: '',
            password_confirmation: '',
            profile: null,
            roles: [],
            is_active: 1
        }
    },

    mounted() {
        this.loadProfile();
    },

    methods: {

        async loadProfile() {
            this.loading = true;

            try {
                const res = await axios.get('/auth/user');
                const user = res.data;

                this.form = {
                    id: user.id,
                    name: user.name,
                    last_name: user.last_name ?? '',
                    second_last_name: user.second_last_name ?? '',
                    phone: user.phone ?? '',
                    email: user.email,
                    password: '',
                    password_confirmation: '',
                    profile: null,
                    roles: user.roles || [],
                    is_active: 1
                };

                this.imagePreview = user.profile
                    ? `/storage/${user.profile}`
                    : '/images/avatar-placeholder.svg';

            } catch (e) {
                console.error(e);
            } finally {
                this.loading = false;
            }
        },

        handleFileChange(e) {
            const file = e.target.files[0];
            if (!file) return;

            this.form.profile = file;
            this.imagePreview = URL.createObjectURL(file);
        },

        async updateProfile() {

            if (this.form.password) {

                const confirm = await Swal.fire({
                    icon: 'warning',
                    title: ProfileTranslations.change_password,
                    html: `
                        <p>${ProfileTranslations.updated_you_profile}</p>
                        <p class="text-danger">
                            ${ProfileTranslations.update_profile_logout}
                        </p>
                        <p><strong>
                            ${ProfileTranslations.update_profile_continue}
                        </strong></p>
                    `,
                    showCancelButton: true,
                    confirmButtonText: yes_continue,
                    cancelButtonText: cancel,
                    confirmButtonColor: '#d33'
                });

                if (!confirm.isConfirmed) {
                    return;
                }
            }

            this.loading = true;

            try {
                const fd = new FormData();

                Object.entries(this.form).forEach(([key, value]) => {
                    if (key !== 'roles' && key !== 'profile') {
                        fd.append(key, value ?? '');
                    }
                });

                this.form.roles.forEach(role => {
                    fd.append('roles[]', role.id);
                });

                if (this.form.profile instanceof File) {
                    fd.append('profile', this.form.profile);
                }

                await axios.post('/update-user', fd);

                if (this.form.password) {

                    Swal.fire({
                        icon: 'success',
                        title: ProfileTranslations.updated_profile,
                        html: `
                            <p>${ProfileTranslations.updated_profile_success}</p>
                            <p class="text-danger">
                                ${ProfileTranslations.update_profile_session_close}
                            </p>
                        `,
                        showConfirmButton: false,
                        timer: 2500
                    });

                    setTimeout(() => {
                        document.getElementById('logout-form').submit();
                    }, 2500);

                } else {
                    Swal.fire({
                        icon: 'success',
                        title: ProfileTranslations.updated_profile,
                        text: ProfileTranslations.updated_profile_success
                    });
                }

            } catch (e) {
                if (e.response && e.response.data.errors) {
                    let html = '<ul class="text-start">';
                    Object.values(e.response.data.errors).forEach(errs => {
                        errs.forEach(msg => html += `<li>${msg}</li>`);
                    });
                    html += '</ul>';

                    Swal.fire({
                        icon: 'error',
                        title: window.erros_validation || 'Error',
                        html
                    });
                }
            } finally {
                this.loading = false;
            }
        }
    }
});
