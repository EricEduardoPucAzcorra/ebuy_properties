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
                            {{ $type->properties_count }} {{auto_trans('Propiedades')}}
                        </span>
                    </a>
                </div>

                @php $delay += 0.1; @endphp
            @endforeach
        </div>


    </div>
</div>

@include('styles.type_properties')
