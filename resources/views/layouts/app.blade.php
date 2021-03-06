<!DOCTYPE html>
<html lang="zxx" class="no-js">

<head>
    <!-- Mobile Specific Meta -->
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Favicon-->
    <link rel="shortcut icon" href="img/fav.png">
    <!-- Author Meta -->
    <meta name="author" content="CloudEstate">
    <!-- Meta Description -->
    <meta name="description" content="">
    <!-- Meta Keyword -->
    <meta name="keywords" content="">
    <!-- meta character set -->
    <meta charset="UTF-8">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Site Title -->
    <title>Cloud Estate - {{$title}}</title>

    <link href="https://fonts.googleapis.com/css?family=Poppins:100,200,400,300,500,600,700" rel="stylesheet">
    <!--
            CSS
            ============================================= -->
    <link rel="stylesheet" href="{{ asset("css/linearicons.css") }}">=
    <link rel="stylesheet" href="{{ asset("css/font-awesome.min.css") }}">
    <link rel="stylesheet" href="{{ asset("css/nice-select.css") }}">
    <link rel="stylesheet" href="{{ asset("css/ion.rangeSlider.css") }}" />
    <link rel="stylesheet" href="{{ asset("css/ion.rangeSlider.skinFlat.css") }}" />
    <link rel="stylesheet" href="{{ asset("css/bootstrap.css") }}">
    <link rel="stylesheet" href="{{ asset("css/owl.carousel.cs") }}s">
    <link rel="stylesheet" href="{{ asset("css/main.css") }}">
</head>

<body>
<!-- Start Header Area -->
<header class="default-header">
    <div class="menutop-wrap">
        <div class="menu-top container">
            <div class="d-flex justify-content-end align-items-center">
                <ul class="list">
                    @if(session()->has('userId'))
                        <li><a href="/add-property">Sell / Rent Property</a></li>
                        <li><a href="/logout">logout</a></li>
                    @else
                        <li><a href="tel:+44 020 7040 5060">+44 020 7040 5060</a></li>
                        <li><a href="/login">login / register</a></li>
                    @endif
                </ul>
            </div>
        </div>
    </div>

    <div class="main-menu">
        <div class="container">
            <div class="row align-items-center justify-content-between d-flex">
                <div id="logo">
                    <a href="/"><img src="{{ asset("img/logo.png") }}" alt="" title="" /></a>
                </div>
                <nav id="nav-menu-container">
                    <ul class="nav-menu">
                        <li><a href="/">Home</a></li>
                        <li><a href="/properties">Properties</a></li>
                        <li><a href="/contact">Contact</a></li>
                    </ul>
                </nav>
                <!--######## #nav-menu-container -->
            </div>
        </div>
    </div>
</header>
<!-- End Header Area -->

@yield('content')

<!-- start footer Area -->
<footer class="footer-area section-gap">
    <div class="container">
        <div class="row">
            <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="single-footer-widget">
                    <h6>About Us</h6>
                    <p>
                        CloudEstate - IN3046 Cloud Computing coursework
                    </p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 col-sm-6">
                <div class="single-footer-widget">
                    <h6>Newsletter</h6>
                    <p>Stay update with our latest</p>
                    <div class="" id="mc_embed_signup">
                        <form target="_blank" novalidate="true" action="https://spondonit.us12.list-manage.com/subscribe/post?u=1462626880ade1ac87bd9c93a&amp;id=92a4423d01"
                              method="get" class="form-inline">
                            <div class="d-flex flex-row">
                                <input class="form-control" name="EMAIL" placeholder="Enter Email" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Enter Email '"
                                       required="" type="email">

                                <button class="click-btn btn btn-default"><i class="lnr lnr-arrow-right" aria-hidden="true"></i></button>
                                <div style="position: absolute; left: -5000px;">
                                    <input name="b_36c4fd991d266f23781ded980_aefe40901a" tabindex="-1" value="" type="text">
                                </div>
                            </div>
                            <div class="info"></div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-lg-2 col-md-6 col-sm-6">
                <div class="single-footer-widget">
                    <h6>Follow Us</h6>
                    <p>Let us be social</p>
                    <div class="footer-social d-flex align-items-center">
                        <a href="#"><i class="fa fa-facebook"></i></a>
                        <a href="#"><i class="fa fa-twitter"></i></a>
                        <a href="#"><i class="fa fa-dribbble"></i></a>
                        <a href="#"><i class="fa fa-behance"></i></a>
                    </div>
                </div>
            </div>
        </div>
        <div class="footer-bottom d-flex justify-content-center align-items-center flex-wrap">
            <p class="footer-text m-0"><!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
                This template is made with <i class="fa fa-heart-o" aria-hidden="true"></i> by <a href="https://colorlib.com" target="_blank">Colorlib</a>
                <!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. --></p>
        </div>
    </div>
</footer>
<!-- End footer Area -->

<script src="{{ asset("js/vendor/jquery-2.2.4.min.js") }}"></script>
<script src="{{ asset("js/vendor/bootstrap.min.js") }}"></script>
<script src="{{ asset("js/jquery.ajaxchimp.min.js") }}"></script>
<script src="{{ asset("js/jquery.nice-select.min.js") }}"></script>
<script src="{{ asset("js/jquery.sticky.js") }}"></script>
<script src="{{ asset("js/ion.rangeSlider.js") }}"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src="{{ asset("js/jquery.magnific-popup.min.js") }}"></script>
<script src="{{ asset("js/owl.carousel.min.js") }}"></script>
<script src="{{ asset("js/main.js") }}"></script>

@yield('customJS')

</body>

</html>