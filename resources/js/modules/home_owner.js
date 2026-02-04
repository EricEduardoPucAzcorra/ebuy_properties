new Vue({
    el:'#planOwner',
    data(){
        return {
            plans:[],
            selectedPlan:null,
            showPlanModal:false,
            loadingModal:false,
            payment:{name:'',card:'',exp:'',cvv:''}
        }
    },
    mounted(){
        this.fetchPlans();
    },
    methods:{
        async fetchPlans(){
            try{
                const response = await axios.get('/api/plans-index');
                this.plans = response.data;
            }catch(e){
                console.error(e);
            }
        },
        openPlanModal(plan){
            this.selectedPlan = plan;
            this.showPlanModal = true;
            this.loadingModal = true;
            setTimeout(()=> this.loadingModal=false, 800);
        },
        closeModal(){
            this.showPlanModal = false;
            this.payment = {name:'',card:'',exp:'',cvv:''};
        },
        processPayment(){
            alert(`Procesando pago de $${this.selectedPlan.price.toLocaleString()} para ${this.payment.name}`);
            this.closeModal();
        }
    }
});
