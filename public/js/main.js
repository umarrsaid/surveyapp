function showLoading() {
	$("body").LoadingOverlay("show", {
		image: imageloading_path,
	});
}

function hideLoading() {
	$("body").LoadingOverlay("hide", true);
}

function languageDatatable() {
	return {
		"decimal":        "",
		"emptyTable":     "Tak ada data yang tersedia pada tabel ini",
		"info":           "Tampil _START_ s/d _END_ dari _TOTAL_ baris",
		"infoEmpty":      "Menampilkan 0 sampai 0 dari 0 entri",
		"infoFiltered":   "(difiler dari total entri _MAX_)",
		"infoPostFix":    "",
		"thousands":      ",",
		"lengthMenu":     "_MENU_ Baris",
		"loadingRecords": "",
		 "processing":     '<div class="loadingoverlay" style="background-color: rgba(255, 255, 255, 0.8); position: fixed; display: flex; flex-direction: column; align-items: center; justify-content: center; z-index: 2147483647; background-image: url(&quot;/assets/images/loading.gif&quot;); background-position: center center; background-repeat: no-repeat; top: 0px; left: 0px; width: 100%; height: 100%; background-size: 100px;"></div>',
		"search":         "Pencarian :",
		"zeroRecords":    "Tidak ada record yang cocok ditemukan",
		"paginate": {
			  "first"         : '<i class="fa fa-step-backward"></i>',
	          "last"          : '<i class="fa fa-step-forward"></i>',
	          "next"          : '<i class="fa fa-play"></i>',
	          "previous"      : '<i class="fa fa-play fa-rotate-180"></i>'
		}
		
	}
}

function inputAngka(id){
	$("#"+id).keypress(function(data){
	if(data.which!=8 && data.which!=0 && (data.which<48 || data.which>57))
	{
		// $("#pesan").html("isikan angka").show().fadeOut("slow");
		return false;
	}
	});
}