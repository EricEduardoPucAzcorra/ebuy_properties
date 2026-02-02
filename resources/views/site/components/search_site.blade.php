<div class="container search-wrapper">
    <div class="search-card shadow-lg">
        <form class="row g-2 align-items-center" method="GET" action="{{ route('properties.search') }}">

            <div class="col-lg-2 col-md-6">
                <select class="form-select search-input search-select" name="operation">
                    @foreach ($type_operations as $operation)
                        <option value="{{ $operation->id }}">{{ $operation->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-lg-2 col-md-6">
                <select class="form-select search-input search-select" name="type">
                    @foreach ($type_properties as $type)
                        <option value="{{ $type->id }}">{{ $type->name }}</option>)
                    @endforeach
                </select>
            </div>

            <div class="col-lg-6 col-md-12 position-relative">
                <input
                    type="text"
                    name="q"
                    id="location-search"
                    class="form-control search-input search-main"
                    placeholder="Buscar por ciudad, estado o palabra clave…"
                    autocomplete="off">

                <input type="hidden" name="location_type" id="location_type">
                <input type="hidden" name="location_id" id="location_id">

                <div id="location-results" class="autocomplete d-none"></div>
            </div>

            <div class="col-lg-2 col-md-12 d-grid">
                <button type="submit" class="btn btn-primary btn-search">
                    Buscar
                </button>
            </div>

        </form>
    </div>
</div>

<style>
.search-main {
    height: 56px;
    font-size: 15px;
    padding-left: 18px;
}

.search-select {
    height: 56px;
    font-size: 14px;
}

.btn-search {
    height: 56px;
    font-weight: 600;
}

/* AUTOCOMPLETE */
.autocomplete {
    position: absolute;
    top: calc(100% + 6px);
    left: 0;
    right: 0;
    background: #fff;
    border-radius: 14px;
    box-shadow: 0 18px 45px rgba(0,0,0,.18);
    max-height: 320px;
    overflow-y: auto;
    z-index: 1000;
}

/* ITEM */
.autocomplete-item {
    display: flex;
    gap: 12px;
    padding: 14px 18px;
    cursor: pointer;
    transition: background .15s ease;
}

.autocomplete-item:hover {
    background: #f5f7fa;
}

.autocomplete-icon {
    font-size: 18px;
}

.autocomplete-text {
    display: flex;
    flex-direction: column;
}

.autocomplete-title {
    font-size: 14px;
    font-weight: 600;
}

.autocomplete-subtitle {
    font-size: 12px;
    color: #6c757d;
}
</style>

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

    // console.log(params.toString());

    // return;

    window.location.href = `/propiedades?${params.toString()}`;
});

document.addEventListener('click', e => {
    if (!e.target.closest('.position-relative')) {
        results.classList.add('d-none');
    }
});
</script>

