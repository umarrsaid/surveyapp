@extends('template')

@push('title','Question')
@push('main_title','Question')
@push('sub_main_title','Setting')

@section('content')
<div class="page-content">
    <div class="container-fluid">
        <div class="page-content-inner">
            <div class="row">
                <div class="col-md-12">

                    <div class="portlet light ">
                        <div class="portlet-title">
                            <div class="caption">
                                <i class="icon-bubble font-green-sharp"></i>
                                <span class="caption-subject font-green-sharp bold uppercase">Input Width Sizing</span>
                            </div>
                            <div class="actions">
                                <div class="btn-group">
                                    <a class="btn green-haze btn-outline btn-circle btn-sm" href="javascript:;" data-toggle="dropdown" data-hover="dropdown" data-close-others="true"> Option
                                        <i class="fa fa-angle-down"></i>
                                    </a>
                                    <ul class="dropdown-menu pull-right">
                                        <li>
                                            <a href="javascript:;"> Add Question</a>
                                        </li>
                                        <li class="divider"> </li>
                                        <li>
                                            <a href="javascript:;"> Rule Question</a>
                                        </li>
                                    </ul>
                                </div>
                            </div> 
                        </div>

                        <div class="portlet-body form">
                            <form role="form" autocomplete="false">

                                <div class="panel panel-default">
                                  <div class="panel-heading">
                                    Pertanyaan 1
                                    <div class="pull-right">
                                        <div class="tools">
                                            <div class="btn-group">
                                                <a class="btn btn-outline btn-sm" href="javascript:;" data-toggle="dropdown" data-hover="dropdown" data-close-others="true"> Option
                                                    <i class="fa fa-angle-down"></i>
                                                </a>
                                                <ul class="dropdown-menu pull-right">
                                                    <li>
                                                        <a href="javascript:;"> Add Question</a>
                                                    </li>
                                                    <li class="divider"> </li>
                                                    <li>
                                                        <a href="javascript:;" id="btn-rule" data-rule="uncek"> Rule Question</a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="panel-body">
                                    <div class="col-md-2">
                                        <div class="form-group ">
                                            <label>Jenis Pertanyaan</label>
                                            <select class="form-control">
                                                <option>Multiple Choice</option>
                                                <option>Multi Select</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div id="input-10" class="col-md-10">
                                        <div class="form-group ">
                                            <label>Circle Input</label>
                                            <input type="text" class="form-control " placeholder="Email Address"> 
                                        </div>
                                         <div class="form-group ">
                                            <label>Circle Input</label>
                                            <input type="text" class="form-control " placeholder="Email Address"> 
                                        </div>
                                    </div>
                                    <div id="input-rule">

                                    </div>

                                </div>
                            </div>

                               {{--  <div class="form-body">
                                    <div class="form-group ">
                                        <label>Circle Input</label>
                                        <input type="text" class="form-control " placeholder="Email Address"> 
                                    </div>
                                </div> --}}

                                <div class="form-actions">
                                    <button type="submit" class="btn blue">Submit</button>
                                    <button type="button" class="btn default">Cancel</button>
                                </div>
                            </form>
                        </div>

                    </div>

                </div>


            </div>
        </div>
    </div>
</div>
{{-- {{ dd(\Session::get('user')[0]->accessToken) }} --}}
@endsection
@push('script')
<script type="text/javascript">
   // $(document).ready(function(){

            $('#btn-rule').on('click',function(){
            var rule = $(this).data("rule");

            if ($(this).data("rule") == 'cek') {
                $('#input-10').removeClass()
                $('#input-10').addClass('col-md-10')
                $('#btn-rule').html("<span>Rule Question</span>");
                $('#input-rule').html('')
                $('#btn-rule').data('rule', 'uncek');

            }else if ($(this).data("rule") == 'uncek') {
                $('#input-10').removeClass()
                $('#input-10').addClass('col-md-5')
                $('#btn-rule').html("<span><i class='fa fa-check'></i>Rule Question</span>");
                $('#btn-rule').data('rule', 'cek');
                $('#input-rule').append(' <div class="col-md-5"> '+
                                           '<div class="form-group "> '+
                                           ' <label>Circle Input</label> '+
                                            '<input type="text" class="form-control " placeholder="Email Address"> '+
                                        '</div> </div>');
            }
            // alert(rule)
            // console.log(d);
             // alert($(this).data("rule"));
        });
   // });

</script>
@endpush