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
            userCards: [],
            loadingCards: true,
            selectedCard: null,
            useNewCard: false,
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
        this.fetchUserCards();

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

        async fetchUserCards() {
            try {
                this.loadingCards = true;
                const response = await axios.get('/owner/user-cards');
                this.userCards = response.data;
            } catch (error) {
                console.error('Error cargando tarjetas:', error);
                this.userCards = [];
            } finally {
                this.loadingCards = false;
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
            // Si hay tarjetas existentes, seleccionar la primera por defecto
            if (this.userCards.length > 0) {
                this.selectedCard = this.userCards[0];
                this.useNewCard = false;
            } else {
                this.useNewCard = true;
            }
            window.scrollTo({ top: 0, behavior: 'smooth' });
            this.$nextTick(() => {
                setTimeout(() => {
                    const firstInput = document.querySelector('input[type="text"]');
                    if (firstInput) firstInput.focus();
                }, 100);
            });
        },

        selectExistingCard(card) {
            this.selectedCard = card;
            this.useNewCard = false;
        },

        clearSelectedCard() {
            this.selectedCard = null;
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

            // Si hay tarjeta seleccionada, usar tarjeta existente
            if (this.selectedCard && !this.useNewCard) {
                this.processPaymentWithExistingCard();
                return;
            }

            // Si es nueva tarjeta, validar y procesar
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

        async processPaymentWithExistingCard() {
            if (this.processingPayment) return;

            this.processingPayment = true;

            try {
                const response = await axios.post('/owner/susbcription/process', {
                    card_id: this.selectedCard.openpay_card_id,
                    plan_id: this.selectedPlan.openpay_plan_id
                });

                this.processingPayment = false;

                await Swal.fire({
                    icon: 'success',
                    title: '¡Pago exitoso!',
                    text: this.hasActiveSubscription ? 'Has cambiado de plan exitosamente' : 'Tu suscripción ha sido activada correctamente',
                    confirmButtonText: 'Entendido'
                });

                await this.fetchsubscriptions();
                await this.fetchUserCards();
                this.currentStep = 1;
                this.selectedPlan = null;
                this.selectedCard = null;
                this.resetPaymentForm();

            } catch (error) {
                this.processingPayment = false;
                console.log(error);
                this.showError(
                    'Error procesando pago',
                    error.response?.data?.message || 'Ocurrió un error al procesar tu pago'
                );
            }
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
                    await this.fetchUserCards();
                    this.currentStep = 1;
                    this.selectedPlan = null;
                    this.selectedCard = null;
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

            const errorCode = response?.data?.error_code;

            let desc = errorCode
                ? this.getOpenPayErrorMessage(errorCode)
                : (response.data?.description || response.message || 'Error desconocido');

            const textosParaTraducir = Array.isArray(desc)
                ? desc
                : [desc];

            let desctext = await window.auto_trans_batch(textosParaTraducir);

            this.showError(`Error en el pago`, desctext);
        },

        getOpenPayErrorMessage(errorCode) {
            const errors = {
                // Errores Generales (1000-1010)
                1000: 'Hubo un error en el sistema. Por favor intenta de nuevo más tarde.',
                1001: 'Verifica que los datos de tu tarjeta estén correctos.',
                1002: 'Error de conexión. Por favor recarga la página e intenta nuevamente.',
                1003: 'Alguno de los datos ingresados no es correcto. Por favor revísalo.',
                1004: 'El servicio de pagos no está disponible en este momento. Intenta más tarde.',
                1005: 'No encontramos el recurso solicitado. Por favor intenta nuevamente.',
                1006: 'Ya existe una transacción en proceso. Por favor espera unos minutos.',
                1007: 'La transferencia no fue aceptada por el banco. Intenta con otra tarjeta.',
                1008: 'Tu cuenta está desactivada. Por favor contacta a soporte.',
                1009: 'La información es muy extensa. Por favor reduce los datos e intenta nuevamente.',
                1010: 'Error de conexión. Por favor recarga la página.',

                // Errores de Almacenamiento (2001-2009)
                2001: 'Esta cuenta bancaria ya está registrada en tu perfil.',
                2002: 'Esta tarjeta ya está guardada en tu cuenta.',
                2003: 'Ya existe un cliente con estos datos.',
                2004: 'El número de tarjeta no es válido. Por favor revísalo.',
                2005: 'La tarjeta ha expirado. Usa una tarjeta vigente.',
                2006: 'Ingresa el código de seguridad (CVV) de tu tarjeta.',
                2007: 'Esta tarjeta es solo para pruebas. No se puede usar en pagos reales.',
                2008: 'Esta tarjeta no acumula puntos.',
                2009: 'El código de seguridad (CVV) no es válido. Verifícalo.',

                // Errores de Tarjetas (3001-3012)
                3001: 'Tu tarjeta fue rechazada. Intenta con otra tarjeta o método de pago.',
                3002: 'Tu tarjeta ha expirado. Usa una tarjeta vigente.',
                3003: 'Tu tarjeta no tiene fondos suficientes. Intenta con otra tarjeta.',
                3004: 'Tu tarjeta fue reportada como robada. Contacta a tu banco.',
                3005: 'Tu tarjeta fue reportada como fraudulenta. Contacta a tu banco.',
                3006: 'Esta operación no está permitida. Contacta a soporte.',
                3008: 'Esta tarjeta no permite pagos en línea. Intenta con otra.',
                3009: 'Tu tarjeta fue reportada como perdida. Contacta a tu banco.',
                3010: 'Tu banco ha restringido esta tarjeta. Contacta a tu banco.',
                3011: 'Tu banco solicitó retener la tarjeta. Contacta a tu banco de inmediato.',
                3012: 'Se requiere autorización del banco. Contacta a tu banco.',

                // Errores de Cuentas (4001)
                4001: 'Error en el procesamiento. Por favor contacta a soporte.'
            };

            return errors[errorCode] || 'Ocurrió un error al procesar tu pago. Por favor intenta nuevamente.';
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