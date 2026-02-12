new Vue({
    el: '#planOwner',
    data() {
        return {
            plans: [],
            selectedPlan: null,
            loadingView: true,
            currentStep: 1, // 1: Elegir plan, 2: Detalles, 3: Pago
            processingPayment: false,
            payment: {
                name: '',
                card: '',
                exp: '',
                cvv: ''
            }
        }
    },

    mounted() {
        this.fetchPlans();
    },

    methods: {
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

        getPlanIcon(planName) {
            const name = planName.toLowerCase();
            if (name.includes('básico') || name.includes('basico')) return 'bi bi-star';
            if (name.includes('profesional')) return 'bi bi-briefcase';
            if (name.includes('premium') || name.includes('empresarial')) return 'bi bi-gem';
            if (name.includes('agencia')) return 'bi bi-building';
            return 'bi bi-box';
        },

        selectPlan(plan) {
            this.selectedPlan = plan;
            this.currentStep = 2;
            window.scrollTo({ top: 0, behavior: 'smooth' });
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
                exp: '',
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

        async processPayment() {
            if (!this.validatePayment()) {
                return;
            }

            this.processingPayment = true;

            try {
                await new Promise(resolve => setTimeout(resolve, 2000));

                Swal.fire({
                    icon: 'success',
                    title: '¡Pago exitoso!',
                    html: `
                        <div class="text-center">
                            <i class="bi bi-check-circle-fill text-success display-4 mb-3"></i>
                            <h5 class="fw-bold">¡Felicidades!</h5>
                            <p class="fs-5">Has adquirido el plan</p>
                            <p class="fs-3 fw-bold text-success">${this.selectedPlan.name}</p>
                            <p class="text-muted">$${this.selectedPlan.price.toLocaleString()}/mes</p>
                        </div>
                    `,
                    confirmButtonText: 'Ir a mi cuenta',
                    confirmButtonColor: '#198754'
                });

                // Reiniciar todo después del pago exitoso
                this.currentStep = 1;
                this.selectedPlan = null;
                this.resetPaymentForm();
                window.scrollTo({ top: 0, behavior: 'smooth' });

            } catch (error) {
                console.error('Error en el pago:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error en el pago',
                    text: 'No se pudo procesar el pago. Por favor intenta nuevamente.',
                    confirmButtonText: 'Entendido',
                    confirmButtonColor: '#dc3545'
                });
            } finally {
                this.processingPayment = false;
            }
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

            if (!/^\d{2}\/\d{2}$/.test(this.payment.exp)) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Fecha incorrecta',
                    text: 'Usa el formato MM/AA (ejemplo: 12/25)',
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
        }
    },

    watch: {
        currentStep(newVal) {
            switch(newVal) {
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
