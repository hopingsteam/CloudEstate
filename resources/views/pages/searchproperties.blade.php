@extends('layouts.app')

@section('content')
    <!-- start banner Area -->
    <section class="banner-area relative" id="home">
        <div class="overlay overlay-bg"></div>
        <div class="container">
            <div class="row d-flex text-center align-items-center justify-content-center">
                <div class="about-content col-lg-12">
                    <p class="text-white link-nav"><a href="/">Home </a>
                        <span class="lnr lnr-arrow-right"></span> <a href="/properties">{{ $title }}</a></p>
                    <h1 class="text-white">{{ $title }}</h1>
                </div>
            </div>
        </div>
    </section>
    <!-- End banner Area -->

    <!-- Start property Area -->
    <section class="property-area section-gap relative" id="property">
        <div class="container">
            <div class="row d-flex justify-content-center">
                <div class="col-md-10 header-text">
                    <p>We found X results that match your search.</p>
                </div>
            </div>
            @if (session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif
            <div class="row">
                @foreach($properties as $propertyKey => $property)
                    <div class="col-lg-4">
                        <div class="single-property">
                            <div class="images">
                                <img class="img-fluid mx-auto d-block" src="{{ asset("img/s1.jpg") }}" alt="">
                                @if((int) $property['toRent'] == 1)
                                    <span>To Rent</span>
                                @else
                                    <span2>For Sale</span2>
                                @endif
                            </div>

                            <div class="desc">
                                <div class="top d-flex justify-content-between">
                                    <h4><a href="#">{{ $property['description'] }}</a></h4>
                                    <h4>£{{ $property['price'] }}</h4>
                                </div>
                                <div class="large">
                                    <div class="d-flex justify-content-center">
                                        <p>Bedrooms: {{ $property['bedrooms'] }} </p>
                                        <p> &nbsp; &nbsp; &nbsp;</p>
                                        <p>Bathrooms: {{ $property['bathrooms'] }}</p>
                                    </div>
                                    <div class="d-flex justify-content-center">
                                        <p>Location: {{ $property['location'] }}</p>
                                        <p> &nbsp; &nbsp; &nbsp;</p>
                                        <p>Area: {{ $property['area'] }}sqm</p>
                                    </div>
                                </div>
                                <div class="bottom d-flex justify-content-center">
                                    <a href="/viewproperty/{{ $propertyKey }}" class="btn-sm btn-info">View Property</a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    <!-- End property Area -->
@endsection
