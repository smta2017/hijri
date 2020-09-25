<!doctype html>
<html>
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>{{$title}}</title>

<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
 

<meta name="description" content="{{$desk}}">
<meta name="keywords" content="{{$keywords}}">
<meta name="author" content="CHEIKH EL MOCTAR Mohamed Yehdhih">

<meta property="og:title" content="{{$page_title}}">
<meta property="og:site_name" content="@lang('main.hijri_calendar')">
<meta property="og:description" content="{{$desk}}">


<meta name="twitter:card" content="{{$title}}">
<meta name="twitter:site" content="@ @lang('main.hijri_calendar')">
<meta name="twitter:title" content="{{$page_title}}">
<meta name="twitter:description" content="{{$desk}}">


    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">


    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <link rel="icon" type="image/png" href="/assets/images/logo.png">
    <link rel="apple-touch-icon" type="image/png" href="/assets/images/logo.png" />
    <link rel="stylesheet" href="/assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/css/all.min.css">
    <link rel="stylesheet" href="/assets/css/datatables.min.css">
    <link rel="stylesheet" href="/assets/css/jquery_modal.min.css" />
    <link rel="stylesheet" href="/assets/css/jquery.magnify.css" />
    <link rel="stylesheet" href="/assets/css/@lang('main.cssfile')">

</head>

<body>
    <div id="app">
        <nav class="navbar navbar-expand-lg navbar-light ">
            <a class="navbar-brand" href="">
                <img src="./assets/images/logo.png" alt="">
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item active">
                        <a class="nav-link active" href="/">@lang('navbar.hijri')</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link " href="/gorgian">@lang('navbar.gorgian')</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="page2.html">@lang('navbar.convert')</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="page4.html">@lang('navbar.months')</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="page5.html">@lang('navbar.ramadan')</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="page6.html">@lang('navbar.chance')</a>
                    </li>
                </ul>
                <form class="form-inline my-2 my-lg-0 lastpart5841">
                    <select name="" id="">
                        <option value="">Select Year</option>
                        <option value="2020">2020</option>
                        <option value="2019">2019</option>
                        <option value="2018">2018</option>
                        <option value="2017">2017</option>
                    </select>
                    <a href="#" class="link_settings">
                        <i class="fas fa-cog"></i>
                    </a>
                </form>
                <br>
                <a href="/changelocale?locale=en">en</a> 
                <a href="/changelocale?locale=ar">ar</a>
            </div>
        </nav>


        <section class="big_section">
            <div class="container">
                <div class="row">


                    <div class="col-md-2">
                        <div class="advertising">
                            <div class="advertising_small_title">
                                <h5>advertising</h5>
                            </div>
                            <h4>Hello, Scarlett!</h4>
                            <p>
                                Welcome Home! The air quality is good & fresh you can go out today.
                            </p>
                            <div class="content_div1">
                                <img src="./assets/images/arara.png" alt="">
                                <span class="arara">+25</span>
                                <span>° C</span>
                            </div>
                            <div class="content_div2">
                                <img src="./assets/images/Path.png" alt="">
                                <span>Fuzzy cloudy</span>
                            </div>
                        </div> <!-- end div advertising -->
                    </div> <!-- end col 2 -->

                    @yield('content')

                    <div class="col-md-2">
                        <div class="time_wather">
                            <div class="top_part">
                                <div class="icon">
                                    <i class="fas fa-bars"></i>
                                </div>
                                <h3><span>8:48 </span> AM</h3>
                                <h5> <img src="./assets/images/Shape.png" alt=""> Now is almost Sunny </h5>
                            </div>
                            <div class="bottom_part">
                                <div class="icon">
                                    <i class="fas fa-times"></i>
                                </div>
                                <h3>Unsleash the freelance super power</h3>
                                <h5>Unlimited task, premium features and much more.</h5>
                                <img src="./assets/images/person-bust.png" alt="">
                                <div class="cta">
                                    <a href="#">
                                        <img src="./assets/images/CTA.png" alt="">
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div> <!-- end col 2 -->


                </div>
            </div>
        </section>


        <!-- js --->
        <script src="./assets/js/jquery.min.js"></script>
        <script src="./assets/js/popper.min.js"></script>
        <script src="./assets/js/bootstrap.min.js"></script>
        <script src="./assets/js/all.min.js"></script>
        <!--  myjs -->
        <script src="./assets/js/myjs.js"></script>

        @yield('scripts')
    </div>
</body>

</html>