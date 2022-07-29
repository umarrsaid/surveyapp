@extends('template')
@push('title','Interviewer')
@push('main_title','Interviewer')
@push('sub_main_title','Setting')
@push('active_interviewer','active')
@push('style')
    <style type="text/css">
        table.dataTable{
            border: none !important;
        }
    </style>
@endpush
@section('content')
<div class="page-content">
    <div class="container-fluid">
        <div class="page-content-inner">
            <div class="row">
                <div class="col-md-12">
                    <div class="portlet light ">
                        <div class="portlet-title">
                            <div class="caption">
                                <i class="fa fa-table biru"></i>
                                <span class="caption-subject ">Title </span>
                                <span class="caption-helper">sub title</span>
                            </div>
                        </div>
                        <div class="portlet-body">
                            <table class="table borderless" id="table_interviewer" >
                                <thead>
                                    <tr>
                                        <th class="w75">No</th>
                                        <th class="w100"><button id="add_modal" class="btn btn-primary btn-xs"><i class="fa fa-plus"></i></button></th>
                                        <th>Username Interviewer</th>
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
@endsection
{{-- @push('script')
    <script>
        const Toast = Swal.mixin({
          toast: true,
          position: 'top-end',
          showConfirmButton: false,
          timer: 3000
        });

        $(document).ready(function(){
            showLoading();
            $('#table_interviewer').DataTable({

                processing    :true,
                serverSide    :true,
                ordering      :false,
                language      :{
                   "decimal"       : "",
                   "emptyTable"    : "<center>Tak ada data yang tersedia pada tabel ini</center>",
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
                ajax: {
                    'url' : "{{ route('tabel_data_interviewer') }}"
                },
                columns:[
                    {data:'DT_RowIndex',searchable: false},
                    {data:'action',searchable:false},
                    {data:'nama'},
                ],
                error : function(){
                    $('#table_interviewer').DataTable().ajax.reload(null ,false);
                }
            });
            hideLoading();

            $('table').removeClass('dataTable');
        });

        /*Scroll*/
        $(document).ready(function(){
            $('.table-scrollable').on('shown.bs.dropdown', function (e) {
                var $table = $(this),
                    $menu = $(e.target).find('.dropdown-menu'),
                    tableOffsetHeight = $table.offset().top + $table.height(),
                    menuOffsetHeight = $menu.offset().top + $menu.outerHeight(true);
                if (menuOffsetHeight > tableOffsetHeight)
                  $table.css("padding-bottom", menuOffsetHeight - tableOffsetHeight);
            });
           
            $('.table-scrollable').on('hide.bs.dropdown', function () {
                $(this).css("padding-bottom", 0);
            })
        });


        $(document).ready(function(){
            $('#add_modal').on('click', function() {
                showLoading();
                $.ajax({
                    url: '{{ route('add_modal_interviewer') }}',
                    type: 'GET',
                    success: function(data){
                        $('#myModal').modal('show');
                        $('.modal-title').html(data.modal_header);
                        $('.modal-body').html(data.modal_body);
                        $('.modal-footer').html(data.modal_footer);
                        $('.modal-dialog').addClass(data.modal_size);
                        
                        $('#nama').keypress(function( e ) {
                            if(e.which === 32) 
                                return false;
                        });
                        
                        hideLoading();
                    },
                    error : function(data){
                        
                    }
                });                
            });

            $('#table_interviewer').on('click','#btn-edit-interviewer',function(){
                showLoading();
                var id = $(this).attr('data-id');
                $.ajax({
                    url: '/edit_modal_interviewer/'+id,
                    type: 'GET',
                    success: function(data){
                        $('#myModal').modal('show');
                        $('.modal-title').html(data.modal_header);
                        $('.modal-body').html(data.modal_body);
                        $('.modal-footer').html(data.modal_footer);
                        $('.modal-dialog').addClass(data.modal_size);
                        
                        $('#nama').keypress(function( e ) {
                            if(e.which === 32) 
                                return false;
                        });

                        hideLoading();
                    },
                    error : function(data){
                        
                    }
                });                
            });
        });

        $('#form').submit(function(e){
            e.preventDefault();

                $.ajaxSetup({
                    'headers' : {
                        'X-CSRF-TOKEN' : $('meta[name=csrf-token]').attr('content'),
                        'Authorization': "Bearer "+$('meta[name=bearer-token]').attr('content')
                    }
                });
                $( "#btn-save" ).prop( "disabled", true );
                status = $('#btn-save').val();

                switch(status){
                    case 'add' :
                        showLoading();
                        $.ajax({
                            type : 'POST',
                            url  : '{{ route("add_interviewer") }}',
                            data : $('#form').serialize(),
                            success : function(data){
                                $('#table_interviewer').DataTable().ajax.reload(null ,false);
                                if(data.status_code == 200){
                                  Toast.fire({
                                    type: 'success',
                                    title: 'Sukses menambah Interviewer'
                                  })
                                }else{
                                  Swal.fire({
                                    type: 'error',
                                    title: "Gagal Menyimpan!",
                                    text: "Gagal menambah Interviewer!",
                                  })
                                }
                                hideLoading();
                                $('#myModal').modal('hide');
                            },
                            error : function(data){
                                $( "#btn-save" ).prop( "disabled", false );
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
                    case 'edit' :
                        showLoading();
                        $.ajax({
                            type : 'POST',
                            url  : '{{ route("edit_interviewer") }}',
                            data : $('#form').serialize(),
                            success : function(data){
                                $('#table_interviewer').DataTable().ajax.reload(null ,false);
                                if(data.status_code == 200){
                                  Toast.fire({
                                    type: 'success',
                                    title: 'Sukses mengubah Interviewer'
                                  })
                                }else{
                                  Swal.fire({
                                    type: 'error',
                                    title: "Gagal Menyimpan!",
                                    text: "Gagal mengubah Interviewer!",
                                  })
                                }
                                hideLoading();
                                $('#myModal').modal('hide');
                            },
                            error : function(data){
                                $( "#btn-save" ).prop( "disabled", false );
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

    $('#table_interviewer').on('click','#btn-delete-interviewer',function(){
        var c = $(this).attr("data-id");
        bootbox.dialog({
            title: '<span class="orange"><i class="fa fa-trash"></i> Hapus</span> Interviewer',
            message: "Anda yakin akan menghapus Interviewer ini ?",
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
                            url: '{{ route('delete_interviewer') }}',
                            data : {id : c},
                            // data: $("#form-edit").serialize(),
                              success: function(data) {
                                $('#table_interviewer').DataTable().ajax.reload(null , false);
                                    if (data.status == 'success') {
                                        Swal.fire({
                                            type: 'success',
                                            title :'Sukses',
                                            text: 'Data Berhasil Dihapus.',
                                        });
                                      hideLoading();
                                    } else {
                                      Swal.fire({
                                        type: 'error',
                                          title :'Gagal',
                                          text: 'Data Gagal Dihapus.',
                                      });
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
    </script>
@endpush --}}