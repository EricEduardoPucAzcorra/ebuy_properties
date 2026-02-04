new Vue({
    el: '#planOwner',
    data() {
        return {
            plans: [],
            selectedPlan: null,
            loadingView: true,
            showPaymentView: false,
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

        selectPlan(plan) {
            this.loadingView = true;
            this.selectedPlan = plan;

            window.scrollTo({ top: 0, behavior: 'smooth' });

            setTimeout(() => {
                this.loadingView = false;
                this.showPaymentView = true;
                this.$nextTick(() => {
                    setTimeout(() => {
                        const firstInput = document.querySelector('input[type="text"]');
                        if (firstInput) firstInput.focus();
                    }, 100);
                });
            }, 500);
        },

        backToPlans() {
            this.loadingView = true;
            setTimeout(() => {
                this.showPaymentView = false;
                this.selectedPlan = null;
                this.resetPaymentForm();
                this.loadingView = false;
                window.scrollTo({ top: 0, behavior: 'smooth' });
            }, 300);
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
                            <h5 class="fw-bold">Has adquirido el plan</h5>
                            <p class="fs-5 text-success fw-bold">${this.selectedPlan.name}</p>
                            <p class="text-muted">$${this.selectedPlan.price.toLocaleString()}/mes</p>
                        </div>
                    `,
                    confirmButtonText: 'Continuar',
                    confirmButtonColor: '#198754'
                });

                this.backToPlans();

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
        showPaymentView(newVal) {
            if (newVal) {
                document.title = `Pagar ${this.selectedPlan?.name || 'Plan'} | Pos Finance ok`;
            } else {
                document.title = 'Planes | Pos Finance';
            }
        }
    }
});
