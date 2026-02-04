<div id="planOwner" class="container py-5">

    <div class="text-center mb-5">
        <h1 class="fw-bold display-5">
            Publica, Gestiona y Llena tu Condominio
        </h1>
        <p class="text-muted fs-5">
            Elige el plan que mejor se adapte a tus objetivos 🚀
        </p>
    </div>

    <div class="row g-4 justify-content-center">
        <div class="col-md-4" v-for="plan in plans" :key="plan.id">
            <div class="card plan-card h-100 position-relative"
                 :class="{ 'shadow-selected': selectedPlan && selectedPlan.id === plan.id }">

                <span v-if="plan.is_featured" class="badge badge-featured">
                    Recomendado
                </span>

                <div class="card-body text-center p-4 d-flex flex-column">

                    <h4 class="fw-bold mb-1">@{{ plan.name }}</h4>
                    <p class="text-muted small mb-3">@{{ plan.description }}</p>

                    <h3 class="fw-bold text-success mb-4">
                        <span v-if="plan.price == 0">Gratis</span>
                        <span v-else>
                            $@{{ plan.price.toLocaleString() }}
                            <small class="text-muted fs-6">/mes</small>
                        </span>
                    </h3>

                     <ul class="features-list list-unstyled mb-4">
                        <li v-for="feature in plan.features" :key="feature.id">
                            <i class="bi bi-check-circle-fill text-success me-2"></i>

                            <span class="fw-semibold">
                                @{{ feature.name }}
                            </span>

                            <span v-if="feature.pivot?.mount" class="text-muted ms-1">
                                (@{{ feature.pivot.mount }} @{{ feature.pivot.description }})
                            </span>
                        </li>
                    </ul>

                    <button class="btn btn-success fw-bold w-100 mt-4"
                            @click="openPlanModal(plan)">
                        Seleccionar plan
                    </button>

                </div>
            </div>
        </div>
    </div>


    @include('home.modal_owner')
</div>

<style>
.plan-card{
    cursor:pointer;
    border-radius:1.5rem;
    transition:all 0.3s;
    border:1px solid #e0e0e0;
    background: #f9fefb;
}
.plan-card:hover{
    transform:translateY(-6px);
    box-shadow:0 1rem 2rem rgba(0,0,0,0.15);
}
.shadow-selected{
    box-shadow:0 0 1rem #28a745;
    border:2px solid #28a745 !important;
}

.badge-featured{
    position:absolute; top:15px; right:15px;
    padding:0.5rem 1rem;
    font-size:0.8rem; font-weight:600;
    background: linear-gradient(90deg, #28a745, #20c997);
    color:white;
}

.plan-features li{
    padding:0.35rem 0;
    font-size:0.95rem;
}

.modal-backdrop{
    position:fixed; top:0;left:0;width:100%;height:100%;
    background: rgba(0,0,0,0.5);
    display:flex; justify-content:center; align-items:center;
    z-index:1050;
    overflow-y:auto;
    padding:1rem;
}

.modal-dialog{
    max-width:620px;
    width:100%;
}
.modal-content{
    background:#fff;
    border-radius:1.5rem;
    padding:2rem;
    box-shadow:0 1.5rem 3rem rgba(0,0,0,0.25);
    position:relative;
}

.fade-enter-active,.fade-leave-active{transition:opacity 0.3s;}
.fade-enter-from,.fade-leave-to{opacity:0;}
</style>
