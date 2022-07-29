<div class="page-wrapper-row">
    <div class="page-wrapper-top">
        <div class="page-header">
            <div class="page-header-top">
                <div class="container-fluid">

                    <div class="page-logo">
                        <a href="#">
                            <img src="{{asset('assets/pages/img/survey-logo.png')}}" alt="logo" class="logo-default" width="25%">
                        </a>
                    </div>
                    <a href="javascript:;" class="menu-toggler"></a>
                    <div class="top-menu">
                        <ul class="nav navbar-nav pull-right">
                            <li class="dropdown dropdown-user dropdown-dark">
                                <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
                                    <i class="fa fa-user-circle fa-fw"></i>
                                    <span class="username username-hide-mobile"> &nbsp; {{\Auth::user()->nama_lengkap}}</span>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-default">
                                    <li>
                                        <a href="/api/auth/destroy"><i class="fa fa-sign-out fa-fw"></i> Log Out </a>
                                    </li>
                                    @role('superuser')
                                    <li class="divider"> </li>
                                    <li>
                                        <a href="{{url('/log-viewer')}}"><i class="fa fa-clipboard fa-fw"></i> Log Aplikasi</a>
                                    </li>
                                    @endrole
                                    {{-- <li class="divider"> </li>
                                    <li>
                                        <a href="#"><i class="fa fa-lock fa-fw"></i> Ubah Password </a>
                                    </li> --}}
                                </ul>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="page-header-menu">
                <div class="container-fluid">
                    <div class="hor-menu  ">
                        <ul class="nav navbar-nav">
                            <li aria-haspopup="true" class="@stack('active_datamaster') menu-dropdown classic-menu-dropdown">
                                <a href="javascript:;"><i class="fa fa-gears"></i> Setting<span class="arrow"></span>
                                </a>
                                <ul class="dropdown-menu pull-left">
                                    <li aria-haspopup="true" class=" @stack('active_interviewer')">
                                        <a href="{{route('page_interviewer')}}" class="nav-link show-loading"><i class="fa fa-users"></i> Nama Interviewer </a>
                                    </li>
                                    <li aria-haspopup="true" class=" @stack('active_devices')">
                                        <a href="{{route('page_devices')}}" class="nav-link show-loading"><i class="fa fa-android"></i> IMEI </a>
                                    </li>
                                    @role(['superuser','admin'])   
                                    <li aria-haspopup="true" class=" @stack('active_user')">
                                        <a href="{{route('page_user')}}" class="nav-link show-loading"><i class="fa fa-users"></i> List Admin</a>
                                    </li>
                                    @endrole
                                </ul>
                            </li>
                            <li aria-haspopup="true" class="@stack('active_datasurvey') menu-dropdown mega-menu-dropdown">
                                <a href="javascript:;"><i class="fa fa-clipboard"></i> Questionnaire<span class="arrow"></span>
                                </a>
                                <ul class="dropdown-menu pull-left">
                                    <li aria-haspopup="true" class=" @stack('active_survey')">
                                        <a href="{{route('page_survey')}}" class="nav-link show-loading"><i class="fa fa-chevron-right"></i> Project </a>
                                    </li>
                                    <!-- <li aria-haspopup="true" class=" @stack('active_survey2')">
                                        <a href="{{route('page_survey')}}" class="nav-link show-loading"><i class="fa fa-chevron-right"></i> Template Kuisioner </a>
                                    </li> -->
                                    <li aria-haspopup="true" class=" @stack('active_survey_reus')">
                                        <a href="{{route('page_survey_reus')}}" class="nav-link show-loading"><i class="fa fa-chevron-right"></i> Template Questionnaire </a>
                                    </li>
                                    <li aria-haspopup="true" class=" @stack('active_pertanyaan')">
                                        <a href="{{route('page_pertanyaan')}}" class="nav-link show-loading "><i class="fa fa-chevron-right"></i> Template Pertanyaan </a>
                                    </li>
                                </ul>
                            </li>
                            <li aria-haspopup="true" class=" @stack('active_jawaban')">
                                <a href="{{route('page_jawaban')}}" class="nav-link show-loading"><i class="fa fa-comments"></i> Response </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
