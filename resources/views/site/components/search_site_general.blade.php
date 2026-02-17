@php
    use App\Models\TypePropetie;
    use App\Models\TypeOperation;
    use App\Models\State;
    use App\Models\Citie;

    $type_properties = TypePropetie::all();
    $type_operations = TypeOperation::all();

    $searchValue = request('q');

    if(request('location_type') && request('location_id') && !request('q')) {
        $locationType = request('location_type');
        $locationId = request('location_id');

        if($locationType === 'state') {
            $estado = State::with('country')->find($locationId);
            if($estado) {
                $searchValue = $estado->statename . ', ' . $estado->country->countryname;
            }
        } else {
            $ciudad = Citie::with('state.country')->find($locationId);
            if($ciudad) {
                $searchValue = $ciudad->cityname . ', ' . $ciudad->state->statename . ', ' . $ciudad->state->country->countryname;
            }
        }
    }
@endphp


<div class="container search-wrapper">
    <div class="search-card">
        <form class="row g-3 align-items-center search-form" method="GET" action="{{ route('properties') }}" id="search-form">

            <div class="col-lg-2 col-md-6 search-group">
                <select class="form-select search-select" name="operation">
                    @foreach ($type_operations as $operation)
                        <option value="{{ $operation->id }}" {{ request('operation') == $operation->id ? 'selected' : '' }}>
                            {{ auto_trans($operation->name) }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-lg-2 col-md-6 search-group">
                <select class="form-select search-select" name="type">
                    @foreach ($type_properties as $type)
                        <option value="{{ $type->id }}" {{ request('type') == $type->id ? 'selected' : '' }}>
                            {{ auto_trans($type->name) }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-lg-5 col-md-12 position-relative search-group">
                <input
                    type="text"
                    name="q"
                    id="location-search"
                    class="form-control search-input"
                    placeholder="{{ auto_trans('Buscar por lugar, característica, atributo etc...') }}"
                    value="{{ $searchValue }}"
                    autocomplete="off">

                <input type="hidden" name="location_type" id="location_type" value="{{ request('location_type') }}">
                <input type="hidden" name="location_id" id="location_id" value="{{ request('location_id') }}">

                <div id="location-results" class="autocomplete d-none"></div>
            </div>

            <div class="col-lg-2 col-md-12 d-grid">
                <button type="submit" class="btn btn-primary btn-search" id="main-search-btn">
                    <i class="fas fa-search me-2"></i>
                    {{ auto_trans('Buscar') }}
                </button>
            </div>

            <div class="col-lg-1 col-md-6 search-group">
                <button type="button" class="btn btn-primary btn-search d-flex align-items-center justify-content-center" id="advanced-filters-btn" title="{{ auto_trans('Más filtros') }}">
                    <i class="fas fa-sliders-h"></i>
                    <span class="d-none d-md-inline ms-1">{{auto_trans('Filtros')}}</span>
                </button>
            </div>

            <!-- Filtros Avanzados -->
            <div class="col-12">
                <div class="advanced-filters mt-4" id="advanced-filters" style="display: none;">
                    <div class="row g-3">
                        <div class="col-lg-3 col-md-6">
                            <label class="form-label small text-muted">{{auto_trans('Precio Min')}}</label>
                            <input type="number" name="price_min" class="form-control" placeholder="{{auto_trans('Min')}}" value="{{ request('price_min') }}">
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <label class="form-label small text-muted">{{auto_trans('Precio Max')}}</label>
                            <input type="number" name="price_max" class="form-control" placeholder="{{auto_trans('Max')}}" value="{{ request('price_max') }}">
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <label class="form-label small text-muted">{{auto_trans('Habitaciones')}}</label>
                            <select name="bedrooms" class="form-select">
                                <option value="">{{auto_trans('Todos')}}</option>
                                <option value="1" {{ request('bedrooms') == '1' ? 'selected' : '' }}>1</option>
                                <option value="2" {{ request('bedrooms') == '2' ? 'selected' : '' }}>2</option>
                                <option value="3" {{ request('bedrooms') == '3' ? 'selected' : '' }}>3</option>
                                <option value="4" {{ request('bedrooms') == '4' ? 'selected' : '' }}>4</option>
                                <option value="5+" {{ request('bedrooms') == '5+' ? 'selected' : '' }}>5+</option>
                            </select>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <label class="form-label small text-muted">{{auto_trans('Baños')}}</label>
                            <select name="bathrooms" class="form-select">
                                <option value="">{{auto_trans('Todos')}}</option>
                                <option value="1" {{ request('bathrooms') == '1' ? 'selected' : '' }}>1</option>
                                <option value="2" {{ request('bathrooms') == '2' ? 'selected' : '' }}>2</option>
                                <option value="3" {{ request('bathrooms') == '3' ? 'selected' : '' }}>3</option>
                                <option value="4" {{ request('bathrooms') == '4' ? 'selected' : '' }}>4+</option>
                            </select>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <label class="form-label small text-muted">{{auto_trans('Superficie (m²)')}}</label>
                            <input type="number" name="area_min" class="form-control" placeholder="{{auto_trans('Mínimo')}}" value="{{ request('area_min') }}">
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <label class="form-label small text-muted">&nbsp;</label>
                            <input type="number" name="area_max" class="form-control" placeholder="{{auto_trans('Máximo')}}" value="{{ request('area_max') }}">
                        </div>
                        <div class="col-lg-6 col-md-12">
                            <label class="form-label small text-muted">&nbsp;</label>
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary flex-fill" id="apply-filters-btn">
                                    <i class="fas fa-search me-2"></i>{{auto_trans('Aplicar Filtros')}}
                                </button>
                                <a href="{{ route('properties') }}" class="btn btn-outline-secondary" id="clear-filters-btn">
                                    <i class="fas fa-times me-2"></i>{{auto_trans('Limpiar')}}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>


<script>
    const input = document.getElementById('location-search');
    const results = document.getElementById('location-results');
    const form = document.getElementById('search-form');
    let controller;
    let isAutocompleteActive = false;

    // Elementos para el control de filtros
    const advancedFiltersBtn = document.getElementById('advanced-filters-btn');
    const advancedFilters = document.getElementById('advanced-filters');
    const mainSearchBtn = document.getElementById('main-search-btn');
    const applyFiltersBtn = document.getElementById('apply-filters-btn');
    const clearFiltersBtn = document.getElementById('clear-filters-btn');
    const locationType = document.getElementById('location_type');
    const locationId = document.getElementById('location_id');

    input.addEventListener('input', async () => {
        const q = input.value.trim();

        if (q === '') {
            locationType.value = '';
            locationId.value = '';

            results.classList.add('d-none');
            results.innerHTML = '';
            isAutocompleteActive = false;
            return;
        }

        if (q.length < 2) {
            results.classList.add('d-none');
            results.innerHTML = '';
            isAutocompleteActive = false;
            return;
        }

        if (controller) controller.abort();
        controller = new AbortController();

        try {
            const res = await fetch(`/location-search?q=${encodeURIComponent(q)}`, {
                signal: controller.signal
            });
            const data = await res.json();

            results.innerHTML = '';
            data.forEach(item => {
                const div = document.createElement('div');
                div.className = 'autocomplete-item';
                div.dataset.type = item.type;
                div.dataset.id = item.id;
                div.textContent = item.label;
                results.appendChild(div);
            });

            results.classList.toggle('d-none', data.length === 0);
            isAutocompleteActive = data.length > 0;
        } catch (err) {
            if (err.name === 'AbortError') {
                return;
            }
            console.error("Error en la búsqueda:", err);
            isAutocompleteActive = false;
        }
    });

    results.addEventListener('click', e => {
        const item = e.target.closest('.autocomplete-item');
        if (!item) return;

        const operation = document.querySelector('[name="operation"]').value;
        const type = document.querySelector('[name="type"]').value;

        locationType.value = item.dataset.type;
        locationId.value = item.dataset.id;

        const params = new URLSearchParams({
            operation,
            type,
            location_type: item.dataset.type,
            location_id: item.dataset.id,
            q: item.textContent.trim()
        });

        window.location.href = `/properties?${params.toString()}`;
    });

    document.addEventListener('click', e => {
        if (!e.target.closest('.position-relative')) {
            results.classList.add('d-none');
            results.innerHTML = '';
            isAutocompleteActive = false;
        }
    });

    if (advancedFiltersBtn && advancedFilters) {
        advancedFiltersBtn.addEventListener('click', () => {
            const isHidden = advancedFilters.style.display === 'none';
            advancedFilters.style.display = isHidden ? 'block' : 'none';

            if (isHidden) {
                advancedFiltersBtn.classList.add('active');
                if (mainSearchBtn) {
                    mainSearchBtn.style.display = 'none';
                }
            } else {
                advancedFiltersBtn.classList.remove('active');
                if (mainSearchBtn) {
                    mainSearchBtn.style.display = 'block';
                }
            }
        });
    }

    // Función para recolectar todos los filtros (ya no es necesaria pero la mantenemos por si acaso)
    function collectAllFilters() {
        const formData = new FormData(form);
        const params = new URLSearchParams();

        for (let [key, value] of formData.entries()) {
            if (value && value.trim() !== '') {
                params.append(key, value);
            }
        }

        return params;
    }

    if (clearFiltersBtn) {
        clearFiltersBtn.addEventListener('click', (e) => {
            e.preventDefault();
            locationType.value = '';
            locationId.value = '';
            input.value = '';
            window.location.href = '{{ route('properties') }}';
        });
    }

    // Modificar el comportamiento del Enter en el input
    input.addEventListener('keypress', (e) => {
        if (e.key === 'Enter') {
            e.preventDefault();

            const isAdvancedFiltersVisible = advancedFilters.style.display !== 'none' && advancedFilters.style.display !== '';

            if (!isAutocompleteActive) {
                form.submit();
            }
        }
    });

    form.addEventListener('submit', (e) => {
        const isAdvancedFiltersVisible = advancedFilters.style.display !== 'none' && advancedFilters.style.display !== '';

        // Si los filtros avanzados están visibles y no hay autocompletado activo, permitir el envío normal
        if (isAdvancedFiltersVisible && !isAutocompleteActive) {
            // El formulario se enviará normalmente con todos los campos
            return true;
        }
    });

    document.addEventListener('DOMContentLoaded', () => {
        const isAdvancedFiltersVisible = advancedFilters.style.display !== 'none' && advancedFilters.style.display !== '';

        if (isAdvancedFiltersVisible) {
            if (mainSearchBtn) {
                mainSearchBtn.style.display = 'none';
            }
            if (advancedFiltersBtn) {
                advancedFiltersBtn.classList.add('active');
            }
        }

        // Inicializar el estado del botón "Aplicar Filtros" (ahora es tipo submit)
        if (applyFiltersBtn) {
            // Como ahora es type="submit", no necesitamos el event listener
            // Pero podemos asegurarnos de que tenga el texto correcto
            applyFiltersBtn.innerHTML = '<i class="fas fa-search me-2"></i>' + '{{auto_trans("Aplicar Filtros")}}';
        }
    });

</script>
