@extends('template')

@push('title','Response')
@push('main_title','<i class="fa fa-comments"></i> Response')
@push('sub_main_title','Response')
@push('active_jawaban','active')

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
                            <table class="" id="tabel_survey_jawaban" >
                              <thead>
                                 <tr>
                                    <th>No</th>
                                    <th>Nama Survey</th>
                                    <th>Total Responden</th>
                                    <th>Detail</th>
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
    $(function () {
        showLoading();
        tabel_jawaban();
    });

    function tabel_jawaban(){
        data_col = [
                {data:'DT_RowIndex',searchable: false, width: '10',class:'dt-center'},
                {data:'nama'},
                {data:'total_responden',width: '100', searchable: false,class:'dt-center'},
                {data:'detail',searchable: false,width: '100', class:'dt-center'},
        ];

          var tabel_survey_jawaban = $('#tabel_survey_jawaban').DataTable({
           lengthMenu    : [[10,20, 30, 50, -1], [10,20, 30, 50, "All"]],
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
                url: "{{ route('tabel_survey_jawaban') }}",
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

</script>
@endpush