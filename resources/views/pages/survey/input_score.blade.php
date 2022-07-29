@extends('template')

@push('title','Input Skor')
@push('main_title','Skor')
@push('sub_main_title','Standard Survey')
@push('active_datasurvey','active')
@push('active_survey_reus','active')
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
                          <div class="form-actions">
                            <button type="submit" class="btn blue">Submit</button>
                            <button type="button" id="btn-cancel" class="btn default">Cancel</button>
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
  
<script type="text/javascript">
    $(document).ready(function() {
        showLoading();
          var survey_id = $('#survey_id').val();
           $.ajax({
              type: "GET",
              url: "/input_score_survey/"+survey_id,
              success: function(data){
                  // console.log(data['rules'][1]);
                  var no = data.nomor;
                  var no_rule_pertanyaan = 1;
                  $('#pertanyaan').append(data.form);


                  tinymce.init({
                    selector: '.static_content',
                    height: 100,
                    plugins: '',
                    toolbar: 'formatselect | bold italic strikethrough forecolor backcolor | numlist bullist outdent indent | removeformat | addcomment',
                    readonly : true
                 });
                   no++;
                  $('#no_pertanyaan').val(no)
                  $('.number').number( true);

                 hideLoading();
              },
              error: function(){
                    hideLoading();
              }
           });
        
        $('#btn-cancel').click(function(e){
            e.preventDefault();
              bootbox.dialog({
                title: '<span class="orange"><i class="fa fa-info"></i> Information</span>',
                message: "Anda yakin akan kembali ke halaman Survey?",
                buttons: {
                    confirm: {
                        label: '<i class="fa fa-check"></i> Ya',
                        className: 'btn btn-default btn-danger',
                        callback: function(){
                                window.location.href = "/survey-reus";
                        }
                    },
                    cancel: {
                    label: '<i class="fa fa-times"></i> Batal',
                    className: 'btn btn-default'
                }, 
              }
          });
        });

        $('#form-pertanyaan').submit(function(e){
            e.preventDefault();
            showLoading();
            var id = $('#survey_id').val();
            $.ajaxSetup({
            headers: {
              'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
            }
            })
            // $('.static').tinyMCE.triggerSave();
            $.ajax({
                type: 'POST',
                url: "/save_input_score_survey/"+id,
                data: $("#form-pertanyaan").serialize(),
                dataType: 'json',
                success: function(data){
                    window.location.href = "/survey-reus";
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
        });
    });

</script>
@endpush