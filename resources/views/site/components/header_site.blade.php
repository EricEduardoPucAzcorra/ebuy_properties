<div class="ebuy-hero position-relative overflow-hidden">
    <!-- Carrusel de fondo -->
    <div class="ebuy-hero-background">
        <div class="owl-carousel header-carousel ebuy-hero-carousel">
            @php
                use App\Models\PropertyImage;
                use Illuminate\Support\Facades\Storage;
                
                $heroImages = PropertyImage::where('is_main', true)
                    ->with('property')
                    ->inRandomOrder()
                    ->limit(5)
                    ->get();
                
                $defaultImages = [
                    'https://images.unsplash.com/photo-1570129477492-45c003edd2be?q=80&w=1170&auto=format&fit=crop',
                    'https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?q=80&w=1175&auto=format&fit=crop',
                    'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?q=80&w=1170&auto=format&fit=crop',
                ];
                
                if($heroImages->isEmpty()) {
                    $heroImages = collect($defaultImages);
                }
            @endphp

            @foreach($heroImages as $image)
            <div class="owl-carousel-item">
                <div class="ebuy-hero-slide">
                    @php
                        if(is_object($image) && isset($image->path)) {
                            $imageUrl = Storage::url($image->path);
                        } else {
                            $imageUrl = $image; 
                        }
                    @endphp
                    <img class="img-fluid" src="{{ $imageUrl }}" alt="Propiedad">
                    <div class="ebuy-hero-overlay"></div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Contenido -->
    <div class="container position-relative z-index-1">
        <div class="row align-items-center">
            <div class="col-lg-7 col-xl-6 text-center text-lg-start py-4 py-md-5">
                <h1 class="ebuy-hero-title animated fadeIn mb-3 mb-md-4">
                    {{ __('site.title_welcome')}}
                </h1>

                <p class="ebuy-hero-subtitle animated fadeIn mb-4">
                    {{ __('site.subtitle_welcome') }}
                </p>

                <div class="d-flex flex-column flex-sm-row gap-3 justify-content-center justify-content-lg-start animated fadeIn">
                    <a href="{{ route('properties') }}" class="btn btn-primary btn-lg px-4 px-md-5 py-2 py-md-3">
                        <i class="fa fa-search me-2"></i>
                        {{ auto_trans('Explorar') }}
                    </a>
                    <a href="{{ route('register') }}" class="btn btn-outline-light btn-lg px-4 px-md-5 py-2 py-md-3">
                        <i class="fa fa-plus-circle me-2"></i>
                        {{ auto_trans('Publicar') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>