<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
    <!--<![endif]-->
    <head>
        <meta charset="utf-8" />
        <title>Survey | @stack('title')</title>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta content="width=device-width, initial-scale=1" name="viewport" />
        <meta content="International Flavor & Fragrances" name="description" />
        <meta content="" name="author" />
        
        @include('partials.head_links')
        @stack('style')
        <style>
            .btn-circle {
              border: none;
              color: white;
              text-align: center;
              text-decoration: none;
              display: inline-block;
              margin: 4px 2px;
            }
        </style>
    </head>

    <body class="page-container-bg-solid">
        <div class="page-wrapper">
            @include('partials.header')
            <div class="page-wrapper-row full-height">
                <div class="page-wrapper-middle">
                    <div class="page-container">
                        <div class="page-content-wrapper">
                            <div class="page-head">
                                <div class="container-fluid">
                                    <div class="page-title">
                                        <h1><span class="biru"> @stack('main_title')</span> <small>@stack('sub_main_title')</small></h1>
                                    </div>
                                </div>
                            </div>
                            @yield('content')
                        </div>
                    </div>
                </div>
            </div>

            @include('components.modal')
            @include('partials.footer')
        </div>

        @include('partials.bottom_scripts')
        @stack('script')
        <script>
            var imageloading_path = "{{asset('assets/images/loading.gif')}}";
        </script>
        <script src="{{asset('js/loadingoverlay.min.js')}}"></script>
        <script src="{{asset('js/main.js')}}"></script>
    </body>
</html>