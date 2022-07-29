@extends('template')

@push('title','Project')
@push('main_title','<i class="fa fa-comments"></i> Pertanyaan')
@push('sub_main_title','Project')
@push('active_survey','active')
@push('active_datasurvey','active')
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

    .funkyradio div {
      clear: both;
      overflow: hidden;
    }

    .funkyradio label {
      width: 100%;
      border-radius: 3px;
      border: 1px solid #D1D3D4;
      font-weight: normal;
    }

    .funkyradio input[type="radio"]:empty,
    .funkyradio input[type="checkbox"]:empty {
      display: none;
    }

    .funkyradio input[type="radio"]:empty ~ label,
    .funkyradio input[type="checkbox"]:empty ~ label {
      position: relative;
      height: 30px;
      line-height: 2em;
      text-indent: 3.25em;
      /*margin-top: 2em;*/
      cursor: pointer;
      -webkit-user-select: none;
         -moz-user-select: none;
          -ms-user-select: none;
              user-select: none;
    }

    .funkyradio input[type="radio"]:empty ~ label:before,
    .funkyradio input[type="checkbox"]:empty ~ label:before {
      position: absolute;
      display: block;
      top: 0;
      bottom: 0;
      left: 0;
      content: '';
      width: 2.5em;
      background: #D1D3D4;
      border-radius: 3px 0 0 3px;
    }
  
  .funkyradio input[type="radio"]:hover:not(:checked) ~ label,
  .funkyradio input[type="checkbox"]:hover:not(:checked) ~ label {
    color: #888;
  }

  .funkyradio input[type="radio"]:hover:not(:checked) ~ label:before,
  .funkyradio input[type="checkbox"]:hover:not(:checked) ~ label:before {
    content: '\2714';
    text-indent: .9em;
    color: #C2C2C2;
  }

  .funkyradio input[type="radio"]:checked ~ label,
  .funkyradio input[type="checkbox"]:checked ~ label {
    color: #777;
  }

  .funkyradio input[type="radio"]:checked ~ label:before,
  .funkyradio input[type="checkbox"]:checked ~ label:before {
    content: '\2714';
    text-indent: .9em;
    color: #333;
    background-color: #ccc;
  }

  .funkyradio input[type="radio"]:focus ~ label:before,
  .funkyradio input[type="checkbox"]:focus ~ label:before {
    box-shadow: 0 0 0 3px #999;
  }

  .funkyradio-default input[type="radio"]:checked ~ label:before,
  .funkyradio-default input[type="checkbox"]:checked ~ label:before {
    color: #333;
    background-color: #ccc;
  }

  .funkyradio-primary input[type="radio"]:checked ~ label:before,
  .funkyradio-primary input[type="checkbox"]:checked ~ label:before {
    color: #fff;
    background-color: #337ab7;
  }

  .funkyradio-success input[type="radio"]:checked ~ label:before,
  .funkyradio-success input[type="checkbox"]:checked ~ label:before {
    color: #fff;
    background-color: #5cb85c;
  }

  .funkyradio-danger input[type="radio"]:checked ~ label:before,
  .funkyradio-danger input[type="checkbox"]:checked ~ label:before {
    color: #fff;
    background-color: #d9534f;
  }

  .funkyradio-warning input[type="radio"]:checked ~ label:before,
  .funkyradio-warning input[type="checkbox"]:checked ~ label:before {
    color: #fff;
    background-color: #f0ad4e;
  }

  .funkyradio-info input[type="radio"]:checked ~ label:before,
  .funkyradio-info input[type="checkbox"]:checked ~ label:before {
    color: #fff;
    background-color: #5bc0de;
  }
  .readonly {
      background-color: #eef1f5;
      opacity: 1;
      pointer-events: none;
  }
  .duplicate {
      border: 1px solid red;
      color: red;
  }

