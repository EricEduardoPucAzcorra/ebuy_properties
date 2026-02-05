<div class="container-fluid property-types-section">
    <div class="container">
        <div class="text-center mx-auto mb-5 wow fadeInUp" data-wow-delay="0.1s" style="max-width: 650px;">
            <h1 class="fw-bold mb-3">{{ __('site.type_properties') }}</h1>
            <p class="text-muted">
                {{ __('site.discover_best_real_estate_opportunities_quickly_and_easily') }}
            </p>
        </div>

        @php
            use App\Models\TypePropetie;

            $types = TypePropetie::where('is_active', true)
                ->withCount(['properties' => function ($q) {
                    $q->where('is_active', true);
                }])
                ->get();

            $delay = 0.1;
        @endphp


        <div class="row g-4">
            @foreach($types as $type)
                <div class="col-lg-3 col-sm-6 wow fadeInUp" data-wow-delay="{{ number_format($delay, 1) }}s">
                    <a href="#" class="property-type-card">
                        <div class="icon-wrapper">
                            <i class="{{ $type->image_icon }}"></i>
                        </div>

                        <h6>{{auto_trans($type->name) }}</h6>

                        <span class="badge">
                            {{ $type->properties_count }} Properties
                        </span>
                    </a>
                </div>

                @php $delay += 0.1; @endphp
            @endforeach
        </div>


    </div>
</div>

<style>
    .property-types-section {
        background: #ffffff !important;
        padding: 100px 0;
    }
    .property-type-card {
    display: block;
    background: #ffffff;
    border-radius: 20px;
    padding: 45px 25px;
    text-align: center;
    text-decoration: none;
    transition: all 0.35s ease;
    border: 1px solid #e9ecef;
    height: 100%;
}

.property-type-card:hover {
    transform: translateY(-10px);
    border-color: #198754;
    box-shadow: 0 25px 55px rgba(0, 0, 0, 0.15);
}

.icon-wrapper {
    width: 95px;
    height: 95px;
    margin: 0 auto 28px;
    background: #f8f9fa;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.35s ease;
}

.property-type-card:hover .icon-wrapper {
    background: rgba(25, 135, 84, 0.12);
}

.icon-wrapper i {
    font-size: 40px;
    color: #198754;
    transition: transform 0.35s ease;
}

.property-type-card:hover .icon-wrapper i {
    transform: scale(1.15);
}

.property-type-card h6 {
    font-size: 19px;
    font-weight: 600;
    color: #0b2239;
    margin-bottom: 10px;
}

.property-type-card .badge {
    background: #f1f3f5;
    color: #198754;
    font-weight: 500;
    font-size: 13px;
    padding: 8px 18px;
    border-radius: 50px;
}

</style>
