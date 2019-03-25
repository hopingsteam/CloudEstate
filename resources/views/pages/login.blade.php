@extends('layouts.app')

@section('content')
    <!-- Start property Area -->
    <section class="property-area section-gap relative" id="property">
        <div class="container">
            <div class="row d-flex justify-content-center">
                <div class="col-md-10 header-text">
                    <h1>Login</h1>
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
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif
                    <div class="single-property">
                        <form class="form-signin" action="{{ route('loginForm') }}" method="POST">
                            @csrf
                            <label for="inputEmail" class="sr-only">Email address</label>
                            <input type="email" id="inputEmail" class="form-control" placeholder="Email address" name="inputEmail" required="" autofocus=""> <br>
                            <label for="inputPassword" name="inputPassword" class="sr-only">Password</label>
                            <input type="password" id="inputPassword" class="form-control" placeholder="Password" name="inputPassword" required=""> <br>
                            <button class="btn btn-lg btn-primary btn-block" type="submit">Sign in</button>
                        </form>
                    </div>
                </div>
            </div>
            <br><br>
            <div class="row d-flex justify-content-center">
                <div class="col-md-6">
                    <div class="single-property">
                        <a href="/register" class="btn btn-lg btn-success btn-block" role="button">Register now</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- End property Area -->

@endsection
