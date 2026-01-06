<div class="page-content-tab">
	<div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
				<div class="activity">
					<?php
						if(!empty($getEmpLog)):
							foreach($getEmpLog as $row):
					?>
								<div class="activity-info">
									<div class="icon-info-activity">
										<i class="las la-check-circle bg-soft-primary"></i>
									</div>
									<div class="activity-info-text">
										<div class="d-flex justify-content-between align-items-center">
											<h6 class="m-0 w-75"><?= $row->notes;?></h6>
										</div>
										<p class="text-muted mt-3"><?= formatDate($row->ref_date);?></p>
										<p class="text-muted mt-1 mb-1"><b><?= ($row->log_type == 2) ? "Created By" : "Approved By";?>: </b> <?= ($row->log_type != 7) ? $row->employee_name. ' || <b>Reason: </b>'.$row->reason : "-";?></p>
										<p class="text-muted mt-1 mb-1"><b>Rejected By: </b><?= ($row->log_type == 7) ? $row->employee_name. ' || <b>Reason: </b>'.$row->reason : "-";?></p>
										<?php
											if($row->from_stage == 3){
												foreach($empDocuments as $doc){
										?>
													<p class="text-muted mt-1 mb-1">
														<b>Document Name:</b> <?= $doc->doc_name;?>, 
														<b>Document No.</b> <?= $doc->doc_no;?>&nbsp;
														<?php if (!empty($doc->doc_file)):?>
															<a style="font-size:20px;" href="<?= base_url("assets/uploads/emp_documents/".$doc->doc_file);?>" target="_blank" download><i class="fa fa-download"></i></a>&nbsp;
														<?php endif; ?>
													</p>
										<?php
												}
											}
											if($row->from_stage == 4 || $row->from_stage == 5){
										?>
												<table class="table table-bordered generalTable">
													<tbody>
														<?php
															if (!empty($skillList)):
																foreach ($skillList as $setRow):
																	if (!empty($setRow['skill'])):
																		$hasSkills = false; $skillRows = [];

																		foreach ($setRow['skill'] as $skill):
																			$act_per = '';
																			if ($row->from_stage == 4) {
																				if (in_array($skill->id, array_column($techStaffSkill, 'set_id'))) {
																					$batch_key = array_search($skill->id, array_column($techStaffSkill, 'set_id'));
																					$act_per = $techStaffSkill[$batch_key]->act_per ?? '';
																				}
																			}
																			if ($row->from_stage == 5) {
																				if (in_array($skill->id, array_column($hrStaffSkill, 'set_id'))) {
																					$batch_key = array_search($skill->id, array_column($hrStaffSkill, 'set_id'));
																					$act_per = $hrStaffSkill[$batch_key]->act_per ?? '';
																				}
																			}

																			
																			if (!empty($act_per)):
																				$hasSkills = true; 
																				$skillRows[] = [
																					'name' => $skill->skill_name,
																					'req_per' => $skill->skill_per,
																					'act_per' => $act_per,
																				];
																			endif;
																		endforeach;

																		if ($hasSkills):
																			echo '<thead class="thead-info"><tr><th width="60%" class="font-weight-bold">' . $setRow['set_name'] . '</th><th width="15%">Req. Skill(%)</th>
																			<th width="25%">Actual Skill(%)</th></tr></thead>';
																			foreach ($skillRows as $rowData):
																				echo '<tr>
																						<td>' . $rowData['name'] . '</td>
																						<td>' . $rowData['req_per'] . '</td>
																						<td>' . $rowData['act_per'] . '</td>
																					  </tr>';
																			endforeach;
																		endif;
																	endif;
																endforeach;
															endif;
														?>
													</tbody>
												</table>
										<?php } ?>
									</div>
								</div>
					<?php
							endforeach;
						else:
							echo "<h4 class='text-center'>No Records found!</h4>";
						endif; 
					?>
				</div>
            </div>
		</div>  
    </div>
</div>