@extends('template')
@push('title','Question')
@push('main_title','Question')
@push('sub_main_title','Setting')
@push('active_pertanyaan','active')
@push('active_datamaster','active')

@push('style')
<style>
    #pertanyaan {
    list-style: none;
    counter-reset: my-awesome-counter;
    display: block;
    }
    #pertanyaan #nomor {
    counter-increment: my-awesome-counter;
    }
    #pertanyaan #nomor::before {
    content: counter(my-awesome-counter) ". ";
    /*color: red;*/
    /*font-weight: bold;*/
    }
    #handle-grip-pilihan:hover{
    color: #3598dc;
    }
    hr {
    margin-top: 1rem;
    margin-bottom: 1rem;
    border: 0;
    border-top: 1px solid rgba(0, 0, 0, 0.1);
    }
    .btn-circle {
    border: none;
    color: white;
    text-align: center;
    text-decoration: none;
    display: inline-block;
    margin: 4px 2px;
    }
    .btn-circle-input {
    border: none;
    color: white;
    padding: 20px;
    text-align: center;
    text-decoration: none;
    display: inline-block;
    font-size: 16px;
    margin: 4px 2px;
    border-radius: 50%;
    }
    .pertanyaan{
    resize:both;
    }
    .panel-primary>.panel-heading {
    color: #fff; 
    background-color: #4d5d7b; 
    border-color: #4d5d7b; 
    }
    textarea {
    resize: none;
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
                                <i class="icon-bubble font-green-sharp"></i>
                                <span class="caption-subject font-green-sharp bold uppercase">{{\Iff::namaSurvey($id)}}</span>
                            </div>
                        </div>
                        <form role="form" id="form-reusable" method="post" autocomplete="off">
                            {!!csrf_field()!!}
                            <input type="hidden" name="template_id" id="template_id" value="{{$id}}">
                            <div id="pertanyaan">
                            </div>

                            {{-- Form untuk menambah jenis pertanyaan --}}
                            <div class="portlet-body form" >
                                <div class="panel panel-primary">
                                    <div class="panel-heading">
                                        Jenis Pertanyaan
                                    </div>
                                    <div class="panel-body">
                                        <input type="hidden" id="no_pertanyaan" value="1">
                                        <div class="col-md-12">
                                            <div class="form-group ">
                                                <select id="cmb-jenis" class="form-control">
                                                    <option value=""> Pilih Jenis </option>
                                                    @foreach(\Iff::jenis() as $jenis)
                                                    <option value="{{$jenis->id}}">{{$jenis->nama}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <button type="button" class="btn blue" id="btn_tambah_pertanyaan"><i class="fa fa-check"></i> Pilih Pertanyaan</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-actions">
                                <button type="submit" class="btn blue">Submit</button>
                                {{-- <a href="{{url('/survey')}}" class="btn btn-danger">Cancel</a> --}}
                                <button type="button" id="btn-cancel" class="btn default">Cancel</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('script')
<script src="{{ asset('/js/jquery-ui.js') }}"></script>
<script>
    
    $(document).ready(function(){
        
        var no = 0;
        
        var id_tmp = $('#template_id').val();
        
        function getData(){
            $.ajax({
                url: '/edit_reusable/'+id_tmp,
                type: 'GET',
                success: function(data){
                    $('#pertanyaan').append(data.form);
                    
                    no = data.counter;
                    useNextLoad(no);
                },
                error: function(data) {
                    
                }
            });
        }

        getData();

        function useNextLoad(no_pil){
            optionReusable();
            optDeleteRow();
        }

        // console.log(no)
        function optionReusable(){
            
            $("#btn_tambah_pilihan").on('click', function() {
                $('#pilihan').append(
                    '<div class="form-group col-md-12" id="row_div'+no+'">'+
                        '<div id="line-pilihan">'+
                            '<div class="input-group">'+
                                '<input type="text" class="form-control input-sm keyup-pilihan" name="pilihan[]"  placeholder="Pilihan" maxlength="185" required> '+ 
                                '<span class="input-group-addon" ><a href="javascript:;" data-id="'+no+'" class="fa fa-trash delete_row" style="color:red;"> Delete</a> </span>'+
                            '</div>'+
                        '</div>'+
                    '</div>'
                );
                optDeleteRow();
                no++;
            });

            $("#btn_tambah_scale").on('click', function() {
                $('#pilihan').append(
                    '<div class="form-group col-md-12" id="row_div'+no+'">'+
                        '<div id="line-pilihan">'+
                            '<div class="input-group">'+
                                '<input type="text" class="form-control input-sm" name="pilihan[]" maxlength="185" placeholder="Scale Text" required> '+
                                '<span class="input-group-addon" ><a href="javascript:;" data-id="'+no+'" class="fa fa-trash delete_row" style="color:red;"> Delete</a> </span>'+
                            '</div>'+
                        '</div>'+
                    '</div>'
                );
                optDeleteRow();
                no++;
            });

            $("#btn_tambah_pertanyaan").on('click', function() {
                $('#pertanyaan_mt').append(
                    '<div class="form-group col-md-12" id="row_div'+no+'">'+
                        '<div id="line-pertanyaan">'+
                            '<div class="input-group">'+
                                '<input type="text" class="form-control input-sm" name="pertanyaan[]" maxlength="500" placeholder="Pertanyaan" required> '+
                                '<span class="input-group-addon" ><a href="javascript:;" data-id="'+no+'" class="fa fa-trash delete_row" style="color:red;"> Delete</a> </span>'+
                            '</div>'+
                        '</div>'+
                    '</div>'
                );
                optDeleteRow();
                no++;
            });

            $("#btn_tambah_instruksi").on('click', function() {
                $('#instruksi').append(
                    '<div id="row_div'+no+'">'+
                        '<div class="form-group col-md-12" >'+
                            '<label>Instruksi / Pertanyaan</label>'+
                            '<div class="row">'+
                                '<div class="col-md-11">'+
                                    '<textarea type="text" class="form-control input-sm" rows="5" name="pertanyaan[]" maxlength="500" placeholder="Tulis Instruksi" required></textarea>'+
                                '</div>'+
                                '<div class="col-md-1">'+
                                    '<a href="javascript:;" data-id="'+no+'" class="btn btn-danger btn-circle delete_row" ><i class="fa fa-close"></i></a>'+
                                '</div>'+
                            '</div>'+
                        '</div>'+
                        '<div class="form-group col-md-11">'+
                            '<label>Type</label>'+
                            '<select class="form-control input-sm" name="tipe_pilihan[]" required>'+
                                '<option value="0">Single Choice</option>'+
                                '<option value="1">Multiple Choice</option>'+
                            '</select>'+
                        '</div>'+
                    '</div>'
                );
                optDeleteRow();
                no++;
            });

        }

        function optDeleteRow(){
            $(document).on('click','.delete_row', function(event) {
                event.stopPropagation();
                event.stopImmediatePropagation();

                var data_id = $(this).attr('data-id');
                bootbox.dialog({
                    title: '<span class="orange"><i class="fa fa-trash"></i> Hapus</span> Baris',
                    message: "Anda yakin akan menghapus Baris ini ?",
                    buttons: {
                        confirm: {
                            label: '<i class="fa fa-trash"></i> Hapus',
                            className: 'btn btn-default btn-danger',
                            callback: function(){
                              $('#row_div'+data_id).remove();
                              optDeleteRow();
                            }
                        },
                        cancel: {
                        label: '<i class="fa fa-times"></i> Batal',
                        className: 'btn btn-default'
                    }, 
                    }
                });
            });
        }

        $('#btn_tambah_pertanyaan').on('click',function(){
            var jenis = $('#cmb-jenis').val();
            switch (jenis) {
                case '':
                    //mereset nomor untuk pilihan
                    no = 1;
                    //menampilkan swal error
                    swal({
                        title: "Gagal Menambah Template!",
                        text: "Harus ada template pertanyaan yang dipilih!",
                    });
                    break;
                default:
                    //mereset nomor untuk pilihan
                    no = 1;

                    var cekReusable = $('.reusableForms').length;
                    // console.log(cekReusable);
                    if (cekReusable == 0) {
                        //mengambil form pertanyaan
                        getFormPertanyaan(jenis);
                    }else {
                        bootbox.dialog({
                            title: '<span class="orange"><i class="fa fa-pencil"></i> Ubah</span> Pertanyaan',
                            message: "Apakah Anda yakin akan mengubah Pertanyaan ini ?<br>Jika di ubah Pertanyaan sebelumnya akan di RESET !!",
                            buttons: {
                                confirm: {
                                    label: '<i class="fa fa-trash"></i> Ubah',
                                    className: 'btn btn-default btn-danger',
                                    callback: function(){
                                      getFormPertanyaan(jenis);
                                    }
                                },
                                cancel: {
                                label: '<i class="fa fa-times"></i> Batal',
                                className: 'btn btn-default'
                            }, 
                            }
                        });
                    }

                    
                    break;
            }
        });

        function getFormPertanyaan(jenis){
            $.ajax({
                url: '/get_form_reusable/'+jenis,
                type: 'GET',
                success: function(data){
                    $('#pertanyaan').html(data);

                    optionReusable();
                },
                error: function(data) {
                    
                }
            });
        }

        $('#form-reusable').submit(function(e){
            e.preventDefault();
            showLoading();

            $.ajaxSetup({
                headers: {
                  'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
                }
            });
            
            var id = $('#template_id').val();

            $.ajax({
                type: 'POST',
                url: "/save_reusable/"+id,
                data: $("#form-reusable").serialize(),
                success: function(data){
                    const Toast = Swal.mixin({
                      toast: true,
                      position: 'top-end',
                      showConfirmButton: false,
                      timer: 3000
                    });


                    hideLoading();
                    
                    window.location.href = '/pertanyaan';
                    Toast.fire({
                      type: 'success',
                      title: 'Sukses Menyimpan Pertanyaan'
                    })
                },
                error:function(data){
                    hideLoading();
                }
            });
        });
    });
</script>
@endpush