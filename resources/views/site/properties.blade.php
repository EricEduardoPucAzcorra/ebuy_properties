@extends('welcome')

@section('content')

<div class="container-fluid p-0">
    <img
        src="https://images.unsplash.com/photo-1512917774080-9991f1c4c750?q=80&w=1600"
        class="w-100 object-fit-cover"
        style="height: 650px"
        alt="Hero">
</div>

@include('site.components.search_site_general')

@include('site.components.list_properties')

@endsection
