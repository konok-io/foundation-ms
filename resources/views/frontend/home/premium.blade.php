@extends('frontend.components.header')

@section('content')
    @include('frontend.components.hero')
    @include('frontend.components.about')
    @include('frontend.components.impact')
    @include('frontend.components.services')
    @include('frontend.components.events')
    @include('frontend.components.notices')
    @include('frontend.components.activities')
    @include('frontend.components.donation')
    @include('frontend.components.gallery')
    @include('frontend.components.footer')
@endsection
