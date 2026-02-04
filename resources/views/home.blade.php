@extends('layouts.app')

@section('content')
    @if(auth()->user()->hasRoleName('Admin'))
        @include('home.index_admin')
    @elseif (auth()->user()->hasRoleName('Owner'))
        @include('home.index_owner')
    @else

    @endif
@endsection
