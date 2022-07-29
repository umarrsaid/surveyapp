// var array_key = Object.keys(datasoal);
var send = [];
var jawaban = [];
var responden = {};
var detail = [];
var arraySquence = [];
var example = [
  {
    responden: {
      id: 1,
      nama: "Gilang",
      telepon: "08932165498",
      umur: 17,
      email: "gilang@email.com"
    },
    jawaban: [
      {
        responden_id: 1,
        pertanyaan_id: 1,
        pilihan_id: 1,
        isian: null,
        scale: null
      },
      {
        responden_id: 1,
        pertanyaan_id: 2,
        pilihan_id: null,
        isian: "saya sangat menyukai wangi ini",
        scale: null
      }
    ]
  },
  {
    responden: {
      id: 2,
      nama: "Taufik",
      telepon: "08932165498",
      umur: 18,
      email: "taufk@email.com"
    },
    jawaban: [
      {
        responden_id: 2,
        pertanyaan_id: 1,
        pilihan_id: 2,
        isian: null,
        scale: null
      },
      {
        responden_id: 2,
        pertanyaan_id: 3,
        pilihan_id: null,
        isian: null,
        scale: 4
      }
    ]
  }
];
responden.survey_id = $('input[name="survey_id"]').val();
responden.waktu_mulai = $.now();
generateResponden();

