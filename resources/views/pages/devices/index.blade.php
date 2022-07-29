@extends('template')

@push('title','Devices')
@push('main_title','<i class="fa fa-android"></i> Devices')
@push('sub_main_title','Setting')
@push('active_datamaster','active')

@push('active_devices','active')

@section('content')
<div class="page-content">
    <div class="container-fluid">
        <div class="page-content-inner">
            <div class="row">
                <div class="col-md-12">
                    <div class="portlet light ">
                        <div class="portlet-body">
                            @if (session('status'))
                                <div class="alert alert-block alert-success fade in">
                                    <a class="close" data-dismiss="alert" href="#" aria-hidden="true">×</a>
                                    <p></p><h4><i class="fa fa-check-square-o"></i> Berhasil</h4>
                                    {{ session('status') }}
                                    <p></p>
                                </div>
                            @endif
                            @if (count($errors)>0)
                                <div class="alert alert-block alert-danger fade in">
                                    <a class="close" data-dismiss="alert" href="#" aria-hidden="true">×</a>
                                    <h4><i class="fa fa-times"></i> Gagal</h4>
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                            <table class="" id="tabel_data_devices" >
                              <thead>
                                 <tr>
                                    <th>No</th>
                                    <th><a href="javascript:;" id="add_modal"><button class="btn btn-primary btn-xs"><i class="fa fa-plus"></i></button></a></th>
                                    <th style="text-align:center">Nama Devices</th>
                                    <th>Imei</th>
                                    <th>Nama Survey</th>
                                    <th>Keterangan</th>
                                    {{-- <th>Status</th> --}}
                                 </tr>
                              </thead>
                              <tbody>
                              </tbody>
                           </table>
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
    $(document).ready(function(){
        const Toast = Swal.mixin({
          toast: true,
          position: 'top-end',
          showConfirmButton: false,
          timer: 3000
        });
    $(function () {
        showLoading();
        tabel_responden();
    });

        $('#form').submit(function(e){
            e.preventDefault();
            var tipe = $('#btn-save').val();
            showLoading();
            $.ajaxSetup({
                headers: {
                  'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
                }
            })
            switch(tipe) {

              case 'tambah':
                $.ajax({
                    type: 'POST',
                    url: "/api/save-devices",
                    data: $("#form").serialize(),
                    // data: $("#form-pertanyaan").sortable("toArray"),
                    dataType: 'json',
                    success: function(data){
                        // console.log(data);
                        $('#tabel_data_devices').DataTable().ajax.reload(null , false);

                        if(data.status_code == 200){
                          Toast.fire({
                            type: 'success',
                            title: 'Sukses Menambah Device'
                          })
                        }else{
                          Swal.fire({
                            type: 'error',
                            title: "Gagal Menyimpan!",
                            text: "Gagal menambah Devices!",
                          })
                        }
                        hideLoading();
                        $('#myModal').modal('hide');
                    },
                    error:function(data){
                      $('input,textarea,select').on('keydown keypress keyup click change',function(){
                          $(this).parent().removeClass('has-error');                                                       
                          $(this).next('.help-block').hide() 
                      });
                      
                      $('.form-group').removeClass('has-error');
                      var coba = new Array();
                      $.each(data.responseJSON.errors, function(name,value){
                          coba.push(name);
                          $('[name='+name+']').parent().addClass('has-error'); 
                          $('[name='+name+']').next('.help-block').show().text(value); 
                      });
                      $('[name='+coba[0]+']').focus(); 
                      hideLoading();
                    }
                });
            break;

              case 'edit':
              var id = $('#btn-save').data('id');
              $.ajax({
                    type: 'POST',
                    url: "/api/update-devices/"+id,
                    data: $("#form").serialize(),
                    dataType: 'json',
                    success: function(data){
                        $('#tabel_data_devices').DataTable().ajax.reload(null , false);

                        if(data.status_code == 200){
                          Toast.fire({
                            type: 'success',
                            title: 'Sukses Mengedit Device'
                          })
                        }else{
                          Swal.fire({
                            type: 'error',
                            title: "Gagal Menyimpan!",
                            text: 'Gagal menambah Devices!',
                          })
                        }
                        hideLoading();
                        $('#myModal').modal('hide');
                    },
                    error:function(data){
                        $('input,textarea,select').on('keydown keypress keyup click change',function(){
                            $(this).parent().removeClass('has-error');                                                       
                            $(this).next('.help-block').hide() 
                        });
                        
                        $('.form-group').removeClass('has-error');
                        var coba = new Array();
                        $.each(data.responseJSON.errors, function(name,value){
                            coba.push(name);
                            $('[name='+name+']').parent().addClass('has-error'); 
                            $('[name='+name+']').next('.help-block').show().text(value); 
                        });
                        $('[name='+coba[0]+']').focus(); 
                        hideLoading();
                    }
                });
            break;
            }
        });

      $(document).on('click', '#hapus_link', function() {
        var c = $(this).attr("data-id");
        bootbox.dialog({
            title: '<span class="orange"><i class="fa fa-trash"></i> Hapus</span> Survey',
            message: "Anda yakin akan menghapus Survey dari Data ini ?",
            buttons: {
                confirm: {
                    label: '<i class="fa fa-trash"></i> Hapus',
                    className: 'btn btn-default btn-danger',
                    callback: function(){
                        showLoading();
                        $.ajaxSetup({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            }
                        });
                        $.ajax({
                            type: 'post',
                            url: '/api/delete-devices-survey/'+c,
                            // data: $("#form-edit").serialize(),
                              success: function(data) {
                                $('#tabel_data_devices').DataTable().ajax.reload(null , false);
                                   if (data.errors) {
                                      Swal.fire({
                                        type: 'error',
                                          title :'Gagal',
                                          text: 'Data Gagal Dihapus.',
                                      })
                                      hideLoading();
                                    } else {
                                      Toast.fire({
                                        type: 'success',
                                        title: 'Link Survey Berhasil Dihapus.',
                                      })
                                       hideLoading();
                                    }
                              },
                        });
                    }
                },
                cancel: {
                label: '<i class="fa fa-times"></i> Batal',
                className: 'btn btn-default'
            }, 
            }
        });
    });


      $(document).on('click', '#hapus_devices', function() {
        var c = $(this).attr("data-id");
        bootbox.dialog({
            title: '<span class="orange"><i class="fa fa-trash"></i> Hapus</span> Device',
            message: "Anda yakin akan menghapus Device ini ?",
            buttons: {
                confirm: {
                    label: '<i class="fa fa-trash"></i> Hapus',
                    className: 'btn btn-default btn-danger',
                    callback: function(){
                        showLoading();
                        $.ajaxSetup({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            }
                        });
                        $.ajax({
                            type: 'post',
                            url: '/api/delete-devices/'+c,
                            // data: $("#form-edit").serialize(),
                              success: function(data) {
                                $('#tabel_data_devices').DataTable().ajax.reload(null , false);
                                    if (data.errors) {
                                      Swal.fire({
                                        type: 'error',
                                          title :'Gagal',
                                          text: 'Data Gagal Dihapus.',
                                      })
                                      hideLoading();
                                    } else {
                                      Swal.fire({
                                        type: 'success',
                                        title :'Sukses',
                                        text: 'Data Berhasil Dihapus.',
                                      })
                                       hideLoading();
                                    }
                              },
                        });
                    }
                },
                cancel: {
                label: '<i class="fa fa-times"></i> Batal',
                className: 'btn btn-default'
            }, 
            }
        });
    });
       // showLoading();
       // inputAngka('durasi');
    function tabel_responden(){
        data_col = [
                {data:'DT_RowIndex',searchable: false, width: '10'},
                {data:'action',width: '10'},
                {data:'nama',width: '100',class:'dt-center'},
                {data:'imei',width: '100' },
                {data:'survey',searchable: false,width: '40%'},
                {data:'keterangan',width: '100'},
                // {data:'status',searchable: false,width: '100', class:'dt-center'},
        ];

          var tabel_data_devices = $('#tabel_data_devices').DataTable({
           lengthMenu    : [[10,20, 30, 50, -1], [10,20, 30, 50, "All"]],
           // processing    :false,
           // serverSide    :true,
           // ordering      :false,

            processing    :true,
            serverSide    :true,
            scrollX         :true,
            sScrollXInner: '100%',
            bScrollCollapse: true,
            ordering      :false,
           "language"      : {
               "decimal"       : "",
               "emptyTable"    : "Tak ada data yang tersedia pada tabel ini",
               "info"          : "Tampil _START_ s/d _END_ dari _TOTAL_ baris",
               "infoEmpty"     : "Menampilkan 0 sampai 0 dari 0 entri",
               "infoFiltered"  : "(difiler dari total entri _MAX_)",
               "infoPostFix"   : "",
               "thousands"     : ",",
               "lengthMenu"    : "_MENU_ Baris",
               "loadingRecords": "Loading...",
               "processing"    : '<div class="loadingoverlay" style="background-color: rgba(255, 255, 255, 0.8); position: fixed; display: flex; flex-direction: column; align-items: center; justify-content: center; z-index: 2147483647; background-image: url(&quot;http:/assets/images/loading.gif&quot;); background-position: center center; background-repeat: no-repeat; top: 0px; left: 0px; width: 100%; height: 100%; background-size: 100px;"></div>',
               "search"        : "Pencarian :",
               "zeroRecords"   : "Tidak ada record yang cocok ditemukan",
               "paginate"      : {
                   "first"         : "Pertama",
                   "last"          : "Terakhir",
                   "next"          : "Berikutnya",
                   "previous"      : "Sebelumnya"
               }
           },
           ajax:{
                url: "{{ route('tabel_data_devices') }}",
                error: function (jqXHR, textStatus, errorThrown) {
                        tabel_responden();
                        hideLoading();
                        // setTimeout( function () {
                        //     oTable.ajax.reload();
                        // }, 1000 );
                    }
                },
           columns: data_col,
           "initComplete": function(settings, json) {
               hideLoading();
           },
           
           // scrollX: true
       });

      (function () {
            $('.dataTables_scrollBody').on('shown.bs.dropdown', function (e) {
               var $table = $(this),
                  $menu = $(e.target).find('.dropdown-menu'),
                  tableOffsetHeight = $table.offset().top + $table.height(),
                  menuOffsetHeight = $menu.offset().top + $menu.outerHeight(true);
               if (menuOffsetHeight > tableOffsetHeight)
               $table.css("padding-bottom", menuOffsetHeight - tableOffsetHeight);
               
            });
            
            $('.dataTables_scrollBody').on('hide.bs.dropdown', function () {
               $(this).css("padding-bottom", 0);
            })
      })();
    }
    
 

     $('#add_modal').on('click',function(){
         $.ajax({
            type: "GET",
            url: "{{ route("add_modal_devices") }}",
            beforeSend: function(){
                  showLoading();
            },
            success: function(data){
               $('#myModal').modal('show');
               $('.modal-title').html(data.modal_header);
               $('.modal-body').html(data.modal_body);
               $('.modal-footer').html(data.modal_footer);
               $('.modal-dialog').addClass(data.modal_size);
               inputAngka('durasi');
               hideLoading();
            },
            error: function(){
                hideLoading();
            }
         });
      }) // end modal

     $(document).on('click','#edit_devices',function(){
        var id = $(this).data('id');
         $.ajax({
            type: "GET",
            url: '/edit_modal_devices/'+id,
            beforeSend: function(){
                  showLoading();
            },
            success: function(data){
               $('#myModal').modal('show');
               $('.modal-title').html(data.modal_header);
               $('.modal-body').html(data.modal_body);
               $('.modal-footer').html(data.modal_footer);
               $('.modal-dialog').addClass(data.modal_size);
               inputAngka('durasi');
               hideLoading();
            },
            error: function(){
                hideLoading();
            }
         });
      }) // end modal


 });

</script>
@endpush