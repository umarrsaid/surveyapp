<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>IFField | Input Soal Survey</title>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta content="width=device-width, initial-scale=1" name="viewport" />
        <meta content="International Flavor & Fragrances" name="description" />
        <meta content="" name="author" />
        <meta name="csrf-token" content="{{ csrf_token() }}">
        
        @include('partials.head_links')
        <link href="{{URL::asset('assets/pages/css/ion.rangeSlider.min.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{URL::asset('css/bootstrap-datepicker.min.css')}}" rel="stylesheet" type="text/css" />
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
			<div class="page-wrapper-row">
				<div class="page-wrapper-top">
					<div class="page-header">
						<div class="page-header-top">
							<div class="container-fluid text-center">
										<img src="{{asset('assets/layout/img/header-iffield.png')}}" width="5%" alt="logo" class="logo-default" style="margin-top:5px;">
							</div>
						</div>

						<div class="page-header-menu">
						</div>
					</div>
				</div>
			</div>
            <div class="page-wrapper-row full-height">
                <div class="page-wrapper-middle">
                    <div class="page-container">
                        <div class="page-content-wrapper">
							<div class="page-content">
								<div class="container-fluid">
									<div class="page-content-inner">
										<div class="row">
											<div class="col-md-12">
												<div class="portlet box blue">
                                                    <div class="portlet-title">
                                                        <div class="caption"></div>
                                                    </div>
                                                    <div class="portlet-body form">
                                                        <form role="form">
                                                            <input type="hidden" name="survey_id" value="{{$id}}">
                                                            <div class="form-body">
                                                                 <span id="soal"></span>
                                                            </div>
                                                            <div class="form-actions text-center">
                                                                <span id="prev"></span>
                                                                <span id="next"></span>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
											</div>
											<div class="col-md-3"></div>
										</div>
									</div>
								</div>
							</div>
                        </div>
                    </div>
                </div>
            </div>
            @include('partials.footer')
        </div>
        @include('partials.bottom_scripts')
        <script src="{{URL::asset('assets/pages/scripts/ion.rangeSlider.min.js')}}" type="text/javascript"></script>
        <script src="{{URL::asset('js/bootstrap-datepicker.min.js')}}" type="text/javascript"></script>
        <script>
            var imageloading_path = "{{asset('assets/images/loading.gif')}}";
            var imageendsurvey = "{{asset('assets/images/end-survey.png')}}";
            var datasoal = {!! json_encode($pilihan) !!};
            var dataresponden = {!! json_encode($responden) !!};
            var array_key = {!! json_encode($array_key) !!};
            </script>
        <script src="{{asset('js/loadingoverlay.min.js')}}"></script>
        <script src="{{asset('js/main.js')}}"></script>
        <script src="{{asset('js/input-web.js')}}"></script>
    </body>
</html>