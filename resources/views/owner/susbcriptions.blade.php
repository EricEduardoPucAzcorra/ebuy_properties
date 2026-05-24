@extends('layouts.app')

@section('content')
  <div class="benefits-section mt-5 pt-3">
      <div class="text-center mb-4">
          <h5 class="fw-bold">{{ auto_trans('¿Por qué activar tu plan?') }}</h5>
      </div>

      <div class="row g-3 mb-4">
          <div class="col-md-3 col-6">
              <div class="benefit-card text-center p-3 rounded-4 border bg-white h-100">
                  <div class="benefit-icon bg-success bg-opacity-10 rounded-3 d-inline-flex align-items-center justify-content-center mb-2" style="width: 48px; height: 48px;">
                      <i class="bi bi-eye-fill text-success fs-4"></i>
                  </div>
                  <div class="fw-bold fs-3 text-success">3x</div>
                  <span class="small text-muted">{{ auto_trans('Más visitas') }}</span>
              </div>
          </div>
          <div class="col-md-3 col-6">
              <div class="benefit-card text-center p-3 rounded-4 border bg-white h-100">
                  <div class="benefit-icon bg-success bg-opacity-10 rounded-3 d-inline-flex align-items-center justify-content-center mb-2" style="width: 48px; height: 48px;">
                      <i class="bi bi-clock-fill text-success fs-4"></i>
                  </div>
                  <div class="fw-bold fs-3 text-success">-50%</div>
                  <span class="small text-muted">{{ auto_trans('Tiempo de venta') }}</span>
              </div>
          </div>
          <div class="col-md-3 col-6">
              <div class="benefit-card text-center p-3 rounded-4 border bg-white h-100">
                  <div class="benefit-icon bg-success bg-opacity-10 rounded-3 d-inline-flex align-items-center justify-content-center mb-2" style="width: 48px; height: 48px;">
                      <i class="bi bi-chat-dots-fill text-success fs-4"></i>
                  </div>
                  <div class="fw-bold fs-3 text-success">2.5x</div>
                  <span class="small text-muted">{{ auto_trans('Más contactos') }}</span>
              </div>
          </div>
          <div class="col-md-3 col-6">
              <div class="benefit-card text-center p-3 rounded-4 border bg-white h-100">
                  <div class="benefit-icon bg-success bg-opacity-10 rounded-3 d-inline-flex align-items-center justify-content-center mb-2" style="width: 48px; height: 48px;">
                      <i class="bi bi-heart-fill text-success fs-4"></i>
                  </div>
                  <div class="fw-bold fs-3 text-success">#1</div>
                  <span class="small text-muted">{{ auto_trans('Propiedad destacada') }}</span>
              </div>
          </div>
      </div>

      <div class="text-center">
          <div class="p-3 bg-light">
              <span class="small fw-semibold">
                  <!-- <i class="bi bi-trophy-fill text-success me-2"></i> -->
                  {{ auto_trans('Publica tus inmuebles con Ebuy Properties')}}
              </span>
          </div>
      </div>
  </div>
  @include('admin.plans.plans_payments')
@endsection

<style>
.benefits-section {
    border-top: 1px solid rgba(0,0,0,0.05);
}

.benefit-card {
    transition: all 0.2s ease-in-out;
    cursor: pointer;
}

.benefit-card:hover {
    transform: translateY(-4px);
    border-color: #198754 !important;
    box-shadow: 0 8px 20px rgba(25, 135, 84, 0.1);
}

.bg-opacity-10 {
    --bs-bg-opacity: 0.1;
}
</style>