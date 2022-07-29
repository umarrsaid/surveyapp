@extends('template')

@push('title','User')
@push('main_title','<i class="fa fa-users"></i> User')
@push('sub_main_title','Setting')
@push('active_user','active')
@push('style')
    <link href="{{asset('assets/global/plugins/select2/css/select2.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('assets/global/plugins/select2/css/select2-bootstrap.min.css')}}" rel="stylesheet" type="text/css" />
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
                        <div class="portlet-body">
                            <table id="table_user" class="table borderless">
                                <thead>
                                    <tr>
                                        <th  class="w25" style="position: center"><button type="button" class="btn btn-primary btn-xs tambahUser" data-id="0" data-toggle="modal"><i class="fa fa-plus"></i></button></th>
                                        <th class="w50">No</th>
                                        <th class="w100">Nama</th>
                                        <th class="w100">Nama Lengkap</th>
                                        <th class="w100">Role</th>
                                        <th class="w100">Status</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('script')
    <script src="{{asset('assets/global/plugins/select2/js/select2.full.min.js')}}" type="text/javascript"></script>
    <script>
        const Toast = Swal.mixin({
          toast: true,
          position: 'top-end',
          showConfirmButton: false,
          timer: 3000
        });

        /*Table User*/
        $(document).ready(function(){
            showLoading();
            $('#table_user').DataTable({
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
                    'url' : "{{ route('table_user') }}",
                },
                columns:[
                    {data:'action',searchable:false},
                    {data:'DT_RowIndex',searchable: false},
                    {data:'nama'},
                    {data:'nama_lengkap'},
                    {data:'display_name'},
                    {data:'status'},
                ],
                error : function(){
                    $('#table_user').DataTable().ajax.reload(null ,false);
                }
            });
            $('table').removeClass('dataTable');
            hideLoading();
        });
        /*Scroll*/
        $(document).ready(function() {
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
        /*Modal Tambah User*/
        $(document).ready(function(){
            $('.tambahUser').on('click',function(){
                showLoading();
                $.ajax({
                    type    : 'GET',
                    url     : '{{ route("modal_tambah_user") }}',
                    success : function(data){
                        $('#myModal').modal('show');
                        $('.modal-title').html(data.modal_header);
                        $('.modal-body').html(data.modal_body);
                        $('.modal-footer').html(data.modal_footer);
                        $('.modal-dialog').addClass(data.modal_size);
                        hideLoading();
                    },
                    error   : function(data){
                        hideLoading();
                        swal({
                            title : 'Error',
                            text  : 'Gagal Membuka Modal',
                            type  : 'error',
                            showConfirmButton : false,
                            timer : 1500,
                        }).catch(swal.noop)
                    }
                });
            })
        });
        $('#table_user').on('click','#btn-edit-user',function(){
            showLoading();
            var id = $(this).attr('data-id');
            $.ajax({
                type: "GET",
                url: "{{ route('modal_edit_user') }}",
                data: {
                    id: id
                },
                success: function(data){
                    $('#myModal').modal('show');
                    $('.modal-title').html(data.modal_header);
                    $('.modal-body').html(data.modal_body);
                    $('.modal-footer').html(data.modal_footer);
                    $('.modal-dialog').addClass(data.modal_size);
                    $('#nama').val(data.user.nama_lengkap);
                    $('#username').val(data.user.nama);
                    $('#role').val(data.user.role_id);
                    hideLoading();                    
                },
                error: function(){
                    hideLoading();
                    swal({
                        title: 'Error',
                        text: 'Gagal membuka modal',
                        type: 'error',
                        showConfirmButton: false,
                        timer:1500
                    }).catch(swal.noop);
                }
            });
        });
        /*Select2*/
        $(document).ready(function(){
            $('#myModal').on('shown.bs.modal', function () {
                // id tag select
                $('.select2').select2({
                    dropdownParent: $('#myModal')
                });
            });
        });
        /*Form Submit*/
        $('#form').submit(function(e){
            e.preventDefault();
            $.ajaxSetup({
                'headers' : {
                    'X-CSRF-TOKEN' : $('meta[name=csrf-token]').attr('content'),
                }
            });
            showLoading();
            $( "#btn-save" ).prop( "disabled", true );
            
            status = $('#btn-save').val();

            switch(status){
                case 'tambahUser' :
                    showLoading();
                    $.ajax({
                        type : 'POST',
                        url  : '{{ route("form_tambah_user") }}',
                        data : $('#form').serialize(),
                        success : function(data){
                            if(data.success){
                                $('#myModal').modal('hide');
                                $('#table_user').DataTable().ajax.reload(null ,false);
                                hideLoading();
                                Toast.fire({
                                    type: 'success',
                                    title :'Sukses',
                                    text: data.message,
                                });
                            }else{
                                $('#btn-save').prop('disabled',false);
                                hideLoading();
                                Toast.fire({
                                    type: 'error',
                                    title :'Error',
                                    text: data.message,
                                });
                            }
                        },
                        error : function(data){
                            $( "#btn-save" ).prop( "disabled", false );
                            $('input,textarea,select').on('keydown keypress keyup click change',function(){
                                $(this).parent().removeClass('has-error');                                                       
                                $(this).next('.help-block').hide() 
                                $(this).next().next('.help-block').hide() 
                            });
                            
                            $('.form-group').removeClass('has-error');
                            var coba = new Array();
                            $.each(data.responseJSON.errors, function(name,value){
                                coba.push(name);
                                $('[name='+name+']').parent().addClass('has-error'); 
                                $('[name='+name+']').next('.help-block').show().text(value); 
                                $('[name='+name+']').next().next('.help-block').show().text(value); 
                            });
                            $('[name='+coba[0]+']').focus(); 
                            hideLoading();
                        }
                    });
                break;
                case 'editUser' :
                        setTimeout(() => {
                            $.ajax({
                                type : "POST",
                                url  : "{{route('form_edit_user')}}",
                                data : $('#form').serialize(),                                    
                                success : function(data){
                                    if(data.success){
                                        $('#myModal').modal('hide');
                                        $('#table_user').DataTable().ajax.reload();
                                        hideLoading();
                                        Toast.fire({
                                            type: 'success',
                                            title :'Sukses',
                                            text: data.message,
                                        });
                                    }else{
                                        hideLoading();
                                        Toast.fire({
                                            type: 'error',
                                            title :'Error',
                                            text: data.message,
                                        });
                                    }
                                },
                                error: function(data){
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
                        }, 800);
                break;
            }
        });
        /*Ubah Status*/
        $(document).ready(function(){
            $('#table_user').on('click','#sta_user',function(){
                var id = $(this).attr('data-id');
                var status = $(this).attr('status');
                console.log(id);
                console.log(status);
                if (status == 1) {
                    $title = 'Nonaktifkan User ?';
                }else {
                    $title = 'Aktifkan User ?';
                }
                swal({
                    title: $title,
                    text: "",
                    type: 'warning',
                    showCancelButton: true,
                    cancelButtonColor: '#d33',
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'Ya',
                    cancelButtonText: 'Tidak'
                }).then((result) => {
                    if(result.value){
                        $.ajaxSetup({
                            'headers' : {
                                'X-CSRF-TOKEN' : $('meta[name=csrf-token]').attr('content'),
                                'Authorization': "Bearer "+$('meta[name=bearer-token]').attr('content')
                            }
                        })
                        $.ajax({
                            url : "{{ route('form_status_user') }}",
                            type : "POST",
                            data : {
                                id:id,
                                status:status
                            },
                            dataType : "JSON",
                            success : function(data) {
    
                                if(data.success){
                                    $('#table_user').DataTable().ajax.reload(null ,false);
                                    Toast.fire({
                                        type: 'success',
                                        title :'Sukses',
                                        text: data.message,
                                    });
                                }else{                            
                                    swal({
                                        title: 'Error',
                                        text: data.message,
                                        type: 'error',
                                        showConfirmButton: false,
                                        timer:1500
                                    }).catch(swal.noop);
                                }
                                
                            },
                            error : function (data) {
                                swal({
                                    title: 'Error',
                                    text: 'Status Gagal Diubah',
                                    type: 'error',
                                    showConfirmButton: false,
                                    timer:1500
                                }).catch(swal.noop);
                            }
                        });
                    }
                });
            });
        });
        $('#table_user').on('click','#btn-hapus-user',function(){
        var c = $(this).attr("data-id");
        bootbox.dialog({
            title: '<span class="orange"><i class="fa fa-trash"></i> Hapus</span> User',
            message: "Anda yakin akan menghapus user ini ?",
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
                            url: '{{ route('form_delete_user') }}',
                            data : {id : c},
                            // data: $("#form-edit").serialize(),
                              success: function(data) {
                                $('#table_user').DataTable().ajax.reload(null , false);
                                    if (data.status == 'success') {
                                        Toast.fire({
                                            type: 'success',
                                            title :'Sukses',
                                            text: 'Data Berhasil Dihapus.',
                                        });
                                      hideLoading();
                                    } else {
                                      Toast.fire({
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
@endpush