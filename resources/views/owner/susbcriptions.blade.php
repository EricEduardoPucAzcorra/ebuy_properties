@extends('layouts.app')

@section('content')
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