<?php
	$leadDetail ='';
	if(!empty($leadData))
	{
		foreach($leadData as $row)
		{
			$lostBtn='';$editButton='';$deleteButton ='';$reOpenBtn="";$inActiveBtn='';$stageBtn='';
			$userImg = base_url('assets/images/users/user_default.png');
			$detailUrl ="";
			if($row->party_type != 1){
				if($row->party_type != 3){
					$lostParam = "{'postData':{'id':".$row->id.",'executive_id':".$row->executive_id.",'party_type':3,'log_type':7},'message':'Are you sure want to Change Status to Lost?','fnsave':'saveLeadStatus','modal_id':'modalCenter','formId':'leadLost','title':'Lead Lost'}";
					$lostBtn = '<a href="javascript:void(0)" class="dropdown-item btn1 btn-danger permission-modify" style="justify-content: flex-start;" onclick="changeLeadStatus('.$lostParam.');" data-msg="Lost Status" flow="down"><i class="mdi mdi-close-circle"></i> Lost Approach</a>';

					$editParam = "{'postData':{'id' : ".$row->id."},'modal_id' : 'right_modal_lg', 'form_id' : 'editApproaches', 'title' : 'Update Approaches','js_store_fn':'saveLead'}";
					$editButton = '<a class="dropdown-item btn1 btn-success btn-edit permission-modify" href="'.base_url("app/lead/edit/".$row->id).'" style="justify-content: flex-start;" flow="down"><i class="mdi mdi-square-edit-outline"></i> Edit</a>';
					
					$deleteParam = "{'postData':{'id' : ".$row->id."},'message' : 'Approaches'}";
					$deleteButton = '<a class="dropdown-item btn1 btn-danger btn-delete permission-remove" href="javascript:void(0)" style="justify-content: flex-start;" onclick="trash('.$deleteParam.');" flow="down"><i class="mdi mdi-trash-can-outline"></i> Remove</a>';
				}elseif($row->party_type == 3){
					$reOpenParam = "{'postData':{'id':".$row->id.",'executive_id':".$row->executive_id.",'party_type':2,'log_type':11},'message':'Are you sure want to Reopen Lead?','fnsave':'saveLeadStatus','modal_id':'modalCenter','formId':'reopenLead','title':'Reopen Lead'}";
					$reOpenBtn = '<a href="javascript:void(0)" class="dropdown-item btn1 btn-danger permission-modify" style="justify-content: flex-start;" onclick="changeLeadStatus('.$reOpenParam.');" data-msg="Reopen" flow="down"><i class="mdi mdi-close-circle"></i> Reopen</a>';

				}elseif($row->party_type == 1){
					$inActiveParam = "{'postData':{'id':".$row->id.",'executive_id':".$row->executive_id.",'party_type':1,'log_type':12,'is_active':2},'message':'Are you sure want to Inactive Party?','fnsave':'saveLeadStatus','modal_id':'modalCenter','formId':'Inactive','title':'Inactive Party'}";
					$inActiveBtn = '<a href="javascript:void(0)" class="dropdown-item btn1 btn-danger permission-modify" style="justify-content: flex-start;" onclick="changeLeadStatus('.$inActiveParam.');" data-msg="Inactive" flow="down"><i class="mdi mdi-close-circle"></i> Inactive</a>';

				}
				foreach($leadStages as $stg){
					if($stg->id != $row->party_type){
						$stageParam = "{'postData':{'id':".$row->id.",'executive_id':".$row->executive_id.",'party_type':'".$stg->id."','log_type':".$stg->log_type.",'is_active' : '".$row->is_active."','notes':'".$stg->stage_type."'},'message':'Are you sure want to Change Status to ".$stg->stage_type."?','fnsave':'saveLeadStatus','modal_id':'modalCenter','formId':'leadLost','title':'".$stg->stage_type."','confirm' :'1'}";
						$stageBtn .= '<a href="javascript:void(0)" class="dropdown-item btn-edit btn-danger permission-modify" style="justify-content: flex-start;" onclick="changeLeadStatus('.$stageParam.');" data-msg="Lost Status" flow="down">'.$stg->stage_type.'</a>';
					}
				}
				$detailUrl =  base_url("app/lead/getLeadDetails/".$row->id."/".$row->party_id);
			}else{
			    $detailUrl =  base_url("app/lead/getLeadDetails/0/".$row->id);
			}
            $contactParam = "{'postData':{'id':".$row->id.",'party_type':".$row->party_type."},'fnedit':'partyContactList','modal_id':'modalCenter','formId':'partyContactList','title':'Contact Detail','button' :'no'}";
			$no ="'tel: ".$row->contact_phone."'";
			$filterCls = $row->party_type.'_lead';
			$cls = "";$wa_text = urlencode('Hello');
			if(empty($row->executive_id)){ $cls = "text-danger"; }
			$wa_number = (!empty($row->whatsapp_no) ? str_replace('-','',str_replace('+','',str_replace(' ','',$row->whatsapp_no))) : '');
			
			$leadDetail .= '<li class=" grid_item listItem item transition position-static '.$filterCls.'" data-category="transition">
								
									<div class="btn btn-icon btn-primary">
										<a href="'.$detailUrl.'" class="swipe-container"><i class="fa fa-user text-white"></i></a>
									</div>
									<div class="media-content">
										
											<div>
												<h6 class="name '.$cls.'">'.$row->party_name.'</h6>
												<p class="mb-0">'.$row->contact_person.'</p>
												<p class="mb-0">'.$row->executive.'</p>
												<p class="time mb-0">'.$row->source.'</p>
											</div>
										
									</div>
									<div class="left-content w-auto">
										'.(($row->party_type != 1)?'	
											<a class="dropdown-toggle lead-action text-right float-end" data-bs-toggle="dropdown" href="#" role="button"><div class="dz-icon" style="margin:0px;">
													<svg xmlns="http://www.w3.org/2000/svg" enable-background="new 0 0 24 24" height="24px" viewBox="0 0 24 24" width="24px" fill="#000000"><g><rect fill="none" height="24" width="24"></rect></g><g><path d="M10,18h10c0.55,0,1-0.45,1-1v0c0-0.55-0.45-1-1-1H10c-0.55,0-1,0.45-1,1v0C9,17.55,9.45,18,10,18z M3,7L3,7 c0,0.55,0.45,1,1,1h16c0.55,0,1-0.45,1-1v0c0-0.55-0.45-1-1-1H4C3.45,6,3,6.45,3,7z M10,13h10c0.55,0,1-0.45,1-1v0 c0-0.55-0.45-1-1-1H10c-0.55,0-1,0.45-1,1v0C9,12.55,9.45,13,10,13z"></path></g></svg>
												</div>
											</a>
											<div class="dropdown-menu dropdown-menu-end text-left mb-3">
												'.$stageBtn.$inActiveBtn.$reOpenBtn.$lostBtn.$editButton.$deleteButton.'
											</div><br>':'').'

											<div class="d-flex mt-2">
												<a role="button" href="javascript:void(0)" class=" text-left p-1" onclick="leadAction('.$contactParam.')"><i class="fas fa-phone text-primary font-20"  ></i></a>
												<!--'.(!empty($wa_number)?'<a role="button" href="https://wa.me/'.$wa_number.'/?text='.$wa_text.'" target="_blank" class="text-left p-1" ><i class="fab fa-whatsapp text-success font-20" ></i></a>':'').'
												
												'.(!empty($row->contact_phone)?'<a role="button" href="javascript:void(0)" class=" text-left p-1" onclick="document.location.href = '.$no.'"><i class="fas fa-phone text-primary font-20"  ></i></a>':'').'-->
											</div>
									</div>
								
							</li>';
		}
	}
	echo $leadDetail;
?>