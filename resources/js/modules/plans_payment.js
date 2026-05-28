// import './../directives/auto_trans';

new Vue({
    el: '#planOwner',
    data() {
        return {
            plans: [],
            selectedPlan: null,
            loadingView: true,
            currentStep: 1,
            processingPayment: false,
            deviceSessionId: null,
            processingAction: false,
            subscriptions: null,
            loadingSubscription: true,
            payment: {
                name: '',
                card: '',
                expMonth: '',
                expYear: '',
                cvv: ''
            }
        }
    },

    computed: {
        hasActiveSubscription() {
            if (!this.subscriptions || !Array.isArray(this.subscriptions)) {
                return false;
            }
            return this.subscriptions.some(sub => sub.status === 'Activa');
        },
        currentPlan() {
            if (!this.hasActiveSubscription) return null
            const activeSubscription = this.subscriptions.find(sub => sub.status === 'Activa')
            return this.plans.find(p => p.id === activeSubscription.plan_id)
        }
    },

    mounted() {
        this.fetchPlans();
        this.fetchsubscriptions();

        OpenPay.setId('mndpzbubugodrfun9l5k');
        OpenPay.setApiKey('pk_13f476df917b4b9b84d1304615d39bbb');
        OpenPay.setSandboxMode(true);

        this.deviceSessionId = OpenPay.deviceData.setup(
            'payment-form',
            'deviceIdHiddenFieldName'
        );
    },

    methods: {
        getPlanStatus(planId) {
            if (!this.subscriptions || !Array.isArray(this.subscriptions)) {
                return null;
            }
            const subscription = this.subscriptions.find(sub => sub.plan_id === planId);
            return subscription ? subscription.status : null;
        },

        async fetchPlans() {
            try {
                this.loadingView = true;
                const response = await axios.get('/api/plans-index');
                this.plans = response.data;
            } catch (error) {
                console.error('Error cargando planes:', error);
                this.showError('No se pudieron cargar los planes');
            } finally {
                this.loadingView = false;
            }
        },

        async fetchsubscriptions() {
            try {
                this.loadingSubscription = true;
                const response = await axios.get('/owner/current-subscriptions');
                this.subscriptions = response.data;
            } catch (error) {
                console.error('Error cargando suscripción:', error);
                this.subscriptions = null;
            } finally {
                this.loadingSubscription = false;
            }
        },

        selectPlan(plan) {
            this.selectedPlan = plan;
            this.currentStep = 2;
            window.scrollTo({ top: 0, behavior: 'smooth' });
        },

        async cancelSubscription() {
            this.processingAction = true;
            try {
                const result = await Swal.fire({
                    title: '¿Cancelar suscripción?',
                    text: 'Esta acción cancelará tu suscripción actual. ¿Estás seguro?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Sí',
                    cancelButtonText: 'No',
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6'
                });

                if (result.isConfirmed) {
                    await axios.post('/owner/subscriptions/cancel');
                    await this.fetchsubscriptions();
                    Swal.fire({
                        icon: 'success',
                        title: 'Suscripción cancelada',
                        text: 'Tu suscripción ha sido cancelada correctamente',
                        confirmButtonText: 'Entendido'
                    });
                }
            } catch (error) {
                console.error('Error:', error);
                this.showError('Error al cancelar la suscripción');
            } finally {
                this.processingAction = false;
            }
        },

        goToStep1() {
            this.currentStep = 1;
            this.selectedPlan = null;
            this.resetPaymentForm();
            window.scrollTo({ top: 0, behavior: 'smooth' });
        },

        goToStep2() {
            this.currentStep = 2;
            window.scrollTo({ top: 0, behavior: 'smooth' });
        },

        goToPayment() {
            this.currentStep = 3;
            window.scrollTo({ top: 0, behavior: 'smooth' });
            this.$nextTick(() => {
                setTimeout(() => {
                    const firstInput = document.querySelector('input[type="text"]');
                    if (firstInput) firstInput.focus();
                }, 100);
            });
        },

        resetPaymentForm() {
            this.payment = {
                name: '',
                card: '',
                expMonth: '',
                expYear: '',
                cvv: ''
            };
            this.processingPayment = false;
        },

        formatCardNumber(event) {
            let value = event.target.value.replace(/\D/g, '');
            let formatted = '';
            for (let i = 0; i < value.length; i++) {
                if (i > 0 && i % 4 === 0) {
                    formatted += ' ';
                }
                formatted += value[i];
            }
            this.payment.card = formatted.substring(0, 19);
        },

        formatExpiration(event) {
            let value = event.target.value.replace(/\D/g, '');
            if (value.length >= 2) {
                value = value.substring(0, 2) + '/' + value.substring(2, 4);
            }
            this.payment.exp = value.substring(0, 5);
        },

        formatCVV(event) {
            this.payment.cvv = event.target.value.replace(/\D/g, '').substring(0, 4);
        },

        validatePayment() {
            if (!this.payment.name.trim() || this.payment.name.length < 3) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Nombre incompleto',
                    text: 'Ingresa el nombre completo que aparece en la tarjeta',
                    confirmButtonText: 'Entendido'
                });
                return false;
            }

            const cardNumber = this.payment.card.replace(/\s/g, '');
            if (!/^\d{13,19}$/.test(cardNumber)) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Tarjeta inválida',
                    text: 'El número de tarjeta debe tener entre 13 y 19 dígitos',
                    confirmButtonText: 'Entendido'
                });
                return false;
            }

            if (!this.payment.expMonth || !this.payment.expYear) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Fecha incorrecta',
                    text: 'Ingresa mes y año de expiración',
                    confirmButtonText: 'Entendido'
                });
                return false;
            }

            if (!/^\d{3,4}$/.test(this.payment.cvv)) {
                Swal.fire({
                    icon: 'warning',
                    title: 'CVV incorrecto',
                    text: 'El CVV debe tener 3 o 4 dígitos',
                    confirmButtonText: 'Entendido'
                });
                return false;
            }

            return true;
        },

        showError(title, message = '') {
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'error',
                    title: title,
                    text: message,
                    confirmButtonText: 'Entendido'
                });
            } else {
                alert(`${title}: ${message}`);
            }
        },

        processPayment() {
            if (this.processingPayment) return;

            if (!this.validatePayment()) {
                return;
            }

            this.processingPayment = true;

            const cleanCard = this.payment.card.replace(/\s/g, '');
            this.payment.card = cleanCard;

            this.$nextTick(() => {
                OpenPay.token.extractFormAndCreate(
                    'payment-form',
                    (response) => {
                        this.successCallback(response);
                    },
                    (response) => {
                        this.errorCallback(response);
                    }
                );
            });
        },

        successCallback(response) {
            const tokenId = response.data.id;

            axios.post('/owner/susbcription/process', {
                token_id: tokenId,
                device_session_id: this.deviceSessionId,
                plan_id: this.selectedPlan.openpay_plan_id,
                name_card: this.payment.name,
                // change_plan: this.hasActiveSubscription ? true : false 
            })
                .then(async (response) => {
                    this.processingPayment = false;
                    console.log(response);

                    await Swal.fire({
                        icon: 'success',
                        title: '¡Pago exitoso!',
                        text: this.hasActiveSubscription ? 'Has cambiado de plan exitosamente' : 'Tu suscripción ha sido activada correctamente',
                        confirmButtonText: 'Entendido'
                    });

                    await this.fetchsubscriptions();
                    this.currentStep = 1;
                    this.selectedPlan = null;
                    this.resetPaymentForm();
                })
                .catch((error) => {
                    this.processingPayment = false;
                    console.log(error);
                    this.showError(
                        'Error procesando pago',
                        error.response?.data?.message || 'Ocurrió un error al procesar tu pago'
                    );
                });
        },

        // async errorCallback(response) {
        //     this.processingPayment = false;
        //     let desc = response.data?.description || response.message || 'Error desconocido';
        //     let desctext = await window.auto_trans_batch(desc);
        //     this.showError(`Error en el pago`, desctext);
        // }

        async errorCallback(response) {
            this.processingPayment = false;

            let desc = response.data?.description || response.message || 'Error desconocido';

            const textosParaTraducir = Array.isArray(desc)
                ? desc
                : [desc];

            let desctext = await window.auto_trans_batch(textosParaTraducir);

            // console.log(desctext);

            this.showError(`Error en el pago`, desctext);
        }
    },

    watch: {
        currentStep(newVal) {
            switch (newVal) {
                case 1:
                    document.title = 'Elige tu plan | Ebuy Properties';
                    break;
                case 2:
                    document.title = `${this.selectedPlan?.name || 'Detalles del plan'} | Ebuy Properties`;
                    break;
                case 3:
                    document.title = 'Realizar pago | Ebuy Properties';
                    break;
            }
        }
    }
});