// generateSoal(array_key[0]);
function generateResponden() {
  soal = "";
  pertanyaan = "";
  $.each(dataresponden, function(key, value) {
    switch (value.tipe) {
      case "pilihan":
        option = "";
        $.each(value.value.split(","), function(key, value) {
          option += '<option value="' + value + '">' + value + "</option>";
        });
        pertanyaan =
          '<select data-type="' +
          value.tipe +
          '" name="soalresponden[' +
          value.id +
          ']" data-ref="' +
          value.id +
          '" class="form-control">' +
          option +
          "</select>";
        break;

      case "tanggal":
        pertanyaan =
          '<input type="text" data-type="' +
          value.tipe +
          '" name="soalresponden[' +
          value.id +
          ']" data-ref="' +
          value.id +
          '" class="form-control datepicker" ' +
          (value.is_required == 1 ? "required" : "") +
          ">";
        break;

      default:
        pertanyaan =
          '<input data-type="' +
          value.tipe +
          '" name="soalresponden[' +
          value.id +
          ']" data-ref="' +
          value.id +
          '" class="form-control" ' +
          (value.is_required == 1 ? "required" : "") +
          ">";
        break;
    }
    soal +=
      '<div class="form-group">' +
      '<label class="control-label">' +
      value.label +
      "</label>" +
      pertanyaan +
      '<span class="help-block" style="display:none;">' +
      (value.is_required == 1 ? " Tidak boleh kosong" : "") +
      "</span></div>";
  });
  $("#soal").html(soal);
  $("#next").html(
    '<button type="button" data-ref="' +
      array_key[0] +
      '" class="btn btn-lg green next">Selanjutnya <i class="fa fa-arrow-right fa-fw"></i></button>'
  );
  $(".datepicker").datepicker({
    format: "dd-mm-yyyy",
    autoclose: true
  });
}
function generateSoal(id, prev_id = null) {
  id = id.toString();
  var next_id = array_key[parseInt(array_key.indexOf(id)) + 1];

  if (prev_id == null) {
    prev_id = array_key[parseInt(array_key.indexOf(id)) - 1];
  }
  var question = datasoal[id].pertanyaan[0];
  var rules = datasoal[id].rule;
  var antis = datasoal[id].anti;

  var choices = datasoal[id].pilihan;
  var up = "";
  quest =
    question.nomor +
    ". " +
    question.text +
    '<input type="hidden" name="pertanyaan_id[' +
    id +
    ']" value="' +
    id +
    '">';
  var choice = "";
  var down = "";
  switch (question.jenis_id) {
    case "1":
    case "2":
    case "4":
      $.each(choices, function(key, value) {
        datanext = "";

        if (rules.length > 0) {
          findId = rules.find(x => x.pilihan_id === value.id);
          if (typeof findId !== "undefined") {
            datanext = 'data-next="' + findId.next_id + '"';
          }
        }
        choice +=
          '<label class="mt-radio mt-radio-outline">' +
          '<input type="radio" name="pilihan_id[' +
          value.pertanyaan_id +
          ']" data-quest="' +
          value.pertanyaan_id +
          '" ' +
          datanext +
          ' value="' +
          value.id +
          '" ' +
          (value.is_required == 1 ? "required" : "") +
          "> " +
          value.text +
          "<span></span>" +
          "</label>";
      });
      choice = '<div class="mt-radio-list">' + choice + "</div>";
      break;
    case "3":
      $.each(choices, function(key, value) {
        dataanti = "";
        datanext = "";
        if (antis.length > 0) {
          findId = antis.find(x => x.pilihan_id === value.id);
          if (typeof findId !== "undefined") {
            dataanti = 'data-anti="' + findId.pilihan_id_anti + '"';
          }
        }
        if (rules.length > 0) {
          findId = rules.find(x => x.pilihan_id === value.id);
          if (typeof findId !== "undefined") {
            datanext = 'data-next="' + findId.next_id + '"';
          }
        }
        choice +=
          '<label class="mt-checkbox mt-checkbox-outline">' +
          '<input type="checkbox" name="pilihan_id[' +
          value.pertanyaan_id +
          ']" data-ref="' +
          value.pertanyaan_id +
          '" ' +
          dataanti +
          datanext +
          ' value="' +
          value.id +
          '"> ' +
          value.text +
          "<span></span>" +
          "</label>";
      });
      choice = '<div class="mt-checkbox-list">' + choice + "</div>";
      break;
    case "5":
      choice =
        '<input type="text" class="js-range-slider" name="scale[' +
        question.pertanyaan_id +
        ']" value=""' +
        'data-min="1"' +
        'data-max="10"' +
        'data-start="10"' +
        '/><span id="mintext" style="display:none;">' +
        choices.mintext +
        '</span><span id="maxtext"style="display:none;">' +
        choices.maxtext +
        "</span>";
      break;
    case "6":
      choice =
        '<textarea name="isian[' +
        question.pertanyaan_id +
        ']" style="resize:none;" rows="10" class="form-control"></textarea>';
      break;
    case "7":
      quest = question.nomor + ". " + datasoal[id].instruksi[0].text;
      th = "";
      td = "";
      choices1 = datasoal[id].pilihan.filter(
        x => x.pertanyaan_id === question.pertanyaan_id
      );
      $.each(choices1, function(key, value) {
        th += '<th class="text-center">' + value.text + "</th>";
      });

      th = "<tr><th></th>" + th + "</tr>";
      // var next_id = array_key[parseInt(array_key.indexOf(id)) + datasoal[id].pertanyaan.length - 1];
      
      $.each(datasoal[id].pertanyaan, function(key, value) {
        choice = "";
        choices1 = datasoal[id].pilihan.filter(
          x => x.pertanyaan_id === value.pertanyaan_id
        );
        $.each(choices1, function(key, value) {
          choice +=
            '<td class="text-center"><label class="mt-radio mt-radio-outline">' +
            '<input type="radio" name="pilihan_id[' +
            value.pertanyaan_id +
            ']" data-ref="' +
            value.pertanyaan_id +
            '" value="' +
            value.id +
            '">' +
            "<span></span>" +
            "</label></td>";
        });
        td +=
          "<tr><td>" +
          '<input type="hidden" name="pertanyaan_id[' +
          key +
          ']" value="' +
          value.pertanyaan_id +
          '">' +
          value.text +
          "</td>" +
          choice +
          "</tr>";
      });
      choice = '<table class="table table-bordered">' + th + td + "</table>";
      break;
    case "8":
      quest = "";
      // var next_id = array_key[parseInt(array_key.indexOf(id)) + datasoal[id].pertanyaan.length - 1];
      $.each(datasoal[id].pertanyaan, function(key, value) {
        choice = "";
        choices1 = datasoal[id].pilihan.filter(
          x => x.pertanyaan_id === value.pertanyaan_id
        );
        $.each(choices1, function(key, value) {
          if (value.is_multiple == 1) {
            choice +=
              '<label class="mt-checkbox mt-checkbox-outline">' +
              '<input type="checkbox" name="pilihan_id[' +
              value.pertanyaan_id +
              ']" data-ref="' +
              value.pertanyaan_id +
              '" value="' +
              value.id +
              '"> ' +
              value.text +
              "<span></span>" +
              "</label>";
          } else {
            choice +=
              '<label class="mt-radio mt-radio-outline">' +
              '<input type="radio" name="pilihan_id[' +
              value.pertanyaan_id +
              ']" data-ref="' +
              value.pertanyaan_id +
              '" value="' +
              value.id +
              '"> ' +
              value.text +
              "<span></span>" +
              "</label>";
          }
        });
        choice = '<div class="mt-radio-list">' + choice + "</div>";
        quest +=
          value.nomor +
          ". " +
          value.text +
          '<input type="hidden" name="pertanyaan_id[' +
          key +
          ']" value="' +
          value.pertanyaan_id +
          '">' +
          choice;
      });
      choice = "";
      break;
  }
  if (question.position == "Atas") {
    if (question.static != null) {
      up = question.static;
    }
  } else {
    if (question.static != null) {
      down = question.static;
    }
  }
  $("#prev").html(
    '<button type="button" data-ref="start" class="btn btn-lg purple prev"><i class="fa fa-arrow-left fa-fw"></i> Sebelumnya</button>'
  );
  $("#next").html(
    '<button type="button" data-ref="end" class="btn btn-lg green next">Selanjutnya <i class="fa fa-arrow-right fa-fw"></i></button>'
  );
  if (prev_id != null) {
    $("#prev").html(
      '<button type="button" data-ref="' +
        prev_id +
        '" class="btn btn-lg purple prev"><i class="fa fa-arrow-left fa-fw"></i> Sebelumnya</button>'
    );
  }
  
  if (next_id != null) {
    $("#next").html(
      '<button type="button" data-ref="' +
        next_id +
        '" class="btn btn-lg green next">Selanjutnya <i class="fa fa-arrow-right fa-fw"></i></button>'
    );
  }
  $("#soal").html(
    '<div class="form-group">' + up + quest + choice + down + "</div>"
  );
}

