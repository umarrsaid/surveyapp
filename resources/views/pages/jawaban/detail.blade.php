@extends('template')

@push('title','Devices')
@push('main_title','Devices')
@push('sub_main_title','Setting')
@push('active_jawaban','active')

@section('content')
<div class="page-content">
    <div class="container-fluid">
        <div class="page-content-inner">
            <div class="row">
                <div class="col-md-12">
                    <div class="portlet light ">
                        <div class="portlet-title">
                            <!-- <div class="caption">
                                <i class="fa fa-table biru"></i>
                                <span class="caption-subject ">Title </span>
                                <span class="caption-helper">sub title</span>
                            </div> -->
                        </div>
                        <div class="portlet-body">
                          
                          <div class="panel panel-default">
                            <div class="panel-body">
                              1. Lorem ipsum dolor sit amet, consectetur adipisicing elit. Minus neque eligendi facere et optio, molestias architecto cumque ea, doloremque itaque, fugiat laborum, autem voluptates incidunt! Reiciendis explicabo id harum quam?
                            </div>
                             <ul class="list-group">
                              <li class="list-group-item">Vestibulum at eros</li>
                            </ul>
                          </div>

                         
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
    
</script>
@endpush