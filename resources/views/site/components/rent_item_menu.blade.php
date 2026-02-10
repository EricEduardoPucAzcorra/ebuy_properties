@php
    use App\Models\TypeOperation;
    use App\Models\TypePropetie;

    $type_operationrent = TypeOperation::where('name','Renta')->first();
    $property_typehouse = TypePropetie::where('name','Casa')->first();
    $property_typedep = TypePropetie::where('name','Departamento')->first();

@endphp
<div class="ebuy-menu-item">
    <a href="javascript:void(0)" class="ebuy-link-top">
        {{ auto_trans('Rentar') }}
        <i class="fa fa-chevron-down ms-1 icon-arrow-nav"></i>
    </a>

    <div class="ebuy-mega-wrapper">
        <div class="mega-card">
            <div class="mega-sidebar">
                <div class="sidebar-item active" data-target="pane-fixed-house-rent">
                    <div class="sidebar-icon"><i class="bi bi-houses"></i></div>
                    <div class="sidebar-text">
                        <span class="title">{{auto_trans('Casas')}}</span>
                        <span class="subtitle">{{ auto_trans('Zonas populares')}}</span>
                    </div>
                    <i class="fa fa-angle-right arrow"></i>
                </div>
                <div class="sidebar-item" data-target="pane-fixed-dep-rent">
                    <div class="sidebar-icon"><i class="bi bi-building"></i></div>
                    <div class="sidebar-text">
                        <span class="title">{{auto_trans('Departamentos')}}</span>
                        <span class="subtitle">{{ auto_trans('Zonas populares')}}</span>
                    </div>
                    <i class="fa fa-angle-right arrow"></i>
                </div>
                <div class="sidebar-item" data-target="pane-fixed-sell">
                    <div class="sidebar-icon"><i class="bi bi-tags"></i></div>
                    <div class="sidebar-text">
                        <span class="title">{{auto_trans('Vender')}}</span>
                        <span class="subtitle">{{ auto_trans('Publica ahora')}}</span>
                    </div>
                    <i class="fa fa-angle-right arrow"></i>
                </div>
            </div>

            <div class="mega-content">

                <div class="mega-pane active" id="pane-fixed-house-rent">
                    <div class="mega-grid-layout">
                        <div class="mega-col">
                            <span class="col-label">{{ auto_trans('Casas en') }} {{ $currentState->statename ?? auto_trans('tu zona') }}</span>
                            <ul class="mega-list-styled">
                                @forelse($featuredCities as $city)
                                    <li>
                                        <a href="{{ route('properties', array_merge(['operation' => $type_operationrent->id, 'type'=> $property_typehouse->id, 'location_type'=>$city->type, 'location_id'=>$city->id])) }}">
                                            <i class="bi bi-geo-alt me-1 text-muted"></i> {{ $city->cityname }}
                                        </a>
                                    </li>
                                @empty
                                    <li><a href="javascript:void(0)">Explorar ciudades</a></li>
                                @endforelse
                            </ul>
                        </div>
                        <div class="mega-col">
                            <span class="col-label">Estados destacados</span>
                            <ul class="mega-list-styled">
                                @foreach($featuredStates as $st)
                                    <li>
                                        <a href="{{ route('properties', array_merge(['operation' => $type_operationrent->id, 'type'=> $property_typehouse->id, 'location_type'=>'state', 'location_id'=>$st->id])) }}">
                                            <strong>{{ $st->statename }}</strong>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="mega-pane" id="pane-fixed-dep-rent">
                    <div class="mega-grid-layout">
                        <div class="mega-col">
                            <span class="col-label">{{ auto_trans('Departamentos en') }} {{ $currentState->statename ?? auto_trans('tu zona') }}</span>
                            <ul class="mega-list-styled">
                                @forelse($featuredCities as $city)
                                    <li>
                                        <a href="{{ route('properties', array_merge(['operation' => $type_operationrent->id, 'type'=> $property_typedep->id, 'location_type'=>$city->type, 'location_id'=>$city->id])) }}">
                                            <i class="bi bi-geo-alt me-1 text-muted"></i> {{ $city->cityname }}
                                        </a>
                                    </li>
                                @empty
                                    <li><a href="javascript:void(0)">Explorar ciudades</a></li>
                                @endforelse
                            </ul>
                        </div>

                        <div class="mega-col">
                            <span class="col-label">Estados destacados</span>
                            <ul class="mega-list-styled">
                                @foreach($featuredStates as $st)
                                    <li>
                                        <a href="{{ route('properties', array_merge(['operation' => $type_operationrent->id, 'type'=> $property_typedep->id, 'location_type'=>'state', 'location_id'=>$st->id])) }}">
                                            <strong>{{ $st->statename }}</strong>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>

                {{-- PANEL VENDER (Sin 'active') --}}
                <div class="mega-pane" id="pane-fixed-sell">
                    <div class="mega-grid-layout">
                        <div class="mega-col">
                            <span class="col-label">¿Quieres vender?</span>
                            <p class="text-muted small">Llega a miles de compradores en las zonas más pobladas.</p>
                            <a href="{{ route('login') }}" class="btn-ebuy-solid mt-2">Publicar Propiedad</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mega-feature">
                <div class="feature-card">
                    <h6>Compramos tu casa</h6>
                    <p>Evaluamos tu zona rápidamente.</p>
                    <a href="#" class="btn-feature">Saber más</a>
                </div>
            </div>
        </div>
    </div>
</div>
