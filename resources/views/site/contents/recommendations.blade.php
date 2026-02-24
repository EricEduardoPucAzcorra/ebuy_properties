@php
    function getAttribute($attributes, $key) {
        return optional(
            $attributes->firstWhere('key', $key)
        )->value;
    }
@endphp

<div class="container-fluid bg-white py-5">
    <div class="container-fluid px-4 px-xl-5">

        <div class="row mb-4">
            <div class="col-12">
                <h2 class="section-title fw-bold">
                    {{ auto_trans('Nosotros te recomendamos') }}
                </h2>
            </div>
        </div>

        @if($properties->count())
            <div class="properties-carousel-wrapper position-relative">
                <div class="swiper-button-prev custom-nav" style="z-index: 10;"></div>

                <div class="swiper properties-swiper">
                    <div class="swiper-wrapper">

                        @foreach($properties as $property)
                            <div class="swiper-slide">
                                <div class="card-clean h-100 d-flex flex-column position-relative">

                                    <div class="img-frame position-relative">
                                        <img
                                            src="{{ $property->images->count()
                                                ? asset('storage/'.$property->images->first()->path)
                                                : asset('images/ebuy_1.png') }}"
                                            alt="{{ $property->title }}">

                                        <span class="floating-tag">
                                            {{ $property->operation->name ?? 'Venta' }}
                                        </span>
                                        
                                        @include('components.favorite-button', ['propertyId' => $property->id])
                                    </div>

                                    <div class="p-4 flex-grow-1">
                                        <h5 class="fw-bold mb-1">
                                            <a href="{{ route('property', $property->id) }}" class="stretched-link text-decoration-none text-dark">
                                                {{ $property->title }}
                                            </a>
                                        </h5>

                                        <div class="price-tag mb-1">
                                            ${{ number_format($property->price, 0) }}
                                            @if(($property->operation->name ?? '') === 'Renta')
                                                <span class="price-period">/Mes</span>
                                            @endif
                                        </div>

                                        <div class="text-muted small mb-2">
                                            {{ $property->type->name ?? 'Casa' }}
                                        </div>

                                        <p class="text-muted small mb-0">
                                            <i class="fa fa-map-marker-alt me-1"></i>
                                            {{ optional($property->address)->city->cityname ?? '' }},
                                            {{ optional($property->address)->state->statename ?? '' }}
                                        </p>
                                    </div>

                                    <div class="amenity-row d-flex justify-content-between px-4 py-3" style="position: relative; z-index: 2;">
                                        @if(getAttribute($property->attributes, 'Camas'))
                                            <span>
                                                <i class="fa fa-bed"></i>
                                                {{ getAttribute($property->attributes, 'Camas') }} {{auto_trans('Camas')}}
                                            </span>
                                        @endif

                                        @if(getAttribute($property->attributes, 'Baños'))
                                            <span>
                                                <i class="fa fa-bath"></i>
                                                {{ getAttribute($property->attributes, 'Baños') }} {{auto_trans('Baños')}}
                                            </span>
                                        @endif

                                        @if(getAttribute($property->attributes, 'M²'))
                                            <span>
                                                <i class="fa fa-ruler-combined"></i>
                                                {{ getAttribute($property->attributes,  'M²') }} m²
                                            </span>
                                        @endif
                                    </div>

                                </div>
                            </div>
                        @endforeach

                    </div>
                </div>

                <div class="swiper-button-next custom-nav" style="z-index: 10;"></div>

            </div>
        @else
            <div class="row">
                <div class="col-12 text-center py-5">
                    <h5 class="text-muted">
                        {{ auto_trans('No hay propiedades disponibles en este momento.') }}
                    </h5>
                    <p class="text-muted">
                        {{ auto_trans('Por favor, vuelve más tarde o contacta con nosotros para más información.') }}
                    </p>
                </div>
            </div>
        @endif

    </div>
</div>

@if($properties->count())
<script>
document.addEventListener('DOMContentLoaded', function () {
    new Swiper('.properties-swiper', {
        slidesPerView: 3,
        spaceBetween: 32,
        loop: true,
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        },
        breakpoints: {
            0: {
                slidesPerView: 1.1,
            },
            576: {
                slidesPerView: 2,
            },
            1200: {
                slidesPerView: 3,
            }
        }
    });
});
</script>
@endif
