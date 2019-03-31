@extends('layouts.app')

@section('content')
    <!-- start banner Area -->
    <section class="banner-area relative" id="home">
        <div class="overlay overlay-bg"></div>
        <div class="container">
            <div class="row d-flex text-center align-items-center justify-content-center">
                <div class="about-content col-lg-12">
                    <p class="text-white link-nav"><a href="index.html">Home </a>
                        <span class="lnr lnr-arrow-right"></span> <a href="contact.html">{{$title}}</a></p>
                    <h1 class="text-white">{{$title}}</h1>
                </div>
            </div>
        </div>
    </section>
    <!-- End banner Area -->

    <!-- Start contact-page Area -->
    <section class="contact-page-area section-gap">
        <div class="container">
            <div class="row d-flex justify-content-center">
                <div class="col-md-6">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif
                    <div class="single-property">
                        <form class="form-addProperty" action="{{ route('addPropertyForm') }}" method="POST">
                            @csrf
                            Description:
                            <label for="inputDescription" class="sr-only">Description</label>
                            <input type="text" id="inputDescription" class="form-control" placeholder="Description" name="inputDescription" required="" autofocus=""> <br>

                            Number of bathrooms:
                            <label for="inputBathrooms" class="sr-only">Number of bathrooms</label>
                            <input type="number" id="inputBathrooms" class="form-control" placeholder="Number of bathrooms" name="inputBathrooms" required="" autofocus=""> <br>

                            Number of bedrooms:
                            <label for="inputBedrooms" class="sr-only">Number of bedrooms</label>
                            <input type="number" id="inputBedrooms" class="form-control" placeholder="Number of bedrooms" name="inputBedrooms" required="" autofocus=""> <br>

                            Location:
                            <label for="inputLocation" class="sr-only">Location</label>
                            @php
                                array_shift($locations)
                            @endphp
                            <select name="inputLocation" class="app-select form-control" required>
                                <option data-display="Choose locations">Choose locations</option>
                                @foreach($locations as $locationKey => $location)
                                    <option value="{{ $locationKey + 1 }}">{{ $location["name"] }}</option>
                                @endforeach
                            </select> <br>

                            Price:
                            <label for="inputPrice" class="sr-only">Price</label>
                            <input type="number" id="inputPrice" class="form-control" placeholder="Price" name="inputPrice" required="" autofocus=""> <br>

                            Area:
                            <label for="inputArea" class="sr-only">Description</label>
                            <input type="number" id="inputArea" class="form-control" placeholder="Area" name="inputArea" required="" autofocus=""> <br>

                            <input type="checkbox" name="inputToRent" class="onoffswitch3-checkbox" id="myonoffswitch3" checked>
                            <label class="onoffswitch3-label" for="myonoffswitch3">
                                <span class="onoffswitch3-inner">
                                    <span class="onoffswitch3-active">
                                        <span class="onoffswitch3-switch">Sell</span>
                                        <span class="lnr lnr-arrow-right"></span>
                                    </span>
                                    <span class="onoffswitch3-inactive">
                                        <span class="lnr lnr-arrow-left"></span>
                                        <span class="onoffswitch3-switch">Rent</span>
                                    </span>
                                </span>
                            </label>

                            &nbsp;

                            <button class="primary-btn btn-block" type="submit">Add Property</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- End contact-page Area -->
@endsection
