@extends('template')

@push('title','Responden')
@push('main_title','Responden')
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
                                <i class="fa fa-table biru"></i>
                                <span class="caption-subject ">Title </span>
                                <span class="caption-helper">sub title</span>
                            </div>
                        </div>
                        <div class="portlet-body">
                            <table class="" id="tabel_data_responden" >
                              <thead>
                                 <tr>
                                    <th>No</th>
                                    <th><a href="javascript:;" id="test"><button class="btn btn-primary btn-xs"><i class="fa fa-plus"></i></button></a></th>
                                    <th>Nama</th>
                                    <th>Telepon</th>
                                    <th>Umur</th>
                                    <th>Alamat</th>
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
       // showLoading();
    function tabel_responden(){

        data_col = [
                {data:'DT_RowIndex',searchable: false, width: '10'},
                {data:'action',width: '10'},
                {data:'nama',width: '150'},
                {data:'telepon',width: '100'},
                {data:'umur',width: '75'},
                {data:'alamat',width: '100'},
        ];

          var tabel_data_responden = $('#tabel_data_responden').DataTable({
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
                url: "{{ route('tabel_data_responden') }}",
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
    
    $(function () {
        showLoading();
        tabel_responden();
    });

     $('#test').on('click',function(){
         // removeClassModal();
         $.ajax({
            type: "GET",
            url: "{{ route("modal_test") }}",
            beforeSend: function(){
                  showLoading();
            },
            success: function(data){
               $('#myModal').modal('show');
               $('.modal-title').html(data.modal_header);
               $('.modal-body').html(data.modal_body);
               $('.modal-footer').html(data.modal_footer);
               $('.modal-dialog').addClass(data.modal_size);

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