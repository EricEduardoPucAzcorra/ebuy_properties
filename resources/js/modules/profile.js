new Vue({
    el: '#profileModule',

    data: {
        loading: false,
        loadingCards: false,
        deletingCard: false,
        imagePreview: null,
        cards: [],
        isOwner: false,

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

                this.isOwner = Array.isArray(user.roles)
                    && user.roles.some(role => role && role.name === 'Owner');

                this.imagePreview = user.profile
                    ? `/storage/${user.profile}`
                    : user.profile_url
                        ? user.profile_url
                        : '/images/avatar-placeholder.svg';

                await this.loadCards();

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
        },

        async loadCards() {
            if (!this.isOwner) return;

            this.loadingCards = true;

            try {
                const response = await axios.get('/owner/user-cards');
                this.cards = response.data;
            } catch (error) {
                console.error('Error al cargar tarjetas:', error);
            } finally {
                this.loadingCards = false;
            }
        },

        getCardIcon(brand) {
            const brandLower = (brand || '').toLowerCase();
            const icons = {
                visa: 'bi bi-credit-card-2-front',
                mastercard: 'bi bi-credit-card-2-front',
                amex: 'bi bi-credit-card-2-front',
                discover: 'bi bi-credit-card-2-front'
            };
            return icons[brandLower] || 'bi bi-credit-card';
        },

        async confirmDeleteCard(card) {
            const result = await Swal.fire({
                title: '¿Eliminar tarjeta?',
                text: `¿Estás seguro de eliminar la tarjeta terminada en ${card.card_number ? card.card_number.slice(-4) : '****'}?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar',
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6'
            });

            if (result.isConfirmed) {
                await this.deleteCard(card.id);
            }
        },

        async deleteCard(cardId) {
            this.deletingCard = true;
            try {
                await axios.post('/owner/user-cards/delete', {
                    card_id: cardId
                });

                await Swal.fire({
                    icon: 'success',
                    title: 'Tarjeta eliminada',
                    text: 'La tarjeta ha sido eliminada correctamente',
                    confirmButtonText: 'Entendido'
                });

                await this.loadCards();
            } catch (error) {
                console.error('Error al eliminar tarjeta:', error);

                const responseData = error.response?.data;
                const message = responseData?.message || 'Ocurrió un error al eliminar la tarjeta';

                if (responseData?.has_active_subscription) {
                    await Swal.fire({
                        icon: 'warning',
                        title: 'Suscripción activa',
                        text: message,
                        confirmButtonText: 'Entendido',
                        confirmButtonColor: '#3085d6'
                    });
                } else {
                    this.showError(
                        'Error al eliminar la tarjeta',
                        message
                    );
                }
            } finally {
                this.deletingCard = false;
            }
        }
    }
});