</style>
<link href="{{URL::asset('bootstrap-multiselect/bootstrap-multiselect.css')}}" rel="stylesheet" type="text/css" />
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

                        
                        <form role="form" id="form-pertanyaan" method="post" autocomplete="off">
                            {!!csrf_field()!!}
                            <input type="hidden" name="survey_id" id="survey_id" value="{{$id}}">
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
                                    <input type="hidden" id="total_responden">
                                    <div class="col-md-6">
                                        <div class="form-group ">
                                            <select id="cmb-jenis" class="form-control">
                                                <option value=""> Pilih Jenis </option>
                                                @foreach(\Iff::jenis($id) as $jenis)
                                                <option value="{{$jenis->id}}">{{$jenis->nama}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <button type="button" class="btn blue" id="btn_tambah_pertanyaan"><i class="fa fa-plus"></i> Tambah Pertanyaan</button>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group ">
                                            <select id="cmb-template" class="form-control">
                                                <option value=""> Pilih Template </option>
                                                @foreach(\Iff::jenisReusable() as $jenis)
                                                <option value="{{$jenis->id}}">{{$jenis->nama}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <button type="button" class="btn blue" id="btn_tambah_reusable"><i class="fa fa-plus"></i> Tambah Template</button>
                                    </div>

                                </div>
                            </div>
                        </div>

                          <div class="form-actions">
                            <button type="submit" class="btn blue">Save</button>
                            <button type="button" id="btn-cancel" class="btn default">Back</button>
                        </div>
                            </form>
            
                    </div>

                </div>


            </div>
        </div>
    </div>
</div>
{{-- {{ dd(\Session::get('user')[0]->accessToken) }} --}}
@endsection
@push('script')
<script src="{{ asset('/js/jquery-ui.js') }}"></script>
<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/additional-methods.min.js"></script>
<script src="{{URL::asset('bootstrap-multiselect/bootstrap-multiselect.js')}}" type="text/javascript"></script>
  
<script type="text/javascript">

    $(document).ready(function() {
        showLoading();

        $("input:not(:checkbox)").prop('required',true);
          var survey_id = $('#survey_id').val();
           $.ajax({
              type: "GET",
              url: "/edit_survey/"+survey_id,
              success: function(data){
                  // console.log(data['rules'][1]);
                  var no = data.nomor;
                  var no_rule_pertanyaan = 1;
                  $('#pertanyaan').append(data.form);

                  // untuk select option selected pada posisi
                  $.each(data['posisi'], function (index, posisi) {
                    if( posisi[0] == null ){
                      $('#posisi_'+index).prop('selectedIndex',0);
                    }else{
                      $('#posisi_'+index).val(posisi);
                    }
                  });

                  // Untuk injectsi col-md-6 jika terdapat rules
                  $.each(data['sequence'], function (index, value) {
                      $.each(data['rules'][value], function (index, rule) {
                          var sequence = rule.sequence;
                          var jenis = 1;
                          var no_count = 1 ;

                          $('#input_rule'+sequence).removeClass()
                          $('#input_rule'+sequence).addClass('col-md-6')
                          $('#input2_rule'+sequence).removeClass()
                          $(this).html("<span><i class='fa fa-check'></i>Rule Question</span>");
                          $(this).attr("data-rule", 'cek');
                          
                          no_rule_pertanyaan += 1;
                          reload_rule_pertanyaan();

                      });
                  });


                  tinymce.init({
                    selector: '.static_content',
                    height: 100,
                    plugins: '',
                    toolbar: 'formatselect | bold italic strikethrough forecolor backcolor | numlist bullist outdent indent | removeformat | addcomment',
                    // readonly : 1
                    });
                    $('.mt-multiselect').multiselect({
                        enableFiltering: true,
                        buttonWidth: '100%',
                        btn_class:'form-control'
                    });
                   no++;
                  $('#no_pertanyaan').val(no)
                  reload_rule_pertanyaan();
                  $('.number').number( true);
                  $('.select-pilihan2').trigger('change');

                  if (data['total_responden'] > 0 ) {

                    var cek = $("#total_responden").val(false);
                    $("#form-pertanyaan :input").not('[id=total_responden]').prop("disabled", true);
                    $("#form-pertanyaan a").prop("disabled", true);
                  }else{
                    var cek = $("#total_responden").val(true);
                  }

                 hideLoading();
              },
              error: function(){
                    hideLoading();
              }
           });

        // $(".readonly").keydown(function(e){
        $(document).on('keydown','.readonly', function(e) {
            e.preventDefault();
        });

        $(document).on('keyup','.keyup-pilihan2', function() {
            reload_rule_pertanyaan();
        })
        
        $('#btn-cancel').click(function(e){
            e.preventDefault();
              bootbox.dialog({
                title: '<span class="orange"><i class="fa fa-info"></i> Information</span>',
                message: "Anda yakin akan kembali ke halaman kuisioner ?",
                buttons: {
                    confirm: {
                        label: 'Kembali',
                        className: 'btn btn-default btn-danger',
                        callback: function(){
                          window.location.href = "/survey";
                        }
                    },
                    cancel: {
                    label: '<i class="fa fa-times"></i> Batal',
                    className: 'btn btn-default'
                }, 
              }
          });
        });

        $(document).on('change','.rules', function() {
            var rules =$(this).val();
            var id = $(this).data('id');
            var no = $(this).data('no');
            // alert(rules);
            switch(rules){
                case 'go':
                    $('#select_rule_pertanyaan'+id+'_'+no).prop('disabled',false);
                break;

                case 'end':
                    $('#select_rule_pertanyaan'+id+'_'+no).prop('disabled',true);
                break;

                default:
                break;

            }
        });

        // $('.line-pilihan').on('click',function(){
        //     alert('sa');

        // });
        $('#form-pertanyaan').submit(function(e){
            e.preventDefault();
            showLoading();
            tinyMCE.triggerSave();
            var cb = $('.cekbox').length;
            var cb_checked = $('.cekbox:checked').length;
              if ((cb > 0) && (cb_checked < 1)){
                Swal.fire({
                  type: 'error',
                  title: "Responden",
                  text: "Pertanyaan responden tidak boleh kosong",
                })
                hideLoading();

              }else{

                var ids = $('.connectedSortable').map(function() {
                  return $(this).attr('data-pertanyaan');
                });
                if(ids.length < 1){
                    swal({
                      title: "Gagal Menyimpan!",
                      text: "Data pertanyaan tidak ada!",
                      icon: "error",
                      button: "Ok!",
                    });
                    hideLoading();
                }else{
                    var id = $('#survey_id').val();
                        $.ajaxSetup({
                        headers: {
                          'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
                        }
                        })
                        // $('.static').tinyMCE.triggerSave();
                        $.ajax({
                            type: 'POST',
                            url: "/save_pertanyaan/"+id,
                            data: $("#form-pertanyaan").serialize(),
                            // data: $("#form-pertanyaan").sortable("toArray"),
                            dataType: 'json',
                            success: function(data){
                              location.reload();
                                // window.location.href = data.link;
                                // swal({
                                //     title: "Sukses Menyimpan!",
                                //     text: "Sukses Menambah Survey",
                                //     icon: "success",
                                //     button: "Ok!",
                                //     timer: 2000
                                // })
                                const Toast = Swal.mixin({
                                  toast: true,
                                  position: 'top-end',
                                  showConfirmButton: false,
                                  timer: 3000
                                });

                                Toast.fire({
                                  type: 'success',
                                  title: 'Sukses Menyimpan Pertanyaan'
                                })
                                hideLoading();
                            },
                            error:function(data){
                                hideLoading();
                            }
                        });
                }
              }


        });

    

        // $("#pertanyaan").sortable({ handle: '#handle-grip' });
        // $("#pilihan-grip").sortable({ handle: '#handle-grip-pilihan' });
        $(function() {
           // console.log(no)
            $( "#pertanyaan" ).sortable({
                handle: '#handle-grip',
                stop: function(event, ui) {
                    var changedList = this.id;
                    var order = $(this).sortable('toArray');
                    var positions = order.join(';');

                    // Remove all editors bound to divs
                    tinymce.remove('div');

                    // Remove all editors bound to textareas
                    tinymce.remove('textarea');

                    // Remove all editors
                    tinymce.remove();

                    // Remove specific instance by id
                    tinymce.remove('.static_content');

                    tinymce.init({
                      selector: '.static_content',
                      height: 100,
                      plugins: '',
                      toolbar: 'formatselect | bold italic strikethrough forecolor backcolor | numlist bullist outdent indent | removeformat | addcomment',
                   });
                   $('.mt-multiselect').multiselect({
                        enableFiltering: true,
                        buttonWidth: '100%',
                        btn_class:'form-control'
                    });
                    
                    reload_rule_pertanyaan();
                  },
            });
            $( "#pertanyaan" ).disableSelection();
        });
    });
    // function delete_rule_pertanyaan(no){
    //    $("#rules"+no).remove();
    //     reload_rule_pertanyaan();
    // }
    function delete_rule_pertanyaan(no,id){
      var c = $(this).attr("data-id");
        bootbox.dialog({
            title: '<span class="orange"><i class="fa fa-trash"></i> Hapus</span> PertanyaanRule',
            message: "Anda yakin akan menghapus Rule ini ?",
            buttons: {
                confirm: {
                    label: '<i class="fa fa-trash"></i> Hapus',
                    className: 'btn btn-default btn-danger',
                    callback: function(){

                      var jumlah_rules = $('.jumlah_rules'+no).length
                      if(jumlah_rules <= 1){
                          Swal.fire({
                            type: 'error',
                            title: "Gagal Menghapus!",
                            text: "Panel Rule tidak bisa dihapus semua!",
                          })
                      }else{
                          var select = $(".rules"+no+"_"+id ).remove();
                          
                      }
                      reload_rule_pertanyaan();
                    }

                },
                cancel: {
                label: '<i class="fa fa-times"></i> Batal',
                className: 'btn btn-default'
            }, 
            }
        });

    }
  
    $(document).on('click','#btn_tambah_instruksi', function() {
        var no = $(this).attr('data-no-instruksi');
        var counter = $(this).attr('data-counter-instruksi');
        counter++;
        $('#instruksi_'+no).append(
        '<div id="line-instruksi'+no+'_'+counter+'" class="instruksi'+no+'" >'+
          '<div class="form-group col-md-2">'+
              '<label>Nomor</label>'+
              '<input class="form-control input-sm" maxlength="9" name="nomor_pertanyaan['+no+'][]" id="nomor_pertanyaan_'+no+'" placeholder="No. Pertanyaan" required>'+
              '<br>'+
              '<label>Type</label>'+
              '<select class="form-control input-sm" name="tipe_pilihan['+no+'][]" required>'+
                  '<option value="0">Single Choice</option>'+
                  '<option value="1">Multiple Choice</option>'+
              '</select>'+
          '</div>'+
            '<div class="form-group col-md-10" >'+
            '<label>Instruksi</label>'+
                '<div class="row">'+
                    '<div class="col-md-11">'+
                    '<textarea type="text" class="form-control input-sm" maxlength="500" rows="5" name="pertanyaan['+no+'][]" placeholder="Tulis Instruksi" required></textarea> '+
                  '</div>'+
                    '<div class="col-md-1">'+
                    '<a href="javascript:;" data-counter="'+counter+'" data-no="'+no+'" id="line-instruksi" class="btn btn-danger btn-circle"><i class="fa fa-close"></i></a>'+
                  '</div>'+
                '</div>'+
            '</div>'+
        '</div>'
        );
        $(this).attr('data-counter-instruksi',counter);
    });

    $(document).on('click','#line-instruksi', function() {
        var no = $(this).attr('data-no');
        var counter = $(this).attr('data-counter');
        var jumlah_pilihan = $('.instruksi'+no).length;

        if (jumlah_pilihan <= 1) {
          Swal.fire({
            type: 'warning',
            title: "Gagal Menghapus!",
            text: "Pilihan tidak bisa dihapus semua!",
          })
        }else{
          bootbox.dialog({
              title: '<span class="orange"><i class="fa fa-trash"></i> Hapus</span> Pilihan',
              message: "Anda yakin akan menghapus Pilihan ini ?",
              buttons: {
                  confirm: {
                      label: '<i class="fa fa-trash"></i> Hapus',
                      className: 'btn btn-default btn-danger',
                      callback: function(){
                        $("#line-instruksi"+no+'_'+counter).last().remove()
                        reload_rule_pertanyaan();
                      }
                  },
                  cancel: {
                  label: '<i class="fa fa-times"></i> Batal',
                  className: 'btn btn-default'
              }, 
              }
          });
        }
    });

    $(document).on('click','#btn_tambah_pilihan8', function() {
        var no = $(this).attr('data-no-pilihan');
        var counter = $(this).attr('data-counter-pilihan');
        counter++;
        $('#pilihan_'+no).append(
            '<div class="col-md-12">'+

            '<div class="form-group " id="line-pilihan'+no+'_'+counter+'">'+
                '<div class="input-group">'+
                '<input type="text" class="form-control input-sm disable_pilihan'+no+' pilihan_'+no+'" maxlength="100" name="pilihan['+no+'][]" placeholder="Pilihan" required> '+
            '<span class="input-group-addon" ><a href="javascript:;" data-counter="'+counter+'" data-no="'+no+'" id="line-pilihan" class="fa fa-trash" style="color:red;"> Delete</a> </span>'+
        '</div>'+
        '</div>'
        );
        $(this).attr('data-counter-pilihan',counter);
    });

    $(document).on('click','#btn_tambah_pertanyaan7', function() {
        var no = $(this).attr('data-no-pertanyaan');
        var counter = $(this).attr('data-counter-pertanyaan');
        counter++;
        $('#pertanyaan_'+no).append(
            '<div class="form-group" id="line-pertanyaan'+no+'_'+counter+'">'+
                '<div class="input-group">'+
                '<input type="text" class="form-control input-sm disable_pilihan'+no+' pertanyaan_'+no+'" maxlength="200" name="pertanyaan['+no+'][]" placeholder="Pertanyaan" required> '+
            '<span class="input-group-addon" ><a href="javascript:;" data-counter="'+counter+'" data-no="'+no+'" id="line-pertanyaan" class="fa fa-trash" style="color:red;"> Delete</a> </span>'+
        '</div>'
        );
        $(this).attr('data-counter-pertanyaan',counter);
    });

    $(document).on('click','#line-pertanyaan', function() {
        var no = $(this).attr('data-no');
        var counter = $(this).attr('data-counter');

        var jumlah_pilihan = $('.pertanyaan_'+no).length;

        if (jumlah_pilihan <= 1) {
          Swal.fire({
            type: 'warning',
            title: "Gagal Menghapus!",
            text: "Pilihan tidak bisa dihapus semua!",
          })
        }else{
          bootbox.dialog({
              title: '<span class="orange"><i class="fa fa-trash"></i> Hapus</span> Pilihan',
              message: "Anda yakin akan menghapus Pilihan ini ?",
              buttons: {
                  confirm: {
                      label: '<i class="fa fa-trash"></i> Hapus',
                      className: 'btn btn-default btn-danger',
                      callback: function(){
                        $("#line-pertanyaan"+no+'_'+counter).last().remove()
                        reload_rule_pertanyaan();
                      }
                  },
                  cancel: {
                  label: '<i class="fa fa-times"></i> Batal',
                  className: 'btn btn-default'
              }, 
              }
          });
        }
    });

    $(document).on('click','#btn_tambah_scale7', function() {
        var no = $(this).attr('data-no-scale');
        var counter = $(this).attr('data-counter-scale');
        counter++;
        $('#pilihan_'+no).append(
            '<div class="form-group" id="line-pilihan'+no+'_'+counter+'">'+
                '<div class="input-group">'+
                '<input type="text" class="form-control input-sm disable_pilihan'+no+' pilihan_'+no+'" maxlength="100" name="pilihan['+no+'][]" placeholder="Scale Text" required> '+
            '<span class="input-group-addon" ><a href="javascript:;" data-counter="'+counter+'" data-no="'+no+'" id="line-pilihan" class="fa fa-trash btn_pilihan'+no+'" style="color:red;"> Delete</a> </span>'+
        '</div>'
        );
        $(this).attr('data-counter-scale',counter);
    });

    $(document).on('click','#btn_tambah_scale4', function() {
        var no = $(this).attr('data-no-pilihan');
        var counter = $(this).attr('data-case');
        counter++;
        $('#pilihan_'+no).append(
            '<div class="form-group" id="line-pilihan'+no+'_'+counter+'">'+
                '<div class="input-group">'+
                '<input type="text" class="form-control input-sm pilihan_'+no+' disable_pilihan'+no+'" maxlength="100" name="pilihan['+no+'][]" placeholder="Scale Text" required> '+
            '<span class="input-group-addon" ><a href="javascript:;" data-counter="'+counter+'" data-no="'+no+'" id="line-pilihan" class="fa fa-trash btn_pilihan'+no+'" style="color:red;"> Delete</a> </span>'+
        '</div>'
        );
        $(this).attr('data-case',counter);
    });

    $(document).on('click','#btn_tambah_pilihan3', function() {
        var no = $(this).attr('data-no-pilihan');
        var counter = $(this).attr('data-case');
        counter++;
        $('#pilihan_'+no).append(
            '<div class="form-group" id="line-pilihan'+no+'_'+counter+'">'+
                '<div class="input-group">'+
                '<textarea type="text" class="form-control input-sm pilihan_'+no+' disable_pilihan'+no+'" maxlength="100" name="pilihan['+no+']['+counter+']" placeholder="Pilihan" required></textarea>'+
            '<span class="input-group-addon" ><a href="javascript:;" data-counter="'+counter+'" data-no="'+no+'" id="line-pilihan" class="fa fa-trash btn_pilihan'+no+'" style="color:red;"> Delete</a> </span>'+
            '<span class="input-group-addon" ><input type="checkbox" name="is_req_pilihan['+no+']['+counter+']" value="1"> Require</span>'+
            '</div>'
        );
        $(this).attr('data-case',counter);
    });

    $(document).on('click','#btn_tambah_pilihan2', function() {
        var no = $(this).attr('data-no-pilihan');
        var counter = $(this).attr('data-case');
        counter++;
        $('#pilihan_'+no).append(
            '<div class="form-group" id="line-pilihan'+no+'_'+counter+'">'+
                '<div class="input-group">'+
                '<textarea class="form-control input-sm pilihan_'+no+' disable_pilihan'+no+'" maxlength="195" name="pilihan['+no+'][]" placeholder="Pilihan" required></textarea>'+
            '<span class="input-group-addon" ><a href="javascript:;" data-counter="'+counter+'" data-no="'+no+'" id="line-pilihan" class="fa fa-trash btn_pilihan'+no+'" style="color:red;"> Delete</a> </span>'+
            '<span class="input-group-addon" ><input type="radio" name="is_req_pilihan['+no+'][]" value="'+counter+'"> Require</span>'+
        '</div>'
        );
        $(this).attr('data-case',counter);
        reload_rule_pertanyaan();

    });

    // click untuk mengahapus input pilihan
    $(document).on('click','#line-pilihan', function() {
        var no = $(this).attr('data-no');
        var counter = $(this).attr('data-counter');
        var jumlah_pilihan = $('.pilihan_'+no).length;

        if (jumlah_pilihan <= 1) {
          Swal.fire({
            type: 'warning',
            title: "Gagal Menghapus!",
            text: "Pilihan tidak bisa dihapus semua!",
          })
        }else{
          bootbox.dialog({
              title: '<span class="orange"><i class="fa fa-trash"></i> Hapus</span> Pilihan',
              message: "Anda yakin akan menghapus Pilihan ini ?",
              buttons: {
                  confirm: {
                      label: '<i class="fa fa-trash"></i> Hapus',
                      className: 'btn btn-default btn-danger',
                      callback: function(){
                        $("#line-pilihan"+no+'_'+counter).last().remove()
                        reload_rule_pertanyaan();
                      }
                  },
                  cancel: {
                  label: '<i class="fa fa-times"></i> Batal',
                  className: 'btn btn-default'
              }, 
              }
          });
        }

    });
    

      // modal untuk menghapus panel pertanyaan
    function hapus_pertanyaan(no){
      var cek = $("#total_responden").val();
      console.log(cek);
      if (cek == 'true') {
          bootbox.dialog({
              title: '<span class="orange"><i class="fa fa-trash"></i> Hapus</span> Pertanyaan',
              message: "Anda yakin akan menghapus Pertanyaan ini ?",
              buttons: {
                  confirm: {
                      label: '<i class="fa fa-trash"></i> Hapus',
                      className: 'btn btn-default btn-danger',
                      callback: function(){
                        $("#panel-pertanyaan"+no).remove();
                        reload_rule_pertanyaan();
                      }
                  },
                  cancel: {
                  label: '<i class="fa fa-times"></i> Batal',
                  className: 'btn btn-default'
              }, 
              }
          });
        }

      }
      
      function hapus_pilihan(no){
          // $("#line-pilihan"+no).last().remove();
      }

      function reload_rule_pertanyaan(){
          // mengambil value dari select, value di isi panel-pertanyaan
          var selected = $('.select_rule_pertanyaan').map(function() {
            var select = $(this).find('option:selected').val();
            return select;
          });

          // mengambil select pertanyaan
          var selected_id = $('.select_rule_pertanyaan').map(function() {
            var select = $(this).attr('id');
            return select;
          });

          // menghapus option value saat melakukan Drag
          $('.select_rule_pertanyaan option').remove();

          // menambah element menggunakan appeend, untuk pilih pertanyaan pada option pada saat drag panel dilakukan
          var select = $('.select_rule_pertanyaan');
          var newOptionPilihan = $('<option selected value>Pilih Pertanyaan</option>');
          $(select).append(newOptionPilihan);

          // mengambil ID dari panel berdasarkan class .connctedSortable
          var id_panel = $('.connectedSortable').map(function() {
            return $(this).attr('data-pertanyaan');
          });

          // // melakukan looping untuk mengganti value id pada saat dilakukan drag.
          for (var i = 0; i < id_panel.length; ++i) {
              var urut = i + 1;
              var newOption = $('<option value="'+id_panel[i]+'">Pertanyaan ke '+urut+'</option>');
              $(select).append(newOption);

              // console.log(id_panel[i]);
          }

          for (var i = 0; i < selected_id.length; ++i) {
              $('#'+selected_id[i]).val(selected[i]);

              // jika saat hapus pertanyaan, maka memilih blank, fungsi ini agar tidak blank dan memilih index ke 0;
              if( $('#'+selected_id[i]).find('option:selected').length < 1){
                  $('#'+selected_id[i]).prop('selectedIndex',0);
              }
          }

          // console.log(sel);

          //     var x = [];
          // for (var i = 0; i < id_panel.length; ++i) {
          //     var urut2 = i + 1;
          //     // var sel = $('.select_rule_pertanyaan').map(function() {
          //         var sel = $('#select_rule_pertanyaan'+urut2).attr('data-select-rule');
          //     var sel = $('pertanyaan'+urut2).filter(function() {
          //         return $(this).data('data-select-rule') !== undefined;
          //     });
          //       // return select;
          //     // });
          //     // var slice = $('#'+selected_id[i]).attr('data-select').length;

          //     // x.push(slice);
          //     // console.log(slice);
          //     // for (var i = 0; i < selected.length; ++i) {
              
          //     // }
          //     // $('#'+selected_id[i]).val(selected[i]);
          //     // console.log(selected[i])
          //     // $('select[data-select-rule="pertanyaan'+urut2+'"]').val(selected[i]);
          //     // $('#select_rule_pertanyaan'+id_panel[i]+'_'+urut2+' option[data-select-rule="pertanyaan'+urut2+'"]').val(selected[i]);
          // console.log(sel);
          // }
          // var panel_slice = x.join(',');
          // console.log(selected.slice(panel_slice));

          // for (var i = 0; i < id_panel.length; ++i) {
          //     var urut = i + 1;
          //     // console.log(urut2);8
          //     for (var j = 0; j < urut2.length; ++j) {
          //         var nomer = j + 1; 
          //         var selected = $('#select_rule_pertanyaan'+urut+'_'+nomer).map(function() {
          //             var select = $(this).find('option:selected').val();
          //           return select;
          //         });
          //         console.log(j);
          //     }
              
          //     // var select_rule = $('select[data-select-rule="pertanyaan'+urut+'"]').val(selected[i]);
          //     // var select_rule = $('#'+selected_id[i]+' option[data-select-rule="pertanyaan'+urut+'"]').val(selected[i]);
          //     // $('#'+selected_id[i]).val(selected[i]);
          // }
      }

    $(document).on('change','.select_rule_pertanyaan', function() {
        // reload_rule_pertanyaan();

    });

    $(document).on('click','.btn-anti3', function() {
        var sequence = $(this).attr('data-sequence');
        var jenis = $(this).attr('data-jenis');
        var no = $(this).attr('data-count');
        var no_rule_pertanyaan = 1;

         // cek jika ada pilihan duplicate
        var arr = [];
        var dup = [];
        $('.pilihan_'+sequence).each(function(){
            var value = $(this).val();
            $(this).removeClass("duplicate");
            if (arr.indexOf(value) == -1){
                arr.push(value);
            }else{
                $(this).addClass("duplicate");
                dup.push('duplicate');
            }
        });

        if (dup.length > 0) {
          Swal.fire({
            type: 'warning',
            title: "Pemberitahuan",
            text: "Isi pilihan tidak boleh sama",
          })
          // console.log(dup);
        }else{
          // console.log(sequence);
          if ($(this).attr("data-anti") == 'cek') {
              $('#input_rule'+sequence).removeClass()
              $('#input_rule'+sequence).addClass('col-md-12')
              $('#input2_rule'+sequence).removeClass()
              $('#input2_rule'+sequence).addClass('col-md-12')
              $(this).html("<span>Rule Question</span>");
              $('#input2-rule'+sequence).html('')
              $(this).attr("data-anti", 'uncek');
              $('.disable_pilihan'+sequence).prop('readonly', false);
              $('.disable_pilihan'+sequence).removeClass('readonly');

              $('.btn_pilihan'+sequence).prop('disabled', false);
          }else if ($(this).attr("data-anti") == 'uncek') {
              $('.disable_pilihan'+sequence).addClass('readonly');
              $('.btn_pilihan'+sequence).prop('disabled', true);
              $('#input_rule'+sequence).removeClass()
              $('#input_rule'+sequence).addClass('col-md-6')
              // $('#input2_rule'+sequence).removeClass()

              $(this).html("<span><i class='fa fa-check'></i>Rule Question</span>");
              $(this).attr("data-anti", 'cek');
              option_end = '<div class="col-md-12">'+
                              '<div class="form-group">'+
                                '<label>End Survey</label>'+
                                    '<select name="option_end['+sequence+'][]" class="mt-multiselect btn btn-default" id="option_end'+sequence+'" multiple="multiple" data-label="left" data-width="100%" data-filter="true" data-action-onchange="true">';
              var elem = $('textarea[name^="pilihan['+sequence+']"]');
              elem.each(function(){
                if(this.value != ""){
                  option_end +='<option value="'+this.value+'">'+this.value+'</option>';
                  }
              });
              option_end +='</select></div></div>';
              $('#input2-rule'+sequence).append(option_end);
              $.ajax({
                  type: "GET",
                  url: "/rules/1/"+jenis+"/"+sequence, // rules/{counter}/{jenis_id}/{no_urut/sequence}
                  beforeSend: function(){
                        showLoading();
                  },
                  success: function(data){
                      $('#input2-rule'+sequence).append(data);
                      reload_rule_pertanyaan();

                      // var elem = document.getElementsByTagName("textarea");
                      var elem = $('textarea[name^="pilihan['+sequence+']"]');
                      
                      var names = [];
                      elem.each(function(){
                        
                        names.push(this.value);

                        // jika input kosong maka 
                        if(this.value != ""){

                          var s= document.getElementById('select_anti_pilihan'+sequence+'_1');
                          s.options[s.options.length]= new Option(this.value, this.value);

                          var s2= document.getElementById('select_anti_pilihan_2_'+sequence+'_1');
                          s2.options[s2.options.length]= new Option(this.value, this.value);

                        }
                      });

                     hideLoading();
                  },
                  error: function(){
                        hideLoading();
                  }
               });
                // ComponentsBootstrapMultiselect.init(); 
                $('#option_end'+sequence).multiselect({
        			enableFiltering: true,
                    buttonWidth: '100%',
                    btn_class:'form-control'
        		});   
              //  $('#option_end'+sequence).multiselect();
          }
        }
    });

     $(document).on('click','.add-btn-anti1', function() { //semua id button ganti menjadi add-btn-rule1
        var sequence = $(this).attr('data-sequence');
        var jenis = $(this).attr('data-jenis');
        var no = $(this).attr('data-count');

         $.ajax({
                type: "GET",
                url: "/rules/"+no+"/"+jenis+"/"+sequence,
                beforeSend: function(){
                      showLoading();
                },
                success: function(data){
                    $('#input2-rule'+sequence).append(data);
                    var elem = document.getElementsByTagName("input");
                    var names = [];
                    for (var i = 0; i < elem.length; ++i) {
                      if (typeof elem[i].attributes.name !== "undefined") {
                        if(elem[i].attributes.name.value === 'pilihan['+sequence+'][]'){
                            names.push(elem[i].value);
                             // console.log(elem[i].value);
                            var s= document.getElementById('select_anti_pilihan'+sequence+'_'+no);
                            s.options[s.options.length]= new Option(elem[i].value, elem[i].value);

                            var s2= document.getElementById('select_anti_pilihan_2_'+sequence+'_'+no);
                            s2.options[s2.options.length]= new Option(elem[i].value, elem[i].value);
                        }
                      }
                    }
                    
                    var elem = $('textarea[name^="pilihan['+sequence+']"]');
                      
                      var names = [];
                      elem.each(function(){
                        
                        names.push(this.value);

                        // jika input kosong maka 
                        if(this.value != ""){
                            names.push(this.value);
                             // console.log(this.value);
                            var s= document.getElementById('select_anti_pilihan'+sequence+'_'+no);
                            s.options[s.options.length]= new Option(this.value, this.value);

                            var s2= document.getElementById('select_anti_pilihan_2_'+sequence+'_'+no);
                            s2.options[s2.options.length]= new Option(this.value, this.value);
                        }
                      });
                    no++;
                     $('.add-btn-anti1').attr('data-count',no)
                    reload_rule_pertanyaan();
                   hideLoading();
                },
                error: function(){
                      hideLoading();
                }
             });
        // alert(no);
    });

    // Digunakan saat dropdown Option->rule question di klik.
    $(document).on('click','.btn-rule1', function() {
        var sequence = $(this).attr('data-sequence');
        var jenis = $(this).attr('data-jenis');
        var no_rule_pertanyaan = 1;
        
        // cek jika ada pilihan duplicate
        var arr = [];
        var dup = [];
        $('.pilihan_'+sequence).each(function(){
            var value = $(this).val();
            $(this).removeClass("duplicate");
            if (arr.indexOf(value) == -1){
                arr.push(value);
            }else{
                $(this).addClass("duplicate");
                dup.push('duplicate');
            }
        });

        if (dup.length > 0) {
          Swal.fire({
            type: 'warning',
            title: "Pemberitahuan",
            text: "Isi pilihan tidak boleh sama",
          })
          // console.log(dup);
        }else{
          $('.pilihan_'+sequence).removeClass("duplicate");

          if ($(this).attr("data-rule") == 'cek') {
              $('#input_rule'+sequence).removeClass()
              $('#input_rule'+sequence).addClass('col-md-12')
              $('#input2_rule'+sequence).removeClass()
              $('#input2_rule'+sequence).addClass('col-md-12')
              $(this).html("<span>Rule Question</span>");
              $('#input2-rule'+sequence).html('')
              $(this).attr("data-rule", 'uncek');
              // $('.disable_pilihan'+sequence).find(':input').prop('readonly', false);
              $('.disable_pilihan'+sequence).prop('readonly', false);
              $('.disable_pilihan'+sequence).removeClass('readonly');
              $('.btn_pilihan'+sequence).prop('disabled', false);
          }else if ($(this).attr("data-rule") == 'uncek') {
              $('.btn_pilihan'+sequence).prop('disabled', true);
              $('.disable_pilihan'+sequence).addClass('readonly');
              // $('.disable_pilihan'+sequence).prop('readonly', true);
              $('#input_rule'+sequence).removeClass()
              $('#input_rule'+sequence).addClass('col-md-6')
              $('#input2_rule'+sequence).removeClass()

              var $inputs = $('.pilihan_'+sequence);
              var input_array = [];
              for(var i = 0; i < $inputs.length; i++){
                  input_array.push($($inputs[i]).val());
              }




              $(this).html("<span><i class='fa fa-check'></i>Rule Question</span>");
              $(this).attr("data-rule", 'cek');

              $.ajax({
                  type: "GET",
                  url: "/rules/1/"+jenis+"/"+sequence, // rules/{counter}/{jenis_id}/{no_urut/sequence}
                  beforeSend: function(){
                        showLoading();
                  },
                  success: function(data){
                      $('#input2-rule'+sequence).append(data);
                      reload_rule_pertanyaan();

                      var elem = document.getElementsByTagName("input");
                      var names = [];
                      for (var i = 0; i < elem.length; ++i) {
                        if (typeof elem[i].attributes.name !== "undefined") {
                          if(elem[i].attributes.name.value === 'pilihan['+sequence+'][]'){
                            // jika input kosong maka tidak akan di append ke dalam select option rule
                            if(elem[i].value != ""){
                              names.push(elem[i].value);
                               // console.log(elem[i].value);
                              var s= document.getElementById('select_rule_pilihan'+sequence+'_1');
                              s.options[s.options.length]= new Option(elem[i].value, elem[i].value);
                            }
                          }
                        }
                      }
                      var elem = $('textarea[name^="pilihan['+sequence+']"]');
                      
                      var names = [];
                      elem.each(function(){
                        
                        names.push(this.value);

                        // jika input kosong maka 
                        if(this.value != ""){
                            names.push(this.value);
                              // console.log(this.value);
                            var s= document.getElementById('select_rule_pilihan'+sequence+'_1');
                            s.options[s.options.length]= new Option(this.value, this.value);
                        }
                      });

                     hideLoading();
                  },
                  error: function(){
                        hideLoading();
                  }
              });
          }

        }

    });


    $(document).on('click','.btn-rule-semantic', function() {
        var sequence = $(this).attr('data-sequence');
        var jenis = $(this).attr('data-jenis');
        var no_rule_pertanyaan = 1;

        if ($(this).attr("data-rule") == 'cek') {
            $('#input_rule'+sequence).removeClass()
            $('#input_rule'+sequence).addClass('col-md-12')
            $('#input2_rule'+sequence).removeClass()
            $('#input2_rule'+sequence).addClass('col-md-12')
            $(this).html("<span>Rule Question</span>");
            $('#input2-rule'+sequence).html('')
            $(this).attr("data-rule", 'uncek');
            $('.disable_pilihan'+sequence).prop('readonly', false);
            $('.btn_pilihan'+sequence).prop('disabled', false);

        }else if ($(this).attr("data-rule") == 'uncek') {
            $('.disable_pilihan'+sequence).prop('readonly', true);
            $('.btn_pilihan'+sequence).prop('disabled', true);
            $('#input_rule'+sequence).removeClass()
            $('#input_rule'+sequence).addClass('col-md-6')
            $('#input2_rule'+sequence).removeClass()

            $(this).html("<span><i class='fa fa-check'></i>Rule Question</span>");
            $(this).attr("data-rule", 'cek');

            $.ajax({
              type: "GET",
              url: "/rules/1/"+jenis+"/"+sequence, // rules/{counter}/{jenis_id}/{no_urut/sequence}
              beforeSend: function(){
                    showLoading();
              },
              success: function(data){
                  $('#input2-rule'+sequence).append(data);
                  reload_rule_pertanyaan();

                  var no_scale = $('#scale'+sequence).val();
                  // jika input kosong maka tidak akan di append ke dalam select option rule
                  if(no_scale != ""){
                    for (var i = 1; i <= no_scale; ++i) {
                        var s= document.getElementById('select_rule_pilihan'+sequence+'_1');
                        s.options[s.options.length]= new Option(i, i);
                    }
                  }

                 hideLoading();
              },
              error: function(){
                    hideLoading();
              }
            });
        }

    });
    $(document).on('click','.add-btn-rule-semantic', function() { //semua id button ganti menjadi add-btn-rule1
        var sequence = $(this).attr('data-sequence');
        var jenis = $(this).attr('data-jenis');
        var no = $(this).attr('data-count');
        var rules = $('.jumlah_rules'+sequence).length;
        var scale = $('#scale'+sequence).val();
        // console.log(rules);
        if (rules < scale) {
         $.ajax({
                type: "GET",
                url: "/rules/"+no+"/"+jenis+"/"+sequence, // rules/{counter}/{jenis_id}/{no_urut/sequence}
                beforeSend: function(){
                      showLoading();
                },
                success: function(data){
                    $('#input2-rule'+sequence).append(data);
                    reload_rule_pertanyaan();

                    var no_scale = $('#scale'+sequence).val();
                    for (var i = 1; i <= no_scale; ++i) {
                        var s= document.getElementById('select_rule_pilihan'+sequence+'_'+no);
                        s.options[s.options.length]= new Option(i, i);
                    }
                    no++;
                     $('.add-btn-rule-semantic').attr('data-count',no)
                    reload_rule_pertanyaan();
                    $('.select-pilihan-semantic').trigger('change');

                   hideLoading();
                },
                error: function(){
                      hideLoading();
                }
             });
         }else{
          Swal.fire({
            type: 'error',
            title: "Rules",
            text: "Rules tidak boleh melebihi jumlah pilihan",
          })
          hideLoading();
        }

    });


    $(document).on('click','.add-btn-rule1', function() { //semua id button ganti menjadi add-btn-rule1
        showLoading();      
        var sequence = $(this).attr('data-sequence');
        var jenis = $(this).attr('data-jenis');
        var no = $(this).attr('data-count');
        var pilihan = $('.pilihan_'+sequence).length;

        // var rules = document.querySelectorAll('#input2-rule'+sequence+' .jumlah_rules'+sequence).length;
        var rules = $('.jumlah_rules'+sequence).length;
        // console.log(rules);
        if (rules < pilihan) {
          $.ajax({
            type: "GET",
            url: "/rules/"+no+"/"+jenis+"/"+sequence,
            beforeSend: function(){
                  showLoading();
            },
            success: function(data){
                $('#input2-rule'+sequence).append(data);
                var elem = document.getElementsByTagName("input");
                var names = [];
                for (var i = 0; i < elem.length; ++i) {
                  if (typeof elem[i].attributes.name !== "undefined") {
                    if(elem[i].attributes.name.value === 'pilihan['+sequence+'][]'){
                      // jika input kosong maka tidak akan di append ke dalam select option rule
                      if(elem[i].value != ""){
                        names.push(elem[i].value);
                        var s= document.getElementById('select_rule_pilihan'+sequence+'_'+no);
                        s.options[s.options.length]= new Option(elem[i].value, elem[i].value);
                      }
                        // var s2= document.getElementById('select_rule_pilihan_2_'+sequence+'_'+no);
                        // s2.options[s2.options.length]= new Option(elem[i].value, elem[i].value);
                    }
                  }
                }
                var elem = $('textarea[name^="pilihan['+sequence+']"]');
                      
                      var names = [];
                      elem.each(function(){
                        
                        names.push(this.value);

                        // jika input kosong maka 
                        if(this.value != ""){
                          names.push(this.value);
                          var s= document.getElementById('select_rule_pilihan'+sequence+'_'+no);
                          s.options[s.options.length]= new Option(this.value, this.value);
                        }
                      });
                no++;
                 $('.add-btn-rule1').attr('data-count',no)
                reload_rule_pertanyaan();
                $('.select-pilihan2').trigger('change');
               hideLoading();
            },
            error: function(){
                  hideLoading();
            }
         });
        }else{
          Swal.fire({
            type: 'error',
            title: "Rules",
            text: "Rules tidak boleh melebihi jumlah pilihan",
          })
          hideLoading();
        }

        // alert(no);
    });

    // function rule_pertanyaan(no){

    //     var rule = $('#btn-rule'+no).attr("data-rule");
    //     var listItem = document.getElementById( "nomor_input" );
    //     var x = $( "#pertanyaan" ).index( listItem )
    //     var pertanyaan = $('#panel-pertanyaan'+no).attr("data-pertanyaan");
        
    //     if ($('#btn-rule'+no).attr("data-rule") == 'cek') {
    //         $('#input_rule'+no).removeClass()
    //         $('#input_rule'+no).addClass('col-md-12')
    //         $('#input2_rule'+no).removeClass()
    //         $('#input2_rule'+no).addClass('col-md-12')
    //         $('#btn-rule'+no).html("<span>Rule Question</span>");
    //         $('#input2-rule'+no).html('')
    //         $('#btn-rule'+no).attr("data-rule", 'uncek');

    //     }else if ($('#btn-rule'+no).attr("data-rule") == 'uncek') {
    //         $('#input_rule'+no).removeClass()
    //         $('#input_rule'+no).addClass('col-md-6')
    //         $('#input2_rule'+no).removeClass()
    //         // $('#input2_rule'+no).addClass('col-md-6')
    //         $('#btn-rule'+no).html("<span><i class='fa fa-check'></i>Rule Question</span>");
    //         $('#btn-rule'+no).attr("data-rule", 'cek');
    //     }
    //     reload_rule_pertanyaan();

    // }

    // var no_rule_pertanyaan = 1;
    // function add_rule_pertanyaan(no){
    //     $('#input2-rule'+no).append(
    //         '<div class="rules'+no+'_'+no_rule_pertanyaan+' jumlah_rules'+no+'">'+
    //             '<div class="col-md-12">'+
    //                 '<div class="form-group">'+
    //                     ' <label>Rule Question Login</label> '+
    //                     '<select name="select_rule_pilihan['+no+'][]" id="select_rule_pilihan'+no+'_'+no_rule_pertanyaan+'" class="form-control select-pilihan2">'+
    //                         '<option>Pilihan</option>'+
    //                     '</select>'+
    //                 '</div>'+
    //             '</div>'+
    //             '<div class="col-md-6">'+
    //                 '<div class="form-group">'+
    //                     ' <label>Rules</label> '+
    //                     '<select name="" id="" class="form-control">'+
    //                         '<option value="">Go To</option>'+
    //                         '<option value="">End Survey</option>'+
    //                     '</select>'+
    //                 '</div>'+
    //             '</div>'+
    //             '<div class="col-md-6">'+
    //                 '<div class="form-group">'+
    //                     ' <label>Pertanyaan</label> '+
    //                     '<select name="select_rule_pertanyaan['+no+'][]" id="select_rule_pertanyaan'+no+'" data-select="pertanyaan" class="form-control select_rule_pertanyaan" data-select-rule'+no_rule_pertanyaan+'="pertanyaan" required>'+
    //                         '<option selected value>Pilih Pertanyaan</option>'+
    //                     '</select>'+
    //                 '</div>'+
    //             '</div>'+
    //             '<div class="">'+
                    
    //                 '<div class="col-md-6 form-group pull-right ">'+
    //                     '<button type="button" class="btn btn-sm btn-danger" id="delete_rule_pertanyaan'+no+'_'+no_rule_pertanyaan+'" data-no="'+no_rule_pertanyaan+'" onClick="delete_rule_pertanyaan('+no+ ',' +no_rule_pertanyaan+')"><i class="fa fa-trash"></i> Delete</button>'+
    //                 '</div>'+
    //                 '<div class="btn-rules'+no+'_'+no_rule_pertanyaan+'">'+

    //                 '</div>'+
    //             '</div>'+
    //         '</div>');
    //     // $('#btn-rules').empty()
    //        var cek_button =  $('.btn-rules'+no+'_'+no_rule_pertanyaan).length;
    //        if(cek_button <= 1){
    //             $('.btn-rules'+no+'_'+no_rule_pertanyaan).append(
    //                 '<div class="col-md-6 form-group ">'+
    //                     '<button type="button" class="btn btn-sm btn-success" onClick="add_rule_pertanyaan('+no+')" id="btn-rules'+no+'"><i class="fa fa-plus"></i> Add</button></div>');
    //        }


    //             // console.log('select_rule_pilihan'+no+'_'+no_rule_pertanyaan);
    //     var elem = document.getElementsByTagName("input");
    //     var names = [];
    //     for (var i = 0; i < elem.length; ++i) {
    //       if (typeof elem[i].attributes.name !== "undefined") {
    //         if(elem[i].attributes.name.value === 'pilihan['+no+'][]'){
    //             names.push(elem[i].value);
    //              console.log(elem[i]);
    //             var s= document.getElementById('select_rule_pilihan'+no+'_'+no_rule_pertanyaan);
    //             s.options[s.options.length]= new Option(elem[i].value, elem[i]);
    //             // s.options[s.options.length]= new Option(elem[i].value, elem[i].value);
    //         }
    //       }
    //     }

    //     // for (var i = 0; i < id_panel.length; ++i) {
    //     //     var urut = i + 1;
    //     //     var newOption = $('<option value="'+id_panel[i]+'">Pertanyaan ke '+urut+'</option>');
    //     //     $(select).append(newOption);
    //     // }
    //     // var ids = $('.connectedSortable').map(function() {
    //     //   return $(this).attr('data-pertanyaan');
    //     // });
    //     // for (var i = 0; i < ids.length; ++i) {
    //     //     var s= document.getElementById('select_rule_pertanyaan'+no);
    //     //     var per = i + 1;
    //     //     s.options[s.options.length]= new Option('Pertanyaan ke '+per,ids[i]);
    //     // }
    //     // console.log(no_rule_pertanyaan);
    //     no_rule_pertanyaan += 1;
    //     reload_rule_pertanyaan();
    // }
   $(document).ready(function(){
      $('#btn_tambah_pertanyaan').on('click',function(){
          var cek = $('#cmb-jenis').val();

          if(cek == ""){

              swal({
                title: "Gagal Menambah Pertanyaan!",
                text: "Harus ada jenis pertanyaan yang dipilih!",
                icon: "error",
                button: "Ok!",
              });
              hideLoading();

          }else{

             var no = $('#no_pertanyaan').val();

              var jenis = $('#cmb-jenis').val();
               $.ajax({
                  type: "GET",
                  url: "/pertanyaan/"+jenis+"/"+no,
                  beforeSend: function(){
                        showLoading();
                  },
                  success: function(data){

                      $('#pertanyaan').append(data);
                     no++;
                     $('#no_pertanyaan').val(no)
                      reload_rule_pertanyaan();
                      tinymce.init({
                          selector: '.static_content',
                          height: 100,
                          plugins: '',
                          toolbar: 'formatselect | bold italic strikethrough forecolor backcolor | numlist bullist outdent indent | removeformat | addcomment',
                       });
                    $('.mt-multiselect').multiselect({
                        enableFiltering: true,
                        buttonWidth: '100%',
                        btn_class:'form-control'
                    });
                  $('.number').number( true);
                     hideLoading();
                  },
                  error: function(){
                        hideLoading();
                  }
               });
          }
      });
    });

   $(document).ready(function(){

      $('#btn_tambah_reusable').on('click',function(){
          var cek = $('#cmb-template').val();
          if(cek == ""){

              swal({
                title: "Gagal Menambah Template!",
                text: "Harus ada template pertanyaan yang dipilih!",
                icon: "error",
                button: "Ok!",
              });
              hideLoading();

          }else{

             var no = $('#no_pertanyaan').val();

              var id = $('#cmb-template').val();
               $.ajax({
                  type: "GET",
                  url: "/get_reusable/"+id+"/"+no,
                  beforeSend: function(){
                        showLoading();
                  },
                  success: function(data){

                      $('#pertanyaan').append(data);
                      no++;
                      $('#no_pertanyaan').val(no)
                      reload_rule_pertanyaan();
                      tinymce.init({
                          selector: '.static_content',
                          height: 100,
                          plugins: '',
                          toolbar: 'formatselect | bold italic strikethrough forecolor backcolor | numlist bullist outdent indent | removeformat | addcomment',
                       });
                       $('.mt-multiselect').multiselect({
                            enableFiltering: true,
                            buttonWidth: '100%',
                            btn_class:'form-control'
                        });
                  $('.number').number( true);
                     hideLoading();

                    },
                  error: function(){
                        hideLoading();
                  }
               });

          }
      });
    });
$(function() {

  $(document).on('change','.select-pilihan2', function(){

    var seq = $(this).data('sequence');
    var no = $(this).data('no');
    var val = $(this).val();

    // seleksi rule pilihan, jika pilihan sudah terpilih maka disable
    var selects = $('.select-rule'+seq);
    $("option", selects).prop("disabled", false);
    selects.each(function() {
      var select = $(this), 
      options = selects.not(select).find('option'),
      selectedText = select.children('option:selected').text();
      options.each(function() {
          if($(this).text() == selectedText) $(this).prop("disabled", true);
      });
    });

  });

  $(document).on('change','.select-pilihan-semantic', function(){
    var seq = $(this).data('sequence');
    var no = $(this).data('no');
    var val = $(this).val();

    // seleksi rule pilihan, jika pilihan sudah terpilih maka disable
    var selects = $('.select-rule'+seq);
    $("option", selects).prop("disabled", false);
    selects.each(function() {
      var select = $(this), 
      options = selects.not(select).find('option'),
      selectedText = select.children('option:selected').text();
      options.each(function() {
          if($(this).text() == selectedText) $(this).prop("disabled", true);
      });
    });
  });

});

</script>
@endpush