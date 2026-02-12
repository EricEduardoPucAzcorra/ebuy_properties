@php
    use App\Models\TypePropetie;
    use App\Models\TypeOperation;

    $type_properties = TypePropetie::all();
    $type_operations = TypeOperation::all();

    function getAttribute($attributes, $key) {
        return optional(
            $attributes->firstWhere('key', $key)
        )->value;
    }
@endphp

<div class="container-fluid bg-white py-5">
    <div class="container">

        <div class="row mb-5 align-items-center">
            <div class="col-lg-6 col-md-8">
                <p class="text-muted fs-5 mb-0">
                    {{auto_trans('Propiedades cuidadosamente seleccionadas para quienes buscan confort, ubicación y estilo.')}}
                </p>
            </div>

            <div class="col-lg-6 col-md-4">
                <div class="nav nav-pills nav-pills-custom d-flex flex-row flex-wrap justify-content-lg-end justify-content-start gap-2">
                    @php
                        $currentOperation = request()->query('operation');
                        $otherParams = request()->except('operation');
                    @endphp

                    <a href="{{ route('properties', $otherParams) }}" data-post-link
                        class="nav-link flex-shrink-0 {{ is_null($currentOperation) ? 'active' : '' }}">
                        {{auto_trans('Todo')}}
                    </a>

                    @foreach ($type_operations as $type_operation)
                        <a data-post-link
                            href="{{ route('properties', array_merge($otherParams, ['operation' => $type_operation->id])) }}"
                            class="nav-link flex-shrink-0 {{ $currentOperation == $type_operation->id ? 'active' : '' }}">
                            {{ $type_operation->name }}
                        </a>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="row g-4">
            @forelse($properties as $property)
                <div class="col-lg-4 col-md-6">
                    <div class="card-clean h-100 d-flex flex-column position-relative">

                        <div class="img-frame">
                            @if($property->images->count())
                                <div id="carousel{{ $property->id }}" class="carousel slide h-100" data-bs-ride="carousel">
                                    <div class="carousel-inner h-100">
                                        @foreach($property->images as $i => $image)
                                            <div class="carousel-item {{ $i === 0 ? 'active' : '' }}">
                                                <img src="{{ asset('storage/'.$image->path) }}" alt="{{ $property->title }}">
                                            </div>
                                        @endforeach
                                    </div>

                                    @if($property->images->count() > 1)
                                        <button class="carousel-control-prev" style="z-index: 5;" type="button" data-bs-target="#carousel{{ $property->id }}" data-bs-slide="prev">
                                            <span class="carousel-control-prev-icon"></span>
                                        </button>
                                        <button class="carousel-control-next" style="z-index: 5;" type="button" data-bs-target="#carousel{{ $property->id }}" data-bs-slide="next">
                                            <span class="carousel-control-next-icon"></span>
                                        </button>
                                    @endif
                                </div>
                            @else
                                <img src="{{ asset('images/ebuy_1.png') }}" alt="No image">
                            @endif

                            <div class="floating-tag">
                                {{ $property->operation->name ?? '' }}
                            </div>
                        </div>

                        <div class="p-4 pb-3 flex-grow-1">
                            <h5 class="fw-bold mb-2">
                                <a href="{{ route('property', $property->id) }}" class="stretched-link text-decoration-none text-dark">
                                    {{ $property->title }}
                                </a>
                            </h5>

                            <div class="price-tag">
                                ${{ number_format($property->price, 0) }}
                                @if(($property->operation->name ?? '') === 'Renta')
                                    <span class="price-period">{{auto_trans('/Mes')}}</span>
                                @endif
                            </div>

                            <div class="property-type mb-2">
                                {{ $property->type->name ?? 'Property' }}
                            </div>

                            <p class="text-muted small mb-0">
                                <i class="fa fa-map-marker-alt me-1"></i>
                                {{ optional($property->address)->city->cityname ?? '' }},
                                {{ optional($property->address)->state->statename ?? '' }}
                            </p>
                        </div>

                        <div class="amenity-row" style="z-index: 5; position: relative;">
                            @if(getAttribute($property->attributes, 'Camas'))
                                <div class="amenity-box">
                                    <i class="fa fa-bed"></i>
                                    {{ getAttribute($property->attributes, 'Camas') }} {{ auto_trans('Camas') }}
                                </div>
                            @endif

                            @if(getAttribute($property->attributes, 'Baños'))
                                <div class="amenity-box">
                                    <i class="fa fa-bath"></i>
                                    {{ getAttribute($property->attributes, 'Baños') }} {{ auto_trans('Baños') }}
                                </div>
                            @endif

                            @if(getAttribute($property->attributes, 'M²'))
                                <div class="amenity-box">
                                    <i class="fa fa-ruler-combined"></i>
                                    {{ getAttribute($property->attributes, 'M²') }} {{ auto_trans('m²') }}
                                </div>
                            @endif
                        </div>

                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-5">
                    <i class="fa fa-search fa-3x text-muted mb-3"></i>
                    <h4>{{auto_trans('No se encontraron propiedades')}}</h4>
                </div>
            @endforelse
        </div>

        @if ($properties->hasPages())
            <div class="pagination-wrapper text-center mt-5">
                <ul class="pagination-modern">
                    <li class="{{ $properties->onFirstPage() ? 'disabled' : '' }}">
                        @if (!$properties->onFirstPage())
                            <a href="{{ $properties->previousPageUrl() }}">&laquo;</a>
                        @else
                            <span>&laquo;</span>
                        @endif
                    </li>

                    @foreach ($properties->getUrlRange(1, $properties->lastPage()) as $page => $url)
                        <li class="{{ $page == $properties->currentPage() ? 'active' : '' }}">
                            @if ($page == $properties->currentPage())
                                <span>{{ $page }}</span>
                            @else
                                <a href="{{ $url }}">{{ $page }}</a>
                            @endif
                        </li>
                    @endforeach

                    <li class="{{ $properties->hasMorePages() ? '' : 'disabled' }}">
                        @if ($properties->hasMorePages())
                            <a href="{{ $properties->nextPageUrl() }}">&raquo;</a>
                        @else
                            <span>&raquo;</span>
                        @endif
                    </li>
                </ul>
            </div>
        @endif

    </div>
</div>
