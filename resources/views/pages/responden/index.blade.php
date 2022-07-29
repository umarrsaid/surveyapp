@extends('template')

@push('title','Detail Response')
@push('main_title','<i class="fa fa-comments"></i> Detail Response')
@push('sub_main_title','Response')
@push('active_jawaban','active')

@section('content')
<div class="page-content">
    <div class="container-fluid">
        <div class="page-content-inner">
            <div class="row">
                <div class="col-md-12">
                    <div class="portlet light ">
                        <div class="portlet-title">
                            <div class="caption">
                                <a href="/jawaban" class="btn btn-primary btn-sm"><i class="fa fa-sm fa-mail-reply"></i> Kembali</a>
                            </div>
                        </div>
                        <div class="portlet-body">
                            <input type="hidden" class="id_survey" id="id_survey" value="{{$id}}">
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
                            <table class="table table-bordered" id="tabel_survey_responden" >
                              <thead>
                                 <tr>
                                    <th width="30"></th>
                                    <th>Waktu Mulai</th>
                                    <th>Waktu Selesai</th>
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

$(document).ready(function() {
    // $(function () {
        showLoading();
        // tabel_responden();
    // });

    // function tabel_responden(){
        var id = $('#id_survey').val();
        data_col = [
                {data:'action',searchable: false, width: '10',class:'dt-center detail'},
                {data:'waktu_mulai'},
                {data:'waktu_selesai'},
        ];
        // var groupColumn = 3;

        var tabel_survey_responden = $('#tabel_survey_responden').DataTable({
           lengthMenu    : [[10,20, 30, 50, -1], [10,20, 30, 50, "All"]],
            processing    :true,
            serverSide    :true,
            scrollX         :true,
            sScrollXInner: '100%',
            bScrollCollapse: true,
            ordering      :false,
            // "columnDefs": [
            //     { "visible": false, "targets": groupColumn }
            // ],
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
                url: "/tabel_survey_responden/"+id,
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
        //    "drawCallback": function ( settings ) {
        //         var api = this.api();
        //         var rows = api.rows( {page:'current'} ).nodes();
        //         var last=null;
    
        //         api.column(groupColumn, {page:'current'} ).data().each( function ( group, i ) {
        //             if ( last !== group ) {
        //                 $(rows).eq( i ).before(
        //                     '<tr class="group"><td colspan="5">'+group+'</td></tr>'
        //                 );
    
        //                 last = group;
        //             }
        //         } );
        //     }
           
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
    // }
    function format ( table_id ) {
        return '<div class="card bg-light">' +
                    '<div class="card-body">' +
                        '<div class="col-md-12">' +
                            '<table id="detail_' + table_id + '" class="table table-striped" width="100%">' +
                            '<thead>' +
                                '<tr>' +
                                    '<th>Label</th>' +
                                    '<th>Value</th>' +
                                '</tr>' +
                            '</thead>' +
                            '</table>' +
                        '</div>' +
                    '</div>' +
                '</div>' +
            '</div>';
    }

    // Add event listener for opening and closing details
    $('#tabel_survey_responden tbody').on('click', 'td.detail', function () {
        var tr = $(this).closest('tr');
        var row = tabel_survey_responden.row( tr );
        var ref=$(this).closest('tr').children('td:first').find('.details').attr('data-ref');
        if ( row.child.isShown() ) {
            // This row is already open - close it
            $(this).closest('tr').children('td:first').find('i').removeClass('fa-chevron-up');
            $(this).closest('tr').children('td:first').find('i').addClass('fa-chevron-down');
            row.child.hide();
            tr.removeClass('shown');
        } else {
            // Open this row
            $(this).closest('tr').children('td:first').find('i').removeClass('fa-chevron-down');
            $(this).closest('tr').children('td:first').find('i').addClass('fa-chevron-up');
            row.child(format(ref, ref)).show();
            tr.addClass('shown');
            table2 = $('#detail_' + ref).DataTable({
                lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
                pageLength: 25,
                processing: false,
                serverSide: true,
                ordering: false,
                searching: false,
                paging: false,
                info: false,
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
                    url: "/tabel_survey_responden_detail/"+ref,
                    error: function (jqXHR, textStatus, errorThrown) {
                            hideLoading();
                        }
                    },
                columns: [
                    {data: 'label'},
                    {data: 'value'},
                ]
            });
        }
    });
});
</script>
@endpush