function pushSoal() {
  if ($('input[name^="pertanyaan_id"]').length > 0) {
    jawab = {};
    jawab.scale = $('input[name^="scale"]').val();
    jawab.isian = $('textarea[name^="isian"]').val();

    if ($('[name^="pilihan_id"]:checked').length > 1) {
      $('[name^="pilihan_id"]:checked').each(function(key, value) {
        jawab.scale = $('input[name^="scale"]').val();
        jawab.isian = $('textarea[name^="isian"]').val();
        jawab.pertanyaan_id = parseInt($(this).data("ref"));

        jawab.pilihan_id = parseInt($(this).val());
        cek = jawaban.findIndex(
          x => x.pertanyaan_id === parseInt($(this).data("ref")) && x.pilihan_id === parseInt($(this).val())
        );
        if (cek !== -1) {
          jawaban.splice(jawaban.findIndex(item => item.pertanyaan_id === parseInt($(this).data("ref"))), 1);
        }
        jawaban.push(jawab);
        jawab = {};
      });
    } else {
      $('[name^="pertanyaan_id"]').each(function(key, value) {
        jawab.pertanyaan_id = parseInt($(this).val());
        jawab.pilihan_id = parseInt(
          $('input[name^="pilihan_id"]:checked').val()
        );
        cek = jawaban.findIndex(
          x => x.pertanyaan_id === parseInt($(this).val())
        );
        if (cek !== -1) {
          cek2 = jawaban.findIndex(
            x =>
              x.pilihan_id ===
              parseInt($('input[name^="pilihan_id"]:checked').val())
          );
          if (cek2 !== -1) {
            jawaban[cek2] = jawab;
          } else {
            nextId = $("input[name^=pilihan_id]:checked").data("next");

            if (typeof nextId !== "undefined") {
              jawaban.splice(cek, jawaban.length);
            }
          }

          jawaban[cek] = jawab;
        } else {
          jawaban.push(jawab);
        }
      });
    }
  }
}

function checkSoal(id) {
  cek = jawaban.filter(x => x.pertanyaan_id === id);
  if (cek.length > 0) {
    $.each(cek, function(key, value) {
      if (!isNaN(value.pilihan_id)) {
        $('input[value="' + value.pilihan_id + '"]').prop("checked", true);
      }
      if (!isNaN(value.scale)) {
        $("input[name^=scale]").val(value.scale);
      }
      if (value.isian != "") {
        $("textarea[name^=isian]").val(value.isian);
      }
    });
  }
}

function checkRule() {
  nextId = $("input[name^=pilihan_id]:checked").data("next");

  if (typeof nextId !== "undefined" && nextId > 0) {
    $(".next").data("ref", nextId);
    $(".next").data(
      "before",
      $("input[name^=pilihan_id]:checked").data("quest")
    );
  }
}

