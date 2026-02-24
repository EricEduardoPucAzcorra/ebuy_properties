<button 
    class="favorite-btn {{ $absolute ?? true ? 'absolute-position' : '' }}" 
    data-property-id="{{ $propertyId }}"
    title="{{auto_trans('Agregar a favoritos')}}"
    aria-label="{{auto_trans('Agregar a favoritos')}}">
    <i class="far fa-heart"></i>
</button>
