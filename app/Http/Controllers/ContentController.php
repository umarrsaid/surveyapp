<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class ContentController extends Controller
{
    public function editQuestion($ids,Request $request){
        // $data = \DB::table('pertanyaan_survey')->where('survey_id',$id)->get();
        $id = Crypt::decryptString($ids);
        $datas = \DB::select(\DB::raw('select * from view_pertanyaan_jenis where survey_id = '.$id.' order by sequence ASC  '));
        // $data = \DB::select(\DB::raw('select * from view_pertanyaan_jenis where survey_id = '.$id.' group by sequence order by sequence ASC  '));
		// $data = \DB::select(\DB::raw('select * from view_pertanyaan_jenis where survey_id = '.$id.' '));
		$data = [];
		foreach ($datas as $key => $value) {
			$data[$value->sequence] = $value;
		}
        
        $nomor = 0;
            foreach($data as $key => $d){
            		switch ($d->jenis_id) {
            			case '1':

					        $rule_data = \DB::select(\DB::raw('SELECT * FROM view_rule_data
																where pertanyaan_id = '.$d->pertanyaan_id.''));
					        $no_rule1 = 1;

					        foreach ($rule_data as $key1 => $value) {
						        $rule_pilihan = \DB::select(\DB::raw('SELECT * from view_pertanyaan_pilihan where pertanyaan_id = '.$d->pertanyaan_id.''));
						        foreach ($rule_pilihan as $key2 => $val_rule) {
						        	if ($value->text == $val_rule->text) {
						        		$select = 'selected';
						        	}else{
						        		$select = '';
						        	}
						        	$pilihan_form[$d->sequence][$key1][] = '<option '.$select.'>'.$val_rule->text.'</option>';
						        }

								$rule_pertanyaan = \DB::select(\DB::raw('SELECT * from pertanyaan_survey where survey_id = '.$id.' AND deleted_at IS NULL '));
						        foreach ($rule_pertanyaan as $key3 => $val_rule_pertanyaan) {

						        	if ($value->next_id == $val_rule_pertanyaan->pertanyaan_id) {
						        		$selected = 'selected';
						        	}else{
						        		$selected = '';
						        	}
						        	if ($value->next_id == 0) {
						        		$select_disable = 'disabled';
						        		$select_end = 'selected';
						        		$select_go = '';
						        	}else{
						        		$select_disable = 'required';
						        		$select_go = 'selected';
						        		$select_end = '';
						        	}

						        	$pertanyaan_form[$d->sequence][$key1][] = '<option value="'.$val_rule_pertanyaan->sequence.'" '.$selected.'> Pertanyaan Ke '.$val_rule_pertanyaan->sequence.'</option>';
						        }

            						$rule_form[$d->sequence][] = '<div class="rules'.$d->sequence.'_'.$no_rule1.' jumlah_rules'.$d->sequence.'">
						                <div class="col-md-12">
						                    <div class="form-group">
						                        <label>Rule Question Pilihan</label> 
						                        <select name="select_rule_pilihan['.$d->sequence.']['.($no_rule1 - 1).']" id="select_rule_pilihan'.$d->sequence.'_'.$no_rule1.'" class="form-control select-pilihan2 select-rule'.$d->sequence.'"  data-sequence="'.$d->sequence.'" data-no="'.$no_rule1.'" required>
						                            <option value="">Pilihan</option>
						                            '.(isset($pilihan_form[$d->sequence][$key1]) ? implode(" ", $pilihan_form[$d->sequence][$key1]) : '').'
						                        </select>
						                    </div>
						                </div>
						                <div class="col-md-6">
						                    <div class="form-group">
						                         <label>Rules</label> 
						                        <select name="rules['.$d->sequence.']['.($no_rule1 - 1).']" id="" class="form-control rules" data-id="'.$d->sequence.'" data-no="'.$no_rule1.'" >
						                            <option value="go" '.$select_go.'>Go To</option>
						                            <option value="end" '.$select_end.'>End Survey</option>
						                        </select>
						                    </div>
						                </div>
						                <div class="col-md-6">
						                    <div class="form-group">
						                        <label>Pertanyaan</label>
						                        <select name="select_rule_pertanyaan['.$d->sequence.']['.($no_rule1 - 1).']" id="select_rule_pertanyaan'.$d->sequence.'_'.$no_rule1.'" data-select="pertanyaan" class="form-control select_rule_pertanyaan" data-select-rule'.$no_rule1.'="pertanyaan" '.$select_disable.' required>
						                            <option selected value>Pilih Pertanyaan</option>
						                            '.(isset($pertanyaan_form[$d->sequence][$key1]) ? implode(" ", $pertanyaan_form[$d->sequence][$key1]) : '').'

						                        </select>
						                    </div>
						                </div>
						                    
						                    <div class="col-md-6 form-group pull-right ">
						                        <button type="button" class="btn btn-sm btn-danger btn-circle" id="delete_rule_pertanyaan'.$d->sequence.'_'.$no_rule1.'" data-no="'.$no_rule1.'" onClick="delete_rule_pertanyaan('.$d->sequence. ',' .$no_rule1.')"><i class="fa fa-trash"></i> Delete</button>
						                    </div>
						                    <div class="col-md-6 form-group ">
						                        <button type="button" class="btn btn-sm btn-success btn-circle add-btn-rule1" data-count="'.($no_rule1 + 1).'" data-jenis="'.$d->jenis_id.'" data-sequence="'.$d->sequence.'" id="btn-rules'.$d->sequence.'"><i class="fa fa-plus"></i> Add</button></div>
						            </div>';
						            $no_rule1++;
					        }


							$pilihan = \DB::select(\DB::raw('select id, text from pilihan where pertanyaan_id = '.$d->pertanyaan_id.''));
		                    $form[] = '<div class="portlet-body form connectedSortable" data-pertanyaan="'.$d->sequence.'" id="panel-pertanyaan'.$d->sequence.'">
		                            <div class="panel panel-primary">
		                              <div class="panel-heading">
		                              <i class="fa fa-reorder grip grip-xl btn btn-success btn-circle" id="handle-grip"></i>
		                                 <span id="nomor"></span> <input type="hidden" id="sequence" name="sequence[]" value="'.$d->sequence.'"> 
		                                 <input type="hidden" id="tipe" name="tipe['.$d->sequence.'][]" value="1">  
		                                 Dichotomous
		                                
		                                <div class="pull-right">
		                                    <div class="tools">
		                                        <div class="btn-group">
		                                            <a class="btn btn-primary btn-sm btn-circle" href="javascript:;" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">Option
		                                                <i class="fa fa-angle-down"></i>
		                                            </a>
		                                            <ul class="dropdown-menu pull-right">
		                                                <li>
		                                                    <a href="javascript:;" class="btn-rule1" id="btn-rule1" data-jenis="1" data-sequence="'.$d->sequence.'" data-count="1" data-rule="'.(isset($pilihan_form[$d->sequence]) ? 'cek' : 'uncek' ).'">'.(isset($pilihan_form[$d->sequence]) ? '<i class="fa fa-check"></i>' : '' ).' Rule Question</a>
		                                                </li>
		                                            </ul>
		                                        </div>
		                                    </div>
		                                </div>
		                                <div class="pull-right">
		                                    <div class="tools">
		                                        <div class="btn-group">
		                                            <a class="btn btn-danger btn-sm btn-circle" onClick="hapus_pertanyaan('.$d->sequence.')" id="hapus_pertanyaan" href="javascript:;">
		                                                <span><i class="fa fa-trash red"></i> Delete</span>
		                                            </a>
		                                        </div>
		                                    </div>
		                                </div> 
		                            </div>

		                            <div class="panel-body">
		                                <div class="row">
		                                    <input type="hidden" id="nomer_urut" value="'.$d->sequence.'">
		                                    <div id="input_rule'.$d->sequence.'" class="col-md-12">

			                                    <div class="form-group col-md-12">
	                                                <label>Static Content</label>
	                                                <textarea class="static_content" name="static_content['.$d->sequence.'][]" id="static_content'.$d->sequence.'" >'.$d->static.'
	                                                </textarea>
	                                            </div>
	                                            <div class="form-group col-md-3">
													<label>Posisi Static</label>
													<input type="hidden" name="id_pertanyaan['.$d->sequence.'][]" value="'.$d->pertanyaan_id.'">  
	                                                <select class="form-control input-sm" name="posisi['.$d->sequence.'][]" id="posisi_'.$d->sequence.'">
	                                                    <option>Atas</option>
	                                                    <option>Bawah</option>
	                                                </select>
	                                                <br>
	                                                <label>Nomor</label>
	                                                <input class="form-control input-sm" name="nomor_pertanyaan['.$d->sequence.'][]" id="nomor_pertanyaan_'.$d->sequence.'" placeholder="No. Pertanyaan" value="'.$d->nomor.'" maxlength="9" required>
	                                            </div>

		                                        <div class="form-group col-md-9">
		                                            <label>Pertanyaan</label>
		                                            <textarea type="text" class="form-control input-sm" rows="5" name="pertanyaan['.$d->sequence.'][]" id="pertanyaan_'.$d->sequence.'" placeholder="Tulis Pertanyaan" maxlength="500" required>'.\Iff::namaPertanyaan($d->pertanyaan_id).'</textarea>
		                                        </div>
		                                        <div class="form-group col-md-12">
													<label>Pilihan</label>
													<input type="hidden" name="id_pilihan['.$d->sequence.'][]" value="'.$pilihan[0]->id.'">  
		                                            <input type="text" class="form-control input-sm pilihan_'.$d->sequence.'" id="pilihan_'.$d->sequence.'" name="pilihan['.$d->sequence.'][]" placeholder="" value="Ya" readonly> 
		                                        </div>
		                                        <div class="form-group col-md-12">
												<input type="hidden" name="id_pilihan['.$d->sequence.'][]" value="'.$pilihan[1]->id.'">  
		                                            <input type="text" class="form-control input-sm pilihan_'.$d->sequence.'" id="pilihan_'.$d->sequence.'" name="pilihan['.$d->sequence.'][]" placeholder="" value="Tidak" readonly> 
		                                        </div>
		                                    </div>

			                                <div id="input2-rule'.$d->sequence.'" class="col-md-6">
			                                '.(isset($rule_form[$d->sequence]) ? implode(" ", $rule_form[$d->sequence]) : '').'
			                                </div>
		                                </div>
		                                <div id="btn-add-rule'.$d->sequence.'" class="col-md-6">
		                                </div>
		                            </div>
		                        </div>
		                        </div>
		                    </div>';
		                    $nomor ++;
	                // return $form;

        				break;
        				
        				case '2':

	        				 $rule_data = \DB::select(\DB::raw('SELECT * FROM view_rule_data
																	WHERE pertanyaan_id = '.$d->pertanyaan_id.''));
						        $no_rule1 = 1;

						        foreach ($rule_data as $key1 => $value) {
							        $rule_pilihan = \DB::select(\DB::raw('SELECT * from view_pertanyaan_pilihan where pertanyaan_id = '.$d->pertanyaan_id.' and deleted_at IS NULL'));
							        foreach ($rule_pilihan as $key2 => $val_rule) {
							        	if ($value->text == $val_rule->text) {
							        		$select = 'selected';
							        	}else{
							        		$select = '';
							        	}
							        	$pilihan_form[$d->sequence][$key1][] = '<option '.$select.'>'.$val_rule->text.'</option>';
							        }

							        $rule_pertanyaan = \DB::select(\DB::raw('SELECT * from pertanyaan_survey where survey_id = '.$id.'  AND deleted_at IS NULL'));
							        foreach ($rule_pertanyaan as $key3 => $val_rule_pertanyaan) {
							        	if ($value->next_id == $val_rule_pertanyaan->pertanyaan_id) {
							        		$select = 'selected';
							        	}else{
							        		$select = '';
							        	}
							        	if ($value->next_id == 0) {
							        		$select_disable = 'disabled';
							        		$select_end = 'selected';
							        		$select_go = '';
							        	}else{
							        		$select_disable = 'required';
							        		$select_go = 'selected';
							        		$select_end = '';
							        	}
							        	$pertanyaan_form[$d->sequence][$key1][] = '<option value="'.$val_rule_pertanyaan->sequence.'" '.$select.'> Pertanyaan Ke '.$val_rule_pertanyaan->sequence.'</option>';
							        }

	            						$rule_form[$d->sequence][] = '<div class="rules'.$d->sequence.'_'.$no_rule1.' jumlah_rules'.$d->sequence.'">
							                <div class="col-md-12">
							                    <div class="form-group">
							                        <label>Rule Question Pilihan</label> 
							                        <select name="select_rule_pilihan['.$d->sequence.']['.($no_rule1 - 1).']" id="select_rule_pilihan'.$d->sequence.'_'.$no_rule1.'" class="form-control select-pilihan2 select-rule'.$d->sequence.'" data-sequence="'.$d->sequence.'" data-no="'.$no_rule1.'" required>
							                            <option>Pilihan</option>
							                            '.(isset($pilihan_form[$d->sequence][$key1]) ? implode(" ", $pilihan_form[$d->sequence][$key1]) : '').'
							                        </select>
							                    </div>
							                </div>
							                <div class="col-md-6">
							                    <div class="form-group">
							                        <label>Rules</label> 
							                        <select name="rules['.$d->sequence.']['.($no_rule1 - 1).']" id="" class="form-control rules" data-id="'.$d->sequence.'" data-no="'.$no_rule1.'" >
							                            <option value="go" '.$select_go.'>Go To</option>
							                            <option value="end" '.$select_end.'>End Survey</option>
							                        </select>
							                    </div>
							                </div>
							                <div class="col-md-6">
							                    <div class="form-group">
							                        <label>Pertanyaan</label>
							                        <select name="select_rule_pertanyaan['.$d->sequence.']['.($no_rule1 - 1).']" id="select_rule_pertanyaan'.$d->sequence.'_'.$no_rule1.'" data-select="pertanyaan" class="form-control select_rule_pertanyaan" data-select-rule'.$no_rule1.'="pertanyaan" '.$select_disable.'>
							                            <option selected value>Pilih Pertanyaan</option>
							                            '.(isset($pertanyaan_form[$d->sequence][$key1]) ? implode(" ", $pertanyaan_form[$d->sequence][$key1]) : '').'

							                        </select>
							                    </div>
							                </div>
							                    
							                    <div class="col-md-6 form-group pull-right ">
							                        <button type="button" class="btn btn-sm btn-danger btn-circle" id="delete_rule_pertanyaan'.$d->sequence.'_'.$no_rule1.'" data-no="'.$no_rule1.'" onClick="delete_rule_pertanyaan('.$d->sequence. ',' .$no_rule1.')"><i class="fa fa-trash"></i> Delete</button>
							                    </div>
							                    <div class="col-md-6 form-group ">
							                        <button type="button" class="btn btn-sm btn-success btn-circle add-btn-rule1" data-count="'.($no_rule1 + 1).'" data-jenis="1" data-sequence="'.$d->sequence.'" id="btn-rules'.$d->sequence.'"><i class="fa fa-plus"></i> Add</button></div>
							            </div>';
							            $no_rule1++;
						        }

        					$pil2 = \DB::select(\DB::raw('select id, text, is_required from pilihan where pertanyaan_id = '.$d->pertanyaan_id.' and deleted_at IS NULL'));
        					$no_case2 = 0;
        					foreach ($pil2 as $key => $value) {
                            	$no_case2++;
	        					$pilihan2[$d->sequence][] = '	<div class="form-group" id="line-pilihan'.$d->sequence.'_'.$no_case2.'">
		                                            <div class="input-group">
													<input type="hidden" name="id_pilihan['.$d->sequence.'][]" value="'.$value->id.'">  
			                                            <textarea type="text" class="form-control input-sm keyup-pilihan2 pilihan_'.$d->sequence.' disable_pilihan'.$d->sequence.'" name="pilihan['.$d->sequence.'][]"  placeholder="Pilihan" maxlength="185" required '.(isset($rule_pertanyaan) ? 'readonly' : '').'>'.$value->text.'</textarea>
			                                        <span class="input-group-addon" ><a href="javascript:;" data-counter="'.$no_case2.'" data-no="'.$d->sequence.'"  class="fa fa-trash btn_pilihan'.$d->sequence.'" id="line-pilihan" style="color:red;" > Delete</a> </span>
													<span class="input-group-addon" ><input type="radio" name="is_req_pilihan['.$d->sequence.'][]" value="'.$no_case2.'" '.($value->is_required == 1 ? 'checked' : '').'> Require</span>
			                                        </div>
		                                    	</div>';
        					}

	        				$form[] = '<div class="portlet-body form connectedSortable" data-pertanyaan="'.$d->sequence.'" id="panel-pertanyaan'.$d->sequence.'">
	                                <div class="panel panel-primary">
	                                  <div class="panel-heading">
	                                  <i class="fa fa-reorder grip grip-xl btn btn-success btn-circle" id="handle-grip"></i>
	                                     <span id="nomor"></span> <input type="hidden" id="sequence" name="sequence[]" value="'.$d->sequence.'"> 
	                                     <input type="hidden" id="tipe" name="tipe['.$d->sequence.'][]" value="2">  


	                                     Multiple Choices, Single Answer

	                                     <div class="pull-right">
	                                        <div class="tools">
	                                            <div class="btn-group">
	                                                <a class="btn btn-primary btn-sm btn-circle" href="javascript:;" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">Option
	                                                    <i class="fa fa-angle-down"></i>
	                                                </a>
	                                                <ul class="dropdown-menu pull-right">
	                                                    <li>
	                                                        <a href="javascript:;" class="btn-rule1" id="btn-rule1" data-jenis="1" data-sequence="'.$d->sequence.'" data-count="1" data-rule="'.(isset($pilihan_form[$d->sequence]) ? 'cek' : 'uncek' ).'">'.(isset($pilihan_form[$d->sequence]) ? '<i class="fa fa-check"></i>' : '' ).' Rule Question</a>
	                                                    </li>
	                                                </ul>
	                                            </div>
	                                        </div>
	                                    </div>

	                                   <div class="pull-right">
	                                        <div class="tools">
	                                            <div class="btn-group">
	                                                <a class="btn btn-danger btn-sm btn-circle" onClick="hapus_pertanyaan('.$d->sequence.')" id="hapus_pertanyaan" href="javascript:;">
	                                                    <span><i class="fa fa-trash red"></i> Delete </span>
	                                                </a>
	                                            </div>
	                                        </div>
	                                    </div>
	                                </div>
	                                <div class="panel-body">
	                                	<div class="row">
		                                	<input type="hidden" id="nomer_urut" value="'.$d->sequence.'">
		                                    <div id="input_rule'.$d->sequence.'" class="col-md-12">

		                                    	<div class="form-group col-md-12">
	                                                <label>Static Content</label>
	                                                <textarea class="static_content" name="static_content['.$d->sequence.'][]" id="static_content'.$d->sequence.'" >'.$d->static.'
	                                                </textarea>
	                                            </div>
	                                            
	                                            <div class="form-group col-md-3">
	                                            	<label>Posisi Static</label>
													<input type="hidden" name="id_pertanyaan['.$d->sequence.'][]" value="'.$d->pertanyaan_id.'">  
	                                                <select class="form-control input-sm" name="posisi['.$d->sequence.'][]" id="posisi_'.$d->sequence.'">
	                                                    <option>Atas</option>
	                                                    <option>Bawah</option>
	                                                </select>
	                                                <br>
	                                                <label>Nomor</label>
	                                                <input class="form-control input-sm" name="nomor_pertanyaan['.$d->sequence.'][]" id="nomor_pertanyaan_'.$d->sequence.'" placeholder="No. Pertanyaan" value="'.$d->nomor.'" maxlength="9" required>
	                                            </div>

		                                        <div class="form-group col-md-9">
		                                            <label>Pertanyaan</label>
		                                            <textarea type="text" class="form-control input-sm" rows="5" name="pertanyaan['.$d->sequence.'][]" placeholder="Tulis Pertanyaan" maxlength="500" required>'.\Iff::namaPertanyaan($d->pertanyaan_id).'</textarea>
		                                        </div>
		                                        
	                                            <div class="form-group col-md-12">
			                                        <label> Pilihan </label>
			                                        '.(isset($pilihan2[$d->sequence]) ? implode(" ", $pilihan2[$d->sequence]) : '' ).'
		                                        	<div id="pilihan_'.$d->sequence.'"></div>
		                                            <button type="button" data-case="'.$no_case2.'" data-tipe="2" class="btn blue btn-tambah-pilihan btn_pilihan'.$d->sequence.'" data-no-pilihan="'.$d->sequence.'" id="btn_tambah_pilihan2" '.(isset($rule_pertanyaan) ? 'disabled' : '').'><i class="fa fa-plus"></i> Tambah Pilihan</button>
	                                            </div>

		                                    </div>

		                                    <div id="input2-rule'.$d->sequence.'" class="col-md-6">
			                                '.(isset($rule_form[$d->sequence]) ? implode(" ", $rule_form[$d->sequence]) : '').'
			                                </div>
		                                </div>
		                                
	                                </div>
	                            </div>
	                        </div>';
		                    $nomor ++;

	                // return $form;
    				break;

        			case '3':
	        			$rule_data = \DB::select(\DB::raw('SELECT * FROM view_anti_data WHERE pertanyaan_id = '.$d->pertanyaan_id.''));


						$rule_data2 = \DB::select(\DB::raw('SELECT * FROM view_rule_data WHERE pertanyaan_id = '.$d->pertanyaan_id.''));
				        $no_rule1 = 1;
						$pilihan_form3='';
						$arrEnd = [];
						foreach ($rule_data2 as $key1 => $value2) {
							$arrEnd[] = $value2->pilihan_id;
						}
						
						if ($rule_data == null && $rule_data2 != null) {
							$rule_pilihan = \DB::select(\DB::raw('SELECT * from view_pertanyaan_pilihan where pertanyaan_id = '.$d->pertanyaan_id.' and deleted_at IS NULL'));
					        foreach ($rule_pilihan as $key2 => $val_rule) {
								$pilihan_form3 .= '<option '.(array_search($val_rule->pilihan_id,$arrEnd) !== false ? 'selected="selected"' : '').' value="'.$val_rule->text.'">'.$val_rule->text.'</option>';
					        	// if ($value->pilihan == $val_rule->text) {
					        	// 	$select = 'selected';
					        	// }else{
					        		$select = '';
					        	// }
					        	$pilihan_form[$d->sequence][$key1][] = '<option '.$select.' value="'.$val_rule->text.'">'.$val_rule->text.'</option>';

								// if ($value->pilihan_anti == $val_rule->text) {
					        	// 	$select = 'selected';
					        	// }else{
					        		$select = '';
					        	// }
								$pilihan_form2[$d->sequence][$key1][] = '<option '.$select.' value="'.$val_rule->text.'">'.$val_rule->text.'</option>';		        	
							}
							
								$anti_form[$d->sequence][0] = '<div class="col-md-12">
													<div class="form-group">
														<label>End Survey</label> 
														<select name="option_end['.$d->sequence.'][]" class="mt-multiselect btn btn-default" id="option_end'.$d->sequence.'" multiple="multiple" data-label="left" data-width="100%" data-filter="true" data-action-onchange="true">
															'.$pilihan_form3.'
														</select>
													</div>
												</div>';
								$anti_form[$d->sequence][] = '
								<div class="rules'.$d->sequence.'_'.$no_rule1.' jumlah_rules'.$d->sequence.'">
									<div class="col-md-6">
										<div class="form-group">
											<label>Rules</label> 
											<select name="select_anti_pilihan['.$d->sequence.'][]" id="select_anti_pilihan'.$d->sequence.'_'.$no_rule1.'" class="form-control">
												<option value="">Pilihan</option>
												'.(isset($pilihan_form[$d->sequence][$key1]) ? implode(" ", $pilihan_form[$d->sequence][$key1]) : '').'

											</select>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label>Pilihan Anti</label>
											<select name="select_anti_pilihan2['.$d->sequence.'][]" id="select_anti_pilihan_2_'.$d->sequence.'_'.$no_rule1.'" class="form-control">
												<option value="">Pilihan</option>
												'.(isset($pilihan_form2[$d->sequence][$key1]) ? implode(" ", $pilihan_form2[$d->sequence][$key1]) : '').'

											</select>
										</div>
									</div>
										
										<div class="col-md-6 form-group pull-right ">
											<button type="button" class="btn btn-sm btn-danger btn-circle" id="delete_rule_pertanyaan'.$d->sequence.'_'.$no_rule1.'" data-no="'.$no_rule1.'" onClick="delete_rule_pertanyaan('.$d->sequence. ',' .$no_rule1.')"><i class="fa fa-trash"></i> Delete</button>
										</div>
										<div class="col-md-6 form-group ">
											<button type="button" class="btn btn-sm btn-success btn-circle add-btn-anti1" data-count="'.($no_rule1 + 1).'" data-jenis="2" data-sequence="'.$d->sequence.'" id="btn-anti'.$d->sequence.'"><i class="fa fa-plus"></i> Add</button></div>
								</div>';
						$no_rule1++;
						}
				        foreach ($rule_data as $key1 => $value) {
							$rule_pilihan = \DB::select(\DB::raw('SELECT * from view_pertanyaan_pilihan where pertanyaan_id = '.$d->pertanyaan_id.' and deleted_at IS NULL'));
					        foreach ($rule_pilihan as $key2 => $val_rule) {
								$pilihan_form3 .= '<option '.(array_search($val_rule->pilihan_id,$arrEnd) !== false ? 'selected="selected"' : '').' value="'.$val_rule->text.'">'.$val_rule->text.'</option>';
					        	if ($value->pilihan == $val_rule->text) {
					        		$select = 'selected';
					        	}else{
					        		$select = '';
					        	}
					        	$pilihan_form[$d->sequence][$key1][] = '<option '.$select.' value="'.$val_rule->text.'">'.$val_rule->text.'</option>';

								if ($value->pilihan_anti == $val_rule->text) {
					        		$select = 'selected';
					        	}else{
					        		$select = '';
					        	}
								$pilihan_form2[$d->sequence][$key1][] = '<option '.$select.' value="'.$val_rule->text.'">'.$val_rule->text.'</option>';		        	
							}
							
							$anti_form[$d->sequence][0] = '<div class="col-md-12">
												<div class="form-group">
													<label>End Survey</label> 
													<select name="option_end['.$d->sequence.'][]" class="mt-multiselect btn btn-default" id="option_end'.$d->sequence.'" multiple="multiple" data-label="left" data-width="100%" data-filter="true" data-action-onchange="true">
														'.$pilihan_form3.'
													</select>
												</div>
											</div>';
							$anti_form[$d->sequence][] = '
							<div class="rules'.$d->sequence.'_'.$no_rule1.' jumlah_rules'.$d->sequence.'">
				                <div class="col-md-6">
				                    <div class="form-group">
				                         <label>Rules</label> 
				                        <select name="select_anti_pilihan['.$d->sequence.'][]" id="select_anti_pilihan'.$d->sequence.'_'.$no_rule1.'" class="form-control">
				                            <option value="">Pilihan</option>
				                            '.(isset($pilihan_form[$d->sequence][$key1]) ? implode(" ", $pilihan_form[$d->sequence][$key1]) : '').'

				                        </select>
				                    </div>
				                </div>
				                <div class="col-md-6">
				                    <div class="form-group">
				                        <label>Pilihan Anti</label>
				                        <select name="select_anti_pilihan2['.$d->sequence.'][]" id="select_anti_pilihan_2_'.$d->sequence.'_'.$no_rule1.'" class="form-control">
				                            <option value="">Pilihan</option>
				                            '.(isset($pilihan_form2[$d->sequence][$key1]) ? implode(" ", $pilihan_form2[$d->sequence][$key1]) : '').'

				                        </select>
				                    </div>
				                </div>
				                    
				                    <div class="col-md-6 form-group pull-right ">
				                        <button type="button" class="btn btn-sm btn-danger btn-circle" id="delete_rule_pertanyaan'.$d->sequence.'_'.$no_rule1.'" data-no="'.$no_rule1.'" onClick="delete_rule_pertanyaan('.$d->sequence. ',' .$no_rule1.')"><i class="fa fa-trash"></i> Delete</button>
				                    </div>
				                    <div class="col-md-6 form-group ">
				                        <button type="button" class="btn btn-sm btn-success btn-circle add-btn-anti1" data-count="'.($no_rule1 + 1).'" data-jenis="2" data-sequence="'.$d->sequence.'" id="btn-anti'.$d->sequence.'"><i class="fa fa-plus"></i> Add</button></div>
				            </div>';
				            $no_rule1++;
				       	}
				       	// if (isset($anti_form[$d->sequence])) {
			        	// 	$class_anti[$d->sequence] = 'col-md-6';
				       	// }else{
			        	// 	$class_anti[$d->sequence] = 'col-md-12';
					       //  // return $anti_form[$d->sequence];
				       	// }


    					$pil3 = \DB::select(\DB::raw('select id, text, is_required from pilihan where pertanyaan_id = '.$d->pertanyaan_id.' and deleted_at IS NULL'));
    					$no_case3 = 0;
    					foreach ($pil3 as $key => $value) {
                        	$no_case3++;
        					$pilihan3[$d->sequence][] = '
								<div class="form-group" id="line-pilihan'.$d->sequence.'_'.$no_case3.'">
                                    <div class="input-group">
										<input type="hidden" name="id_pilihan['.$d->sequence.']['.$no_case3.']" value="'.$value->id.'">  
                                        <textarea type="text" class="form-control input-sm keyup-pilihan2 pilihan_'.$d->sequence.' disable_pilihan'.$d->sequence.'" name="pilihan['.$d->sequence.']['.$no_case3.']"  placeholder="Pilihan" maxlength="185" required '.(isset($anti_form) ? 'readonly' : '').' >'.$value->text.'</textarea>
                                		<span class="input-group-addon" ><a href="javascript:;" data-counter="'.$no_case3.'" data-no="'.$d->sequence.'"  class="fa fa-trash btn_pilihan'.$d->sequence.'" id="line-pilihan" style="color:red;"> Delete</a> </span>
										<span class="input-group-addon" ><input type="checkbox" name="is_req_pilihan['.$d->sequence.']['.$no_case3.']" value="'.$no_case3.'" '.($value->is_required == 1 ? 'checked' : '').'> Require</span>
                                    </div>
                            	</div>';
    					}

        				$form[] = '<div class="portlet-body form connectedSortable" data-pertanyaan="'.$d->sequence.'" id="panel-pertanyaan'.$d->sequence.'">
	                                <div class="panel panel-primary">
	                                  <div class="panel-heading">
	                                  <i class="fa fa-reorder grip grip-xl btn btn-success btn-circle" id="handle-grip"></i>
	                                     <span id="nomor"></span> <input type="hidden" id="sequence" name="sequence[]" value="'.$d->sequence.'"> 
	                                     <input type="hidden" id="tipe" name="tipe['.$d->sequence.'][]" value="3">  

	                                     Multiple Choices, Multiple Answer

	                                     <div class="pull-right">
	                                        <div class="tools">
	                                            <div class="btn-group">
	                                                <a class="btn btn-primary btn-sm btn-circle" href="javascript:;" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">Option
	                                                    <i class="fa fa-angle-down"></i>
	                                                </a>
	                                                <ul class="dropdown-menu pull-right">
	                                                    <li>
	                                                        <a href="javascript:;" id="btn-anti'.$d->sequence.'" class="btn-anti3" data-jenis="2" data-sequence="'.$d->sequence.'" data-count="1" data-anti="'.(isset($anti_form[$d->sequence]) ? 'cek' : 'uncek' ).'">'.(isset($anti_form[$d->sequence]) ? '<i class="fa fa-check"></i>' : '' ).' Rule Anti</a>
	                                                    </li>
	                                                </ul>
	                                            </div>
	                                        </div>
	                                    </div>

	                                   <div class="pull-right">
	                                        <div class="tools">
	                                            <div class="btn-group">
	                                                <a class="btn btn-danger btn-sm btn-circle" onClick="hapus_pertanyaan('.$d->sequence.')" id="hapus_pertanyaan" href="javascript:;">
	                                                    <span><i class="fa fa-trash red"></i> Delete </span>
	                                                </a>
	                                            </div>
	                                        </div>
	                                    </div>
	                                </div>

	                                <div class="panel-body">
		                                <div class="row">
		                                    <input type="hidden" id="nomer_urut" value="'.$d->sequence.'">
		                                    <div id="input_rule'.$d->sequence.'" class="'.(isset($anti_form[$d->sequence]) ? 'col-md-6' : 'col-md-12' ).'">
		                                        
		                                        <div class="form-group col-md-12">
		                                            <label>Static Content</label>
		                                            <textarea class="static_content" name="static_content['.$d->sequence.'][]" id="static_content'.$d->sequence.'" >'.$d->static.'
		                                            </textarea>
		                                        </div>
		                                        
		                                        <div class="form-group col-md-3">
		                                            <label>Posisi Static</label>
													<input type="hidden" name="id_pertanyaan['.$d->sequence.'][]" value="'.$d->pertanyaan_id.'">  
		                                        	<select class="form-control input-sm" name="posisi['.$d->sequence.'][]" id="posisi_'.$d->sequence.'">
	                                                    <option>Atas</option>
	                                                    <option>Bawah</option>
	                                                </select>
	                                                <br>
		                                            <label>Nomor</label>
		                                            <input class="form-control input-sm" name="nomor_pertanyaan['.$d->sequence.'][]" id="nomor_pertanyaan_'.$d->sequence.'" placeholder="No. Pertanyaan" value="'.$d->nomor.'" maxlength="9" required>
		                                        </div>

		                                        <div class="form-group col-md-9">
		                                            <label>Pertanyaan</label>
		                                            <textarea type="text" class="form-control input-sm" rows="5" name="pertanyaan['.$d->sequence.'][]" placeholder="Tulis Pertanyaan" maxlength="500" required>'.\Iff::namaPertanyaan($d->pertanyaan_id).'</textarea>
		                                        </div>
		                                        
		                                        <div class="form-group col-md-12">
			                                        <label> Pilihan </label>
			                                        '.implode(" ", $pilihan3[$d->sequence]).'
			                                        <div id="pilihan_'.$d->sequence.'"></div>
		                                            <button type="button" data-case="'.$no_case3.'" class="btn blue btn_pilihan'.$d->sequence.'" data-no-pilihan="'.$d->sequence.'" id="btn_tambah_pilihan3" '.(isset($anti_form) ? 'disabled' : '').'><i class="fa fa-plus"></i> Tambah Pilihan</button>
		                                        </div>

		                                    </div>

		                                    <div id="input2-rule'.$d->sequence.'" class="col-md-6">
			                                	'.(isset($anti_form[$d->sequence]) ? implode(" ", $anti_form[$d->sequence]) : '').'
		                                    </div>

	                                	</div>
	                                </div>
	                            </div>
	                        </div>';
	                    $nomor ++;
    				break;

        			case '4':
        				$rule_data = \DB::select(\DB::raw('SELECT * FROM view_rule_data
																	WHERE pertanyaan_id = '.$d->pertanyaan_id.''));
						        $no_rule4 = 1;

						        foreach ($rule_data as $key1 => $value) {
							        $rule_pilihan = \DB::select(\DB::raw('SELECT * from view_pertanyaan_pilihan where pertanyaan_id = '.$d->pertanyaan_id.''));
							        foreach ($rule_pilihan as $key2 => $val_rule) {
							        	if ($value->text == $val_rule->text) {
							        		$select = 'selected';
							        	}else{
							        		$select = '';
							        	}
							        	$pilihan_form[$d->sequence][$key1][] = '<option '.$select.'>'.$val_rule->text.'</option>';
							        }

							        $rule_pertanyaan = \DB::select(\DB::raw('SELECT * from pertanyaan_survey where survey_id = '.$id.'  AND deleted_at IS NULL'));
							        foreach ($rule_pertanyaan as $key3 => $val_rule_pertanyaan) {
							        	if ($value->next_id == $val_rule_pertanyaan->pertanyaan_id) {
							        		$select = 'selected';
							        	}else{
							        		$select = '';
							        	}
							        	if ($value->next_id == 0) {
							        		$select_disable = 'disabled';
							        		$select_end = 'selected';
							        		$select_go = '';
							        	}else{
							        		$select_disable = 'required';
							        		$select_go = 'selected';
							        		$select_end = '';
							        	}
							        	$pertanyaan_form[$d->sequence][$key1][] = '<option value="'.$val_rule_pertanyaan->sequence.'" '.$select.'> Pertanyaan Ke '.$val_rule_pertanyaan->sequence.'</option>';
							        }

	            						$rule_form[$d->sequence][] = '<div class="rules'.$d->sequence.'_'.$no_rule4.' jumlah_rules'.$d->sequence.'">
							                <div class="col-md-12">
							                    <div class="form-group">
							                        <label>Rule Question Pilihan</label> 
							                        <select name="select_rule_pilihan['.$d->sequence.']['.($no_rule4 - 1).']" id="select_rule_pilihan'.$d->sequence.'_'.$no_rule4.'" class="form-control select-pilihan2 select-rule'.$d->sequence.'" data-sequence="'.$d->sequence.'" data-no="'.$no_rule4.'" required>
							                            <option>Pilihan</option>
							                            '.(isset($pilihan_form[$d->sequence][$key1]) ? implode(" ", $pilihan_form[$d->sequence][$key1]) : '').'
							                        </select>
							                    </div>
							                </div>
							                <div class="col-md-6">
							                    <div class="form-group">
							                         <label>Rules</label> 
							                        <select name="rules['.$d->sequence.']['.($no_rule4 - 1).']" id="" class="form-control rules" data-id="'.$d->sequence.'" data-no="'.$no_rule4.'" >
							                            <option value="go" '.$select_go.'>Go To</option>
							                            <option value="end" '.$select_end.'>End Survey</option>
							                        </select>
							                    </div>
							                </div>
							                <div class="col-md-6">
							                    <div class="form-group">
							                        <label>Pertanyaan</label>
							                        <select name="select_rule_pertanyaan['.$d->sequence.']['.($no_rule4 - 1).']" id="select_rule_pertanyaan'.$d->sequence.'_'.$no_rule4.'" data-select="pertanyaan" class="form-control select_rule_pertanyaan" data-select-rule'.$no_rule4.'="pertanyaan" '.$select_disable.'>
							                            <option selected value>Pilih Pertanyaan</option>
							                            '.(isset($pertanyaan_form[$d->sequence][$key1]) ? implode(" ", $pertanyaan_form[$d->sequence][$key1]) : '').'

							                        </select>
							                    </div>
							                </div>
							                    
							                    <div class="col-md-6 form-group pull-right ">
							                        <button type="button" class="btn btn-sm btn-danger btn-circle" id="delete_rule_pertanyaan'.$d->sequence.'_'.$no_rule4.'" data-no="'.$no_rule4.'" onClick="delete_rule_pertanyaan('.$d->sequence. ',' .$no_rule4.')"><i class="fa fa-trash"></i> Delete</button>
							                    </div>
							                    <div class="col-md-6 form-group ">
							                        <button type="button" class="btn btn-sm btn-success btn-circle add-btn-rule1" data-count="'.($no_rule4 + 1).'" data-jenis="1" data-sequence="'.$d->sequence.'" id="btn-rules'.$d->sequence.'"><i class="fa fa-plus"></i> Add</button></div>
							            </div>';
							            $no_rule4++;
						        }

        				$pil4 = \DB::select(\DB::raw('select id, text from pilihan where pertanyaan_id = '.$d->pertanyaan_id.''));
    					$no_case4 = 0;
    					foreach ($pil4 as $key => $value) {
                        	$no_case4++;
        					$pilihan4[$d->sequence][] = '
        						<div class="form-group" id="line-pilihan'.$d->sequence.'_'.$no_case4.'">
                                    <div class="input-group">
										<input type="hidden" name="id_pilihan['.$d->sequence.'][]" value="'.$value->id.'">  
                                        <input type="text" class="form-control input-sm keyup-pilihan4 pilihan_'.$d->sequence.' disable_pilihan'.$d->sequence.'" name="pilihan['.$d->sequence.'][]"  placeholder="Pilihan" value="'.$value->text.'" maxlength="185" required '.(isset($rule_form[$d->sequence]) ? 'readonly' : '').'> 
                                    	<span class="input-group-addon" ><a href="javascript:;" data-counter="'.$no_case4.'" data-no="'.$d->sequence.'"  class="fa fa-trash" id="line-pilihan" style="color:red;"> Delete</a> </span>
                                    </div>
                            	</div>';
    					}

        				$form[] = '<div class="portlet-body form connectedSortable" data-pertanyaan="'.$d->sequence.'" id="panel-pertanyaan'.$d->sequence.'">
                                <div class="panel panel-primary">
                                  	<div class="panel-heading">
	                                  	<i class="fa fa-reorder grip grip-xl btn btn-success btn-circle" id="handle-grip"></i>
	                                     <span id="nomor"></span> <input type="hidden" id="sequence" name="sequence[]" value="'.$d->sequence.'"> 
	                                     <input type="hidden" id="tipe" name="tipe['.$d->sequence.'][]" value="4">  

	                                     Rating Scale

	                                     <div class="pull-right">
	                                        <div class="tools">
	                                            <div class="btn-group">
	                                                <a class="btn btn-primary btn-sm btn-circle" href="javascript:;" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">Option
	                                                    <i class="fa fa-angle-down"></i>
	                                                </a>
	                                                <ul class="dropdown-menu pull-right">
	                                                    <li>
	                                                        <a href="javascript:;" class="btn-rule1" id="btn-rule1" data-jenis="1" data-sequence="'.$d->sequence.'" data-count="1" data-rule="'.(isset($pilihan_form[$d->sequence]) ? 'cek' : 'uncek' ).'">'.(isset($pilihan_form[$d->sequence]) ? '<i class="fa fa-check"></i>' : '' ).' Rule Question</a>
	                                                    </li>
	                                                </ul>
	                                            </div>
	                                        </div>
	                                    </div>

	                                    <div class="pull-right">
	                                        <div class="tools">
	                                            <div class="btn-group">
	                                                <a class="btn btn-danger btn-sm btn-circle" onClick="hapus_pertanyaan('.$d->sequence.')" id="hapus_pertanyaan" href="javascript:;">
	                                                    <span><i class="fa fa-trash red"></i> Delete </span>
	                                                </a>
	                                            </div>
	                                        </div>
	                                    </div>
	                                </div>

	                                <div class="panel-body">
		                                <div class="row">
		                                    <input type="hidden" id="nomer_urut" value="'.$d->sequence.'">
		                                    <div id="input_rule'.$d->sequence.'" class="col-md-12">

			                                    <div class="form-group col-md-12">
		                                            <label>Static Content</label>
													<input type="hidden" name="id_pertanyaan['.$d->sequence.'][]" value="'.$d->pertanyaan_id.'">  
		                                            <textarea class="static_content" name="static_content['.$d->sequence.'][]" id="static_content'.$d->sequence.'" >'.$d->static.'
		                                            </textarea>
		                                        </div>
		                                        
		                                        <div class="form-group col-md-3">
		                                        	<label>Posisi Static</label>
		                                        	<select class="form-control input-sm" name="posisi['.$d->sequence.'][]" id="posisi_'.$d->sequence.'">
	                                                    <option>Atas</option>
	                                                    <option>Bawah</option>
	                                                </select>
	                                                <br>
		                                            <label>Nomor</label>
		                                            <input class="form-control input-sm" name="nomor_pertanyaan['.$d->sequence.'][]" id="nomor_pertanyaan_'.$d->sequence.'" placeholder="No. Pertanyaan" value="'.$d->nomor.'" maxlength="9" required>
		                                        </div>
		    
		                                        <div class="form-group col-md-9">
		                                            <label>Pertanyaan</label>
		                                            <textarea type="text" class="form-control input-sm" rows="5" name="pertanyaan['.$d->sequence.'][]" placeholder="Tulis Pertanyaan" maxlength="500" required>'.\Iff::namaPertanyaan($d->pertanyaan_id).'</textarea>
		                                        </div>
		                                        
		                                        <div class="form-group col-md-12">
			                                        <label> Scale Text </label>
			                                        '.(isset($pilihan4[$d->sequence]) ? implode(" ", $pilihan4[$d->sequence]) : "").'
		                                        	<div id="pilihan_'.$d->sequence.'"></div>
		                                            <button type="button" data-case="'.$no_case4.'" class="btn blue btn_pilihan'.$d->sequence.'" data-no-pilihan="'.$d->sequence.'" id="btn_tambah_scale4" '.(isset($rule_form[$d->sequence]) ? 'disabled' : '').'><i class="fa fa-plus"></i> Tambah Scale</button>
		                                        </div>
		                                    </div>

		                                    <div id="input2-rule'.$d->sequence.'" class="col-md-6">
				                                '.(isset($rule_form[$d->sequence]) ? implode(" ", $rule_form[$d->sequence]) : '').'
		                                    </div>
	                                    </div>

	                                </div>
	                            </div>
	                        </div>';
		                    $nomor ++;
    				break;
        			case '5':
        				$rule_data = \DB::select(\DB::raw('SELECT r.id, r.pertanyaan_id, r.pilihan_id, r.next_id,r.scale, p.text, pil.bobot
															FROM rule AS r
															JOIN pertanyaan AS p ON p.id = r.pertanyaan_id
															JOIN pilihan AS pil ON pil.pertanyaan_id = p.id
															WHERE r.pertanyaan_id = '.$d->pertanyaan_id.' AND r.deleted_at IS NULL'));
						        $no_rule4 = 1;
						        // dd($rule_data);
						        
						        foreach ($rule_data as $key1 => $value) {
						        	// \DB::select()

							        $rule_pilihan = \DB::select(\DB::raw('SELECT id, pertanyaan_id, next_id, scale FROM rule WHERE pertanyaan_id = '.$d->pertanyaan_id.' AND deleted_at IS NULL'));
							        
						        // dd($rule_pilihan[0]->scale);
		
							        foreach ($rule_pilihan as $key2 => $val_rule) {
						        		for($i = 1; $i <= $value->bobot; $i++){
								        	if ($i == $val_rule->scale) {
								        		$select = 'selected';
								        		$pilihan_form[$d->sequence][$key2][] = '<option value="'.$i.'" '.$select.'>'.$i.'</option>';
								        	}else{
								        		$select = '';
								        		$pilihan_form[$d->sequence][$key2][] = '<option value="'.$i.'" '.$select.'>'.$i.'</option>';
								        	}
						        		}
							        }
							        // return $select[$key];
					        		
							        foreach ($pilihan_form[$d->sequence] as $key_pil => $val_pil) {
					        			
						        		$pil_form[$d->sequence][] = implode(' ', $val_pil);

							        }

							        $rule_pertanyaan = \DB::select(\DB::raw('SELECT * from pertanyaan_survey where survey_id = '.$id.'  AND deleted_at IS NULL'));
					        		// dd($pilihan_form);0

							        foreach ($rule_pertanyaan as $key3 => $val_rule_pertanyaan) {
							        	if ($value->next_id == $val_rule_pertanyaan->pertanyaan_id) {
							        		$select = 'selected';
							        	}else{
							        		$select = '';
							        	}
							        	
							        	if ($value->next_id == 0) {
							        		$select_disable = 'disabled';
							        		$select_end = 'selected';
							        		$select_go = '';
							        	$pertanyaan_form[$d->sequence][$key1][] = '<option value="'.$val_rule_pertanyaan->sequence.'" '.$select.'> Pertanyaan Ke '.$val_rule_pertanyaan->sequence.'</option>';
							        	}else{
							        		$select_disable = 'required';
							        		$select_go = 'selected';
							        		$select_end = '';
							        	$pertanyaan_form[$d->sequence][$key1][] = '<option value="'.$val_rule_pertanyaan->sequence.'" '.$select.'> Pertanyaan Ke '.$val_rule_pertanyaan->sequence.'</option>';
							        	}
							        }

	            						$rule_form[$d->sequence][] = '<div class="rules'.$d->sequence.'_'.$no_rule4.' jumlah_rules'.$d->sequence.'">
							                <div class="col-md-12">
							                    <div class="form-group">
							                        <label>Rule Question Pilihan</label> 
							                        <select name="select_rule_pilihan['.$d->sequence.']['.($no_rule4 - 1).']" id="select_rule_pilihan'.$d->sequence.'_'.$no_rule4.'" class="form-control select-pilihan2 select-rule'.$d->sequence.'" data-sequence="'.$d->sequence.'" data-no="'.$no_rule4.'" required>
							                            <option value="">Pilihan</option>
							                            '.(isset($pil_form[$d->sequence][$key1]) ? $pil_form[$d->sequence][$key1] : '').'
							                        </select>
							                    </div>
							                </div>
							                <div class="col-md-6">
							                    <div class="form-group">
							                         <label>Rules</label> 
							                        <select name="rules['.$d->sequence.']['.($no_rule4 - 1).']" id="" class="form-control rules" data-id="'.$d->sequence.'" data-no="'.$no_rule4.'" >
							                            <option value="go" '.$select_go.'>Go To</option>
							                            <option value="end" '.$select_end.'>End Survey</option>
							                        </select>
							                    </div>
							                </div>
							                <div class="col-md-6">
							                    <div class="form-group">
							                        <label>Pertanyaan</label>
							                        <select name="select_rule_pertanyaan['.$d->sequence.']['.($no_rule4 - 1).']" id="select_rule_pertanyaan'.$d->sequence.'_'.$no_rule4.'" data-select="pertanyaan" class="form-control select_rule_pertanyaan" data-select-rule'.$no_rule4.'="pertanyaan" '.$select_disable.'>
							                            <option selected value>Pilih Pertanyaan</option>
							                            '.(isset($pertanyaan_form[$d->sequence][$key1]) ? implode(" ", $pertanyaan_form[$d->sequence][$key1]) : '').'

							                        </select>
							                    </div>
							                </div>
							                    
							                    <div class="col-md-6 form-group pull-right ">
							                        <button type="button" class="btn btn-sm btn-danger btn-circle" id="delete_rule_pertanyaan'.$d->sequence.'_'.$no_rule4.'" data-no="'.$no_rule4.'" onClick="delete_rule_pertanyaan('.$d->sequence. ',' .$no_rule4.')"><i class="fa fa-trash"></i> Delete</button>
							                    </div>
							                    <div class="col-md-6 form-group ">
							                        <button type="button" class="btn btn-sm btn-success btn-circle add-btn-rule-semantic" data-count="'.($no_rule4 + 1).'" data-jenis="3" data-sequence="'.$d->sequence.'" id="btn-rules'.$d->sequence.'"><i class="fa fa-plus"></i> Add</button></div>
							            </div>';
							            $no_rule4++;
						        }


        				$pil5 = \DB::select(\DB::raw('SELECT id, text, bobot FROM pilihan WHERE pertanyaan_id = '.$d->pertanyaan_id.''));
        				// dD($pil5);
    					foreach ($pil5 as $key => $value) {
    						// if (isset($key)) {
    						// 	if ($key == 0) {
    						// 		$placeholder = 'Min. Text';
    						// 	}else {
    						// 		$placeholder = 'Max. Text';
    						// 	}
    						// }
    						$pilihan = explode('|||', $value->text);
    						// dd($pilihan);
        					$input5 = '
                                            <div class="col-md-5">
											<input type="hidden" name="id_pilihan['.$d->sequence.'][]" value="'.$value->id.'">  
                                                <label> Minimal Text </label>
                                                <input type="text" class="form-control input-sm disable_pilihan'.$d->sequence.'" name="pilihan['.$d->sequence.'][]" placeholder="Min. Text" value="'.$pilihan[0].'" maxlength="80" required '.(isset($rule_form) ? 'readonly' : '').'> 
                                            </div>
                                            <div class="col-md-5">
                                                <label> Maximal Text </label>
                                                <input type="text" class="form-control input-sm disable_pilihan'.$d->sequence.'" name="pilihan['.$d->sequence.'][]" placeholder="Max. Text" value="'.$pilihan[1].'" maxlength="80" required '.(isset($rule_form) ? 'readonly' : '').'> 
                                            </div>
                                            <div class="col-md-2">
                                                <label> Scale </label>
                                                <input type="text" class="form-control input-sm number disable_pilihan'.$d->sequence.'" id="scale'.$d->sequence.'" name="scale['.$d->sequence.'][]" value="'.$value->bobot.'" maxlength="2" required placeholder="Scale" required '.(isset($rule_form) ? 'readonly' : '').'> 
                                            </div>
                                            ';
    					}
    					// dd($input5);
        				$form[] = '<div class="portlet-body form connectedSortable" data-pertanyaan="'.$d->sequence.'" id="panel-pertanyaan'.$d->sequence.'">
                                <div class="panel panel-primary">
                                  <div class="panel-heading">
                                  <i class="fa fa-reorder grip grip-xl btn btn-success btn-circle" id="handle-grip"></i>
                                     <span id="nomor"></span> <input type="hidden" id="sequence" name="sequence[]" value="'.$d->sequence.'"> 
                                     <input type="hidden" id="tipe" name="tipe['.$d->sequence.'][]" value="5">  

                                     Semantic Differential

                                     <div class="pull-right">
                                        <div class="tools">
                                            <div class="btn-group">
                                                <a class="btn btn-primary btn-sm btn-circle" href="javascript:;" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">Option
                                                    <i class="fa fa-angle-down"></i>
                                                </a>
                                                <ul class="dropdown-menu pull-right">
                                                    <li>
														<a href="javascript:;" class="btn-rule-semantic" data-jenis="3" data-sequence="'.$d->sequence.'" data-count="1" data-rule="'.(isset($pilihan_form[$d->sequence]) ? 'cek' : 'uncek' ).'">'.(isset($pilihan_form[$d->sequence]) ? '<i class="fa fa-check"></i>' : '' ).' Rule Question</a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>

                                   <div class="pull-right">
                                        <div class="tools">
                                            <div class="btn-group">
                                                <a class="btn btn-danger btn-sm btn-circle" onClick="hapus_pertanyaan('.$d->sequence.')" id="hapus_pertanyaan" href="javascript:;">
                                                    <span><i class="fa fa-trash red"></i> Delete </span>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="panel-body">
                                    <div class="row">
	                                    <input type="hidden" id="nomer_urut" value="'.$d->sequence.'">
	                                    <div id="input_rule'.$d->sequence.'" class="col-md-12">

	                                    	<div class="form-group col-md-12">
	                                            <label>Static Content</label>
	                                            <textarea class="static_content" name="static_content['.$d->sequence.'][]" id="static_content'.$d->sequence.'" >'.$d->static.'
	                                            </textarea>
	                                        </div>
	                                        
	                                        <div class="form-group col-md-3">
	                                        	<label>Posisi Static</label>
												<input type="hidden" name="id_pertanyaan['.$d->sequence.'][]" value="'.$d->pertanyaan_id.'">  
	                                        	<select class="form-control input-sm" name="posisi['.$d->sequence.'][]" id="posisi_'.$d->sequence.'">
                                                    <option>Atas</option>
                                                    <option>Bawah</option>
                                                </select>
                                                <br>
	                                            <label>Nomor</label>
	                                            <input class="form-control input-sm" name="nomor_pertanyaan['.$d->sequence.'][]" id="nomor_pertanyaan_'.$d->sequence.'" placeholder="No. Pertanyaan" value="'.$d->nomor.'" maxlength="9" required>
	                                        </div>

	                                        <div class="form-group col-md-9">
	                                            <label>Pertanyaan</label>
	                                            <textarea type="text" class="form-control input-sm" rows="5" name="pertanyaan['.$d->sequence.'][]" placeholder="Tulis Pertanyaan" maxlength="500" required>'.\Iff::namaPertanyaan($d->pertanyaan_id).'</textarea> 
	                                        </div>

			                                '.( isset($input5) ? $input5 : "").'
                                        </div>

                                        
	                                    <div id="input2-rule'.$d->sequence.'" class="col-md-6">
			                                '.(isset($rule_form[$d->sequence]) ? implode(" ", $rule_form[$d->sequence]) : '').'
	                                    </div>
                                    </div>
                                </div>
                            </div>
                        </div>';
	                    $nomor ++;

    				break;
        			case '6':
        				$form[] = '<div class="portlet-body form connectedSortable" data-pertanyaan="'.$d->sequence.'" id="panel-pertanyaan'.$d->sequence.'">
                                <div class="panel panel-primary">
                                  <div class="panel-heading">
                                  <i class="fa fa-reorder grip grip-xl btn btn-success btn-circle" id="handle-grip"></i>
                                     <span id="nomor"></span> <input type="hidden" id="sequence" name="sequence[]" value="'.$d->sequence.'"> 
                                     <input type="hidden" id="tipe" name="tipe['.$d->sequence.'][]" value="6">  

                                     Open Ended

                                   <div class="pull-right">
                                        <div class="tools">
                                            <div class="btn-group">
                                                <a class="btn btn-danger btn-sm btn-circle" onClick="hapus_pertanyaan('.$d->sequence.')" id="hapus_pertanyaan" href="javascript:;">
                                                    <span><i class="fa fa-trash red"></i> Delete </span>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="panel-body">
		                            <div class="row">
		                                <input type="hidden" id="nomer_urut" value="'.$d->sequence.'">
		                                <div id="input_rule'.$d->sequence.'" class="col-md-12">
											
											<div class="form-group col-md-12">
		                                        <label>Static Content</label>
		                                        <textarea class="static_content" name="static_content['.$d->sequence.'][]" id="static_content'.$d->sequence.'" >'.$d->static.'</textarea>
		                                    </div>
		                                    
		                                    <div class="form-group col-md-3">
		                                    	<label>Posisi Static</label>
												<input type="hidden" name="id_pertanyaan['.$d->sequence.'][]" value="'.$d->pertanyaan_id.'">  
	                                        	<select class="form-control input-sm" name="posisi['.$d->sequence.'][]" id="posisi_'.$d->sequence.'">
                                                    <option>Atas</option>
                                                    <option>Bawah</option>
                                                </select>
                                                <br>
		                                        <label>Nomor</label>
		                                        <input class="form-control input-sm" name="nomor_pertanyaan['.$d->sequence.'][]" id="nomor_pertanyaan_'.$d->sequence.'" placeholder="No. Pertanyaan" value="'.$d->nomor.'" maxlength="9" required>
		                                    </div>

		                                    <div class="form-group col-md-9">
		                                        <label>Pertanyaan</label>
		                                        <textarea class="form-control input-sm" rows="5" maxlength="500" placeholder="Tulis Pertanyaan" name="pertanyaan['.$d->sequence.'][]" required>'.\Iff::namaPertanyaan($d->pertanyaan_id).'</textarea>
		                                    </div>
		                                
		                                </div>
		                                
		                                <div id="input2-rule'.$d->sequence.'" class="col-md-6">
		                                </div>
	                                </div>
                                </div>
                            </div>
                        </div>';
	                    $nomor ++;

    				break;
        			case '7':

    					$per7 = \DB::select(\DB::raw('select id, survey_id, pertanyaan, sequence from view_pertanyaan_jenis where survey_id = '.$id.' and sequence = '.$d->sequence.' '));
        				// dd($per7);

    					$data_counter7 = 0;
    					foreach ($per7 as $key => $value) {
							$arrPil = \DB::select('select id from pilihan where deleted_at is null and pertanyaan_id = '.$value->id.'');
							foreach ($arrPil as $key2 => $idpil) {
								$newArray[$d->sequence][$key2][] = $idpil->id; 
							}

                        	$data_counter7++;
        					$pertanyaan7[$d->sequence][] = '
									<div class="form-group">
										<div id="line-pertanyaan'.$d->sequence.'_'.$data_counter7.'">
                                            <div class="input-group">
											<input type="hidden" name="id_pertanyaan['.$d->sequence.'][]" value="'.$value->id.'">  
	                                            <input type="text" class="form-control input-sm disable_pilihan'.$d->sequence.' pertanyaan_'.$d->sequence.'" name="pertanyaan['.$d->sequence.'][]" value="'.$value->pertanyaan.'"  placeholder="Pertanyaan" maxlength="500" required> 
		                                        <span class="input-group-addon" ><a href="javascript:;" data-counter="'.$data_counter7.'" data-no="'.$d->sequence.'" id="line-pertanyaan" class="fa fa-trash" style="color:red;"> Delete</a> </span>
	                                        </div>
                                        </div>
                                    </div>';
						}
						

        				$pil7 = \DB::select(\DB::raw('select id, text from pilihan where deleted_at is null and pertanyaan_id = '.$d->pertanyaan_id.''));
    					$data_counter_scale7 = 0;
    					foreach ($pil7 as $key => $value) {
                        	$data_counter_scale7++;
        					$pilihan7[$d->sequence][] = '
									<div class="form-group">
	        							<div id="line-pilihan'.$d->sequence.'_'.$data_counter_scale7.'">
	                                        <div class="input-group">
												<input type="hidden" name="id_pilihan['.$d->sequence.'][]" value="'.implode(',',$newArray[$d->sequence][$key]).'">  
		                                        <input type="text" class="form-control input-sm pilihan_'.$d->sequence.'" name="pilihan['.$d->sequence.'][]" value="'.$value->text.'"  placeholder="Scale Text" maxlength="185" required> 
		                                        <span class="input-group-addon" ><a href="javascript:;" data-counter="'.$data_counter_scale7.'" data-no="'.$d->sequence.'" id="line-pilihan" class="fa fa-trash" style="color:red;"> Delete</a> </span>
		                                    </div>
                                        </div>
                                    </div>';
    					}

        				$form[] = '<div class="portlet-body form connectedSortable" data-pertanyaan="'.$d->sequence.'" id="panel-pertanyaan'.$d->sequence.'">
                                <div class="panel panel-primary">
                                  <div class="panel-heading">
                                  <i class="fa fa-reorder grip grip-xl btn btn-success btn-circle" id="handle-grip"></i>
                                     <span id="nomor"></span> <input type="hidden" id="sequence" name="sequence[]" value="'.$d->sequence.'"> 
                                     <input type="hidden" id="tipe" name="tipe['.$d->sequence.'][]" value="7">  


                                     Matrix Table

                                   <div class="pull-right">
                                        <div class="tools">
                                            <div class="btn-group">
                                                <a class="btn btn-danger btn-sm btn-circle" onClick="hapus_pertanyaan('.$d->sequence.')" id="hapus_pertanyaan" href="javascript:;">
                                                    <span><i class="fa fa-trash red"></i> Delete </span>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="panel-body">

                                	<div class="row">
	                                    <input type="hidden" id="nomer_urut" value="'.$d->sequence.'">
	                                    <div id="input_rule'.$d->sequence.'" class="col-md-12">

	                                    	<div class="form-group col-md-12">
		                                        <label>Static Content</label>
		                                        <textarea class="static_content" name="static_content['.$d->sequence.'][]" id="static_content'.$d->sequence.'" >'.$d->static.'</textarea>
		                                    </div>
		                                    
		                                    <div class="form-group col-md-3">
		                                    	<label>Posisi Static</label>
	                                        	<select class="form-control input-sm" name="posisi['.$d->sequence.'][]" id="posisi_'.$d->sequence.'">
                                                    <option>Atas</option>
                                                    <option>Bawah</option>
                                                </select>
                                                <br>
		                                        <label>Nomor</label>
		                                        <input class="form-control input-sm" name="nomor_pertanyaan['.$d->sequence.'][]" id="nomor_pertanyaan_'.$d->sequence.'" placeholder="No. Pertanyaan" value="'.$d->nomor.'" maxlength="9" required>
		                                    </div>

	                                        <div class="form-group col-md-9">
	                                            <label>Instruksi</label>
	                                            <textarea type="text" class="form-control input-sm" rows="5" name="instruksi['.$d->sequence.']" placeholder="Tulis Instruksi" maxlength="500" rows="4" required>'.\Iff::namaInstruksi($d->pertanyaan_id).'</textarea>
	                                        </div>
	                                        
	                                        <div class="form-group col-md-12">
		                                        <label>Scale</label>
		                                        '.(isset($pilihan7[$d->sequence]) ? implode(" ", $pilihan7[$d->sequence]) : '').'

                                        		<div id="pilihan_'.$d->sequence.'"></div>
	                                            <button type="button" data-counter-scale="'.$data_counter_scale7.'" data-no-scale="'.$d->sequence.'" class="btn blue btn_pilihan'.$d->sequence.'" id="btn_tambah_scale7"><i class="fa fa-plus"></i> Tambah Scale</button>
	                                        </div>
	                                        
	                                        <div class="form-group col-md-12">
		                                        <label>Pertanyaan</label>
		                                        '.(isset($pertanyaan7[$d->sequence]) ? implode(" ", $pertanyaan7[$d->sequence]) : '').'
                                        		
                                        		<div id="pertanyaan_'.$d->sequence.'"></div>
	                                            <button type="button" data-counter-pertanyaan="'.$data_counter7.'" data-no-pertanyaan="'.$d->sequence.'" data-case="7" class="btn blue" id="btn_tambah_pertanyaan7"><i class="fa fa-plus"></i> Tambah Pertanyaan</button>
	                                        </div>
	                                        
	                                    </div>
	                                    <div id="input2-rule'.$d->sequence.'" class="col-md-6">
	                                    </div>
                                    </div>

                                </div>
                            </div>
                        </div>';
	                    $nomor ++;

    				break;

        			case '8':	
        				// $per7 = \DB::select(\DB::raw('select id, survey_id, pertanyaan, sequence from view_pertanyaan_jenis where survey_id = '.$id.' and sequence = '.$d->sequence.' '));
        				$per7 = \DB::select(\DB::raw('select id, pertanyaan_id, pertanyaan, nomor, is_multiple from view_pertanyaan_jenis where survey_id = '.$d->survey_id.' and sequence = '.$d->sequence.' order by id ASC'));
        				// dd($per7);
        				// return $per7;
    					$data_counter7 = 0;
    					foreach ($per7 as $key => $value) {
                        	$data_counter7++;
                        	if ($value->is_multiple == 1) {
                        		$select = 'selected';
                        	}else{
                        		$select = '';
                        	}
							$arrPil = \DB::select('select id from pilihan where deleted_at is null and pertanyaan_id = '.$value->pertanyaan_id.'');
							foreach ($arrPil as $key2 => $idpil) {
								$newArray2[$d->sequence][$key2][] = $idpil->id; 
							}
                        	// $dat[] = $key;
        					$pertanyaan7[$d->sequence][] = '
        								<div id="line-instruksi'.$d->sequence.'_'.$data_counter7.'" class="instruksi'.$d->sequence.'" >
        									<div class="form-group col-md-2">
                                                <label>Nomor</label>
												<input type="hidden" name="id_pertanyaan['.$d->sequence.'][]" value="'.$value->pertanyaan_id.'">  
                                                <input class="form-control input-sm" maxlength="9" name="nomor_pertanyaan['.$d->sequence.'][]" id="nomor_pertanyaan_'.$d->sequence.'" value="'.$value->nomor.'" placeholder="No. Pertanyaan" required>
                                                <br>
                                                <label>Type</label>
                                                <select class="form-control input-sm" name="tipe_pilihan['.$d->sequence.'][]" required>
                                                    <option value="0">Single Choice</option>
                                                    <option '.$select.' value="1">Multiple Choice</option>
                                                </select>
                                            </div>

                                            <div class="form-group col-md-10" >
                                            <label>Instruksi / Pertanyaan</label>
                                                <div class="row">
                                                    <div class="col-md-11">
                                                    <textarea type="text" class="form-control input-sm" rows="5" name="pertanyaan['.$d->sequence.'][]"  placeholder="Tulis Instruksi" maxlength="500" required>'.$value->pertanyaan.'</textarea>
                                                	</div>
                                                    
                                                    <div class="col-md-1">
                                                		<a href="javascript:;" data-counter="'.$data_counter7.'" data-no="'.$d->sequence.'" id="line-instruksi" class="btn btn-danger btn-circle"><i class="fa fa-close"></i></a>
                                                	</div>
                                            </div>
                                            </div>

                                        </div>';
    					}
    					// dd($dat);
                        	// return $dat;


    					$pil7 = \DB::select(\DB::raw('select id, text from pilihan where deleted_at is null and pertanyaan_id = '.$d->pertanyaan_id.''));
        				// dd($pil7);
    					$data_counter_pilihan = 0;
    					foreach ($pil7 as $key => $value) {
                        	$data_counter_pilihan++;
        					$pilihan7[$d->sequence][] = '<div class="form-group" id="line-pilihan'.$d->sequence.'_'.$data_counter_pilihan.'">
			                                                <div class="input-group">
															<input type="hidden" name="id_pilihan['.$d->sequence.'][]" value="'.implode(',',$newArray2[$d->sequence][$key]).'">  
			                                                    <input type="text" class="form-control input-sm disable_pilihan'.$d->sequence.' pilihan_'.$d->sequence.'" name="pilihan['.$d->sequence.'][]" value="'.$value->text.'" placeholder="Pilihan" maxlength="185" required> 
			                                                <span class="input-group-addon" ><a href="javascript:;" data-counter="'.$data_counter_pilihan.'" data-no="'.$d->sequence.'" id="line-pilihan" class="fa fa-trash" style="color:red;"> Delete</a> </span>
			                                                </div>
			                                            </div>';
    					}

    					$form[] = '<div class="portlet-body form connectedSortable" data-pertanyaan="'.$d->sequence.'" id="panel-pertanyaan'.$d->sequence.'">
                                <div class="panel panel-primary">
                                  <div class="panel-heading">
                                  <i class="fa fa-reorder grip grip-xl btn btn-success btn-circle" id="handle-grip"></i>
                                     <span id="nomor"></span> <input type="hidden" id="sequence" name="sequence[]" value="'.$d->sequence.'"> 
                                     <input type="hidden" id="tipe" name="tipe['.$d->sequence.'][]" value="8">  


                                     Side-Beside Matrix

                                   <div class="pull-right">
                                        <div class="tools">
                                            <div class="btn-group">
                                                <a class="btn btn-danger btn-sm btn-circle" onClick="hapus_pertanyaan('.$d->sequence.')" id="hapus_pertanyaan" href="javascript:;">
                                                    <span><i class="fa fa-trash red"></i> Delete </span>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="panel-body">
                                    <input type="hidden" id="nomer_urut" value="'.$d->sequence.'">
                                    <div id="input_rule'.$d->sequence.'">
                                        
                                        <div class="form-group col-md-12">
	                                        <label>Static Content</label>
	                                        <textarea class="static_content" name="static_content['.$d->sequence.'][]" id="static_content'.$d->sequence.'" >'.$d->static.'</textarea>
                                            <br>
	                                    	<label>Posisi Static</label>
                                        	<select class="form-control input-sm" name="posisi['.$d->sequence.'][]" id="posisi_'.$d->sequence.'">
                                                <option>Atas</option>
                                                <option>Bawah</option>
                                            </select>
	                                    </div>
		                                    

                                        '.implode(" ", $pertanyaan7[$d->sequence]).'
                                        


                                        <div id="instruksi_'.$d->sequence.'">
                                        </div>
                                        
                                        <div class="form-group col-md-12">
                                            <button type="button" data-counter-instruksi="1" data-no-instruksi="'.$d->sequence.'" class="btn blue" id="btn_tambah_instruksi"><i class="fa fa-plus"></i> Tambah Instruksi</button>
                                        </div>
	
                                        <div class="col-md-12">
                                            <label>Pilihan</label>
                                        		'.implode(" ", $pilihan7[$d->sequence]).'

                                            
                                        </div>

                                        <div id="pilihan_'.$d->sequence.'"></div>
                                        <div class="form-group col-md-12">
                                            <button type="button" data-counter-pilihan="1" data-no-pilihan="'.$d->sequence.'" data-case="8" class="btn blue btn_pilihan'.$d->sequence.'" id="btn_tambah_pilihan8"><i class="fa fa-plus"></i> Tambah pilihan</button>
                                        </div>

                                    </div>
                                    <div id="input2-rule'.$d->sequence.'" class="col-md-6">
                                    </div>
                                </div>
                            </div>
                        </div>';
                // return $form;
	                    $nomor ++;

    				break;

    				case '9':
    				// dd($d->pertanyaan);
    				    // foreach ($pertanyaan as $key_pertanyan => $val_pertanyaan) {
    				    // 	// if ($val_pertanyaan->pertanyaan == "Nomor Responden") {
    				    // 	// 	$cek_nomor = 'checked';
    				    // 	// }else{
    				    // 	// 	$cek_nomor = '';
    				    // 	// }
    				    // 	// dd();
    				    // }

    					$form[] = '<div class="portlet-body form connectedSortable" data-pertanyaan="'.$d->sequence.'" id="panel-pertanyaan'.$d->sequence.'">
                                <div class="panel panel-primary">
                                  <div class="panel-heading">
                                  <i class="fa fa-reorder grip grip-xl btn btn-primary btn-circle" id="handle-grip"></i>
                                     <span id="nomor"></span> <input type="hidden" id="sequence" name="sequence[]" value="'.$d->sequence.'"> 
                                     <input type="hidden" id="tipe" name="tipe['.$d->sequence.'][]" value="9">   

                                     Biodata Responden
                                    
                                    <div class="pull-right">
                                        <div class="tools">
                                            <div class="btn-group">
                                                <a class="btn btn-danger btn-sm btn-circle" onClick="hapus_pertanyaan('.$d->sequence.')" id="hapus_pertanyaan" href="javascript:;">
                                                    <span><i class="fa fa-trash red"></i> Delete </span>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="panel-body">
                                    <input type="hidden" id="nomer_urut" value="'.$d->sequence.'">
                                    <div id="input_rule'.$d->sequence.'">
                                        <div class="form-group col-md-12">
                                            <label>Static Content</label>
                                            <textarea class="static_content" name="static_content['.$d->sequence.'][]" id="static_content'.$d->sequence.'"></textarea>
                                            <br>
                                            <label>Posisi Static</label>
                                            <select class="form-control input-sm" name="posisi['.$d->sequence.'][]" id="posisi_'.$d->sequence.'">
                                                <option>Atas</option>
                                                <option>Bawah</option>
                                            </select>
                                        </div>

                                        <input type="hidden" id="nomer_urut" value="">
                                        
                                            <div class="col-md-12">
                                                  <label>Pertanyaan</label>
                                                <div class="row">

                                                  <div class="form-group">
                                                    <div class="col-md-12 funkyradio">
                                                      <div class="funkyradio-success">
                                                          <input type="checkbox" class="cekbox" name="responden['.$d->sequence.'][]" id="checkbox_nomor" value="Nomor Responden"/>
                                                          <label for="checkbox_nomor">Nomor Responden</label>
                                                      </div>
                                                    </div>
                                                  </div>
                                                  <div class="form-group">
                                                    <div class="col-md-12 funkyradio">
                                                      <div class="funkyradio-success">
                                                          <input type="checkbox" class="cekbox" name="responden['.$d->sequence.'][]" id="checkbox_nama" value="Nama Responden"/>
                                                          <label for="checkbox_nama">Nama Responden</label>
                                                      </div>
                                                    </div>
                                                  </div>
                                                  <div class="form-group">
                                                    <div class="col-md-12 funkyradio">
                                                      <div class="funkyradio-success">
                                                          <input type="checkbox" class="cekbox" name="responden['.$d->sequence.'][]" id="checkbox_telepon" value="Telepon Responden"/>
                                                          <label for="checkbox_telepon">Telepon Responden</label>
                                                      </div>
                                                    </div>
                                                  </div>
                                                  <div class="form-group">
                                                    <div class="col-md-12 funkyradio">
                                                      <div class="funkyradio-success">
                                                          <input type="checkbox" class="cekbox" name="responden['.$d->sequence.'][]" id="checkbox_umur" value="Umur Responden"/>
                                                          <label for="checkbox_umur">Umur Responden</label>
                                                      </div>
                                                    </div>
                                                  </div>
                                                  <div class="form-group">
                                                    <div class="col-md-12 funkyradio">
                                                      <div class="funkyradio-success">
                                                          <input type="checkbox" class="cekbox" name="responden['.$d->sequence.'][]" id="checkbox_email" value="Email Responden"/>
                                                          <label for="checkbox_email">Email Responden</label>
                                                      </div>
                                                    </div>
                                                  </div>
                                                
                                                </div>
                                            </div>
                                            
                                        </div>
                                </div>
                                </div>
                                </div>';
                // return $form;
	                    $nomor ++;
    				break;

        			default:
        				$form = '';
        				break;
        		}
        		$sequence[] = $d->sequence;


        		$posisi[$d->sequence][] = $d->position;

	            $rule = \DB::select(\DB::raw('SELECT r.*,ps.sequence, ps.survey_id from rule as r
											JOIN pertanyaan_survey as ps
											ON r.pertanyaan_id = ps.pertanyaan_id  
											where r.pertanyaan_id = '.$d->pertanyaan_id.' and ps.survey_id = '.$id.''));
	            // dd($rule);
	            if ($rule) {
	            	foreach ($rule as $key => $value) {
		            	$rules[$d->sequence][] = $value;
	            	}
	            }

			    $responden = \DB::select(\DB::raw('select pertanyaan from view_pertanyaan_jenis where survey_id = '.$id.' and jenis_id = "9" order by id ASC  '));


            } 
        	// dd($responden);
            if (!isset($form)) {
            	$form = '';
            }

            if (!isset($rules)) {
            	$rules = '';
            }

            if (!isset($sequence)) {
            	$sequence = '';
            }

            if (!isset($posisi)) {
            	$posisi = '';
            }
            if (!isset($responden)) {
            	$responden = '';
            }
			$survey = \DB::table('survey')->find($id);
			if ($survey->is_reusable == 2) {
				$total_responden = \DB::select(\DB::raw('SELECT total_responden FROM view_survey_reusable
												WHERE id = "'.$id.'" LIMIT 1 '));
			}else{
				$total_responden = \DB::select(\DB::raw('SELECT total_responden FROM view_survey
												WHERE id = "'.$id.'" LIMIT 1 '));
			}
            // dd($total_responden[0]->total_responden);

        // $data = \DB::select(\DB::raw('select * from view_pertanyaan_pilihan where survey_id = '.$id.' order by sequence ASC'));
        // dd($data);
            // return $rules;
            return response()->json([
            	'form' => $form,
            	'responden' => $responden,
            	'rules' => $rules,
            	'sequence' => $sequence,
            	'posisi' => $posisi,
            	'total_responden' => $total_responden[0]->total_responden,
            	'nomor' => $nomor]);
        // return view('pages.question.edit',compact('id','form'));
    }
    public function inputScoreSurvey($ids,Request $request){
        // $data = \DB::table('pertanyaan_survey')->where('survey_id',$id)->get();
        $id = Crypt::decryptString($ids);
        $datas = \DB::select(\DB::raw('select * from view_pertanyaan_jenis where survey_id = '.$id.' order by sequence ASC  '));
        // $data = \DB::select(\DB::raw('select * from view_pertanyaan_jenis where survey_id = '.$id.' group by sequence order by sequence ASC  '));
        // $data = \DB::select(\DB::raw('select * from view_pertanyaan_jenis where survey_id = '.$id.' '));
		// dd($data);
		$data = [];
		foreach ($datas as $key => $value) {
			$data[$value->sequence] = $value;
		}
        
        $nomor = 0;
            foreach($data as $key => $d){
            		switch ($d->jenis_id) {
					case '2':
        					$pil2 = \DB::select(\DB::raw('select id, text, bobot from pilihan where pertanyaan_id = '.$d->pertanyaan_id.''));
        					$no_case2 = 0;
        					foreach ($pil2 as $key => $value) {
                            	$no_case2++;
	        					$pilihan2[$d->sequence][] = '	<div class="form-group" id="line-pilihan'.$d->sequence.'_'.$no_case2.'">
			                                            <input type="text" class="form-control input-sm" name="pilihan['.$d->sequence.'][]" disabled  placeholder="Pilihan" value="'.$value->text.'" maxlength="185" required '.(isset($rule_pertanyaan) ? 'readonly' : '').'> 
		                                    	</div>';
								$score2[$d->sequence][] = '	
												<div class="form-group" id="line-pilihan'.$d->sequence.'_'.$no_case2.'">
													<input type="text" class="form-control input-sm number" name="bobot_pilihan['.$d->sequence.'][]"  placeholder="Skor" value="'.($value->bobot != NULL ? $value->bobot : 0 ).'" maxlength="185" required '.(isset($rule_pertanyaan) ? 'readonly' : '').'> 
													<input type="hidden" class="form-control input-sm" name="id_pilihan['.$d->sequence.'][]"  placeholder="Skor" value="'.$value->id.'">
												</div>';
        					}

	        				$form[] = '<div class="portlet-body form connectedSortable" data-pertanyaan="'.$d->sequence.'" id="panel-pertanyaan'.$d->sequence.'">
	                                <div class="panel panel-primary">
	                                  <div class="panel-heading">
	                                     <span id="nomor"></span> <input type="hidden" id="sequence" name="sequence[]" value="'.$d->sequence.'"> 
	                                     <input type="hidden" id="tipe" name="tipe['.$d->sequence.'][]" value="2">  

	                                     Multiple Choices, Single Answer

	                                </div>
	                                <div class="panel-body">
	                                	<div class="row">
		                                	<input type="hidden" id="nomer_urut" value="'.$d->sequence.'">
		                                    <div id="input_rule'.$d->sequence.'" class="col-md-12">

		                                    	<div class="form-group col-md-12">
	                                                <label>Static Content</label>
	                                                <textarea class="static_content" name="static_content['.$d->sequence.'][]" >'.$d->static.'
	                                                </textarea>
	                                            </div>
	                                            
	                                            <div class="form-group col-md-3">
	                                            	<label>Posisi Static</label>
													<input class="form-control input-sm" disabled name="nomor_pertanyaan['.$d->sequence.'][]" id="nomor_pertanyaan_'.$d->sequence.'" placeholder="No. Pertanyaan" value="'.$d->position.'" maxlength="9" required>
	                                                <br>
	                                                <label>Nomor</label>
	                                                <input class="form-control input-sm" disabled name="nomor_pertanyaan['.$d->sequence.'][]" id="nomor_pertanyaan_'.$d->sequence.'" placeholder="No. Pertanyaan" value="'.$d->nomor.'" maxlength="9" required>
	                                            </div>

		                                        <div class="form-group col-md-9">
		                                            <label>Pertanyaan</label>
		                                            <textarea disabled type="text" class="form-control input-sm" rows="5" name="pertanyaan['.$d->sequence.'][]" placeholder="Tulis Pertanyaan" maxlength="500" required>'.\Iff::namaPertanyaan($d->pertanyaan_id).'</textarea>
		                                        </div>
		                                        
	                                            <div class="form-group col-md-11">
			                                        <label> Pilihan </label>
			                                        '.(isset($pilihan2[$d->sequence]) ? implode(" ", $pilihan2[$d->sequence]) : '' ).'
	                                            </div>
		                                        
	                                            <div class="form-group col-md-1">
			                                        <label> Skor </label>
			                                        '.(isset($score2[$d->sequence]) ? implode(" ", $score2[$d->sequence]) : '' ).'
	                                            </div>

		                                    </div>
		                                </div>
		                                
	                                </div>
	                            </div>
	                        </div>';
		                    $nomor ++;

    				break;
					case '3':
        					$pil2 = \DB::select(\DB::raw('select id, text, bobot from pilihan where pertanyaan_id = '.$d->pertanyaan_id.''));
        					$no_case2 = 0;
        					foreach ($pil2 as $key => $value) {
                            	$no_case2++;
	        					$pilihan2[$d->sequence][] = '	<div class="form-group" id="line-pilihan'.$d->sequence.'_'.$no_case2.'">
			                                            <input type="text" class="form-control input-sm" name="pilihan['.$d->sequence.'][]" disabled  placeholder="Pilihan" value="'.$value->text.'" maxlength="185" required '.(isset($rule_pertanyaan) ? 'readonly' : '').'> 
		                                    	</div>';
								$score2[$d->sequence][] = '	
												<div class="form-group" id="line-pilihan'.$d->sequence.'_'.$no_case2.'">
													<input type="text" class="form-control input-sm number" name="bobot_pilihan['.$d->sequence.'][]"  placeholder="Skor" value="'.($value->bobot != NULL ? $value->bobot : 0 ).'" maxlength="185" required '.(isset($rule_pertanyaan) ? 'readonly' : '').'> 
													<input type="hidden" class="form-control input-sm" name="id_pilihan['.$d->sequence.'][]"  placeholder="Skor" value="'.$value->id.'">
												</div>';
        					}
        					// dd($pilihan2);

	        				$form[] = '<div class="portlet-body form connectedSortable" data-pertanyaan="'.$d->sequence.'" id="panel-pertanyaan'.$d->sequence.'">
	                                <div class="panel panel-primary">
	                                  <div class="panel-heading">
	                                     <span id="nomor"></span> <input type="hidden" id="sequence" name="sequence[]" value="'.$d->sequence.'"> 
	                                     <input type="hidden" id="tipe" name="tipe['.$d->sequence.'][]" value="3">  

	                                     Multiple Choices, Multiple Answer

	                                </div>
	                                <div class="panel-body">
	                                	<div class="row">
		                                	<input type="hidden" id="nomer_urut" value="'.$d->sequence.'">
		                                    <div id="input_rule'.$d->sequence.'" class="col-md-12">

		                                    	<div class="form-group col-md-12">
	                                                <label>Static Content</label>
	                                                <textarea class="static_content" name="static_content['.$d->sequence.'][]" >'.$d->static.'
	                                                </textarea>
	                                            </div>
	                                            
	                                            <div class="form-group col-md-3">
	                                            	<label>Posisi Static</label>
													<input class="form-control input-sm" disabled name="nomor_pertanyaan['.$d->sequence.'][]" id="nomor_pertanyaan_'.$d->sequence.'" placeholder="No. Pertanyaan" value="'.$d->position.'" maxlength="9" required>
	                                                <br>
	                                                <label>Nomor</label>
	                                                <input class="form-control input-sm" disabled name="nomor_pertanyaan['.$d->sequence.'][]" id="nomor_pertanyaan_'.$d->sequence.'" placeholder="No. Pertanyaan" value="'.$d->nomor.'" maxlength="9" required>
	                                            </div>

		                                        <div class="form-group col-md-9">
		                                            <label>Pertanyaan</label>
		                                            <textarea disabled type="text" class="form-control input-sm" rows="5" name="pertanyaan['.$d->sequence.'][]" placeholder="Tulis Pertanyaan" maxlength="500" required>'.\Iff::namaPertanyaan($d->pertanyaan_id).'</textarea>
		                                        </div>
		                                        
	                                            <div class="form-group col-md-11">
			                                        <label> Pilihan </label>
			                                        '.(isset($pilihan2[$d->sequence]) ? implode(" ", $pilihan2[$d->sequence]) : '' ).'
	                                            </div>
		                                        
	                                            <div class="form-group col-md-1">
			                                        <label> Skor </label>
			                                        '.(isset($score2[$d->sequence]) ? implode(" ", $score2[$d->sequence]) : '' ).'
	                                            </div>

		                                    </div>
		                                </div>
		                                
	                                </div>
	                            </div>
	                        </div>';
		                    $nomor ++;

					break;
					
					case '7':
							$pil = \DB::select(\DB::raw('select id, text, bobot from pilihan where pertanyaan_id = '.$d->pertanyaan_id.''));
							$per7 = \DB::select(\DB::raw('select id, survey_id, pertanyaan, sequence from view_pertanyaan_jenis where survey_id = '.$id.' and sequence = '.$d->sequence.' '));
							$no_case2 = 0;
							$textpil = '';
							$textpertanyaan = '';
							$col = 12 - count($pil);
        					foreach ($pil as $key => $value) {
								 $textpil .= '
								 <div class="form-group col-md-1 text-center">
									 <label>
									 '.$value->text.'
									 </label>
								 </div>';
							}
							foreach ($per7 as $key => $value) {
                            	$no_case2++;
								// $pertanyaan7[$d->sequence][] = '
								// 				<div class="form-group">
			                    //                         <input type="text" class="form-control input-sm" disabled value="'.$value->pertanyaan.'"> 
		                        //             	</div>';
								// $score2[$d->sequence][] = '	
								// 				<div class="form-group">
								// 					<input type="text" class="form-control input-sm number" name="bobot_pilihan['.$d->sequence.'][]"  placeholder="Skor" value="'.($value->bobot != NULL ? $value->bobot : 0 ).'" maxlength="185" required '.(isset($rule_pertanyaan) ? 'readonly' : '').'> 
								// 					<input type="hidden" class="form-control input-sm" name="id_pilihan['.$d->sequence.'][]"  placeholder="Skor" value="'.$value->id.'">
								// 				</div>';


								$textpertanyaan .= '
								<div class="form-group col-md-'.$col.'">
									<input type="text" class="form-control input-sm" disabled value="'.$value->pertanyaan.'"> 
								</div>';

								$pils = \DB::select(\DB::raw('select id, text, bobot from pilihan where pertanyaan_id = '.$value->id.''));
								foreach ($pils as $key => $value) {
									$textpertanyaan .= '
									<div class="form-group col-md-1 text-center">
										<input type="text" class="form-control input-sm number" name="bobot_pilihan['.$d->sequence.'][]"  placeholder="Skor" value="'.($value->bobot != NULL ? $value->bobot : 0 ).'" maxlength="185" required '.(isset($rule_pertanyaan) ? 'readonly' : '').'> 
										<input type="hidden" class="form-control input-sm" name="id_pilihan['.$d->sequence.'][]"  placeholder="Skor" value="'.$value->id.'">
									</div>';
							   }
        					}
	        				$form[] = '<div class="portlet-body form connectedSortable" data-pertanyaan="'.$d->sequence.'" id="panel-pertanyaan'.$d->sequence.'">
	                                <div class="panel panel-primary">
	                                  <div class="panel-heading">
	                                     <span id="nomor"></span> <input type="hidden" id="sequence" name="sequence[]" value="'.$d->sequence.'"> 
	                                     <input type="hidden" id="tipe" name="tipe['.$d->sequence.'][]" value="3">  

	                                     Matrix Table

	                                </div>
	                                <div class="panel-body">
	                                	<div class="row">
		                                	<input type="hidden" id="nomer_urut" value="'.$d->sequence.'">
		                                    <div id="input_rule'.$d->sequence.'" class="col-md-12">

		                                    	<div class="form-group col-md-12">
	                                                <label>Static Content</label>
	                                                <textarea class="static_content" name="static_content['.$d->sequence.'][]" >'.$d->static.'
	                                                </textarea>
	                                            </div>
	                                            
	                                            <div class="form-group col-md-3">
	                                            	<label>Posisi Static</label>
													<input class="form-control input-sm" disabled name="nomor_pertanyaan['.$d->sequence.'][]" id="nomor_pertanyaan_'.$d->sequence.'" placeholder="No. Pertanyaan" value="'.$d->position.'" maxlength="9" required>
	                                                <br>
	                                                <label>Nomor</label>
	                                                <input class="form-control input-sm" disabled name="nomor_pertanyaan['.$d->sequence.'][]" id="nomor_pertanyaan_'.$d->sequence.'" placeholder="No. Pertanyaan" value="'.$d->nomor.'" maxlength="9" required>
	                                            </div>

		                                        <div class="form-group col-md-9">
		                                            <label>Pertanyaan</label>
		                                            <textarea disabled type="text" class="form-control input-sm" rows="5" name="pertanyaan['.$d->sequence.'][]" placeholder="Tulis Pertanyaan" maxlength="500" required>'.\Iff::namaPertanyaan($d->pertanyaan_id).'</textarea>
		                                        </div>
		                                        
	                                            <div class="form-group col-md-'.$col.'">
			                                        <label> Pertanyaan </label>
												</div>
												'.$textpil.'
												'.$textpertanyaan.'

		                                    </div>
		                                </div>
		                                
	                                </div>
	                            </div>
	                        </div>';
		                    $nomor ++;

					break;


        			default:
        				$form[] = '';
        				break;
        		}
        		$sequence[] = $d->sequence;


        		$posisi[$d->sequence][] = $d->position;

	            $rule = \DB::select(\DB::raw('SELECT r.*,ps.sequence, ps.survey_id from rule as r
											JOIN pertanyaan_survey as ps
											ON r.pertanyaan_id = ps.pertanyaan_id  
											where r.pertanyaan_id = '.$d->pertanyaan_id.' and ps.survey_id = '.$id.''));
	            // dd($rule);
	            if ($rule) {
	            	foreach ($rule as $key => $value) {
		            	$rules[$d->sequence][] = $value;
	            	}
	            }

			    $responden = \DB::select(\DB::raw('select pertanyaan from view_pertanyaan_jenis where survey_id = '.$id.' and jenis_id = "9" order by id ASC  '));


            } 
        	// dd($responden);
            if (!isset($form)) {
            	$form = '';
            }

            if (!isset($rules)) {
            	$rules = '';
            }

            if (!isset($sequence)) {
            	$sequence = '';
            }

            if (!isset($posisi)) {
            	$posisi = '';
            }
            if (!isset($responden)) {
            	$responden = '';
            }
			$survey = \DB::table('survey')->find($id);
			if ($survey->is_reusable == 2) {
				$total_responden = \DB::select(\DB::raw('SELECT total_responden FROM view_survey_reusable
												WHERE id = "'.$id.'" LIMIT 1 '));
			}else{
				$total_responden = \DB::select(\DB::raw('SELECT total_responden FROM view_survey
												WHERE id = "'.$id.'" LIMIT 1 '));
			}
            // dd($total_responden[0]->total_responden);

        // $data = \DB::select(\DB::raw('select * from view_pertanyaan_pilihan where survey_id = '.$id.' order by sequence ASC'));
        // dd($data);
            // return $rules;
            return response()->json([
            	'form' => $form,
            	'responden' => $responden,
            	'rules' => $rules,
            	'sequence' => $sequence,
            	'posisi' => $posisi,
            	'total_responden' => $total_responden[0]->total_responden,
            	'nomor' => $nomor]);
        // return view('pages.question.edit',compact('id','form'));
    }

    public function rules($no,$jenis,$sequence,Request $request){
    	$no_rule_pertanyaan1 = 1;
    	switch ($jenis) {
    		case '1':
    			$form = '<div class="rules'.$sequence.'_'.$no.' jumlah_rules'.$sequence.'">
                <div class="col-md-12">
                    <div class="form-group">
                        <label>Rule Question Pilihan</label> 
                        <select name="select_rule_pilihan['.$sequence.']['.($no - 1).']" id="select_rule_pilihan'.$sequence.'_'.$no.'" class="form-control select-pilihan2 select-rule'.$sequence.'" data-sequence="'.$sequence.'" data-no="'.$no.'" required>
                            <option value="">Pilihan</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                         <label>Rules</label> 
                        <select name="rules['.$sequence.']['.($no - 1).']" id="" class="form-control rules" data-id="'.$sequence.'" data-no="'.$no.'">
                            <option value="go">Go To</option>
                            <option value="end">End Survey</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Pertanyaan</label>
                        <select name="select_rule_pertanyaan['.$sequence.']['.($no - 1).']" id="select_rule_pertanyaan'.$sequence.'_'.$no.'" data-select="pertanyaan" class="form-control select_rule_pertanyaan" data-select-rule'.$no.'="pertanyaan" required>
                            <option selected value>Pilih Pertanyaan</option>
                        </select>
                    </div>
                </div>
                    
                    <div class="col-md-6 form-group pull-right ">
                        <button type="button" class="btn btn-sm btn-danger btn-circle" id="delete_rule_pertanyaan'.$sequence.'_'.$no.'" data-no="'.$no.'" onClick="delete_rule_pertanyaan('.$sequence. ',' .$no.')"><i class="fa fa-trash"></i> Delete</button>
                    </div>
                    <div class="col-md-6 form-group ">
                        <button type="button" class="btn btn-sm btn-success btn-circle add-btn-rule1" data-count="2" data-jenis="'.$jenis.'" data-sequence="'.$sequence.'" id="btn-rules'.$sequence.'"><i class="fa fa-plus"></i> Add</button></div>
	            </div>';
	            $no_rule_pertanyaan1++;
	            return $form;

    			break;
    		case '2':
    		$form = '<div class="rules'.$sequence.'_'.$no.' jumlah_rules'.$sequence.'">
                <div class="col-md-6">
                    <div class="form-group">
                         <label>Rules</label> 
                        <select name="select_anti_pilihan['.$sequence.'][]" id="select_anti_pilihan'.$sequence.'_'.$no.'" class="form-control">
                            <option value="">Pilihan</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Pilihan Anti</label>
                        <select name="select_anti_pilihan2['.$sequence.'][]" id="select_anti_pilihan_2_'.$sequence.'_'.$no.'" class="form-control">
                            <option value="">Pilihan</option>
                        </select>
                    </div>
                </div>
                    
                    <div class="col-md-6 form-group pull-right ">
                        <button type="button" class="btn btn-sm btn-danger btn-circle" id="delete_rule_pertanyaan'.$sequence.'_'.$no.'" data-no="'.$no.'" onClick="delete_rule_pertanyaan('.$sequence. ',' .$no.')"><i class="fa fa-trash"></i> Delete</button>
                    </div>
                    <div class="col-md-6 form-group ">
                        <button type="button" class="btn btn-sm btn-success btn-circle add-btn-anti1" data-count="2" data-jenis="'.$jenis.'" data-sequence="'.$sequence.'" id="btn-antis'.$sequence.'"><i class="fa fa-plus"></i> Add</button></div>
            </div>';
            $no_rule_pertanyaan1++;
            return $form;

    		break;

    		case '3':
    			$form = '<div class="rules'.$sequence.'_'.$no.' jumlah_rules'.$sequence.'">
                <div class="col-md-12">
                    <div class="form-group">
                        <label>Rule Question Pilihan</label> 
                        <select name="select_rule_pilihan['.$sequence.']['.($no - 1).']" id="select_rule_pilihan'.$sequence.'_'.$no.'" class="form-control select-pilihan-semantic select-rule'.$sequence.'" data-sequence="'.$sequence.'" data-no="'.$no.'" required>
                            <option value="">Pilihan</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                         <label>Rules</label> 
                        <select name="rules['.$sequence.']['.($no - 1).']" id="" class="form-control rules" data-id="'.$sequence.'" data-no="'.$no.'">
                            <option value="go">Go To</option>
                            <option value="end">End Survey</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Pertanyaan</label>
                        <select name="select_rule_pertanyaan['.$sequence.']['.($no - 1).']" id="select_rule_pertanyaan'.$sequence.'_'.$no.'" data-select="pertanyaan" class="form-control select_rule_pertanyaan" data-select-rule'.$no.'="pertanyaan" required>
                            <option selected value>Pilih Pertanyaan</option>
                        </select>
                    </div>
                </div>
                    
                    <div class="col-md-6 form-group pull-right ">
                        <button type="button" class="btn btn-sm btn-danger btn-circle" id="delete_rule_pertanyaan'.$sequence.'_'.$no.'" data-no="'.$no.'" onClick="delete_rule_pertanyaan('.$sequence. ',' .$no.')"><i class="fa fa-trash"></i> Delete</button>
                    </div>
                    <div class="col-md-6 form-group ">
                        <button type="button" class="btn btn-sm btn-success btn-circle add-btn-rule-semantic" data-count="2" data-jenis="'.$jenis.'" data-sequence="'.$sequence.'" id="btn-rules'.$sequence.'"><i class="fa fa-plus"></i> Add</button></div>
            </div>';
            $no_rule_pertanyaan1++;
            return $form;
			break;

    		default:
    			# code...
    			break;
    	}
	}

    public function reusable($id, $no, Request $request){
    	// $data = \DB::select(\DB::raw('SELECT ps.id, ps.survey_id, ps.pertanyaan_id, ps.sequence, p.text, p.jenis_id
					// 			FROM pertanyaan_survey as ps 
					// 			JOIN pertanyaan as p 
					// 			ON p.id = ps.pertanyaan_id
					// 			WHERE p.deleted_at IS NULL AND ps.deleted_at IS NULL AND ps.survey_id = "'.$id.'" ORDER BY ps.sequence '));
    	$data = \DB::select(\DB::raw('select * from view_pertanyaan_jenis where survey_id = '.$id.' group by sequence order by id ASC'));
    	// return $data;
    	$counter = 0;
    	foreach ($data as $key => $val) {
			switch($val->jenis_id){
				case '1':
					$form[] = '<div class="portlet-body form connectedSortable" data-pertanyaan="'.$no.'" id="panel-pertanyaan'.$no.'">
		                            <div class="panel panel-primary">
		                              <div class="panel-heading">
		                              <i class="fa fa-reorder grip grip-xl btn btn-success btn-circle" id="handle-grip"></i>
		                                 <span id="nomor"></span> <input type="hidden" id="sequence" name="sequence[]" value="'.$no.'"> 
		                                 <input type="hidden" id="tipe" name="tipe['.$no.'][]" value="1">  
		                                 Dichotomous
		                                
		                                <div class="pull-right">
		                                    <div class="tools">
		                                        <div class="btn-group">
		                                            <a class="btn btn-primary btn-sm btn-circle" href="javascript:;" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">Option
		                                                <i class="fa fa-angle-down"></i>
		                                            </a>
		                                            <ul class="dropdown-menu pull-right">
		                                                <li>
		                                                    <a href="javascript:;" class="btn-rule1" id="btn-rule1" data-jenis="1" data-sequence="'.$no.'" data-count="1" data-rule="uncek"> Rule Question</a>
		                                                </li>
		                                            </ul>
		                                        </div>
		                                    </div>
		                                </div>
		                                <div class="pull-right">
		                                    <div class="tools">
		                                        <div class="btn-group">
		                                            <a class="btn btn-danger btn-sm btn-circle" onClick="hapus_pertanyaan('.$no.')" id="hapus_pertanyaan" href="javascript:;">
		                                                <span><i class="fa fa-trash red"></i> Delete</span>
		                                            </a>
		                                        </div>
		                                    </div>
		                                </div> 
		                            </div>

		                            <div class="panel-body">
		                                <div class="row">
		                                    <input type="hidden" id="nomer_urut" value="'.$no.'">
		                                    <div id="input_rule'.$no.'" class="col-md-12">

			                                    <div class="form-group col-md-12">
	                                                <label>Static Content</label>
	                                                <textarea class="static_content" name="static_content['.$no.'][]" id="static_content'.$no.'" ></textarea>
	                                            </div>
	                                            <div class="form-group col-md-3">
	                                            	<label>Posisi Static</label>
	                                                <select class="form-control input-sm" name="posisi['.$no.'][]" id="posisi_'.$no.'">
	                                                    <option>Atas</option>
	                                                    <option>Bawah</option>
	                                                </select>
	                                                <br>
	                                                <label>Nomor</label>
	                                                <input class="form-control input-sm" name="nomor_pertanyaan['.$no.'][]" id="nomor_pertanyaan_'.$no.'" placeholder="No. Pertanyaan" maxlength="9" required>
	                                            </div>

		                                        <div class="form-group col-md-9">
		                                            <label>Pertanyaan</label>
		                                            <textarea type="text" class="form-control input-sm" rows="5" name="pertanyaan['.$no.'][]" id="pertanyaan_'.$no.'" placeholder="Tulis Pertanyaan" maxlength="500" required>'.\Iff::namaPertanyaan($val->pertanyaan_id).'</textarea>
		                                        </div>
		                                        <div class="form-group col-md-12">
		                                            <label>Pilihan</label>
		                                            <input type="text" class="form-control input-sm pilihan_'.$no.'" id="pilihan_'.$no.'" name="pilihan['.$no.'][]" placeholder="" value="Ya" readonly> 
		                                        </div>
		                                        <div class="form-group col-md-12">
		                                            <input type="text" class="form-control input-sm pilihan_'.$no.'" id="pilihan_'.$no.'" name="pilihan['.$no.'][]" placeholder="" value="Tidak" readonly> 
		                                        </div>
		                                    </div>

			                                <div id="input2-rule'.$no.'" class="col-md-6">
			                                '.(isset($rule_form[$no]) ? implode(" ", $rule_form[$no]) : '').'
			                                </div>
		                                </div>
		                                <div id="btn-add-rule'.$no.'" class="col-md-6">
		                                </div>
		                            </div>
		                        </div>
		                        </div>
		                    </div>';
		                    // $nomor ++;
				break;
				case '2':
					$pil2 = \DB::select(\DB::raw('select id, text from pilihan where pertanyaan_id = '.$val->pertanyaan_id.''));
					$no_case2 = 0;
					foreach ($pil2 as $key => $value) {
                    	$no_case2++;
    					$pilihan2[$no][] = '	
    							<div class="form-group" id="line-pilihan'.$no.'_'.$no_case2.'">
                                    <div class="input-group">

                                        <input type="text" class="form-control input-sm keyup-pilihan2 disable_pilihan'.$no.' pilihan_'.$no.'" name="pilihan['.$no.'][]"  placeholder="Pilihan" value="'.$value->text.'" maxlength="185" required > 
                                    <span class="input-group-addon" ><a href="javascript:;" data-counter="'.$no_case2.'" data-no="'.$no.'"  class="fa fa-trash btn_pilihan'.$no.'" id="line-pilihan" style="color:red;" > Delete</a> </span>
                                    </div>
                            	</div>';
					}

					$form = '<div class="portlet-body form connectedSortable" data-pertanyaan="'.$no.'" id="panel-pertanyaan'.$no.'">
	                                <div class="panel panel-primary">
	                                  <div class="panel-heading">
	                                  <i class="fa fa-reorder grip grip-xl btn btn-success btn-circle" id="handle-grip"></i>
	                                     <span id="nomor"></span> <input type="hidden" id="sequence" name="sequence[]" value="'.$no.'"> 
	                                     <input type="hidden" id="tipe" name="tipe['.$no.'][]" value="2">  


	                                     Multiple Choices, Single Answer

	                                     <div class="pull-right">
	                                        <div class="tools">
	                                            <div class="btn-group">
	                                                <a class="btn btn-primary btn-sm btn-circle" href="javascript:;" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">Option
	                                                    <i class="fa fa-angle-down"></i>
	                                                </a>
	                                                <ul class="dropdown-menu pull-right">
	                                                    <li>
	                                                        <a href="javascript:;" class="btn-rule1" id="btn-rule1" data-jenis="1" data-sequence="'.$no.'" data-count="1" data-rule="uncek"> Rule Question</a>
	                                                    </li>
	                                                </ul>
	                                            </div>
	                                        </div>
	                                    </div>

	                                   <div class="pull-right">
	                                        <div class="tools">
	                                            <div class="btn-group">
	                                                <a class="btn btn-danger btn-sm btn-circle" onClick="hapus_pertanyaan('.$no.')" id="hapus_pertanyaan" href="javascript:;">
	                                                    <span><i class="fa fa-trash red"></i> Delete </span>
	                                                </a>
	                                            </div>
	                                        </div>
	                                    </div>
	                                </div>
	                                <div class="panel-body">
	                                	<div class="row">
		                                	<input type="hidden" id="nomer_urut" value="'.$no.'">
		                                    <div id="input_rule'.$no.'" class="col-md-12">

		                                    	<div class="form-group col-md-12">
	                                                <label>Static Content</label>
	                                                <textarea class="static_content" name="static_content['.$no.'][]" id="static_content'.$no.'" >
	                                                </textarea>
	                                            </div>
	                                            
	                                            <div class="form-group col-md-3">
	                                            	<label>Posisi Static</label>
	                                                <select class="form-control input-sm" name="posisi['.$no.'][]" id="posisi_'.$no.'">
	                                                    <option>Atas</option>
	                                                    <option>Bawah</option>
	                                                </select>
	                                                <br>
	                                                <label>Nomor</label>
	                                                <input class="form-control input-sm" name="nomor_pertanyaan['.$no.'][]" id="nomor_pertanyaan_'.$no.'" placeholder="No. Pertanyaan" maxlength="9" required>
	                                            </div>

		                                        <div class="form-group col-md-9">
		                                            <label>Pertanyaan</label>
		                                            <textarea type="text" class="form-control input-sm" rows="5" name="pertanyaan['.$no.'][]" placeholder="Tulis Pertanyaan" maxlength="500" required>'.\Iff::namaPertanyaan($val->pertanyaan_id).'</textarea>
		                                        </div>
		                                        
	                                            <div class="form-group col-md-12">
			                                        <label> Pilihan </label>
			                                        '.(isset($pilihan2[$no]) ? implode(" ", $pilihan2[$no]) : '' ).'
		                                        	<div id="pilihan_'.$no.'"></div>
		                                            <button type="button" data-case="'.$no_case2.'" data-tipe="2" class="btn blue btn-tambah-pilihan btn_pilihan'.$no.'" data-no-pilihan="'.$no.'" id="btn_tambah_pilihan2"><i class="fa fa-plus"></i> Tambah Pilihan</button>
	                                            </div>

		                                    </div>

		                                    <div id="input2-rule'.$no.'" class="col-md-6">
			                                </div>
		                                </div>
		                                
	                                </div>
	                            </div>
	                        </div>';
				break;
				case '3':
					$pil3 = \DB::select(\DB::raw('select id, text from pilihan where pertanyaan_id = '.$val->pertanyaan_id.''));
					$no_case3 = 0;
					foreach ($pil3 as $key => $value) {
	                	$no_case3++;
						$pilihan3[$no][] = '
							<div class="form-group" id="line-pilihan'.$no.'_'.$no_case3.'">
	                            <div class="input-group">
	                                <input type="text" class="form-control input-sm keyup-pilihan2 pilihan_'.$no.' disable_pilihan'.$no.'" name="pilihan['.$no.'][]"  placeholder="Pilihan" value="'.$value->text.'" maxlength="185" required > 
	                        		<span class="input-group-addon" ><a href="javascript:;" data-counter="'.$no_case3.'" data-no="'.$no.'"  class="fa fa-trash btn_pilihan'.$no.'" id="line-pilihan" style="color:red;"> Delete</a> </span>
	                            </div>
	                    	</div>';
					}

					$form = '<div class="portlet-body form connectedSortable" data-pertanyaan="'.$no.'" id="panel-pertanyaan'.$no.'">
                                <div class="panel panel-primary">
                                  <div class="panel-heading">
                                  <i class="fa fa-reorder grip grip-xl btn btn-success btn-circle" id="handle-grip"></i>
                                     <span id="nomor"></span> <input type="hidden" id="sequence" name="sequence[]" value="'.$no.'"> 
                                     <input type="hidden" id="tipe" name="tipe['.$no.'][]" value="3">  

                                     Multiple Choices, Multiple Answer

                                     <div class="pull-right">
                                        <div class="tools">
                                            <div class="btn-group">
                                                <a class="btn btn-primary btn-sm btn-circle" href="javascript:;" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">Option
                                                    <i class="fa fa-angle-down"></i>
                                                </a>
                                                <ul class="dropdown-menu pull-right">
                                                    <li>
                                                        <a href="javascript:;" id="btn-anti'.$no.'" class="btn-anti3" data-jenis="2" data-sequence="'.$no.'" data-count="1" data-anti="uncek"> Rule Anti</a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>

                                   <div class="pull-right">
                                        <div class="tools">
                                            <div class="btn-group">
                                                <a class="btn btn-danger btn-sm btn-circle" onClick="hapus_pertanyaan('.$no.')" id="hapus_pertanyaan" href="javascript:;">
                                                    <span><i class="fa fa-trash red"></i> Delete </span>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="panel-body">
	                                <div class="row">
	                                    <input type="hidden" id="nomer_urut" value="'.$no.'">
	                                    <div id="input_rule'.$no.'" class="col-md-12">
	                                        
	                                        <div class="form-group col-md-12">
	                                            <label>Static Content</label>
	                                            <textarea class="static_content" name="static_content['.$no.'][]" id="static_content'.$no.'" >
	                                            </textarea>
	                                        </div>
	                                        
	                                        <div class="form-group col-md-3">
	                                            <label>Posisi Static</label>
	                                        	<select class="form-control input-sm" name="posisi['.$no.'][]" id="posisi_'.$no.'">
                                                    <option>Atas</option>
                                                    <option>Bawah</option>
                                                </select>
                                                <br>
	                                            <label>Nomor</label>
	                                            <input class="form-control input-sm" name="nomor_pertanyaan['.$no.'][]" id="nomor_pertanyaan_'.$no.'" placeholder="No. Pertanyaan" maxlength="9" required>
	                                        </div>

	                                        <div class="form-group col-md-9">
	                                            <label>Pertanyaan</label>
	                                            <textarea type="text" class="form-control input-sm" rows="5" name="pertanyaan['.$no.'][]" placeholder="Tulis Pertanyaan" maxlength="500" required>'.\Iff::namaPertanyaan($val->pertanyaan_id).'</textarea>
	                                        </div>
	                                        
	                                        <div class="form-group col-md-12">
		                                        <label> Pilihan </label>
		                                        '.implode(" ", $pilihan3[$no]).'
		                                        <div id="pilihan_'.$no.'"></div>
	                                            <button type="button" data-case="'.$no_case3.'" class="btn blue btn_pilihan'.$no.'" data-no-pilihan="'.$no.'" id="btn_tambah_pilihan3" '.(isset($anti_form) ? 'disabled' : '').'><i class="fa fa-plus"></i> Tambah Pilihan</button>
	                                        </div>

	                                    </div>

	                                    <div id="input2-rule'.$no.'" class="col-md-6">
	                                    </div>

                                	</div>
                                </div>
                            </div>
                        </div>';
				break;
				case '4':
					$pil4 = \DB::select(\DB::raw('select id, text from pilihan where pertanyaan_id = '.$val->pertanyaan_id.''));
    					$no_case4 = 0;
    					foreach ($pil4 as $key => $val_pil) {
                        	$no_case4++;
        					$pilihan4[$no][] = '
        						<div class="form-group" id="line-pilihan'.$no.'_'.$no_case4.'">
                                    <div class="input-group">
                                        <input type="text" class="form-control input-sm keyup-pilihan4 pilihan_'.$no.' disable_pilihan'.$no.'" name="pilihan['.$no.'][]"  placeholder="Pilihan" value="'.$val_pil->text.'" maxlength="185" required> 
                                    	<span class="input-group-addon" ><a href="javascript:;" data-counter="'.$no_case4.'" data-no="'.$no.'"  class="fa fa-trash" id="line-pilihan" style="color:red;"> Delete</a> </span>
                                    </div>
                            	</div>';
    					}

        				$form[] = '<div class="portlet-body form connectedSortable" data-pertanyaan="'.$no.'" id="panel-pertanyaan'.$no.'">
                                <div class="panel panel-primary">
                                  	<div class="panel-heading">
	                                  	<i class="fa fa-reorder grip grip-xl btn btn-success btn-circle" id="handle-grip"></i>
	                                     <span id="nomor"></span> <input type="hidden" id="sequence" name="sequence[]" value="'.$no.'"> 
	                                     <input type="hidden" id="tipe" name="tipe['.$no.'][]" value="4">  

	                                     Rating Scale

	                                     <div class="pull-right">
	                                        <div class="tools">
	                                            <div class="btn-group">
	                                                <a class="btn btn-primary btn-sm btn-circle" href="javascript:;" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">Option
	                                                    <i class="fa fa-angle-down"></i>
	                                                </a>
	                                                <ul class="dropdown-menu pull-right">
	                                                    <li>
	                                                        <a href="javascript:;" class="btn-rule1" id="btn-rule1" data-jenis="1" data-sequence="'.$no.'" data-count="1" data-rule="uncek"> Rule Question</a>
	                                                    </li>
	                                                </ul>
	                                            </div>
	                                        </div>
	                                    </div>

	                                    <div class="pull-right">
	                                        <div class="tools">
	                                            <div class="btn-group">
	                                                <a class="btn btn-danger btn-sm btn-circle" onClick="hapus_pertanyaan('.$no.')" id="hapus_pertanyaan" href="javascript:;">
	                                                    <span><i class="fa fa-trash red"></i> Delete </span>
	                                                </a>
	                                            </div>
	                                        </div>
	                                    </div>
	                                </div>

	                                <div class="panel-body">
		                                <div class="row">
		                                    <input type="hidden" id="nomer_urut" value="'.$no.'">
		                                    <div id="input_rule'.$no.'" class="col-md-12">

			                                    <div class="form-group col-md-12">
		                                            <label>Static Content</label>
		                                            <textarea class="static_content" name="static_content['.$no.'][]" id="static_content'.$no.'" >
		                                            </textarea>
		                                        </div>
		                                        
		                                        <div class="form-group col-md-3">
		                                        	<label>Posisi Static</label>
		                                        	<select class="form-control input-sm" name="posisi['.$no.'][]" id="posisi_'.$no.'">
	                                                    <option>Atas</option>
	                                                    <option>Bawah</option>
	                                                </select>
	                                                <br>
		                                            <label>Nomor</label>
		                                            <input class="form-control input-sm" name="nomor_pertanyaan['.$no.'][]" id="nomor_pertanyaan_'.$no.'" placeholder="No. Pertanyaan" maxlength="9" required>
		                                        </div>
		    
		                                        <div class="form-group col-md-9">
		                                            <label>Pertanyaan</label>
		                                            <textarea type="text" class="form-control input-sm" rows="5" name="pertanyaan['.$no.'][]" placeholder="Tulis Pertanyaan" maxlength="500" required>'.\Iff::namaPertanyaan($val->pertanyaan_id).'</textarea>
		                                        </div>
		                                        
		                                        <div class="form-group col-md-12">
			                                        <label> Scale Text </label>
			                                        '.(isset($pilihan4[$no]) ? implode(" ", $pilihan4[$no]) : "").'
		                                        	<div id="pilihan_'.$no.'"></div>
		                                            <button type="button" data-case="'.$no_case4.'" class="btn blue btn_pilihan'.$no.'" data-no-pilihan="'.$no.'" id="btn_tambah_scale4"><i class="fa fa-plus"></i> Tambah Scale</button>
		                                        </div>
		                                    </div>

		                                    <div id="input2-rule'.$no.'" class="col-md-6">
		                                    </div>
	                                    </div>

	                                </div>
	                            </div>
	                        </div>';
				break;
				case '5':
					$pil5 = \DB::select(\DB::raw('SELECT id, text, bobot FROM pilihan WHERE pertanyaan_id = '.$val->pertanyaan_id.''));

					foreach ($pil5 as $key => $value) {

						$pilihan = explode('|||', $value->text);
    					$input5 = '
                                        <div class="col-md-5">
                                            <label> Minimal Text </label>
                                            <input type="text" class="form-control input-sm disable_pilihan'.$no.'" name="pilihan['.$no.'][]" placeholder="Min. Text" value="'.$pilihan[0].'" maxlength="80" required > 
                                        </div>
                                        <div class="col-md-5">
                                            <label> Maximal Text </label>
                                            <input type="text" class="form-control input-sm disable_pilihan'.$no.'" name="pilihan['.$no.'][]" placeholder="Max. Text" value="'.$pilihan[1].'" maxlength="80" required > 
                                        </div>
                                        <div class="col-md-2">
                                            <label> Scale </label>
                                            <input type="text" class="form-control input-sm number disable_pilihan'.$no.'" id="scale'.$no.'" name="scale['.$no.'][]" value="'.$value->bobot.'" maxlength="2" required placeholder="Scale" required> 
                                        </div>
                                        ';
					}

    				$form = '<div class="portlet-body form connectedSortable" data-pertanyaan="'.$no.'" id="panel-pertanyaan'.$no.'">
                            <div class="panel panel-primary">
                              <div class="panel-heading">
                              <i class="fa fa-reorder grip grip-xl btn btn-success btn-circle" id="handle-grip"></i>
                                 <span id="nomor"></span> <input type="hidden" id="sequence" name="sequence[]" value="'.$no.'"> 
                                 <input type="hidden" id="tipe" name="tipe['.$no.'][]" value="5">  

                                 Semantic Differential

                                 <div class="pull-right">
                                    <div class="tools">
                                        <div class="btn-group">
                                            <a class="btn btn-primary btn-sm btn-circle" href="javascript:;" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">Option
                                                <i class="fa fa-angle-down"></i>
                                            </a>
                                            <ul class="dropdown-menu pull-right">
                                                <li>
													<a href="javascript:;" class="btn-rule-semantic" data-jenis="3" data-sequence="'.$no.'" data-count="1" data-rule="uncek"> Rule Question</a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>

                               <div class="pull-right">
                                    <div class="tools">
                                        <div class="btn-group">
                                            <a class="btn btn-danger btn-sm btn-circle" onClick="hapus_pertanyaan('.$no.')" id="hapus_pertanyaan" href="javascript:;">
                                                <span><i class="fa fa-trash red"></i> Delete </span>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="panel-body">
                                <div class="row">
                                    <input type="hidden" id="nomer_urut" value="'.$no.'">
                                    <div id="input_rule'.$no.'" class="col-md-12">

                                    	<div class="form-group col-md-12">
                                            <label>Static Content</label>
                                            <textarea class="static_content" name="static_content['.$no.'][]" id="static_content'.$no.'" >
                                            </textarea>
                                        </div>
                                        
                                        <div class="form-group col-md-3">
                                        	<label>Posisi Static</label>
                                        	<select class="form-control input-sm" name="posisi['.$no.'][]" id="posisi_'.$no.'">
                                                <option>Atas</option>
                                                <option>Bawah</option>
                                            </select>
                                            <br>
                                            <label>Nomor</label>
                                            <input class="form-control input-sm" name="nomor_pertanyaan['.$no.'][]" id="nomor_pertanyaan_'.$no.'" placeholder="No. Pertanyaan" maxlength="9" required>
                                        </div>

                                        <div class="form-group col-md-9">
                                            <label>Pertanyaan</label>
                                            <textarea type="text" class="form-control input-sm" rows="5" name="pertanyaan['.$no.'][]" placeholder="Tulis Pertanyaan" maxlength="500" required>'.\Iff::namaPertanyaan($val->pertanyaan_id).'</textarea> 
                                        </div>

		                                '.( isset($input5) ? $input5 : "").'
                                    </div>

                                    
                                    <div id="input2-rule'.$no.'" class="col-md-6">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>';
				break;

				case '6':
					$form = '<div class="portlet-body form connectedSortable" data-pertanyaan="'.$no.'" id="panel-pertanyaan'.$no.'">
                                <div class="panel panel-primary">
                                  <div class="panel-heading">
                                  <i class="fa fa-reorder grip grip-xl btn btn-success btn-circle" id="handle-grip"></i>
                                     <span id="nomor"></span> <input type="hidden" id="sequence" name="sequence[]" value="'.$no.'"> 
                                     <input type="hidden" id="tipe" name="tipe['.$no.'][]" value="6">  

                                     Open Ended

                                   <div class="pull-right">
                                        <div class="tools">
                                            <div class="btn-group">
                                                <a class="btn btn-danger btn-sm btn-circle" onClick="hapus_pertanyaan('.$no.')" id="hapus_pertanyaan" href="javascript:;">
                                                    <span><i class="fa fa-trash red"></i> Delete </span>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="panel-body">
		                            <div class="row">
		                                <input type="hidden" id="nomer_urut" value="'.$no.'">
		                                <div id="input_rule'.$no.'" class="col-md-12">
											
											<div class="form-group col-md-12">
		                                        <label>Static Content</label>
		                                        <textarea class="static_content" name="static_content['.$no.'][]" id="static_content'.$no.'" >
		                                        </textarea>
		                                    </div>
		                                    
		                                    <div class="form-group col-md-3">
		                                    	<label>Posisi Static</label>
	                                        	<select class="form-control input-sm" name="posisi['.$no.'][]" id="posisi_'.$no.'">
                                                    <option>Atas</option>
                                                    <option>Bawah</option>
                                                </select>
                                                <br>
		                                        <label>Nomor</label>
		                                        <input class="form-control input-sm" name="nomor_pertanyaan['.$no.'][]" id="nomor_pertanyaan_'.$no.'" placeholder="No. Pertanyaan" maxlength="9" required>
		                                    </div>

		                                    <div class="form-group col-md-9">
		                                        <label>Pertanyaan</label>
		                                        <textarea class="form-control input-sm" rows="5" maxlength="500" placeholder="Tulis Pertanyaan" name="pertanyaan['.$no.'][]" required>'.\Iff::namaPertanyaan($val->pertanyaan_id).'</textarea>
		                                    </div>
		                                
		                                </div>
		                                
		                                <div id="input2-rule'.$no.'" class="col-md-6">
		                                </div>
	                                </div>
                                </div>
                            </div>
                        </div>';
				break;
				case '7':
					$pil7 = \DB::select(\DB::raw('select id, text from pilihan where pertanyaan_id = '.$val->pertanyaan_id.''));
					// return $pil7
					$data_counter_scale7 = 0;

					foreach ($pil7 as $key => $value) {
                    	$data_counter_scale7++;
    					$pilihan7[$no][] = '
								<div class="form-group">
        							<div id="line-pilihan'.$no.'_'.$data_counter_scale7.'">
                                        <div class="input-group">
	                                        <input type="text" class="form-control input-sm pilihan_'.$no.'" name="pilihan['.$no.'][]" value="'.$value->text.'"  placeholder="Scale Text" maxlength="185" required> 
	                                        <span class="input-group-addon" ><a href="javascript:;" data-counter="'.$data_counter_scale7.'" data-no="'.$no.'" id="line-pilihan" class="fa fa-trash" style="color:red;"> Delete</a> </span>
	                                    </div>
                                    </div>
                                </div>';
					}

					$per7 = \DB::select(\DB::raw('select id, survey_id, pertanyaan, sequence from view_pertanyaan_jenis where survey_id = '.$id.' '));

					$data_counter7 = 0;
					foreach ($per7 as $key => $value) {
                    	$data_counter7++;
    					$pertanyaan7[$no][] = '
								<div class="form-group">
									<div id="line-pertanyaan'.$no.'_'.$data_counter7.'">
                                        <div class="input-group">
                                            <input type="text" class="form-control input-sm pertanyaan_'.$no.' disable_pilihan'.$no.'" name="pertanyaan['.$no.'][]" value="'.$value->pertanyaan.'"  placeholder="Pertanyaan" maxlength="500" required> 
	                                        <span class="input-group-addon" ><a href="javascript:;" data-counter="'.$data_counter7.'" data-no="'.$no.'" id="line-pertanyaan" class="fa fa-trash" style="color:red;"> Delete</a> </span>
                                        </div>
                                    </div>
                                </div>';
					}

    				$form = '<div class="portlet-body form connectedSortable" data-pertanyaan="'.$no.'" id="panel-pertanyaan'.$no.'">
                            <div class="panel panel-primary">
                              <div class="panel-heading">
                              <i class="fa fa-reorder grip grip-xl btn btn-success btn-circle" id="handle-grip"></i>
                                 <span id="nomor"></span> <input type="hidden" id="sequence" name="sequence[]" value="'.$no.'"> 
                                 <input type="hidden" id="tipe" name="tipe['.$no.'][]" value="7">  


                                 Matrix Table

                               <div class="pull-right">
                                    <div class="tools">
                                        <div class="btn-group">
                                            <a class="btn btn-danger btn-sm btn-circle" onClick="hapus_pertanyaan('.$no.')" id="hapus_pertanyaan" href="javascript:;">
                                                <span><i class="fa fa-trash red"></i> Delete </span>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="panel-body">

                            	<div class="row">
                                    <input type="hidden" id="nomer_urut" value="'.$no.'">
                                    <div id="input_rule'.$no.'" class="col-md-12">

                                    	<div class="form-group col-md-12">
	                                        <label>Static Content</label>
	                                        <textarea class="static_content" name="static_content['.$no.'][]" id="static_content'.$no.'" ></textarea>
	                                    </div>
	                                    
	                                    <div class="form-group col-md-3">
	                                    	<label>Posisi Static</label>
                                        	<select class="form-control input-sm" name="posisi['.$no.'][]" id="posisi_'.$no.'">
                                                <option>Atas</option>
                                                <option>Bawah</option>
                                            </select>
                                            <br>
	                                        <label>Nomor</label>
	                                        <input class="form-control input-sm" name="nomor_pertanyaan['.$no.'][]" id="nomor_pertanyaan_'.$no.'" placeholder="No. Pertanyaan" maxlength="9" required>
	                                    </div>

                                        <div class="form-group col-md-9">
                                            <label>Instruksi</label>
                                            <textarea type="text" class="form-control input-sm" rows="5" name="instruksi['.$no.']" placeholder="Tulis Instruksi" maxlength="500" rows="4" required>'.\Iff::namaInstruksi($val->pertanyaan_id).'</textarea>
                                        </div>
                                        
                                        <div class="form-group col-md-12">
	                                        <label>Scale</label>
	                                        '.(isset($pilihan7[$no]) ? implode(" ", $pilihan7[$no]) : '').'

                                    		<div id="pilihan_'.$no.'"></div>
                                            <button type="button" data-counter-scale="'.$data_counter_scale7.'" data-no-scale="'.$no.'" class="btn blue btn_pilihan'.$no.'" id="btn_tambah_scale7"><i class="fa fa-plus"></i> Tambah Scale</button>
                                        </div>
                                        
                                        <div class="form-group col-md-12">
	                                        <label>Pertanyaan</label>
	                                        '.(isset($pertanyaan7[$no]) ? implode(" ", $pertanyaan7[$no]) : '').'
                                    		
                                    		<div id="pertanyaan_'.$no.'"></div>
                                            <button type="button" data-counter-pertanyaan="'.$data_counter7.'" data-no-pertanyaan="'.$no.'" data-case="7" class="btn blue" id="btn_tambah_pertanyaan7"><i class="fa fa-plus"></i> Tambah Pertanyaan</button>
                                        </div>
                                        
                                    </div>
                                    <div id="input2-rule'.$no.'" class="col-md-6">
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>';
				break;

				case '8':
					$per7 = \DB::select(\DB::raw('select id, pertanyaan_id, pertanyaan, nomor, is_multiple from view_pertanyaan_jenis where survey_id = '.$val->survey_id.' order by id ASC'));

    					$data_counter7 = 0;
    					foreach ($per7 as $key => $value) {
                        	$data_counter7++;
                        	if ($value->is_multiple == 1) {
                        		$select = 'selected';
                        	}else{
                        		$select = '';
                        	}

        					$pertanyaan7[$no][] = '
        								<div id="line-instruksi'.$no.'_'.$data_counter7.'" class="instruksi'.$no.'" >
        									<div class="form-group col-md-2">
                                                <label>Nomor</label>
                                                <input class="form-control input-sm" maxlength="9" name="nomor_pertanyaan['.$no.'][]" id="nomor_pertanyaan_'.$no.'" placeholder="No. Pertanyaan" required>
                                                <br>
                                                <label>Type</label>
                                                <select class="form-control input-sm" name="tipe_pilihan['.$no.'][]" required>
                                                    <option value="0">Single Choice</option>
                                                    <option '.$select.' value="1">Multiple Choice</option>
                                                </select>
                                            </div>

                                            <div class="form-group col-md-10" >
                                            <label>Instruksi / Pertanyaan</label>
                                                <div class="row">
                                                    <div class="col-md-11">
                                                    <textarea type="text" class="form-control input-sm" rows="5" name="pertanyaan['.$no.'][]"  placeholder="Tulis Instruksi" maxlength="500" required>'.$value->pertanyaan.'</textarea>
                                                	</div>
                                                    
                                                    <div class="col-md-1">
                                                		<a href="javascript:;" data-counter="'.$data_counter7.'" data-no="'.$no.'" id="line-instruksi" class="btn btn-danger btn-circle"><i class="fa fa-close"></i></a>
                                                	</div>
                                            </div>
                                            </div>

                                        </div>';
    					}

    					$pil7 = \DB::select(\DB::raw('select id, text from pilihan where pertanyaan_id = '.$val->pertanyaan_id.''));

    					$data_counter_pilihan = 0;
    					foreach ($pil7 as $key => $value) {
                        	$data_counter_pilihan++;
        					$pilihan7[$no][] = '<div class="form-group" id="line-pilihan'.$no.'_'.$data_counter_pilihan.'">
			                                                <div class="input-group">

			                                                    <input type="text" class="form-control pilihan_'.$no.' input-sm disable_pilihan'.$no.'" name="pilihan['.$no.'][]" value="'.$value->text.'" placeholder="Pilihan" maxlength="185" required> 
			                                                <span class="input-group-addon" ><a href="javascript:;" data-counter="'.$data_counter_pilihan.'" data-no="'.$no.'" id="line-pilihan" class="fa fa-trash" style="color:red;"> Delete</a> </span>
			                                                </div>
			                                            </div>';
    					}

    					$form[] = '<div class="portlet-body form connectedSortable" data-pertanyaan="'.$no.'" id="panel-pertanyaan'.$no.'">
                                <div class="panel panel-primary">
                                  <div class="panel-heading">
                                  <i class="fa fa-reorder grip grip-xl btn btn-success btn-circle" id="handle-grip"></i>
                                     <span id="nomor"></span> <input type="hidden" id="sequence" name="sequence[]" value="'.$no.'"> 
                                     <input type="hidden" id="tipe" name="tipe['.$no.'][]" value="8">  


                                     Side-Beside Matrix

                                   <div class="pull-right">
                                        <div class="tools">
                                            <div class="btn-group">
                                                <a class="btn btn-danger btn-sm btn-circle" onClick="hapus_pertanyaan('.$no.')" id="hapus_pertanyaan" href="javascript:;">
                                                    <span><i class="fa fa-trash red"></i> Delete </span>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="panel-body">
                                    <input type="hidden" id="nomer_urut" value="'.$no.'">
                                    <div id="input_rule'.$no.'">
                                        
                                        <div class="form-group col-md-12">
	                                        <label>Static Content</label>
	                                        <textarea class="static_content" name="static_content['.$no.'][]" id="static_content'.$no.'" ></textarea>
                                            <br>
	                                    	<label>Posisi Static</label>
                                        	<select class="form-control input-sm" name="posisi['.$no.'][]" id="posisi_'.$no.'">
                                                <option>Atas</option>
                                                <option>Bawah</option>
                                            </select>
	                                    </div>
		                                    
                                        '.implode(" ", $pertanyaan7[$no]).'

                                        <div id="instruksi_'.$no.'">
                                        </div>
                                        
                                        <div class="form-group col-md-12">
                                            <button type="button" data-counter-instruksi="1" data-no-instruksi="'.$no.'" class="btn blue" id="btn_tambah_instruksi"><i class="fa fa-plus"></i> Tambah Instruksi</button>
                                        </div>
	
                                        <div class="col-md-12">
                                            <label>Pilihan</label>
                                        		'.implode(" ", $pilihan7[$no]).'
                                        </div>

                                        <div id="pilihan_'.$no.'"></div>
                                        <div class="form-group col-md-12">
                                            <button type="button" data-counter-pilihan="1" data-no-pilihan="'.$no.'" data-case="8" class="btn blue btn_pilihan'.$no.'" id="btn_tambah_pilihan8"><i class="fa fa-plus"></i> Tambah pilihan</button>
                                        </div>

                                    </div>
                                    <div id="input2-rule'.$no.'" class="col-md-6">
                                    </div>
                                </div>
                            </div>
                        </div>';
				break;
			}    		
    	}
    	
    	return $form;
    }

    public function editReusable($idr, Request $request)
    {
        // $jenis = $request->jenis;

    	$id = Crypt::decryptString($idr);

        $reusable = \DB::table('view_pertanyaan_jenis')->where([['survey_id', $id],['deleted_at',null]])->get();


        // return $jenis;
        foreach ($reusable as $key => $value) {
        	$jenis = $value->jenis_id;
	        
	        switch ($jenis) {
	            case '1':
	                $form = '<div class="portlet-body form connectedSortable reusableForms">
	                            <div class="panel panel-primary">
	                                <div class="panel-heading">
	                                    <span class="">'.\Iff::namaJenis($jenis).'</span>
	                                </div>
	                                <div class="panel-body">
	                                    <div class="row">
	                                        <input type="hidden" name="jenis" id="jenis" value="'.$jenis.'">
	                                        <div class="col-md-12">
	                                            <div class="form-group col-md-12">
	                                                <label>Pertanyaan</label>
	                                                <textarea class="form-control input-sm" rows="5" name="pertanyaan" id="pertanyaan" maxlength="500" required>'.\Iff::namaPertanyaan($value->pertanyaan_id).'</textarea>
	                                            </div>

	                                            <div class="form-group col-md-12">
	                                                <label>Pilihan</label>
	                                                <input type="text" class="form-control input-sm" id="pilihan" name="pilihan[]" placeholder="" value="Ya" readonly> 
	                                            </div>
	                                            <div class="form-group col-md-12">
	                                                <input type="text" class="form-control input-sm" id="pilihan" name="pilihan[]" placeholder="" value="Tidak" readonly> 
	                                            </div>
	                                        </div>
	                                    </div>
	                                </div>
	                            </div>
	                        </div>';
	                    return response()->json(['form'=>$form]);
	                break;
	            case '2':
	            	$pil2 = \DB::select(\DB::raw('select id, text from pilihan where pertanyaan_id = '.$value->pertanyaan_id.''));

	            	$counter_pil2 = 1;

	            	foreach ($pil2 as $key => $value_pil2) {
	            		if ($counter_pil2 == 1) {
	            			$lable = '<label>Pilihan</label>';
	            			$disabled = 'color:red;cursor: not-allowed;opacity: 0.5;text-decoration: none;';
	            		}else{
	            			$lable = '';
	            			$disabled = 'color:red';
	            		}
	            		$pilihan2[] = '<div class="form-group col-md-12" id="row_div'.$counter_pil2.'">
                                            '.$lable.'
                                            <div id="line-pilihan">
                                                <div class="input-group">
                                                    <input type="text" class="form-control input-sm keyup-pilihan" name="pilihan[]"  placeholder="Pilihan" maxlength="185" required value="'.$value_pil2->text.'"> 
                                                    <span class="input-group-addon" ><a href="javascript:;" data-id="'.$counter_pil2.'" class="fa fa-trash delete_row" id="delete_pilihan0" style="'.$disabled.'"> Delete</a> </span>
                                                </div>
                                            </div>
                                        </div>';
                        $counter_pil2++;
	            	}
	                $form = '<div class="portlet-body form connectedSortable reusableForms">
	                            <div class="panel panel-primary">
	                                <div class="panel-heading">
	                                    <span class="">'.\Iff::namaJenis($jenis).'</span>
	                                </div>
	                                <div class="panel-body">
	                                    <div class="row">
	                                        <input type="hidden" name="jenis" id="jenis" value="'.$jenis.'">
	                                        <div class="col-md-12">

	                                            <div class="form-group col-md-12">
	                                                <label>Pertanyaan</label>
	                                                <textarea class="form-control input-sm" maxlength="500" rows="5" name="pertanyaan" id="pertanyaan" required>'.\Iff::namaPertanyaan($value->pertanyaan_id).'</textarea>
	                                            </div>
	                                            
	                                            '.implode(" ", $pilihan2).'
	                                            <div id="pilihan"></div>

	                                            <div class="form-group col-md-12">
	                                                <button type="button" data-case="1" data-tipe="2" class="btn blue btn-tambah-pilihan btn_pilihan" id="btn_tambah_pilihan"><i class="fa fa-plus"></i> Tambah Pilihan</button>
	                                            </div>

	                                        </div>

	                                    </div>
	                                </div>
	                            </div>
	                        </div>';
	                    return response()->json(['form'=>$form, 'counter'=>$counter_pil2]);
	                break;
	            case '3':
	            	$pil3 = \DB::select(\DB::raw('select id, text from pilihan where pertanyaan_id = '.$value->pertanyaan_id.''));

	            	$counter_pil3 = 1;

	            	foreach ($pil3 as $key => $value_pil3) {
	            		if ($counter_pil3 == 1) {
	            			$lable = '<label>Pilihan</label>';
	            			$disabled = 'color:red;cursor: not-allowed;opacity: 0.5;text-decoration: none;';
	            		}else{
	            			$lable = '';
	            			$disabled = 'color:red';
	            		}
	            		$pilihan3[] = '<div class="form-group col-md-12" id="row_div'.$counter_pil3.'">
                                            '.$lable.'
                                            <div id="line-pilihan">
                                                <div class="input-group">
                                                    <input type="text" class="form-control input-sm keyup-pilihan" name="pilihan[]"  placeholder="Pilihan" maxlength="185" required value="'.$value_pil3->text.'"> 
                                                    <span class="input-group-addon" ><a href="javascript:;" data-id="'.$counter_pil3.'" class="fa fa-trash delete_row" id="delete_pilihan0" style="'.$disabled.'"> Delete</a> </span>
                                                </div>
                                            </div>
                                        </div>';
                        $counter_pil3++;
	            	}
	                $form = '<div class="portlet-body form connectedSortable reusableForms">
	                            <div class="panel panel-primary">
	                                <div class="panel-heading">
	                                    <span class="">'.\Iff::namaJenis($jenis).'</span>
	                                </div>
	                                <div class="panel-body">
	                                    <div class="row">
	                                        <input type="hidden" name="jenis" id="jenis" value="'.$jenis.'">
	                                        <div class="col-md-12">

	                                            <div class="form-group col-md-12">
	                                                <label>Pertanyaan</label>
	                                                <textarea class="form-control input-sm" maxlength="500" rows="5" name="pertanyaan" id="pertanyaan" required>'.\Iff::namaPertanyaan($value->pertanyaan_id).'</textarea>
	                                            </div>
	                                            
	                                            '.implode(" ", $pilihan3).'
	                                            <div id="pilihan"></div>

	                                            <div class="form-group col-md-12">
	                                                <button type="button" data-case="1" data-tipe="2" class="btn blue btn-tambah-pilihan btn_pilihan" id="btn_tambah_pilihan"><i class="fa fa-plus"></i> Tambah Pilihan</button>
	                                            </div>

	                                        </div>

	                                    </div>
	                                </div>
	                            </div>
	                        </div>';
	                    return response()->json(['form'=>$form, 'counter'=>$counter_pil3]);
	                break;
	            case '4':

	            	$pil4 = \DB::select(\DB::raw('select id, text from pilihan where pertanyaan_id = '.$value->pertanyaan_id.''));

	            	$counter_pil4 = 1;

	            	foreach ($pil4 as $key => $value_pil4) {
	            		if ($counter_pil4 == 1) {
	            			$lable = '<label>Scale Text</label>';
	            			$disabled = 'color:red;cursor: not-allowed;opacity: 0.5;text-decoration: none;';
	            		}else{
	            			$lable = '';
	            			$disabled = 'color:red';
	            		}
	            		$pilihan4[] = '<div class="form-group col-md-12" id="row_div'.$counter_pil4.'">
                                            '.$lable.'
                                            <div id="line-pilihan">
                                                <div class="input-group">
                                                    <input type="text" class="form-control input-sm keyup-pilihan" name="pilihan[]"  placeholder="Scale Text" maxlength="185" required value="'.$value_pil4->text.'"> 
                                                    <span class="input-group-addon" ><a href="javascript:;" data-counter="1" data-id="'.$counter_pil4.'" class="fa fa-trash delete_row" id="delete_pilihan0" style="'.$disabled.'"> Delete</a> </span>
                                                </div>
                                            </div>
                                        </div>';
                        $counter_pil4++;
	            	}

	                $form = '<div class="portlet-body form connectedSortable reusableForms">
	                            <div class="panel panel-primary">
	                                <div class="panel-heading">
	                                    <span class="">'.\Iff::namaJenis($jenis).'</span>
	                                </div>
	                                <div class="panel-body">
	                                    <div class="row">
	                                        <input type="hidden" name="jenis" id="jenis" value="'.$jenis.'">
	                                        <div class="col-md-12">

	                                            <div class="form-group col-md-12">
	                                                <label>Pertanyaan</label>
	                                                <textarea class="form-control input-sm" maxlength="500" rows="5" name="pertanyaan" id="pertanyaan" required>'.\Iff::namaPertanyaan($value->pertanyaan_id).'</textarea>
	                                            </div>
	                                            
	                                            '.implode(" ", $pilihan4).'

	                                            <div id="pilihan"></div>

	                                            <div class="form-group col-md-12">
	                                                <button type="button" data-case="1" data-tipe="2" class="btn blue btn-tambah-scale" id="btn_tambah_scale"><i class="fa fa-plus"></i> Tambah Scale</button>
	                                            </div>

	                                        </div>

	                                    </div>
	                                </div>
	                            </div>
	                        </div>';
	                    return response()->json(['form'=>$form, 'counter'=>$counter_pil4]);
	                break;
	            case '5':
	            	$pil5 = \DB::select(\DB::raw('SELECT id, text, bobot FROM pilihan WHERE pertanyaan_id = '.$value->pertanyaan_id.''));

	            	foreach ($pil5 as $key => $value_pil5) {
	            		$pilihan = explode('|||', $value_pil5->text);

	            		$formscale = '<div class="col-md-5">
                                        <label> Minimal Text </label>
                                        <input type="text" class="form-control input-sm disable_pilihan" name="pilihan[]" maxlength="75" value="'.$pilihan[0].'" required placeholder="Min. Text"> 
                                    </div>
                                    <div class="col-md-5">
                                        <label> Maximal Text </label>
                                        <input type="text" class="form-control input-sm disable_pilihan" name="pilihan[]" maxlength="75" value="'.$pilihan[1].'" required placeholder="Max. Text"> 
                                    </div>
                                    <div class="col-md-2">
                                        <label> Scale </label>
                                        <input type="text" class="form-control number input-sm disable_pilihan" id="scale" maxlength="2" value="'.$value_pil5->bobot.'" name="scale"  required placeholder="Scale"> 
                                    </div>';

	            	}

	                $form = '<div class="portlet-body form connectedSortable reusableForms">
	                            <div class="panel panel-primary">
	                                <div class="panel-heading">
	                                    <span class="">'.\Iff::namaJenis($jenis).'</span>
	                                </div>
	                                <div class="panel-body">
	                                    <div class="row">
	                                        <input type="hidden" name="jenis" id="jenis" value="'.$jenis.'">
	                                        <div class="col-md-12">

	                                            <div class="form-group col-md-12">
	                                                <label>Pertanyaan</label>
	                                                <textarea class="form-control input-sm" maxlength="500" rows="5" name="pertanyaan" id="pertanyaan" required>'.\Iff::namaPertanyaan($value->pertanyaan_id).'</textarea>
	                                            </div>
	                                            
	                                            '.( isset($formscale) ? $formscale : "").'
	                                        </div>

	                                    </div>
	                                </div>
	                            </div>
	                        </div>';
	                    return response()->json(['form'=>$form]);
	                break;
	            case '6':
	                $form = '<div class="portlet-body form connectedSortable reusableForms">
	                            <div class="panel panel-primary">
	                                <div class="panel-heading">
	                                    <span class="">'.\Iff::namaJenis($jenis).'</span>
	                                </div>
	                                <div class="panel-body">
	                                    <div class="row">
	                                        <input type="hidden" name="jenis" id="jenis" value="'.$jenis.'">
	                                        <div class="col-md-12">

	                                            <div class="form-group col-md-12">
	                                                <label>Pertanyaan</label>
	                                                <textarea class="form-control input-sm" maxlength="500" rows="5" name="pertanyaan" id="pertanyaan" required>'.\Iff::namaPertanyaan($value->pertanyaan_id).'</textarea>
	                                            </div>

	                                        </div>

	                                    </div>
	                                </div>
	                            </div>
	                        </div>';
	                    return response()->json(['form'=>$form]);
	                break;
	            case '7':
	            	$pil7 = \DB::select(\DB::raw('select id, text from pilihan where pertanyaan_id = '.$value->pertanyaan_id.''));

	            	// dd($pil7);


	            	$counter_pil7 = 1;

	            	foreach ($pil7 as $key => $value_pil7) {
	            		if ($counter_pil7 == 1) {
	            			$lable = '<label>Scale Text</label>';
	            			$disabled = 'color:red;cursor: not-allowed;opacity: 0.5;text-decoration: none;';
	            		}else{
	            			$lable = '';
	            			$disabled = 'color:red';
	            		}
	            		$pilihan7[] = '<div class="form-group col-md-12" id="row_div'.$counter_pil7.'">
                                            '.$lable.'
                                            <div id="line-pilihan">
                                                <div class="input-group">
                                                    <input type="text" class="form-control input-sm keyup-pilihan" name="pilihan[]"  placeholder="Scale Text" maxlength="185" required value="'.$value_pil7->text.'"> 
                                                    <span class="input-group-addon" ><a href="javascript:;" data-id="'.$counter_pil7.'" class="fa fa-trash delete_row" id="delete_pilihan0" style="'.$disabled.'"> Delete</a> </span>
                                                </div>
                                            </div>
                                        </div>';
                        $counter_pil7++;
	            	}

					$per7 = \DB::select(\DB::raw('select id, survey_id, pertanyaan, sequence from view_pertanyaan_jenis where survey_id = '.$id.' '));

					// dd($per7);

					$counter_pertanyaan = $counter_pil7;

					foreach ($per7 as $key => $value_per7) {
	            		if ($counter_pertanyaan == $counter_pil7) {
	            			$lable = '<label>Pertanyaan</label>';
	            			$disabled = 'color:red;cursor: not-allowed;opacity: 0.5;text-decoration: none;';
	            		}else{
	            			$lable = '';
	            			$disabled = 'color:red';
	            		}
                    	
    					$pertanyaan7[] = '<div class="form-group col-md-12" id="row_div'.$counter_pertanyaan.'">
                                            '.$lable.'
                                            <div id="line-pilihan">
                                                <div class="input-group">
                                                    <input type="text" class="form-control input-sm keyup-pilihan" name="pertanyaan[]"  placeholder="Pertanyaan" maxlength="185" required value="'.$value_per7->pertanyaan.'"> 
                                                    <span class="input-group-addon" ><a href="javascript:;" data-id="'.$counter_pertanyaan.'" class="fa fa-trash delete_row" id="delete_pilihan0" style="'.$disabled.'"> Delete</a> </span>
                                                </div>
                                            </div>
                                        </div>';
                        $counter_pertanyaan++;
					}

	                $form = '<div class="portlet-body form connectedSortable reusableForms">
	                            <div class="panel panel-primary">
	                                <div class="panel-heading">
	                                    <span class="">'.\Iff::namaJenis($jenis).'</span>
	                                </div>
	                                <div class="panel-body">
	                                    <div class="row">
	                                        <input type="hidden" name="jenis" id="jenis" value="'.$jenis.'">
	                                        <div class="col-md-12">

	                                            <div class="form-group col-md-12">
	                                                <label>Instruksi</label>
	                                                <textarea class="form-control input-sm" name="instruksi" maxlength="500" rows="5" required>'.\Iff::namaPertanyaan($value->pertanyaan_id).'</textarea> 
	                                            </div>
	                                            
	                                            '.(isset($pilihan7) ? implode(" ", $pilihan7) : '').'

	                                            <div id="pilihan"></div>

	                                            <div class="form-group col-md-12">
	                                                <button type="button" class="btn blue btn-sm" id="btn_tambah_scale"><i class="fa fa-plus"></i> Tambah Scale</button>
	                                            </div>


	                                            '.(isset($pertanyaan7) ? implode(" ", $pertanyaan7) : '').'

	                                            <div id="pertanyaan_mt"></div>

	                                            <div class="form-group col-md-12">
	                                                <button type="button" data-case="7" class="btn-sm btn blue" id="btn_tambah_pertanyaan"><i class="fa fa-plus"></i> Tambah Pertanyaan</button>
	                                            </div>
	                                        </div>
	                                    </div>
	                                </div>
	                            </div>
	                        </div>';
	                    return response()->json(['form'=>$form, 'counter'=>$counter_pertanyaan]);
	                break;
	            case '8':

	            	$pertanyaan8 = \DB::select(\DB::raw('select id, pertanyaan_id, pertanyaan, nomor, is_multiple from view_pertanyaan_jenis where survey_id = '.$value->survey_id.' order by id ASC'));

	            	$counter_prt = 1;
	            	foreach ($pertanyaan8 as $key_p8 => $value_p8) {
	            		$tb_pertanyaan = \DB::table('pilihan_multiple')->where('pertanyaan_id',$value_p8->pertanyaan_id)->first();
	            		
	            		if ($tb_pertanyaan->is_multiple == 1) {
                    		$select = 'selected';
                    	}else{
                    		$select = '';
                    	}

                    	if ($counter_prt == 1) {
            				$disabled_prt = "color:red;cursor: not-allowed;opacity: 0.5;text-decoration: none;";
            			}else{
            				$disabled_prt = "";
            			}

	            		$form_p[] = '<div id="row_div'.$counter_prt.'">
                                        <div class="form-group col-md-12" >
                                            <label>Instruksi / Pertanyaan</label>
                                            <div class="row">
                                                <div class="col-md-11">
                                                    <textarea type="text" class="form-control input-sm" rows="5" name="pertanyaan[]" maxlength="500" placeholder="Tulis Instruksi" required>'.$value_p8->pertanyaan.'</textarea>
                                                </div>
                                                <div class="col-md-1">
                                                    <a href="javascript:;" class="btn btn-danger btn-circle delete_row" data-id="'.$counter_prt.'" style="'.$disabled_prt.'"><i class="fa fa-close"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group col-md-11">
                                            <label>Type</label>
                                            <select class="form-control input-sm" name="tipe_pilihan[]" required>
                                                <option value="0">Single Choice</option>
                                                <option value="1" '.$select.'>Multiple Choice</option>
                                            </select>
                                        </div>
                                    </div>';

                        $counter_prt++;
	            	}

	            	$pil8 = \DB::select(\DB::raw('select id, text from pilihan where pertanyaan_id = '.$value->pertanyaan_id.''));

	            	// dd($counter_prt);
	            	$counter_pil = $counter_prt;

	            	foreach ($pil8 as $key_pil => $value_pil) {
            			if ($counter_pil == $counter_prt) {
            				$disabled = "color:red;cursor: not-allowed;opacity: 0.5;text-decoration: none;";
            				$lable = '<label>Pilihan</label>';
            			}else{
            				$lable = '';
            				$disabled = "color:red";
            			}

    					$pilihan8[] = '<div class="form-group col-md-12" id="row_div'.$counter_pil.'">
                                                '.$lable.'
                                                <div class="line-pilihan">
                                                    <div class="input-group">
                                                        <input type="text" class="form-control input-sm keyup-pilihan" name="pilihan[]"  placeholder="Pilihan" maxlength="185" required value="'.$value_pil->text.'""> 
                                                        <span class="input-group-addon" ><a href="javascript:;" data-id="'.$counter_pil.'" class="fa fa-trash delete_row" style="'.$disabled.'"> Delete</a> </span>
                                                    </div>
                                                </div>
                                            </div>';
                        $counter_pil++;
					}

	                $form[] = '<div class="portlet-body form connectedSortable reusableForms">
	                            <div class="panel panel-primary">
	                                <div class="panel-heading">
	                                    <span class="">'.\Iff::namaJenis($jenis).'</span>
	                                </div>
	                                <div class="panel-body">
	                                    <div class="row">
	                                        <input type="hidden" name="jenis" id="jenis" value="'.$jenis.'">
	                                        <div class="col-md-12">
	                                        	'.implode(" ", $form_p).'
	                                            <div id="instruksi">
	                                            </div>
	                                            
	                                            <div class="form-group col-md-12">
	                                                <button type="button" class="btn blue" id="btn_tambah_instruksi"><i class="fa fa-plus"></i> Tambah Instruksi</button>
	                                            </div>

	                                            '.implode(" ", $pilihan8).'
	                                            <div id="pilihan"></div>

	                                            <div class="form-group col-md-12">
	                                                <button type="button" data-case="1" data-tipe="2" class="btn blue btn-tambah-pilihan btn_pilihan" id="btn_tambah_pilihan"><i class="fa fa-plus"></i> Tambah Pilihan</button>
	                                            </div>

	                                        </div>
	                                    </div>
	                                </div>
	                            </div>
	                        </div>';

	                    return response()->json(['form'=>$form, 'counter'=>$counter_pil]);
	                break;
	            default:
	                # code...
	                break;
	        }
        }
    }
}
