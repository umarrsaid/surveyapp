@extends('template')

@push('title','Question')
@push('main_title','Question')
@push('sub_main_title','Setting')
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
                           {{--  <div class="actions">
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
                            </div>  --}}
                        </div>

                        {{-- <div class="portlet-body form connectedSortable" data-id="'.$no.'" id="panel-pertanyaan'.$no.'">
                                <div class="panel panel-default">
                                  <div class="panel-heading">
                                  <i class="fa fa-reorder grip grip-xl btn btn-default" id="handle-grip"></i>
                                     <span id="nomor"></span> <input type="hidden" id="nomor_input" value="'.$no.'">  Dichotomous
                                    
                                    <div class="pull-right">
                                        <div class="tools">
                                            <div class="btn-group">
                                                <a class="btn btn-outline btn-sm" href="javascript:;" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">Option
                                                    <i class="fa fa-angle-down"></i>
                                                </a>
                                                <ul class="dropdown-menu pull-right">
                                                    <li>
                                                        <a href="javascript:;" id="btn-rule'.$no.'" onClick="rule_pertanyaan('.$no.')" data-rule="uncek"> Rule Question</a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="pull-right">
                                        <div class="tools">
                                            <div class="btn-group">
                                                <a class="btn btn-outline btn-sm" onClick="hapus_pertanyaan('.$no.')" id="hapus_pertanyaan" href="javascript:;">
                                                    <span style="color:red"><i class="fa fa-trash red"></i> Delete</span>
                                                </a>
                                            </div>
                                        </div>
                                    </div> 
                                </div>
                                <div class="panel-body">
                                    <input type="hidden" id="nomer_urut" value="'.$no.'">
                                    <div id="input_rule'.$no.'" class="col-md-6">
                                        <div class="form-group">
                                            <label>Pertanyaan</label>
                                            <input type="text" class="form-control" name="pertanyaan_'.$no.'" id="pertanyaan_'.$no.'" placeholder="Tulis Pertanyaan"> 
                                            <br>
                                            <label>Pilihan</label>
                                        <div class="form-group">
                                            <input type="text" class="form-control" name="pilihan_'.$no.'" placeholder="" value="Ya" readonly> 
                                        </div>
                                        <div class="form-group">
                                            <input type="text" class="form-control" name="pilihan_'.$no.'" placeholder="" value="Tidak" readonly> 
                                        </div>
                                    </div>
                                </div>

                                <div id="input2-rule'.$no.'">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="">Pilihan</label> 
                                            <select name="" id="" class="form-control">
                                                <option value=""></option>
                                                <option value=""></option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <select name="" id="" class="form-control">
                                                <option value=""></option>
                                                <option value=""></option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <select name="" id="" class="form-control">
                                                <option value=""></option>
                                                <option value=""></option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group pull-right">
                                            <button class="btn btn-sm btn-success"><i class="fa fa-plus"></i> Add</button>
                                            <button class="btn btn-sm btn-danger"><i class="fa fa-trash"></i> Delete</button>
                                        </div>
                                    </div>
                                </div>
                                </div>
                                </div>
                            </div>
                        </div> --}}
                        <form role="form" id="form-pertanyaan" method="post" autocomplete="false">
                            {!!csrf_field()!!}
                            <input type="hidden" name="survey_id" value="{{$id}}">
                            {{-- <div class="col-md-1"> --}}
                                {{-- <ol id="nomor_pertanyaan"> --}}
                                    
                                {{-- </ol> --}}
                            {{-- </div> --}}

                            {{-- <div class="col-md-12"> --}}
                                <div id="pertanyaan" >
                                    
                                </div>
                            {{-- </div> --}}
                
                        <div class="portlet-body form" >
                            {{-- <form role="form"> --}}
                                <div class="panel panel-default">
                                  <div class="panel-heading">
                                    Jenis Pertanyaan
                                </div>
                                <div class="panel-body">
                                    <input type="hidden" id="no_pertanyaan" value="1">
                                    <div class="col-md-12">
                                        <div class="form-group ">
                                            {{-- <label>Jenis Pertanyaan</label> --}}
                                            <select id="cmb-jenis" class="form-control">
                                                <option value=""> Pilih Jenis </option>
                                                @foreach(\Iff::jenis() as $jenis)
                                                <option value="{{$jenis->id}}">{{$jenis->nama}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <button type="button" class="btn blue" id="btn_tambah_pertanyaan"><i class="fa fa-plus"></i> Tambah Pertanyaan</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                          <div class="form-actions">
                            <button type="submit" class="btn blue">Submit</button>
                            <button type="button" id="tesbtn" class="btn default">Cancel</button>
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
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script type="text/javascript">
    $(document).ready(function() {

        $('#tesbtn').click(function(e){
            e.preventDefault();
            var x = $('select[data-select-rule="pertanyaan1"]').length;
            alert(x);
        });
        // $('.line-pilihan').on('click',function(){
        //     alert('sa');

        // });
        $('#form-pertanyaan').submit(function(e){
            e.preventDefault();
            showLoading();
            
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

                    $.ajaxSetup({
                    headers: {
                      'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
                    }
                    })
                    $.ajax({
                        type: 'POST',
                        url: "/save_pertanyaan",
                        data: $("#form-pertanyaan").serialize(),
                        // data: $("#form-pertanyaan").sortable("toArray"),
                        dataType: 'json',
                        success: function(data){
                            // window.location.href = "/survey";
                            swal({
                                title: "Sukses Menyimpan!",
                                text: "Sukses Menambah Survey",
                                icon: "success",
                                button: "Ok!",
                                timer: 2000
                            })
                            hideLoading();
                        },
                        error:function(data){
                            hideLoading();
                        }
                    });
            }
        });

    

        // $("#pertanyaan").sortable({ handle: '#handle-grip' });
        // $("#pilihan-grip").sortable({ handle: '#handle-grip-pilihan' });
        $(function() {
           // console.log(no)
            $( "#pertanyaan" ).sortable({
                handle: '#handle-grip',
                update: function(event, ui) {
                    var changedList = this.id;
                    var order = $(this).sortable('toArray');
                    var positions = order.join(';');
                    // console.log(order);
                    // $.each( order, function( key, value ) {
                      // alert( key + ": " + value );
                      // $('#'+value+' #nomor_input').val(key+1)
                    // });
                    // console.log({
                    //   id: changedList,
                    //   positions: positions
                    // });

                    reload_rule_pertanyaan();
                  },
            });
            $( "#pertanyaan" ).disableSelection();
        });

        // $("#pertanyaan").sortable({

        //      start: function(e, ui) {
        //         // creates a temporary attribute on the element with the old index
        //         $(this).attr('data-previndex', ui.item.index());
        //         console.log(ui);
        //     },
        //     update: function(e, ui) {
        //         // gets the new and old index then removes the temporary attribute
        //         var newIndex = ui.item.index();
        //         var oldIndex = $(this).attr('data-previndex');
        //         var element_id = ui.item.attr('id');
        //         alert('id of Item moved = '+element_id+' old position = '+oldIndex+' new position = '+newIndex);
        //         $(this).removeAttr('data-previndex');
        //         $('#nomor').val(newIndex + 1);
                
        //     }
        // placeholder: 'sortable-placeholder',
        // opacity: 0.6,
        // helper: 'clone',
        // sort: function(event,ui){
        //     var no = Number($('#pertanyaan > div:visible').index(ui.placeholder)+1);
        //     $(ui.placeholder).html(Number($('#pertanyaan > div:visible').index(ui.placeholder)+1));
        // }
    // });
        // $("#pertanyaan").disableSelection();
      // $('.component-container').sortable({
      //   cursor: 'move',
      //   placeholder: 'ui-state-highlight',
      //   start: function(e, ui) {
      //       console.log('tes');
      //     ui.placeholder.width(ui.item.find('.connectedSortable').width());
      //     ui.placeholder.height(ui.item.find('.connectedSortable ').height());
      //     ui.placeholder.addClass(ui.item.attr("class"));
      //   }
      // });
    });
    // function delete_rule_pertanyaan(no){
    //    $("#rules"+no).remove();
    //     reload_rule_pertanyaan();
    // }
    function delete_rule_pertanyaan(no,id){
        var jumlah_rules = $('.jumlah_rules'+no).length
        if(jumlah_rules <= 1){
            swal({
              title: "Gagal Menghapus!",
              text: "Panel Rule tidak bisa dihapus semua!",
              icon: "error",
              button: "Ok!",
            });
        }else{
            var select = $(".rules"+no+"_"+id ).remove();
            
        }
        reload_rule_pertanyaan();
    }
    // function pertanyaan(no){
    //    $('#pertanyaan_'+no).append(
    //         '<div class="form-group" id="line-pertanyaan'+no+'">'+
    //             '<div class="input-group">'+
    //             '<input type="text" class="form-control" name="pertanyaan['+no+'][]" placeholder="Pertanyaan"> '+
    //         '<span class="input-group-addon" ><a href="javascript:;" onClick="hapus_pertanyaan('+no+')" class="fa fa-trash" style="color:red;"> Delete</a> </span>'+
    //     '</div>'
    //     );
    // }

    // function hapus_pertanyaan_matrix(no){
    //     $("#line-pertanyaan"+no).last().remove();
    // }

    // function pilihan(no){
    //    $('#pilihan_'+no).append(
    //         '<div class="form-group " id="line-pilihan'+no+'_'+counter+'">'+
    //             '<div class="input-group">'+
    //             '<input type="text" class="form-control keyup-pilihan2" name="pilihan['+no+'][]" placeholder="Pilihan"> '+
    //         '<span class="input-group-addon" ><a href="javascript:;" data-no="'+no+'" id="line-pilihan" onClick="hapus_pilihan('+no+')" class="fa fa-trash" style="color:red;"> Delete</a> </span>'+
    //     '</div>'
    //     );
    // }

    // $(document).on('keyup','.keyup-pilihan2', function() {
    //     console.log($(this).val());
    // });

    $(document).on('click','#btn_tambah_instruksi', function() {
        var no = $(this).attr('data-no-instruksi');
        var counter = $(this).attr('data-counter-instruksi');
        counter++;
        $('#instruksi_'+no).append(
            '<div class="form-group " id="line-instruksi'+no+'_'+counter+'">'+
                '<div class="input-group">'+
                '<input type="text" class="form-control" name="pilihan['+no+'][]" placeholder="Tulis Instruksi"> '+
            '<span class="input-group-addon" ><a href="javascript:;" data-counter="'+counter+'" data-no="'+no+'" id="line-instruksi" class="fa fa-trash" style="color:red;"> Delete</a> </span>'+
        '</div>'
        );
        $(this).attr('data-counter-instruksi',counter);
    });

    $(document).on('click','#line-instruksi', function() {
        var no = $(this).attr('data-no');
        var counter = $(this).attr('data-counter');
        $("#line-instruksi"+no+'_'+counter).last().remove()
    });



    $(document).on('click','#btn_tambah_pertanyaan7', function() {
        var no = $(this).attr('data-no-pertanyaan');
        var counter = $(this).attr('data-counter-pertanyaan');
        counter++;
        $('#pertanyaan_'+no).append(
            '<div class="form-group " id="line-pertanyaan'+no+'_'+counter+'">'+
                '<div class="input-group">'+
                '<input type="text" class="form-control" name="pertanyaan['+no+'][]" placeholder="Pertanyaan"> '+
            '<span class="input-group-addon" ><a href="javascript:;" data-counter="'+counter+'" data-no="'+no+'" id="line-pertanyaan" class="fa fa-trash" style="color:red;"> Delete</a> </span>'+
        '</div>'
        );
        $(this).attr('data-counter-pertanyaan',counter);
    });

    $(document).on('click','#line-pertanyaan', function() {
        var no = $(this).attr('data-no');
        var counter = $(this).attr('data-counter');
        $("#line-pertanyaan"+no+'_'+counter).last().remove()
    });

    $(document).on('click','#btn_tambah_scale7', function() {
        var no = $(this).attr('data-no-scale');
        var counter = $(this).attr('data-counter-scale');
        counter++;
        $('#pilihan_'+no).append(
            '<div class="form-group " id="line-pilihan'+no+'_'+counter+'">'+
                '<div class="input-group">'+
                '<input type="text" class="form-control" name="pilihan['+no+'][]" placeholder="Scale Text"> '+
            '<span class="input-group-addon" ><a href="javascript:;" data-counter="'+counter+'" data-no="'+no+'" id="line-pilihan" class="fa fa-trash" style="color:red;"> Delete</a> </span>'+
        '</div>'
        );
        $(this).attr('data-counter-scale',counter);
    });

    $(document).on('click','#btn_tambah_scale4', function() {
        var no = $(this).attr('data-no-pilihan');
        var counter = $(this).attr('data-case');
        counter++;
        $('#pilihan_'+no).append(
            '<div class="form-group " id="line-pilihan'+no+'_'+counter+'">'+
                '<div class="input-group">'+
                '<input type="text" class="form-control" name="pilihan['+no+'][]" placeholder="Scale Text"> '+
            '<span class="input-group-addon" ><a href="javascript:;" data-counter="'+counter+'" data-no="'+no+'" id="line-pilihan" class="fa fa-trash" style="color:red;"> Delete</a> </span>'+
        '</div>'
        );
        $(this).attr('data-case',counter);
    });

    $(document).on('click','#btn_tambah_pilihan3', function() {
        var no = $(this).attr('data-no-pilihan');
        var counter = $(this).attr('data-case');
        counter++;
        $('#pilihan_'+no).append(
            '<div class="form-group " id="line-pilihan'+no+'_'+counter+'">'+
                '<div class="input-group">'+
                '<input type="text" class="form-control" name="pilihan['+no+'][]" placeholder="Pilihan"> '+
            '<span class="input-group-addon" ><a href="javascript:;" data-counter="'+counter+'" data-no="'+no+'" id="line-pilihan" class="fa fa-trash" style="color:red;"> Delete</a> </span>'+
        '</div>'
        );
        $(this).attr('data-case',counter);
    });

    $(document).on('click','#btn_tambah_pilihan2', function() {
        var no = $(this).attr('data-no-pilihan');
        var counter = $(this).attr('data-case');
        counter++;
        $('#pilihan_'+no).append(
            '<div class="form-group " id="line-pilihan'+no+'_'+counter+'">'+
                '<div class="input-group">'+
                '<input type="text" class="form-control" name="pilihan['+no+'][]" placeholder="Pilihan"> '+
            '<span class="input-group-addon" ><a href="javascript:;" data-counter="'+counter+'" data-no="'+no+'" id="line-pilihan" class="fa fa-trash" style="color:red;"> Delete</a> </span>'+
        '</div>'
        );
        $(this).attr('data-case',counter);
        reload_rule_pertanyaan();

    });

    $(document).on('click','#line-pilihan', function() {
        var no = $(this).attr('data-no');
        var counter = $(this).attr('data-counter');
        $("#line-pilihan"+no+'_'+counter).last().remove()
        reload_rule_pertanyaan();
    });
    

    function hapus_pertanyaan(no){
        $("#panel-pertanyaan"+no).remove();
        reload_rule_pertanyaan();
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

        // console.log(selected);
        // mengambil select pertanyaan
        var selected_id = $('.select_rule_pertanyaan').map(function() {
            var select = $(this).attr('id');
          return select;
        });

        // console.log(selected_id);
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
        }

        // console.log(select_rule);

        for (var i = 0; i < id_panel.length; ++i) {
            $('#'+selected_id[i]).val(selected[i]);
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

    
    function rule_pertanyaan(no){

        var rule = $('#btn-rule'+no).attr("data-rule");
        var listItem = document.getElementById( "nomor_input" );
        var x = $( "#pertanyaan" ).index( listItem )
        var pertanyaan = $('#panel-pertanyaan'+no).attr("data-pertanyaan");
        console.log(no);
        // var elem = document.getElementById('pilihan_'+no);
        

        // alert(names);
        if ($('#btn-rule'+no).attr("data-rule") == 'cek') {
            $('#input_rule'+no).removeClass()
            $('#input_rule'+no).addClass('col-md-12')
            $('#input2_rule'+no).removeClass()
            $('#input2_rule'+no).addClass('col-md-12')
            $('#btn-rule'+no).html("<span>Rule Question</span>");
            $('#input2-rule'+no).html('')
            $('#btn-rule'+no).attr("data-rule", 'uncek');

        }else if ($('#btn-rule'+no).attr("data-rule") == 'uncek') {
            $('#input_rule'+no).removeClass()
            $('#input_rule'+no).addClass('col-md-6')
            $('#input2_rule'+no).removeClass()
            // $('#input2_rule'+no).addClass('col-md-6')
            $('#btn-rule'+no).html("<span><i class='fa fa-check'></i>Rule Question</span>");
            $('#btn-rule'+no).attr("data-rule", 'cek');
            add_rule_pertanyaan(no);
            // $('#input2-rule'+no).append(
            //                         '<div class="rules'+no+'_1">'+
            //                             '<div class="col-md-12">'+
            //                                 '<div class="form-group">'+
            //                                     ' <label>Rule Question Login</label> '+
            //                                     '<select name="select_rule_pilihan['+no+'][]" id="select_rule_pilihan'+no+'_1" class="form-control select_rule_pilihan">'+
            //                                         '<option>Pilihan</option>'+
            //                                     '</select>'+
            //                                 '</div>'+
            //                             '</div>'+
            //                             '<div class="col-md-6">'+
            //                                 '<div class="form-group">'+
            //                                     ' <label>Rules</label> '+
            //                                     '<select name="" id="" class="form-control">'+
            //                                         '<option value="">Go To</option>'+
            //                                         '<option value="">End Survey</option>'+
            //                                     '</select>'+
            //                                 '</div>'+
            //                             '</div>'+
            //                             '<div class="col-md-6">'+
            //                                 '<div class="form-group">'+
            //                                     ' <label>Pertanyaan</label> '+
            //                                     '<select name="select_rule_pertanyaan['+no+'][]" id="select_rule_pertanyaan'+no+'" data-select="pertanyaan" class="form-control select_rule_pertanyaan" data-select-rule'+no+'="pertanyaan" required>'+
            //                                         '<option selected value>Pilih Pertanyaan</option>'+
            //                                     '</select>'+
            //                                 '</div>'+
            //                             '</div>'+
            //                             '<div class="col-md-12">'+
            //                                     '<button type="button" class="btn btn-sm btn-success" onClick="add_rule_pertanyaan('+no+')" id="btn-rule'+no+'"><i class="fa fa-plus"></i> Add</button>'+
            //                                 '<div class="form-group pull-right">'+
            //                                     '<button type="button" class="btn btn-sm btn-danger" onClick="delete_rule_pertanyaan('+no+',1)"><i class="fa fa-trash"></i> Delete</button>'+
            //                                 '</div>'+
            //                             '</div>'+
            //                         '</div>');
            // var elem = document.getElementsByTagName("input");
            // var names = [];
            // for (var i = 0; i < elem.length; ++i) {
            //   if (typeof elem[i].attributes.name !== "undefined") {
            //     if(elem[i].attributes.name.value === 'pilihan['+no+'][]'){
            //         names.push(elem[i].value);
            //          // console.log(elem[i].value);
            //         var s= document.getElementById('select_rule_pilihan'+no+'_1');
            //         s.options[s.options.length]= new Option(elem[i].value, elem[i].value);
            //     }
            //   }
            // }



            // var pertanyaan = $('.connectedSortable').length;
            // for (var i = 1; i <= pertanyaan; ++i) {
            //     var s= document.getElementById('select_rule_pertanyaan'+no);
            //     s.options[s.options.length]= new Option('Pertanyaan ke '+i);
            // }

            // var ids = $('.connectedSortable').map(function() {
            //   return $(this).attr('data-pertanyaan');
            // });
            // for (var i = 0; i < ids.length; ++i) {
            //     var s= document.getElementById('select_rule_pertanyaan'+no);
            //     var per = i + 1;
            //     s.options[s.options.length]= new Option('Pertanyaan ke '+per,ids[i]);
            // }
            // console.log(ids[0]);


        }
        reload_rule_pertanyaan();

    }

    var no_rule_pertanyaan = 1;
    function add_rule_pertanyaan(no){
        $('#input2-rule'+no).append(
            '<div class="rules'+no+'_'+no_rule_pertanyaan+' jumlah_rules'+no+'">'+
                '<div class="col-md-12">'+
                    '<div class="form-group">'+
                        ' <label>Rule Question Login</label> '+
                        '<select name="select_rule_pilihan['+no+'][]" id="select_rule_pilihan'+no+'_'+no_rule_pertanyaan+'" class="form-control select-pilihan2">'+
                            '<option>Pilihan</option>'+
                        '</select>'+
                    '</div>'+
                '</div>'+
                '<div class="col-md-6">'+
                    '<div class="form-group">'+
                        ' <label>Rules</label> '+
                        '<select name="" id="" class="form-control">'+
                            '<option value="">Go To</option>'+
                            '<option value="">End Survey</option>'+
                        '</select>'+
                    '</div>'+
                '</div>'+
                '<div class="col-md-6">'+
                    '<div class="form-group">'+
                        ' <label>Pertanyaan</label> '+
                        '<select name="select_rule_pertanyaan['+no+'][]" id="select_rule_pertanyaan'+no+'" data-select="pertanyaan" class="form-control select_rule_pertanyaan" data-select-rule'+no_rule_pertanyaan+'="pertanyaan" required>'+
                            '<option selected value>Pilih Pertanyaan</option>'+
                        '</select>'+
                    '</div>'+
                '</div>'+
                '<div class="">'+
                    
                    '<div class="col-md-6 form-group pull-right ">'+
                        '<button type="button" class="btn btn-sm btn-danger" id="delete_rule_pertanyaan'+no+'_'+no_rule_pertanyaan+'" data-no="'+no_rule_pertanyaan+'" onClick="delete_rule_pertanyaan('+no+ ',' +no_rule_pertanyaan+')"><i class="fa fa-trash"></i> Delete</button>'+
                    '</div>'+
                    '<div class="btn-rules'+no+'_'+no_rule_pertanyaan+'">'+

                    '</div>'+
                '</div>'+
            '</div>');
        // $('#btn-rules').empty()
           var cek_button =  $('.btn-rules'+no+'_'+no_rule_pertanyaan).length;
           if(cek_button <= 1){
                $('.btn-rules'+no+'_'+no_rule_pertanyaan).append(
                    '<div class="col-md-6 form-group ">'+
                        '<button type="button" class="btn btn-sm btn-success" onClick="add_rule_pertanyaan('+no+')" id="btn-rules'+no+'"><i class="fa fa-plus"></i> Add</button></div>');
           }


                // console.log('select_rule_pilihan'+no+'_'+no_rule_pertanyaan);
        var elem = document.getElementsByTagName("input");
        var names = [];
        for (var i = 0; i < elem.length; ++i) {
          if (typeof elem[i].attributes.name !== "undefined") {
            if(elem[i].attributes.name.value === 'pilihan['+no+'][]'){
                names.push(elem[i].value);
                 // console.log(elem[i].value);
                var s= document.getElementById('select_rule_pilihan'+no+'_'+no_rule_pertanyaan);
                s.options[s.options.length]= new Option(elem[i].value, elem[i].value);
            }
          }
        }

        // for (var i = 0; i < id_panel.length; ++i) {
        //     var urut = i + 1;
        //     var newOption = $('<option value="'+id_panel[i]+'">Pertanyaan ke '+urut+'</option>');
        //     $(select).append(newOption);
        // }
        // var ids = $('.connectedSortable').map(function() {
        //   return $(this).attr('data-pertanyaan');
        // });
        // for (var i = 0; i < ids.length; ++i) {
        //     var s= document.getElementById('select_rule_pertanyaan'+no);
        //     var per = i + 1;
        //     s.options[s.options.length]= new Option('Pertanyaan ke '+per,ids[i]);
        // }
        // console.log(no_rule_pertanyaan);
        no_rule_pertanyaan += 1;
        reload_rule_pertanyaan();
    }
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
                           hideLoading();
                        },
                        error: function(){
                              hideLoading();
                        }
                     });
                }
            });
    });

</script>
@endpush