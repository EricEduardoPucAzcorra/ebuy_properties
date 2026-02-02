@extends('welcome')

@section('content')
<div class="container py-4">

    <h4 class="mb-4">
        Resultados encontrados: {{ $properties->total() }}
    </h4>

    <div class="row g-4">
        @forelse ($properties as $property)
            <div class="col-md-4">
                <div class="card shadow-sm h-100">
                    <img src="{{ $property->image_url ?? 'https://via.placeholder.com/400x250' }}"
                         class="card-img-top">

                    <div class="card-body">
                        <h6 class="fw-bold">{{ $property->title }}</h6>
                        <p class="text-muted mb-1">
                            {{ $property->city->cityname }},
                            {{ $property->city->state->statename }}
                        </p>

                        <strong class="text-primary">
                            ${{ number_format($property->price, 2) }}
                        </strong>
                    </div>
                </div>
            </div>
        @empty
            <p>No se encontraron resultados.</p>
        @endforelse
    </div>

    <div class="mt-4">
        {{ $properties->links() }}
    </div>

</div>
@endsection