$(document).delegate(".next, .prev", "click", function(event) {
  var myClass = this.className;
  showLoading();
  switch ($(this).data("ref")) {
    case "end":
      swal({
        title: "Peringatan !",
        text:
          "Anda telah menjawab semua pertanyaan. Apakah Anda yakin ingin menyimpan survey?",
        type: "warning",
        showCancelButton: true,
        cancelButtonColor: "#d33",
        confirmButtonColor: "#3085d6",
        confirmButtonText: "Ya, Akhiri Survey",
        cancelButtonText: "Tidak, kembali ke pertanyaan"
      }).then(result => {
        if (result.value === true) {
          pushSoal();
          responden.waktu_selesai = $.now();
          send[0] = {
            responden: responden,
            detail: detail,
            jawaban: jawaban
          };
          postJawaban(send);
          $("#prev").html("");
          $("#soal").html(
            '<center><img src="' + imageendsurvey + '" width="50%"/></center>'
          );
          $("#next").html(
            '<button type="button" data-ref="end" class="btn btn-lg red exit"><i class="fa fa-close fa-fw"></i> Tutup Aplikasi</button>'
          );
        }
      });

      break;
    case "start":
      $("#prev").html("");
      arraySquence.pop();
      generateResponden();
      break;

    default:
      i = 0;
      $('[name^="soalresponden"]').each(function() {
        if ($(this).prop("required") && $(this).val() == "") {
          $(this)
            .closest(".form-group")
            .addClass("has-error");
          $(this)
            .nextAll(".help-block:first")
            .css("display", "block");
          i = i + 1;
        }
        if ($(this).prop("required") && $(this).val() != "") {
          $(this)
            .closest(".form-group")
            .removeClass("has-error");
          $(this)
            .nextAll(".help-block:first")
            .css("display", "none");
          i = i - 1;
        }
      });
      if (i > 0) {
        break;
      }
      checkRule();
      var total_req = $("input[required]").length;
      var checked_req = $("input[required]:checked").length;
      var end_survey = $("input[name^=pilihan_id]:checked").data("next");

      if (
        (total_req != checked_req &&
          $('[name^="soalresponden"]').length == 0) ||
        end_survey == 0
      ) {
        swal({
          title: "Peringatan !",
          text:
            "Anda memilih Jawaban yang akan mengakhiri survey. Apakah Anda yakin dengan jawaban Anda?",
          type: "warning",
          showCancelButton: true,
          cancelButtonColor: "#d33",
          confirmButtonColor: "#3085d6",
          confirmButtonText: "Ya, Akhiri Survey",
          cancelButtonText: "Tidak, kembali ke pertanyaan"
        }).then(result => {
          if (result.value === true) {
            $("#prev").html("");
            $("#soal").html(
              '<center><img src="' + imageendsurvey + '" width="50%"/></center>'
            );
            $("#next").html(
              '<button type="button" data-ref="end" class="btn btn-lg red exit"><i class="fa fa-close fa-fw"></i> Tutup Aplikasi</button>'
            );
          }
        });
        break;
      }
      var check_input = $('input[name^="pertanyaan_id"]').length;
      var check_isian = $('textarea[name^="isian"]').length;
      
      if (myClass == "btn btn-lg green next") {
        var check_null = $("input:checked").length;

        if (
          (check_null == 0 && $('[name^="soalresponden"]').length == 0 && check_isian == 0) || (check_null < check_input && check_isian == 0)) {
          swal({
            title: "Peringatan !",
            text:
              "Terdapat Pertanyaan yang belum di isi mohon cek kembali jawaban Anda .",
            type: "warning",
            confirmButtonColor: "#3085d6",
            confirmButtonText: "Ya"
          });
          break;
        }
        pushSoal();
        console.log(array_key);
        // console.log(arraySquence);
        console.log(jawaban);
        
        arraySquence.push($(this).data("ref"));
      } else {
        arraySquence.pop();
      }

      if ($('[name^="soalresponden"]').length > 0) {
        det = {};
        $('[name^="soalresponden"]').each(function(key, value) {
          det.soalresp_id = $(this).data("ref");
          det.tipe = $(this).data("type");
          det.value = $(this).val();
          detail.push(det);
          det = {};
        });
      }
      
      generateSoal(
        arraySquence[arraySquence.length - 1],
        $(this).data("before")
      );
      $('[name^="pertanyaan_id"]').each(function(key, value) {
        checkSoal(parseInt($(this).val()));
      })
      $(".js-range-slider").ionRangeSlider({
        skin: "round",
        hide_from_to: "true",
        onChange: function(data) {
          $(".irs-max").text($("#maxtext").text());
          $(".irs-min").text($("#mintext").text());
        }
      });
      $(".irs-max").text($("#maxtext").text());
      $(".irs-min").text($("#mintext").text());
      break;
  }
  hideLoading();
});

$(document).delegate(".exit", "click", function(event) {
  window.top.close();
});

function postJawaban(data) {
  $.ajaxSetup({
    headers: {
      "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
    }
  });
  $.ajax({
    type: "POST",
    url: "/api/post-survey-web",
    contentType: "application/json",
    // data: JSON.stringify(example),
    data: JSON.stringify(data),
    success: function(data) {
      console.log("success");
    },
    error: function(data) {
      console.log("error");
      hideLoading();
    }
  });
}

$(document).delegate('input[type="checkbox"]', "click", function(event) {
  antiId = $(this).data("anti");
  if (typeof antiId !== "undefined") {
    if ($(this).is(":checked")) {
      $('input[value="' + antiId + '"]').prop("disabled", true);
    } else {
      $('input[value="' + antiId + '"]').prop("disabled", false);
    }
  }
});
