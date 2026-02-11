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
                        <option value="{{ $operation->id }}">{{ auto_trans($operation->name) }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-lg-2 col-md-6 search-group">
                <select class="form-select search-select" name="type">
                    @foreach ($type_properties as $type)
                        <option value="{{ $type->id }}">{{ auto_trans($type->name) }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-lg-6 col-md-12 position-relative search-group">
                <input
                    type="text"
                    name="q"
                    id="location-search"
                    class="form-control search-input"
                    placeholder="{{ auto_trans('¿Dónde quieres vivir?') }}"
                    autocomplete="off">

                <input type="hidden" name="location_type" id="location_type">
                <input type="hidden" name="location_id" id="location_id">

                <div id="location-results" class="autocomplete d-none"></div>
            </div>

            <div class="col-lg-2 col-md-12 d-grid">
                <button type="submit" class="btn btn-primary btn-search">
                    {{ auto_trans('Buscar') }}
                </button>
            </div>

        </form>
    </div>
</div>

<script>
    const input = document.getElementById('location-search');
    const results = document.getElementById('location-results');

    let controller;

    input.addEventListener('input', async () => {
        const q = input.value.trim();

        if (q.length < 2) {
            results.classList.add('d-none');
            results.innerHTML = '';
            return;
        }

        if (controller) controller.abort();
        controller = new AbortController();

        const res = await fetch(`/location-search?q=${q}`, {
            signal: controller.signal
        });

        const data = await res.json();
        results.innerHTML = '';

        data.forEach(item => {
            console.log(item)
            results.innerHTML += `
                <div class="autocomplete-item"
                    data-type="${item.type}"
                    data-id="${item.id}">
                    ${item.label}
                </div>
            `;
        });

        results.classList.toggle('d-none', data.length === 0);
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
            q: item.innerText.trim()
        });

        window.location.href = `/properties?${params.toString()}`;
    });

    document.addEventListener('click', e => {
        if (!e.target.closest('.position-relative')) {
            results.classList.add('d-none');
        }
    });
</script>

