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

        <!-- INformacion de propiedades -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="results-info p-3 rounded-3">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h4 class="mb-0 fw-bold">
                                <i class="fas fa-search me-2"></i>
                                {{ $properties->total() }} {{ auto_trans('propiedades encontradas') }}
                            </h4>
                        </div>
                        <div class="col-md-4">
                            <div class="active-filters d-flex flex-wrap gap-2 justify-content-md-end">
                                @php
                                    $activeFilters = [];

                                    if(request('operation')) {
                                        $operation = $type_operations->firstWhere('id', request('operation'));
                                        if($operation) $activeFilters[] = $operation->name;
                                    }

                                    if(request('type')) {
                                        $type = $type_properties->firstWhere('id', request('type'));
                                        if($type) $activeFilters[] = $type->name;
                                    }

                                    $locationDisplayed = false;

                                    if(request('location_type') && request('location_id') && !$locationDisplayed) {
                                        $locationType = request('location_type');
                                        $locationId = request('location_id');

                                        if($locationType === 'state') {
                                            $estado = \App\Models\State::with('country')->find($locationId);
                                            if($estado) {
                                                $activeFilters[] = $estado->statename . ', ' . $estado->country->countryname;
                                                $locationDisplayed = true;
                                            }
                                        } else {
                                            $ciudad = \App\Models\Citie::with('state.country')->find($locationId);
                                            if($ciudad) {
                                                $activeFilters[] = $ciudad->cityname . ', ' . $ciudad->state->statename . ', ' . $ciudad->state->country->countryname;
                                                $locationDisplayed = true;
                                            }
                                        }
                                    }

                                    if(request('q') && !$locationDisplayed) {
                                        $activeFilters[] = request('q');
                                    }

                                    if(request('price_min') || request('price_max')) {
                                        $priceRange = [];
                                        if(request('price_min')) $priceRange[] = '$' . number_format(request('price_min'), 0);
                                        if(request('price_max')) $priceRange[] = '$' . number_format(request('price_max'), 0);
                                        $activeFilters[] = implode(' - ', $priceRange);
                                    }

                                    if(request('bedrooms')) {
                                        $activeFilters[] = request('bedrooms') . ' ' . auto_trans('hab');
                                    }

                                    if(request('bathrooms')) {
                                        $activeFilters[] = request('bathrooms') . ' ' . auto_trans('baños');
                                    }

                                    if(request('area_min') || request('area_max')) {
                                        $areaRange = [];
                                        if(request('area_min')) $areaRange[] = request('area_min') . 'm²';
                                        if(request('area_max')) $areaRange[] = request('area_max') . 'm²';
                                        $activeFilters[] = implode(' - ', $areaRange);
                                    }
                                @endphp

                                @if(count($activeFilters) > 0)
                                    @foreach($activeFilters as $filter)
                                        <span class="badge">
                                            {{ $filter }}
                                        </span>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-4 align-items-center">
            <div class="col-lg-6 col-md-8">
                <h3 class="fw-bold mb-0">
                    {{auto_trans('Propiedades cuidadosamente seleccionadas')}}
                </h3>
                <p class="text-muted mb-0 mt-1">
                    {{auto_trans('Para quienes buscan confort, ubicación y estilo.')}}
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
                        <i class="fas fa-th me-2"></i>{{auto_trans('Todo')}}
                    </a>

                    @foreach ($type_operations as $type_operation)
                        <a data-post-link
                            href="{{ route('properties', array_merge($otherParams, ['operation' => $type_operation->id])) }}"
                            class="nav-link flex-shrink-0 {{ $currentOperation == $type_operation->id ? 'active' : '' }}">
                            @if($type_operation->name === 'Venta')
                                <i class="fas fa-tag me-2"></i>
                            @elseif($type_operation->name === 'Renta')
                                <i class="fas fa-key me-2"></i>
                            @else
                                <i class="fas fa-home me-2"></i>
                            @endif
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

        <!-- Secciones de ayuda -->
        <div class="row mt-5">
            <div class="col-12">
                <div class="tips-section bg-gradient-primary text-white p-4 rounded-3">
                    <h4 class="mb-4">
                        <i class="fas fa-lightbulb me-2"></i>
                        {{ auto_trans('Consejos para tu búsqueda') }}
                    </h4>
                    <div class="row g-3">
                        <div class="col-md-6 col-lg-3">
                            <div class="tip-card bg-white bg-opacity-10 p-3 rounded-2 h-100">
                                <div class="tip-icon mb-2">
                                    <i class="fas fa-map-marked-alt fa-2x"></i>
                                </div>
                                <h6 class="fw-bold">{{ auto_trans('Ubicación Estratégica') }}</h6>
                                <p class="small mb-0">{{ auto_trans('Considera la cercanía a tu trabajo, escuelas y servicios básicos.') }}</p>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-3">
                            <div class="tip-card bg-white bg-opacity-10 p-3 rounded-2 h-100">
                                <div class="tip-icon mb-2">
                                    <i class="fas fa-calculator fa-2x"></i>
                                </div>
                                <h6 class="fw-bold">{{ auto_trans('Presupuesto Realista') }}</h6>
                                <p class="small mb-0">{{ auto_trans('Incluye gastos adicionales como mantenimiento, impuestos y servicios.') }}</p>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-3">
                            <div class="tip-card bg-white bg-opacity-10 p-3 rounded-2 h-100">
                                <div class="tip-icon mb-2">
                                    <i class="fas fa-home fa-2x"></i>
                                </div>
                                <h6 class="fw-bold">{{ auto_trans('Visita las Propiedades') }}</h6>
                                <p class="small mb-0">{{ auto_trans('Siempre visita personalmente antes de tomar una decisión importante.') }}</p>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-3">
                            <div class="tip-card bg-white bg-opacity-10 p-3 rounded-2 h-100">
                                <div class="tip-icon mb-2">
                                    <i class="fas fa-file-contract fa-2x"></i>
                                </div>
                                <h6 class="fw-bold">{{ auto_trans('Revisa Documentación') }}</h6>
                                <p class="small mb-0">{{ auto_trans('Verifica que todos los documentos legales estén en orden.') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contacto de asesoria-->
        <div class="row mt-4">
            <div class="col-12">
                <div class="contact-advisory bg-light p-4 rounded-3 text-center">
                    <h5 class="mb-3">
                        <i class="fas fa-headset me-2 text-primary"></i>
                        {{ auto_trans('¿Necesitas ayuda profesional?') }}
                    </h5>
                    <p class="text-muted mb-4">
                        {{ auto_trans('Nuestros expertos están disponibles para ayudarte a encontrar la propiedad perfecta según tus necesidades.') }}
                    </p>
                    <div class="d-flex flex-column flex-md-row gap-3 justify-content-center">
                        <a href="tel:+1234567890" class="btn btn-primary">
                            <i class="fas fa-phone me-2"></i>
                            {{ auto_trans('Llamar Ahora') }}
                        </a>
                        <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#contactModal">
                            <i class="fas fa-envelope me-2"></i>
                            {{ auto_trans('Solicitar Asesoría') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal de Contacto -->
        <div class="modal fade" id="contactModal" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ auto_trans('Solicitar Asesoría Gratuita') }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <form>
                            <div class="mb-3">
                                <label class="form-label">{{ auto_trans('Nombre') }}</label>
                                <input type="text" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">{{ auto_trans('Teléfono') }}</label>
                                <input type="tel" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">{{ auto_trans('Email') }}</label>
                                <input type="email" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">{{ auto_trans('Mensaje') }}</label>
                                <textarea class="form-control" rows="3" placeholder="{{ auto_trans('Cuéntanos qué tipo de propiedad buscas...') }}"></textarea>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ auto_trans('Cancelar') }}</button>
                        <button type="button" class="btn btn-primary">{{ auto_trans('Enviar Solicitud') }}</button>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
