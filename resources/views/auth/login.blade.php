<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>IFField | Login</title>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta content="width=device-width, initial-scale=1" name="viewport" />
        <meta content="Preview page of Metronic Admin Theme #3 for " name="description" />
        <meta content="" name="author" />
        <link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css" />
        <link href="{{asset('/assets/login/assets/global/plugins/font-awesome/css/font-awesome.min.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{asset('/assets/login/assets/global/plugins/simple-line-icons/simple-line-icons.min.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{asset('/assets/login/assets/global/plugins/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{asset('/assets/login/assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{asset('/assets/login/assets/global/plugins/select2/css/select2.min.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{asset('/assets/login/assets/global/plugins/select2/css/select2-bootstrap.min.css')}}" rel="stylesheet" type="text/css" />

        <link href="{{asset('/assets/login/assets/global/css/components.min.css')}}" rel="stylesheet" id="style_components" type="text/css" />
        <link href="{{asset('/assets/login/assets/global/css/plugins.min.css')}}" rel="stylesheet" type="text/css" />

        <link href="{{asset('/assets/login/assets/pages/css/login-5.min.css')}}" rel="stylesheet" type="text/css" />
        <link rel="shortcut icon" href="favicon.png" /> </head>
<style>
.user-login-5 .login-logo.login-6 {
    top: 25%;
    left: 18%;
    height: 23%;
}
.user-login-5 .login-container>.login-content {
    margin-top: 40%;
}
.user-login-5 .login-container>.login-content>.login-form {
    margin-top: 50px;
    color: #a4aab2;
    font-size: 13px;
}
</style>
    <body class=" login">
        <div class="user-login-5">
            <div class="row bs-reset">
                <div class="col-md-6 login-container bs-reset">
                    <img class="login-logo login-6" src="{{asset('/assets/pages/img/survey-logo.png')}}" />
                    <div class="login-content text-center">
                        <h4>Selamat Datang. Silakan login terlebih dahulu</h4>
                        <form action="javascript:;" class="login-form" id="login-form" method="post">
                            <div class="alert alert-danger display-hide">
                                <button class="close" data-close="alert"></button>
                                <span> Masukan nama dan password. </span>
                            </div>
                            <div class="row">
                                <div class="col-xs-6">
                                    <input class="form-control form-control-solid placeholder-no-fix form-group" type="text" autocomplete="off" placeholder="Nama" id="nama" name="nama" required/> </div>
                                <div class="col-xs-6">
                                    <input class="form-control form-control-solid placeholder-no-fix form-group" type="password" autocomplete="off" placeholder="Password" id="password" name="password" required="required"/> </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-4">
                                    <label class="rememberme mt-checkbox mt-checkbox-outline">
                                        <input type="checkbox" name="remember" value="1" /> Remember me
                                        <span></span>
                                    </label>
                                </div>
                                <div class="col-sm-8 text-right">
                                    {{-- <div class="forgot-password">
                                        <a href="javascript:;" id="forget-password" class="forget-password">Forgot Password?</a>
                                    </div> --}}
                                    <button id="btn-login" class="btn blue" type="submit">Sign In</button>
                                </div>
                        </div>
                        </form>
                       {{--  <form class="forget-form" action="javascript:;" method="post">
                            <h3>Forgot Password ?</h3>
                            <p> Enter your e-mail address below to reset your password. </p>
                            <div class="form-group">
                                <input class="form-control placeholder-no-fix" type="text" autocomplete="off" placeholder="Email" name="email" /> </div>
                            <div class="form-actions">
                                <button type="button" id="back-btn" class="btn blue btn-outline">Back</button>
                                <button type="submit" class="btn blue uppercase pull-right">Submit</button>
                            </div>
                        </form> --}}
                    </div>
                    <div class="login-footer">
                        <div class="row bs-reset">
                            <div class="col-xs-12 bs-reset">
                                <div class="login-copyright text-right">
                                    <p>2022 © Survey App.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 bs-reset">
                    <div class="login-bg"> </div>
                </div>
            </div>
        </div>
        <script src="{{URL::asset('assets/global/plugins/jquery.min.js')}}" type="text/javascript"></script>

        {{-- <script src="{{asset('/assets/login/assets/global/plugins/jquery.min.js')}}" type="text/javascript"></script> --}}
        <script src="{{asset('/assets/login/assets/global/plugins/bootstrap/js/bootstrap.min.js')}}" type="text/javascript"></script>
        <script src="{{asset('/assets/login/assets/global/plugins/js.cookie.min.js')}}" type="text/javascript"></script>
        <script src="{{asset('/assets/login/assets/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js')}}" type="text/javascript"></script>
        <script src="{{asset('/assets/login/assets/global/plugins/jquery.blockui.min.js')}}" type="text/javascript"></script>
        <script src="{{asset('/assets/login/assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js')}}" type="text/javascript"></script>

        <script src="{{asset('/assets/login/assets/global/plugins/jquery-validation/js/jquery.validate.min.js')}}" type="text/javascript"></script>
        <script src="{{asset('/assets/login/assets/global/plugins/jquery-validation/js/additional-methods.min.js')}}" type="text/javascript"></script>
        <script src="{{asset('/assets/login/assets/global/plugins/select2/js/select2.full.min.js')}}" type="text/javascript"></script>
        <script src="{{asset('/assets/login/assets/global/plugins/backstretch/jquery.backstretch.min.js')}}" type="text/javascript"></script>

        <script src="{{asset('/assets/login/assets/global/scripts/app.min.js')}}" type="text/javascript"></script>

        <script src="{{asset('/assets/login/assets/pages/scripts/login-5.min.js')}}" type="text/javascript"></script>
        <script src="{{asset('js/sweetalert.min.js')}}" type="text/javascript"></script>
        <script>
            var imageloading_path = "{{asset('assets/images/loading.gif')}}";
        </script>
        <script src="{{asset('js/loadingoverlay.min.js')}}"></script>
        <script src="{{asset('js/main.js')}}"></script>

    <script>
        $('#login-form').on('submit',function(e) {
            if( ($('#nama').val() != "") && ($('#password').val() != "") ){
                showLoading();
                e.preventDefault();
                $.ajaxSetup({
                      headers: {
                        // 'accept': 'application/json',
                        // 'Content-Type': 'application/json'
                    }
                });
                $.ajax({
                    url: '/api/auth/login',
                    type: 'POST',
                    dataType:'json',     
                    data: {nama:$('#nama').val(),password:$('#password').val(),type:'web'},       
                    success: function(res, textStatus, xhr) {
                        window.location.href = "/interviewer";
                    },
                    error: function(xhr, textStatus, errorThrown){
                        // console.log(xhr.status);
                        if (xhr.status == 401) {
                            swal({
                              title: "Gagal!",
                              text: "Nama atau Password Salah!",
                              icon: "error",
                              button: "Ok!",
                            });
                        }
                        hideLoading();
                    }
                });
            }
        })
    </script>
    </body>

</html>