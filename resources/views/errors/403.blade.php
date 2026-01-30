@extends('layouts.app')

@section('title', 'Acceso denegado')

@section('content')
<div class="container text-center mt-5">
    <h1 class="display-4 text-danger">{{__('errors.403.code')}}</h1>
    <h3>{{__('errors.403.title')}}</h3>

    <p class="text-muted mt-3">
        {{__('errors.403.contact_admin')}}
    </p>
</div>
@endsection
