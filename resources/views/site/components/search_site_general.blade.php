@php
    use App\Models\TypePropetie;
    use App\Models\TypeOperation;

    $type_properties = TypePropetie::all();
    $type_operations = TypeOperation::all();
@endphp


<div class="container search-wrapper">
    <div class="search-card">
        <form class="row g-3 align-items-center search-form" method="GET" action="{{ route('properties') }}">

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
                    placeholder="{{ auto_trans('¿Dónde quieres vivir?') }}"
                    value="{{ request('q') }}"
                    autocomplete="off">

                <input type="hidden" name="location_type" id="location_type" value="{{ request('location_type') }}">
                <input type="hidden" name="location_id" id="location_id" value="{{ request('location_id') }}">

                <div id="location-results" class="autocomplete d-none"></div>
            </div>

            <div class="col-lg-2 col-md-12 d-grid">
                <button type="submit" class="btn btn-primary btn-search">
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

        </form>

        <!-- Filtros Avanzados -->
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
                        <button type="submit" class="btn btn-primary flex-fill">
                            <i class="fas fa-search me-2"></i>{{auto_trans('Aplicar Filtros')}}
                        </button>
                        <a href="{{ route('properties') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-times me-2"></i>{{auto_trans('Limpiar')}}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const input = document.getElementById('location-search');
    const results = document.getElementById('location-results');
    const form = document.querySelector('.search-form');
    let controller;
    let isAutocompleteActive = false;

    input.addEventListener('input', async () => {
        const q = input.value.trim();

        if (q.length < 2) {
            results.classList.add('d-none');
            results.innerHTML = '';
            isAutocompleteActive = false;
            return;
        }

        if (controller) controller.abort();
        controller = new AbortController();

        try {
            const res = await fetch(`/location-search?q=${q}`, {
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

        const params = new URLSearchParams({
            operation,
            type,
            location_type: item.dataset.type,
            location_id: item.dataset.id,
            q: item.textContent.trim()
        });

        window.location.href = `/properties?${params.toString()}`;
    });

    // Permitir búsqueda general con Enter cuando no hay autocomplete activo
    input.addEventListener('keypress', (e) => {
        if (e.key === 'Enter' && !isAutocompleteActive) {
            e.preventDefault();
            form.submit();
        }
    });

    // Ocultar autocomplete al hacer clic fuera
    document.addEventListener('click', e => {
        if (!e.target.closest('.position-relative')) {
            results.classList.add('d-none');
            results.innerHTML = '';
            isAutocompleteActive = false;
        }
    });

    // Toggle filtros avanzados
    const advancedFiltersBtn = document.getElementById('advanced-filters-btn');
    const advancedFilters = document.getElementById('advanced-filters');

    if (advancedFiltersBtn && advancedFilters) {
        advancedFiltersBtn.addEventListener('click', () => {
            const isHidden = advancedFilters.style.display === 'none';
            advancedFilters.style.display = isHidden ? 'block' : 'none';
            
            if (isHidden) {
                advancedFiltersBtn.classList.add('active');
            } else {
                advancedFiltersBtn.classList.remove('active');
            }
        });
    }
</script>
