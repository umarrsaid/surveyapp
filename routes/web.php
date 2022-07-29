<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login','AuthController@loginPage')->name('login');
Route::get('/privacy-policy', function () {
    return view('privacy');
});
// Route::get('/login','AuthController@loginPage')->name('login')->middleware('guest');
// Auth::routes();
// Route::get('/token','PageController@index');

Route::get('/input-soal-web/{id}','PageController@inputSoalWeb');
Route::group(['middleware'=>'auth'],function(){
	// Route Page
	Route::get('/', function () {
		return redirect('/interviewer');
	});
	Route::get('/data-user','PageController@user')->name('page_user');
	Route::get('/responden/{id}','PageController@responden')->name('page_responden');
	Route::get('/question/{survey_id}','PageController@question')->name('page_question');
	Route::get('/edit_question/{survey_id}','PageController@editQuestion')->name('page_edit_question');
	Route::get('/input-score-survey/{survey_id}','PageController@inputScoreSurvey')->name('page_input_score_survey');
	Route::get('/survey','PageController@survey')->name('page_survey');
	Route::get('/survey-reus','PageController@surveyReus')->name('page_survey_reus');
	Route::get('/devices','PageController@devices')->name('page_devices');
	Route::get('/interviewer','PageController@interviewer')->name('page_interviewer');
	Route::get('/jawaban','PageController@jawaban')->name('page_jawaban');
	Route::get('/detail-jawaban','PageController@detailJawaban')->name('page_detail_jawaban');
	Route::get('/detail/survey/{id}','PageController@detailSurvey')->name('page_detail_survey');

	// Pertanyaan Reusable
	Route::get('/pertanyaan','PageController@pertanyaanReusable')->name('page_pertanyaan');
	Route::get('/reusable/{survey_id}','PageController@editPertanyaanReusable');
	// Route::get('/edit_reusable/{survey_id}','PageController@editReusable');


	// Route Modal
	Route::get('/modal_test','ModalController@modalTest')->name('modal_test');
	Route::get('/klasifikas-skor/{id?}','ModalController@klasifikasiSkor')->name('klasifikasi_skor');
	Route::get('/soal-resp/{id?}','ModalController@soalResp')->name('soal_resp');
	Route::get('/linked/{id?}','ModalController@linkedSurvey')->name('linked');
	Route::get('/add_modal_survey/{ref?}','ModalController@addModalSurvey')->name('add_modal_survey');
	Route::get('/edit_modal_survey/{id}','ModalController@editModalSurvey')->name('edit_modal_survey');
	Route::get('/copy_modal_survey/{id}','ModalController@copyModalSurvey')->name('copy_modal_survey');

	Route::get('/add_modal_devices','ModalController@addModalDevice')->name('add_modal_devices');
	Route::get('/edit_modal_devices/{id}','ModalController@editModalDevice')->name('edit_modal_devices');

	Route::get('/add_modal_interviewer','ModalController@addModalInterwiewer')->name('add_modal_interviewer');
	Route::get('/edit_modal_interviewer/{id}','ModalController@editModalInterviewer')->name('edit_modal_interviewer');
	
	Route::get('/pertanyaan/{jenis}/{no}','ModalController@pertanyaan')->name('form_pertanyaan');
	Route::get('/pilihan/{jenis}','ModalController@pilihan')->name('form_pilihan');


	Route::get('/rules/{no}/{jenis}/{sequence}','ContentController@rules')->name('form_pertanyaan');
	Route::get('/rules-edit/{sequence}/{jenis}','ContentController@rulesEdit')->name('form_pertanyaan');

	Route::get('/add_modal_reusable','ModalController@addModalReusable')->name('add_modal_reusable');
	Route::get('/edit_modal_reusable/{id}','ModalController@editModalReusable')->name('edit_modal_reusable');
	Route::get('/detail_modal_responden/{survey_id}/{id}','ModalController@detailModalResponden')->name('detail_modal_responden');

	// Route Tabel
	Route::get('/tabel_data_responden','TableController@tabelResponden')->name('tabel_data_responden');
	Route::get('/tabel_data_survey','TableController@tabelSurvey')->name('tabel_data_survey');
	Route::get('/tabel_data_survey_reusable','TableController@tabelSurveyReusable')->name('tabel_data_survey_reusable');
	Route::get('/tabel_data_devices','TableController@tabelDevices')->name('tabel_data_devices');
	Route::get('/tabel_data_reusable','TableController@tabelReusable')->name('tabel_data_reusable');
	Route::get('/tabel_data_interviewer','TableController@tabelInterviewer')->name('tabel_data_interviewer');
    Route::get('table-user', 'TableController@tableUser')->name('table_user');
	
	Route::get('/tabel_survey_jawaban','TableController@tabelSurveyJawaban')->name('tabel_survey_jawaban');
	Route::get('/tabel_survey_responden/{id}','TableController@tabelSurveyResponden')->name('tabel_survey_responden');
	Route::get('/tabel_survey_responden_detail/{id}','TableController@tabelSurveyRespondenDetail')->name('tabel_survey_responden_detail');

	// Route Survey
	Route::get('/edit_survey/{id}','ContentController@editQuestion')->name('edit_survey');
	Route::get('/input_score_survey/{id}','ContentController@inputScoreSurvey')->name('input_score_survey');
	Route::post('/save_pertanyaan/{id}','FormController@pertanyaan')->name('save_pertanyaan');
	Route::post('/save_input_score_survey/{id}','FormController@inputScoreSurvey')->name('save_input_score_survey');
	Route::get('/get_reusable/{id}/{no}','ContentController@reusable')->name('get_reusable');

	//Route Reusable
	Route::get('/get_form_reusable/{jenis}','ModalController@formReusable')->name('form_reusable');
	Route::post('/save_reusable/{id}','FormController@reusable')->name('save_reusable');
	Route::get('/edit_reusable/{id}','ContentController@editReusable')->name('edit_reusable');

	/*Route Modal User*/
	Route::get('modal-tambah-user', 'ModalController@tambahUser')->name('modal_tambah_user');
	Route::get('modal-edit-user', 'ModalController@editUser')->name('modal_edit_user');

	/*Route Report answer*/
	Route::get('/answer/export_excel/{id}', 'ReportController@export')->name('aswerExportexel');

});