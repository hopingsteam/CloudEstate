@extends('layouts.app')

@section('content')
    <!-- Start property Area -->
    <section class="property-area section-gap relative" id="property">
        <div class="container">
            <div class="row d-flex justify-content-center">
                <div class="col-md-10 header-text">
                    <h1>Register</h1>
                </div>
            </div>
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
                    <div class="single-property">
                        <form class="form-signin" action="{{ route('registerForm') }}" method="POST">
                            @csrf
                            <label for="firstName" class="sr-only">First Name</label>
                            <input type="text" id="firstName" class="form-control {{ $errors->has('firstName') ? ' is-invalid' : '' }}" placeholder="Frist Name" name="firstName" required="" autofocus=""> <br>
                            <label for="lastName" class="sr-only">Last Name</label>
                            <input type="text" id="lastName" class="form-control {{ $errors->has('lastName') ? ' is-invalid' : '' }}" placeholder="Last Name" name="lastName" required="" autofocus=""> <br>
                            <label for="inputEmail" class="sr-only">Email address</label>
                            <input type="email" id="inputEmail" class="form-control {{ $errors->has('inputEmail') ? ' is-invalid' : '' }}" placeholder="Email address" name="inputEmail" required="" autofocus=""> <br>
                            <label for="inputPassword" name="inputPassword" class="sr-only">Password</label>
                            <input type="password" id="inputPassword" class="form-control {{ $errors->has('inputPassword') ? ' is-invalid' : '' }}" placeholder="Password" name="inputPassword" required=""> <br>
                            <label for="inputPassword" name="inputPassword2" class="sr-only">Confirm Password</label>
                            <input type="password" id="inputPassword2" class="form-control {{ $errors->has('inputPassword2') ? ' is-invalid' : '' }}" placeholder="ConfirmPassword" name="inputPassword2" required=""> <br> <br>
                            <button class="btn btn-lg btn-success btn-block" type="submit">Register</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- End property Area -->

@endsection
