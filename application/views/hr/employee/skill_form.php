<form autocomplete="off" id="saveBulkAdvance">
	<div class="col-md-12">
        <div class="error general"></div>
    </div>
    <div class="col-md-12 mt-3">
        <div class="row form-group">
            <div class="table-responsive">
				<input type="hidden" name="emp_id" value="<?= !empty($dataRow->id) ? $dataRow->id : 0;?>">
				<input type="hidden" name="type" value="<?= $type;?>">
                <table id="finaltbl" class="table table-bordered generalTable">
					<?php
						if(!empty($skillList)):$i=0;
							foreach($skillList as $row):
								$hasSkills = false; $skillRows = [];
								
								if(!empty($row['skill'])):
									foreach($row['skill'] as $skill):
										
										$trans_id = ''; $act_per = '';
										if(in_array($skill->id,array_column($staffSkill, 'set_id'))):
											$batch_key = array_search($skill->id,array_column($staffSkill, 'set_id'));
											$trans_id = $staffSkill[$batch_key]->id;
											$act_per = $staffSkill[$batch_key]->act_per;
										endif;
										
										$hasSkills = true; 
										$skillRows[] = [
											'id' => $skill->id,
											'trans_id' => $trans_id,
											'name' => $skill->skill_name,
											'req_per' => $skill->skill_per,
											'act_per' => $act_per,
										];
									endforeach;
									
									if ($hasSkills):
										echo '<thead class="thead-info">
												<tr>
													<th width="60%" class="font-weight-bold">' . $row['set_name'] . '</th>
													<th width="15%">Req. Skill(%)</th>
													<th width="25%">Actual Skill(%)</th>
												</tr>
											</thead>';
										foreach ($skillRows as $rowData):
											echo '<tbody>
													<tr>
														<td>'.$rowData['name'].'</td>
														<td>'.$rowData['req_per'].'</td>
														<td>
															<input type="hidden" name="id[]" value="'.$rowData['trans_id'].'">
															<input type="hidden" name="set_id[]" value="'.$rowData['id'].'">
															<input type="text" name="act_per[]" class="form-control floatOnly" value="'.$rowData['act_per'].'"/>
															<div class="error act_per'.$i.'"></div>
														</td>
													</tr>
												</tbody>'; $i++;
										endforeach;
									endif;
								endif;
							endforeach;
						endif;
					?>
                </table>
            </div>
        </div>
    </div>
</form>