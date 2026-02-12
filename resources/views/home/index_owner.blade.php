@if (Auth::user()->hasActiveSubscription())
    ok
@else
    @include('admin.plans.plans_payments')
@endif



