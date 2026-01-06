<?php
class Lead extends MY_Controller{
    private $crm_desk = "lead/crm_desk";
    private $index = "lead/index";
    private $form = "party/form";
    private $sales_enq_form = "lead/sales_enq_form";
    private $sales_quot_form = "lead/sales_quot_form";
    private $sales_ord_form = "lead/sales_ord_form";
    private $sales_quot_index = "lead/sales_quot_index";
    private $lead_lost = "lead/lead_lost";
    private $sales_order_index = "lead/sales_order_index";
    private $reminder_response = "lead/reminder_response";
    private $sales_quotation_index = "lead/sales_quotation_index";
    private $return_order_index = "lead/return_order_index";
    private $return_order_form = "lead/return_order_form";
    private $sales_form = "lead/sales_form";
    private $bulk_executive = "lead/bulk_executive";
    private $dispatch_form = "lead/dispatch_form";
    private $excel_upload_form = "lead/excel_upload_form";
    private $asign_executive = "lead/asign_executive";
    private $view_lead_detail = "lead/view_lead_detail";
		
    public function __construct(){
        parent::__construct();
		$this->data['headData']->pageTitle = "Leads";
		$this->data['headData']->controller = "lead";    
        $this->data['headData']->pageUrl = "lead/crmDesk";    
    }

	/* Updated BY :- JP @06-04-2024 */
    public function crmDesk(){
        $this->data['rec_per_page'] = 25; // Records Per Page
        $this->data['stageList'] = $this->configuration->getLeadStagesList();
        $this->data['sourceList'] = $this->configuration->getSelectOptionList(['type'=>1]); 
        $this->load->view($this->crm_desk,$this->data);
    }
	
    /* Updated BY :- JP @11-04-2024 */
    public function getLeadData($party_type="",$fnCall = "Ajax"){
        $postData = $this->input->post();
        $leadStages = $this->configuration->getLeadStagesList(['not_in'=>[1,2,3]]);
		if(empty($postData)){$fnCall = 'Outside';$postData['party_type']=$party_type;}
        $next_page = 0;
		
		$leadData = Array();
		if(!empty($postData['party_type']))
		{
			if(isset($postData['page']) AND isset($postData['start']) AND isset($postData['length']))
			{
				$leadData = $this->party->getLeadList($postData);
				$next_page = intval($postData['page']) + 1;
				
			}
			else{ $leadData = $this->party->getLeadList($postData); }
		}
		else
		{
			$postData['crmDesk'] = 1;$postData['status'] = 1;$postData['group_by'] = 'sales_logs.lead_id';
			if(isset($postData['page']) AND isset($postData['start']) AND isset($postData['length']))
			{
				$leadData = $this->sales->getReminders($postData);
				$next_page = intval($postData['page']) + 1;
			}
			else{ $leadData = $this->sales->getReminders($postData); }
		}
		
		$leadList['allLead'] = '';$leadList['pendingLead'] = '';$leadList['wonLead'] = '';$leadList['lostLead'] = '';$leadDetail ='';
		if(!empty($leadData))
		{
			foreach($leadData as $row)
			{
				$lostBtn='';$editButton='';;$reOpenBtn="";$inActiveBtn='';
				$userImg = base_url('assets/images/users/user_default.png');
                
                if($row->party_type != 3){
                    $lostParam = "{'postData':{'id':".$row->id.",'executive_id':".$row->executive_id.",'party_type':3,'log_type':7},'message':'Are you sure want to Change Status to Lost?','fnsave':'changeLeadStatus','modal_id':'modal-md','form_id':'leadLost','fnedit':'leadLost'}";
                    $lostBtn = '<a href="javascript:void(0)" class="dropdown-item btn-edit btn-danger permission-modify" style="justify-content: flex-start;" onclick="leadEdit('.$lostParam.');" data-msg="Lost Status" flow="down"><i class="mdi mdi-close-circle"></i> Lost Approach</a>';

                    $editParam = "{'postData':{'id' : ".$row->id."},'modal_id' : 'right_modal_lg', 'form_id' : 'editApproaches', 'title' : 'Update Approaches','controller':'parties'}";
                    $editButton = '<a class="dropdown-item btn-success btn-edit permission-modify" href="javascript:void(0)" flow="down" onclick="edit('.$editParam.');"><i class="mdi mdi-square-edit-outline"></i> Edit</a>';
                }elseif($row->party_type == 3){
                    $reOpenParam = "{'postData':{'id':".$row->id.",'executive_id':".$row->executive_id.",'party_type':2,'log_type':11},'message':'Are you sure want to Reopen Lead?','fnsave':'changeLeadStatus','modal_id':'modal-md','formId':'reopenLead','title':'Reopen Lead','fnedit':'leadLost'}";
                    $reOpenBtn = '<a href="javascript:void(0)" class="dropdown-item btn1 btn-danger permission-modify" style="justify-content: flex-start;" onclick="leadEdit('.$reOpenParam.');" data-msg="Reopen" flow="down"><i class="mdi mdi-close-circle"></i> Reopen</a>';

                }elseif($row->party_type == 1 && $row->is_active == 1){
                    $inActiveParam = "{'postData':{'id':".$row->id.",'executive_id':".$row->executive_id.",'party_type':1,'log_type':12,'is_active':2},'message':'Are you sure want to Inactive Party?','fnsave':'changeLeadStatus','modal_id':'modal-md','formId':'Inactive','title':'Inactive Party','fnedit':'leadLost'}";
                    $inActiveBtn = '<a href="javascript:void(0)" class="dropdown-item btn1 btn-danger permission-modify" style="justify-content: flex-start;" onclick="leadEdit('.$inActiveParam.');" data-msg="Inactive" flow="down"><i class="mdi mdi-close-circle"></i> Inactive</a>';

                }elseif($row->party_type == 1 && $row->is_active == 2){
                    $inActiveParam = "{'postData':{'id':".$row->id.",'executive_id':".$row->executive_id.",'party_type':1,'log_type':13,'is_active':1},'message':'Are you sure want to Active Party?','fnsave':'changeLeadStatus','modal_id':'modal-md','formId':'Inactive','title':'Active Party','fnedit':'leadLost'}";
                    $inActiveBtn = '<a href="javascript:void(0)" class="dropdown-item btn1 btn-danger permission-modify" style="justify-content: flex-start;" onclick="leadEdit('.$inActiveParam.');" data-msg="Active" flow="down"><i class="mdi mdi-close-circle"></i> Active</a>';

                }
                $stageBtn='';
                foreach($leadStages as $stg){
                    if($stg->id != $row->party_type){
                        $stageParam = "{'postData':{'id':".$row->id.",'executive_id':".$row->executive_id.",'party_type':'".$stg->id."','log_type':".$stg->log_type.",'is_active':".$row->is_active.",'notes':'".$stg->stage_type."'},'message':'Are you sure want to Change Status to ".$stg->stage_type."?','fnsave':'changeLeadStatus','modal_id':'modal-md','form_id':'','fnedit':'leadLost','title':'".$stg->stage_type."' ,'confirm' :'1'}";
                        $stageBtn .= '<a href="javascript:void(0)" class="dropdown-item btn-edit btn-danger permission-modify" style="justify-content: flex-start;" onclick="leadEdit('.$stageParam.');" data-msg="Lost Status" flow="down"><i class="fas fa-dot-circle"></i> '.$stg->stage_type.'</a>';
                    }
                }

                $asignParam = "{'postData':{'id':".$row->id.",'executive_id':".$row->executive_id.",'status':10},'fnsave':'saveExecutive','modal_id':'modal-md','form_id':'asignExecutive','fnedit':'asignExecutive'}";
                $asignBtn = '<a href="javascript:void(0)" class="dropdown-item btn-edit btn-danger permission-modify" style="justify-content: flex-start;" onclick="leadEdit('.$asignParam.');" data-msg="Assign Executive" flow="down"><i class=" fas fa-user-plus"></i> Assign Executive</a>';


				$deleteParam = "{'postData':{'id' : ".$row->id."},'message' : 'Approaches','controller':'parties'}";
				$deleteButton = '<a class="dropdown-item btn-danger btn-delete permission-remove" href="javascript:void(0)" onclick="trash('.$deleteParam.');" flow="down"><i class="mdi mdi-trash-can-outline"></i> Remove</a>';
				$filterCls = $row->party_type.'_lead';$cls="";
                $rmdClass=!empty($row->reminder_date)?'pending_response':'';
                if(empty($row->executive_id)){$cls = "text-danger";}
				$maxChars = 30;
                $wa_text = urlencode('Hello');
				$wa_number = (!empty($row->whatsapp_no) ? str_replace('-','',str_replace('+','',$row->whatsapp_no)) : '');
				$partyName = ((strlen($row->party_name) > $maxChars) ? substr($row->party_name, 0, $maxChars).'...' : $row->party_name);
				$contactPerson = "";
				if(!empty($row->contact_person))
				{
				    $contactPerson = ((strlen($row->contact_person) > $maxChars) ? substr($row->contact_person, 0, $maxChars).'...' : $row->contact_person);
				}
				
				$viewLeadParam = "{'postData':{'id':".$row->id."},'modal_id':'modal-md','form_id':'viewLead','fnedit':'viewLeadDetails','button':'close','title':'Lead Details'}";
                $viewLeadBtn = '<a href="javascript:void(0)" class="stage-btn view-btn m-0" onclick="leadEdit('.$viewLeadParam.');" data-msg="View Lead Details" flow="down"><i class="fas fa-eye fs-13"></i> <span class="lable">View</span></a>';

                // 15-05-2024
				$leadDetail .= '<div class="grid_item '.$filterCls.' '.$rmdClass.'" style="width:24%;">
									<div class="card stage-item transition" data-category="transition">
										<div class="stage-title">
											<span>'.$row->source.'</span>
											<div class="dropdown d-inline-block float-end">
												<div class="time float-start">'.formatDate($row->created_at,"d M Y H:i:s").'</div>
												<a class="dropdown-toggle item-stage-icon" data-bs-toggle="dropdown" href="#" role="button" aria-expanded="false">
													<i class="las la-ellipsis-v"></i>
												</a>
												<div class="dropdown-menu dropdown-menu-end" aria-labelledby="drop2" style="">
													'.$inActiveBtn.$reOpenBtn.$lostBtn.$stageBtn.$asignBtn.$editButton.$deleteButton.'
												</div>
											</div>
										</div>
										<div class="stage-body">
											<a href="javascript:void(0)" class="mt-0 font-13 partyData fw-bold '.$cls.'" data-party_id="'.$row->party_id.'" data-lead_id="'.$row->id.'">'.$partyName.'</a>
											<p class="mb-0 font-13">'.$contactPerson.'</p>
											<p class="text-muted mb-0 font-13"><i class="fas fa-user font-12"></i> '.$row->executive.'</p>
										</div>
										<div class="stage-footer">
												'.(!empty($wa_number)?'<a role="button" href="https://wa.me/'.$wa_number.'/?text='.$wa_text.'" target="_blank" class="stage-btn wp-btn m-0" ><i class="fab fa-whatsapp fs-13"></i> <span class="lable">Share</span></a>':'').' '.$viewLeadBtn.'
											</div>
									</div>
								</div>';
			}
		}
        //print_r($fnCall);exit;
		if($fnCall == 'Ajax'){$this->printJson(['leadDetail'=>$leadDetail,'next_page'=>$next_page]);}
		else{return $leadDetail;}
    }
	
	// Created By JP@08.03.2024
	public function getSalesLog($param = [],$fnCall = "Ajax"){
        $postData = $this->input->post();
		if(!empty($param)){$fnCall = 'Outside';$postData = $param;} 
        $slData = $this->sales->getSalesLog($postData);
       
		$salesLog = '';
		if(!empty($slData))
		{
			foreach($slData as $row)
			{
				$msgSide = ($row->created_by == $this->loginId) ? 'reverse' : '';
				$userImg = base_url('assets/images/users/user_default.png');
                $link = '';
                if($row->log_type == 3){
                    $link = '<p class="text-muted fs-11"><strong>'.$row->mode.' : </strong> '.date("d M Y H:i A",strtotime($row->ref_date." ".$row->reminder_time)).'</p>';
                }
                elseif($row->log_type == 4){
                    $link = '<a href="'.base_url('lead/printEnquiry/'.$row->ref_id).'" class="fw-bold text-primary" target="_blank">'.$row->ref_no.'</a>';
                }
                elseif($row->log_type == 5){
                    $link = '<a href="'.base_url('lead/printQuotation/'.$row->ref_id).'" class="fw-bold text-primary" target="_blank">'.$row->ref_no.'</a>';
                }
                elseif($row->log_type == 6){
                    $link = '<a href="'.base_url('lead/printOrder/'.$row->ref_id).'" class="fw-bold text-primary" target="_blank">'.$row->ref_no.'</a>';
                }
                elseif($row->log_type == 7){
                    $link = "Lost Lead";
                }
                elseif($row->log_type == 9){
                    $link = '<a href="'.base_url('lead/printEnquiry/'.$row->ref_id).'" class="fw-bold text-primary" target="_blank">'.$row->ref_no.'</a>';
                }
                if($row->log_type == 26){
                    $link = $row->remark;
                }

				$orderBtn='';$quoteBtn="";$editEnq="";$editQuot="";$editOrd="";$deleteOrd="";$responseBtn="";
				$btn="";
				if(in_array($row->log_type,[4,9])){
				    $btn .= '<a href="javascript:void(0)" class="dropdown-item btn btn-danger permission-modify addCrmForm"  data-button="both" data-modal_id="right_modal_lg" data-function="addSalesData" data-fnsave="saveSalesData" data-form_title="Add Quotation [ '.$row->party_name.' ]" datatip="Add Quotation" data-module_type="2" data-entry_type="'.$row->log_type.'" data-ref_id="'.$row->ref_id.'" data-party_id="'.$row->party_id.'" data-lead_id="'.$row->lead_id.'" flow="down"><i class="mdi mdi-close-circle"></i> Create Quotation</a>';
				}
				
				if(in_array($row->log_type,[4,5])){
    				$btn .= '<a href="javascript:void(0)" data-module_type="3" class="dropdown-item btn btn-danger permission-modify addCrmForm"  data-button="both" data-modal_id="right_modal_lg" data-function="addSalesData" data-fnsave="saveSalesData" data-form_title="Add Order" datatip="Add Order" data-ref_id="'.$row->ref_id.'" data-entry_type="'.$row->log_type.'" data-party_id="'.$row->party_id.'" data-lead_id="'.$row->lead_id.'" flow="down"><i class="mdi mdi-close-circle"></i> Create Order</a>';
				}

                if($row->log_type == 4){
                    $editEnqParam = "{'postData':{'id' : ".$row->ref_id."},'modal_id' : 'right_modal_lg', 'form_id' : 'editEnquiry', 'title' : 'Update Sales Enquiry', 'fnedit' : 'editSalesEnquiry', 'fnsave' : 'saveSalesData'}";
                    $btn .= '<a class="dropdown-item btn btn-success btn-edit permission-modify" href="javascript:void(0)" datatip="Edit" flow="down" onclick="edit('.$editEnqParam.');"><i class="mdi mdi-square-edit-outline"></i> Edit</a>';
                }

                if($row->log_type == 5){
                    $editQuotParam = "{'postData':{'id' : ".$row->ref_id."},'modal_id' : 'right_modal_lg', 'form_id' : 'editQuotation', 'title' : 'Update Sales Quotation [ ".$row->party_name." ]', 'fnedit' : 'editSalesQuotation', 'fnsave' : 'saveSalesData'}";
                    $btn .= '<a class="dropdown-item btn btn-success btn-edit permission-modify" href="javascript:void(0)" datatip="Edit" flow="down" onclick="edit('.$editQuotParam.');"><i class="mdi mdi-square-edit-outline"></i> Edit</a>';
                }

                if($row->log_type == 6){
                    $editOrdParam = "{'postData':{'id' : ".$row->ref_id."},'modal_id' : 'right_modal_lg', 'form_id' : 'editOrder', 'title' : 'Update Sales Order [ ".$row->party_name." ]', 'fnedit' : 'editSalesOrder', 'fnsave' : 'saveSalesData'}";
                    $btn .= '<a class="dropdown-item btn btn-success btn-edit permission-modify" href="javascript:void(0)" datatip="Edit" flow="down" onclick="edit('.$editOrdParam.');"><i class="mdi mdi-square-edit-outline"></i> Edit</a>';

                    $deleteOrdParam = "{'postData':{'id' : ".$row->ref_id."},'message' : 'Sales Order','fndelete' : 'deleteSalesOrder'}";
                    $btn .= '<a class="dropdown-item btn-danger btn-delete permission-remove" href="javascript:void(0)" onclick="trash('.$deleteOrdParam.');" flow="down"><i class="mdi mdi-trash-can-outline"></i> Remove</a>';
                }

                if($row->log_type == 3 && ($row->remark == null)){
                    $responseParam = "{'postData':{'id' : ".$row->id.",'lead_id' : ".$row->lead_id."},'modal_id' : 'right_modal', 'form_id' : 'response', 'title' : 'Reminder Response', 'fnedit' : 'reminderResponse', 'fnsave' : 'saveSalesLog'}";
                    $btn .= '<a class="dropdown-item btn btn-success btn-edit permission-modify" href="javascript:void(0)" datatip="Edit" flow="down" onclick="edit('.$responseParam.');"><i class="mdi mdi-square-edit-outline"></i> Response</a>';
                }
				$reminderRes="";
                if($row->log_type == 3 && !empty($row->remark)){
                    $reminderRes = '<p class="text-muted font-11">Res : '.$row->remark.'</p>';
                }
				$dropDown = "";
				if(!in_array($row->log_type,[1,2,7,8,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26]) && !empty($btn)){
				    $dropDown='<a class="dropdown-toggle lead-action" data-bs-toggle="dropdown" href="#" role="button"><i class="fas fa-ellipsis-v"></i></a>
								<div class="dropdown-menu">'.$btn.'</div>';
				}
				$salesLog.= '<div class="activity-info">
								<div class="icon-info-activity"><i class="'.$this->iconClass[$row->log_type].'"></i></div>
								<div class="activity-info-text">
									<div class="d-flex justify-content-between align-items-center">
										<h6 class="m-0 fs-13">'.$this->logTitle[$row->log_type].'</h6>
                                       
										<span class="text-muted w-30 d-block font-12">
										'.date("d F",strtotime($row->created_at)).$dropDown.'</span>
									</div>
									<p class=" m-1 font-12"><i class="fa fa-user"></i> '.$row->creator.'</p>
									<p class="text-muted m-1 font-13">'.$row->notes.$link.'</p>
                                    '.$reminderRes.'
								</div>
							</div>';
			}
		}
		if($fnCall == 'Ajax'){$this->printJson(['salesLog'=>$salesLog]);}
		else{return $salesLog;}
    }
	
	// Created By JP@08.03.2024
    /* Updated BY :- Sweta @28-03-2024 */
    public function getLeadDetails(){
        $data = $this->input->post();     
        $partyData = $this->party->getLead(['id'=>$data['lead_id']]);   //09-04-2024
        $salesLog = $this->getSalesLog($data);
		
        $this->printJson(['partyData'=>$partyData, 'salesLog'=>$salesLog]);
    }

    /*** Lead Lost */
    public function leadLost(){
        $data = $this->input->post();
        $this->data['data'] = $data;
        if($data['party_type'] == 3){
            $this->data['reasonList'] = $this->configuration->getSelectOptionList(['type'=>2]);
        }
        $this->load->view($this->lead_lost,$this->data);
    }
  
    public function changeLeadStatus(){
        $data = $this->input->post(); //print_r($data);exit;
        if (empty($data['id'])){ $errorMessage['id'] = "Lead is required.";}
        if ($data['party_type'] == 2 && empty($data['notes'])){ $errorMessage['notes'] = "Reason is required.";}
        if ($data['party_type'] == 2 && $data['notes'] == 'OTHER' && empty($data['remark']) ){ $errorMessage['remark'] = "Remark is required.";}

        if (!empty($errorMessage)) :
            $this->printJson(['status' => 0, 'message' => $errorMessage]);
        else :
        $leadData=[
            'id'=>$data['id'], 'party_type'=>$data['party_type'] ];

            $result = $this->party->changeLeadStatus($data);
            $result['leadList'] = $this->getLeadData($data,'Return');

            $this->printJson($result);
        endif;
    }

    /*** Assign Executive */
    public function asignExecutive(){
        $data = $this->input->post();
        $this->data['id'] = $data['id'];
        $this->data['executive_id'] = $data['executive_id'];
        $this->data['salesExecutives'] = $this->usersModel->getEmployeeList();
        $this->data['salesZoneList'] = $this->configuration->getSalesZoneList();
        $this->load->view($this->asign_executive,$this->data);
    }

    /* Save Executive */
    public function saveExecutive(){
        $data = $this->input->post();
        if(empty($data['id'])){ $errorMessage['id'] = "Lead is required.";}
        if(empty($data['executive_id'])){ $errorMessage['executive_id'] = "Executive is required.";}
        if(empty($data['sales_zone_id'])){ $errorMessage['sales_zone_id'] = "Sales Zone is required.";}

        if (!empty($errorMessage)) :
            $this->printJson(['status' => 0, 'message' => $errorMessage]);
        else :
            $result = $this->party->saveExecutive($data);
            $result['leadList'] = $this->getLeadData($data,'Return');

            $this->printJson($result);
        endif;
    }

    /* Bulk Executive */
    /* Created By :- Sweta @03-04-2024 09:55 AM */
    public function addBulkExecutive(){
        $this->data['leadData'] = $this->party->getLeadList(['bulk_executive'=>1]);//09-04-2024
        $this->data['empData'] = $this->usersModel->getEmployeeList();
        $this->load->view($this->bulk_executive,$this->data);        
    }

    /* Created By :- Sweta @03-04-2024 10:43 AM */
    public function saveBulkExecutive(){
        $data = $this->input->post();
        $errorMessage = array();

        if(empty($data['executive_id'])){
            $errorMessage['executive_id'] = "Executive is required.";
        }
        if(empty($data['sales_zone_id'])){
            $errorMessage['sales_zone_id'] = "Sales Zone is required.";
        }
        if(empty($data['ref_id'][0])){
            $errorMessage['general_error'] = "Please select at least one lead.";
        }

        if(!empty($errorMessage)):
            $this->printJson(['status'=>0,'message'=>$errorMessage]);
        else:
            foreach($data['ref_id'] as $key=>$value){
                $this->party->saveExecutive(['id'=>$value, 'executive_id'=>$data['executive_id'], 'sales_zone_id'=>$data['sales_zone_id']]);
            }
			$this->printJson(['status'=>1,'message'=>'Assigned successfuly']);
        endif;
    }

    /* Lead Excel Upload */ 
    /* Created By :- Sweta @05-04-2024 */
    public function addApproachExcel(){
        $this->load->view($this->excel_upload_form,$this->data);
    }

    /* Created By :- Sweta @05-04-2024 */
    public function saveApproachExcel(){
        $data = $this->input->post();
        $errorMessage = array();

        if(empty($data['itemData']))
            $errorMessage['itemData'] = "Enter party detail";

        if(!empty($errorMessage)):
            $this->printJson(['status'=>0,'message'=>$errorMessage]);
        else:
            foreach($data['itemData'] as $row):
                $statutoryData = $this->configuration->getStatutoryDetail(['state'=>$row['state'], 'district'=>$row['district'], 'taluka'=>$row['taluka'], 'group_by'=>'state,district,taluka', 'single_row'=>1]);
                $party_code = $this->party->getLeadCode();
                $row['party_type'] = 2;
                $row['party_code'] = 'L'.sprintf("%03d",$party_code); 
                $row['statutory_id'] = !empty($statutoryData->id)?$statutoryData->id:0;
                $row['party_address'] = $row['address'];
                unset($row['state'],$row['district'],$row['taluka'],$row['address']);
                $result = $this->party->saveLead($row);
            endforeach;
            $this->printJson(['status'=>1,'message'=>'Lead saved successfully.']); //09-04-2024
        endif;
    }

    /* Save Remider Form */
    public function saveSalesLog(){
        $postData = $this->input->post();
		$errorMessage = array();

        if(!empty($postData['log_type']) && $postData['log_type'] == 3){
            if(empty($postData['ref_date']))
                $errorMessage['ref_date'] = "Date is required.";
            if(empty($postData['reminder_time']))
                $errorMessage['reminder_time'] = "Time is required.";
            if(empty($postData['notes']))
                $errorMessage['notes'] = "Notes is required.";
        }
        
        if(!empty($errorMessage)):
            $this->printJson(['status'=>0,'message'=>$errorMessage]);
        else:
			$result = $this->sales->saveSalesLogs($postData);
			$result['salesLog'] = $this->getSalesLog(['lead_id'=>$postData['lead_id']]);
			$this->printJson($result);
		endif;
    }

    public function reminderResponse(){
        $id = $this->input->post('id');
        $this->data['lead_id'] = $this->input->post('lead_id');
        $this->data['id'] = $id;
        $this->load->view($this->reminder_response,$this->data);
    }

    /*** Sales Enquiry Functions ***/
    public function addSalesEnquiry(){
        $data = $this->input->post();
        $this->data['party_id'] = (!empty($data['party_id']) ? $data['party_id'] : 0);
        $p_executive_id = (!empty($this->data['party_id']) ? $this->party->getParty(['id'=>$this->data['party_id']])->executive_id : 0);
        $this->data['lead_id'] = (!empty($data['lead_id']) ? $data['lead_id'] : 0);
        $l_executive_id = (!empty($this->data['lead_id']) ? $this->party->getLead(['id'=>$this->data['lead_id']])->executive_id : 0);//09-04-2024
        $this->data['executive_id'] = (!empty($l_executive_id) ? $l_executive_id : $p_executive_id);
        $this->data['trans_prefix'] = 'SE/'.$this->shortYear.'/';
        $this->data['trans_no'] = $this->sales->getNextTransNumber(['tableName'=>'se_master']);
        $this->data['trans_number'] = $this->data['trans_prefix'].$this->data['trans_no'];
        $this->data['partyList'] = $this->party->getPartyList(['party_type'=>1]);
        $this->data['itemList'] = $this->item->getItemList(['item_type'=>1]);
        $this->load->view($this->sales_enq_form,$this->data);
    }

    public function editSalesEnquiry(){
        $id = $this->input->post('id');
        $this->data['dataRow'] = $dataRow = $this->sales->getSalesEnquiry(['id'=>$id,'itemList'=>1]);
        $this->data['partyList'] = $this->party->getPartyList(['party_type' => 1]);
        $this->data['itemList'] = $this->item->getItemList(['item_type'=>1]);
        $this->data['module_type'] = 1;
        $this->load->view($this->sales_form,$this->data);
    }
    
    public function printEnquiry($id){
        $this->data['dataRow'] = $dataRow = $this->sales->getSalesEnquiry(['id'=>$id,'itemList'=>1]);
        if(!empty($dataRow->party_id)){
            $this->data['partyData'] = $this->party->getParty(['id'=>$dataRow->party_id]);
        }
        elseif(!empty($dataRow->lead_id)){
            $this->data['partyData'] = $this->party->getLead(['id'=>$dataRow->lead_id]);//09-04-2024
        }
        $this->data['companyData'] = $this->masterModel->getCompanyInfo();
        
        $this->data['letter_head'] =  base_url('assets/images/letterhead_top.png');

        $prepare = $this->usersModel->getEmployee(['id'=>$dataRow->created_by]);
        $this->data['dataRow']->prepareBy = $prepareBy = $prepare->emp_name.' <br>('.formatDate($dataRow->created_at).')'; 
        $this->data['dataRow']->approveBy = $approveBy = '';
        if(!empty($dataRow->is_approve)){
            $approve = $this->usersModel->getEmployee(['id'=>$dataRow->is_approve]);
            $this->data['dataRow']->approveBy = $approveBy .= $approve->emp_name.' <br>('.formatDate($dataRow->approve_date).')'; 
        }
    
        $pdfData = $this->load->view('lead/print_enquiry', $this->data, true);        

        $mpdf = new \Mpdf\Mpdf();
        $pdfFileName = str_replace(["/","-"],"_",$dataRow->trans_number) . '.pdf';        
        $stylesheet = file_get_contents(base_url('assets/css/pdf_style.css'));
        $mpdf->WriteHTML($stylesheet, 1);
        $mpdf->SetDisplayMode('fullpage');
        $mpdf->AddPage('P','','','','',5,5,5,15,5,5,'','','','','','','','','','A4-P');
        $mpdf->WriteHTML($pdfData);		
        $mpdf->Output($pdfFileName, 'I');	
    }

    /****************** END ************** */
    
	/*** Sales Quotation Request Functions ***/
	public function salesQuotRequest(){
		$this->data['headData']->pageTitle = "Sales Quotation Request";
        $this->data['tableHeader'] = getSalesDtHeader("salesQuotRequest");
        $this->load->view($this->sales_quot_index,$this->data);
    }

    public function getSqRequestDTRows($status=0){
        $data = $this->input->post(); $data['status']=$status;
        $result = $this->sales->getSqRequestDTRows($data);
        $sendData = array();
        $i = ($data['start']+1);
        foreach ($result['data'] as $row) :
            $row->sr_no = $i++;
            $sendData[] = getSqRequestData($row);
        endforeach;
        $result['data'] = $sendData;
        $this->printJson($result);
    }

	/*** Sales Quotation Functions ***/
    public function salesQuotation(){
		$this->data['headData']->pageTitle = "Sales Quotation";
		$this->data['headData']->pageUrl = "lead/salesQuotation";
        $this->data['tableHeader'] = getSalesDtHeader("salesQuotation");
        $this->load->view($this->sales_quotation_index,$this->data);
    }

    public function getSalesQuotationDTRows($status=0){
        $data = $this->input->post(); $data['status'] = $status;
        $result = $this->sales->getSalesQuotationDTRows($data);
        $sendData = array();
        $i = ($data['start']+1);
        foreach ($result['data'] as $row) :
            $row->sr_no = $i++;
            $sendData[] = getSalesQuotationData($row);
        endforeach;
        $result['data'] = $sendData;
        $this->printJson($result);
    }

    public function addSalesQuotation(){
        $data = $this->input->post();
        $this->data['party_id'] = (!empty($data['party_id']) ? $data['party_id']:"");
        $executive_id = (!empty($this->data['party_id']) ? $this->party->getParty(['id'=>$this->data['party_id']])->executive_id : 0);
        $this->data['lead_id'] = (!empty($data['lead_id']) ? $data['lead_id'] : 0);
        $l_executive_id = (!empty($this->data['lead_id']) ? $this->party->getLead(['id'=>$this->data['lead_id']])->executive_id : 0);//09-04-2024
        $this->data['executive_id'] = (!empty($l_executive_id) ? $l_executive_id : $p_executive_id);
        if(!empty($data['ref_id'])){
            $this->data['from_entry_type'] = 1;
            $this->data['from_ref_id'] = $data['ref_id'];
            $this->data['enqItemList'] = $this->sales->getSalesEnquiryItems(['id'=>$data['ref_id']]);
        }
        $this->data['trans_prefix'] = 'SQ/'.$this->shortYear.'/';
        $this->data['trans_no'] = $this->sales->getNextTransNumber(['tableName'=>'sq_master']);
        $this->data['trans_number'] = $this->data['trans_prefix'].$this->data['trans_no'];
        $this->data['partyList'] = $this->party->getPartyList(['party_type' => 1]);
        $this->data['itemList'] = $this->item->getItemList(['item_type'=>1]);
        $this->data['currencyData'] = $this->configuration->getCurrencyList();   
        $this->load->view($this->sales_quot_form,$this->data);
    }

    public function editSalesQuotation(){
        $id = $this->input->post('id');
        $this->data['dataRow'] = $dataRow = $this->sales->getSalesQuotation(['id'=>$id,'itemList'=>1]);
        $this->data['partyList'] = $this->party->getPartyList(['party_type' => 1]);
        $this->data['itemList'] = $this->item->getItemList(['item_type'=>1]);
        $this->data['currencyData'] = $this->configuration->getCurrencyList();
        $this->data['module_type'] = 2;
        $this->load->view($this->sales_form,$this->data);  
    }

    public function deleteSalesQuotation(){
        $data = $this->input->post();
        if (empty($data['id'])) :
            $this->printJson(['status' => 0, 'message' => 'Somthing went wrong...Please try again.']);
        else :
            $result = $this->sales->deleteSalesQuotation($data['id']);
            $this->printJson($result);
        endif;
    }

    public function changeQuotStatus(){
		$data = $this->input->post();
		
		if(empty($data['id'])):
			$this->printJson(['status'=>0,'message'=>'Something went wrong...Please try again.']);
		else:
			$this->printJson($this->sales->changeQuotStatus($data));
		endif;
	}

    public function printQuotation($id,$pdf_type=''){
        $this->data['dataRow'] = $dataRow = $this->sales->getSalesQuotation(['id'=>$id,'itemList'=>1]);
        if(!empty($dataRow->party_id)){
            $this->data['partyData'] = $this->party->getParty(['id'=>$dataRow->party_id]);
        }
        elseif(!empty($dataRow->lead_id)){
            $this->data['partyData'] = $this->party->getLead(['id'=>$dataRow->lead_id]); //09-04-2024
        }
        $this->data['companyData'] = $this->masterModel->getCompanyInfo();        
        $this->data['letter_head'] =  base_url('assets/images/letterhead_top.png');
        
        $pdfData = $this->load->view('lead/print_quotation', $this->data, true);        
        
		$mpdf = new \Mpdf\Mpdf();
        $pdfFileName = str_replace(["/","-"],"_",$dataRow->trans_number) . '.pdf';      
        $stylesheet = file_get_contents(base_url('assets/css/pdf_style.css'));
        $mpdf->WriteHTML($stylesheet, 1);
        $mpdf->SetDisplayMode('fullpage');
		$mpdf->AddPage('P','','','','',5,5,5,15,5,5,'','','','','','','','','','A4-P');
        $mpdf->WriteHTML($pdfData);
		$mpdf->Output($pdfFileName, 'I');
		
    }

	/*** Sales Order Functions ***/
	public function salesOrder(){
		$this->data['headData']->pageTitle = "Sales Order";
		$this->data['headData']->pageUrl = "lead/salesOrder";
        $this->data['tableHeader'] = getSalesDtHeader("salesOrder");
        $this->load->view($this->sales_order_index,$this->data);
    }

    /* Updated By :- Sweta @02-04-2024 */
    public function getSalesOrderDTRows($status=0){
        $data = $this->input->post(); $data['status'] = $status;
        $result = $this->sales->getSalesOrderDTRows($data);
        $sendData = array();
        $i = ($data['start']+1);
        foreach ($result['data'] as $row) :
            $row->sr_no = $i++;
            $sendData[] = getSalesOrderData($row);
        endforeach;
        $result['data'] = $sendData;
        $this->printJson($result);
    }

    public function addSalesOrder(){
        $data = $this->input->post();
        $this->data['party_id'] = (!empty($data['party_id']) ? $data['party_id']:"");
        $executive_id = (!empty($this->data['party_id']) ? $this->party->getParty(['id'=>$this->data['party_id']])->executive_id : 0);
        $this->data['lead_id'] = (!empty($data['lead_id']) ? $data['lead_id'] : 0);
        $l_executive_id = (!empty($this->data['lead_id']) ? $this->party->getLead(['id'=>$this->data['lead_id']])->executive_id : 0); //09-04-2024
        $this->data['executive_id'] = (!empty($l_executive_id) ? $l_executive_id : $p_executive_id);
         if(!empty($data['ref_id'])){
            $from_entry_type = ($data['entry_type'] == 4) ? 1 : 2;
            $this->data['from_entry_type'] = $from_entry_type;
            $this->data['from_ref_id'] = $data['ref_id'];
            $this->data['fromRefItemList'] = ($from_entry_type == 1) ? $this->sales->getSalesEnquiryItems(['id'=>$data['ref_id']]) : $this->sales->getSalesQuotationItems(['id'=>$data['ref_id']]);
        }
        $this->data['trans_prefix'] = 'SO/'.$this->shortYear.'/';
        $this->data['trans_no'] = $this->sales->getNextTransNumber(['tableName'=>'so_master']);
        $this->data['trans_number'] = $this->data['trans_prefix'].$this->data['trans_no'];
        $this->data['partyList'] = $this->party->getPartyList(['party_type'=>1]);
        $this->data['itemList'] = $this->item->getItemList(['item_type'=>1]);
        $this->load->view($this->sales_ord_form,$this->data);
    }

    public function editSalesOrder(){
        $id = $this->input->post('id');
        $this->data['dataRow'] = $dataRow = $this->sales->getSalesOrder(['id'=>$id,'itemList'=>1]);
        $this->data['partyList'] = $this->party->getPartyList(['party_type'=>1]);
        $this->data['itemList'] = $this->item->getItemList(['item_type'=>1]);
        $this->data['currencyData'] = $this->configuration->getCurrencyList();
        $this->data['module_type'] = 3;
        $this->load->view($this->sales_form,$this->data); 
    }

    public function deleteSalesOrder(){
        $data = $this->input->post();
        if (empty($data['id'])) :
            $this->printJson(['status' => 0, 'message' => 'Somthing went wrong...Please try again.']);
        else :
            $result = $this->sales->deleteSalesOrder($data['id']);
            $this->printJson($result);
        endif;
    }

    public function printOrder($id,$pdf_type=''){
        $this->data['dataRow'] = $dataRow = $this->sales->getSalesOrder(['id'=>$id,'itemList'=>1]);
        if(!empty($dataRow->party_id)){
            $this->data['partyData'] = $this->party->getParty(['id'=>$dataRow->party_id]);
        }
        elseif(!empty($dataRow->lead_id)){
            $this->data['partyData'] = $this->party->getLead(['id'=>$dataRow->lead_id]); //09-04-2024
        }
        $this->data['companyData'] = $this->masterModel->getCompanyInfo();
        
        $this->data['letter_head'] =  base_url('assets/images/letterhead_top.png');

        $prepare = $this->usersModel->getEmployee(['id'=>$dataRow->created_by]);
		$this->data['dataRow']->prepareBy = $prepareBy = $prepare->emp_name.' <br>('.formatDate($dataRow->created_at).')'; 
		$this->data['dataRow']->approveBy = $approveBy = '';
		if(!empty($dataRow->is_approve)){
			$approve = $this->usersModel->getEmployee(['id'=>$dataRow->is_approve]);
			$this->data['dataRow']->approveBy = $approveBy .= $approve->emp_name.' <br>('.formatDate($dataRow->approve_date).')'; 
		}
 
        $pdfData = $this->load->view('lead/print_order', $this->data, true);   
		$mpdf = new \Mpdf\Mpdf();
        $pdfFileName = str_replace(["/","-"],"_",$dataRow->trans_number) . '.pdf';
        $stylesheet = file_get_contents(base_url('assets/css/pdf_style.css'));
        $mpdf->WriteHTML($stylesheet, 1);
        $mpdf->SetDisplayMode('fullpage');
		$mpdf->AddPage('P','','','','',5,5,5,15,5,5,'','','','','','','','','','A4-P');
        $mpdf->WriteHTML($pdfData);
		$mpdf->Output($pdfFileName, 'I');		
    }

    public function changeOrderStatus(){
		$data = $this->input->post();
		
		if(empty($data['id'])):
			$this->printJson(['status'=>0,'message'=>'Something went wrong...Please try again.']);
		else:
			$this->printJson($this->sales->changeOrderStatus($data));
		endif;
	}

    public function getDefaultDiscount(){
        $data = $this->input->post();
        $discData = $this->configuration->getDiscountData(['structure_name'=>$data['disc_structure'], 'category_id'=>$data['category_id'], 'single_row'=>1]);
        $this->printJson(['status'=>1, 'regular_disc'=>(!empty($discData) ? $discData->discount : 0)]);
    }
    
   
    /*** Return Order Functions ***/
	public function returnOrder(){
		$this->data['headData']->pageTitle = "Return Order";
        $this->data['tableHeader'] = getSalesDtHeader("returnOrder");
        $this->load->view($this->return_order_index,$this->data);
    }

    /* Updated By :- Sweta @02-04-2024 */
    public function getReturnOrderDTRows($status=0){
        $data = $this->input->post(); $data['status'] = $status;
        $result = $this->sales->getReturnOrderDTRows($data);
        $sendData = array();
        $i = ($data['start']+1);
        foreach ($result['data'] as $row) :
            $row->sr_no = $i++;
            $sendData[] = getReturnOrderData($row);
        endforeach;
        $result['data'] = $sendData;
        $this->printJson($result);
    }

    public function addReturnOrder(){
        $data = $this->input->post();       
        $this->data['trans_prefix'] = 'RT'.n2y(date('Y')).n2m(date('m'));
        $this->data['trans_no'] = $this->sales->getNextTransNumber(['tableName'=>'so_master', 'entry_type'=>2]);
        $this->data['partyList'] = $this->party->getPartyList(['party_type'=>1]);
        $this->data['bTypeList'] = $this->configuration->getBusinessTypeList();
        $this->load->view($this->return_order_form,$this->data);
    }

    public function editReturnOrder(){
        $id = $this->input->post('id');
        $this->data['dataRow'] = $dataRow = $this->sales->getSalesOrder(['id'=>$id,'itemList'=>0]);
        $this->data['bTypeList'] = $this->configuration->getBusinessTypeList();
        $this->data['transData'] = $transData = $this->sales->getSalesOrderItems(['trans_main_id'=>$id,'single_row'=>1]);
        $this->data['partyList'] = $this->party->getPartyList(['party_type'=>1,'business_type'=>$dataRow->business_type]);
        $this->data['options'] = $this->getSoWiseItemList(['party_id'=>$dataRow->party_id,'item_id'=>$transData->item_id]);
        $this->load->view($this->return_order_form,$this->data);
    }

    public function getSoWiseItemList($param =[]){
        $data = (!empty($param))?$param:$this->input->post();
        $itemData = $this->sales->getSalesOrderItems(['party_id'=>$data['party_id'],'entry_type'=>1,'group_by'=>'item_id']);
        $options = '<option value="">Select Product</option>';
        if(!empty($itemData)){
            foreach($itemData as $row){
                $selected = (!empty($data['item_id']) && $data['item_id'] == $row->item_id) ? 'selected' : '';
                $options .= '<option value="'.$row->item_id.'" '.$selected.'>[ '.$row->item_code.' ] '.$row->item_name.'</option>';
            }
        }
        if(!empty($param)){  return $options; }
        else{  $this->printJson(['status'=>1, 'options'=>$options]);  }
    }

    public function saveReturnOrder(){
        $data = $this->input->post();
        $errorMessage = array();

        if(empty($data['trans_number'])){
            $errorMessage['trans_number'] = "Return No. is required.";
        }
        if(empty($data['trans_date'])){
            $errorMessage['trans_date'] = "Return Date is required.";
        }
        if(empty($data['party_id'])){
            $errorMessage['party_id'] = "Customer is required.";
        }
        if(empty($data['item_id'])){
            $errorMessage['item_id'] = "Product is required.";
        }
        if(empty($data['qty'])){
            $errorMessage['qty'] = "Qty. is required.";
        }
        
        if(!empty($errorMessage)):
            $this->printJson(['status'=>0,'message'=>$errorMessage]);
        else:
			$this->printJson($this->sales->saveReturnOrder($data));
        endif;
    }

    /*** Comman Function for Sales Enquiry, Sales Quotation, Sales Order */
    public function addSalesData(){
		$data = $this->input->post(); $transPrefix = ""; $tableName="";$executive_id = 0;
		/**get Table Name and Trans Prefix*/
		switch ($data['module_type']) {
			case 1:
				$transPrefix = 'SE';
				$tableName="se_master";
				break;
			case 2:
				$transPrefix = 'SQ' ;
				$tableName="sq_master";
				break;
			case 3:
				$transPrefix = 'SO';
				$tableName="so_master";
				break;
		}
		$this->data['module_type'] = $data['module_type'];
		$this->data['trans_prefix'] = $transPrefix.n2y(date("Y"));
		$this->data['trans_no'] = $this->sales->getNextTransNumber(['tableName'=>$tableName]);
        $this->data['trans_number'] = $this->data['trans_prefix'].str_pad($this->data['trans_no'],4,0,STR_PAD_LEFT );
		$this->data['party_id'] = (!empty($data['party_id']) ? $data['party_id']:"");
		$this->data['lead_id'] = (!empty($data['lead_id']) ? $data['lead_id']:"");
		if(!empty($data['party_id'])){ $executive_id = $this->party->getParty(['id'=>$this->data['party_id']])->executive_id ; }
		elseif(!empty($data['lead_id'])){ $executive_id = $this->party->getLead(['id'=>$this->data['lead_id']])->executive_id ; } //09-04-2024
        $this->data['executive_id'] = $executive_id;
        $this->data['itemList'] = $this->item->getItemList(['item_type'=>1]);
        $this->data['currencyData'] = $this->configuration->getCurrencyList();   
        if(!empty($data['ref_id'])){
            $from_entry_type = ($data['entry_type'] == 4) ? 1 : 2;
            $this->data['from_entry_type'] = $from_entry_type;
            $this->data['from_ref_id'] = $data['ref_id'];
            $this->data['fromRefItemList'] = ($from_entry_type == 1) ? $this->sales->getSalesEnquiryItems(['id'=>$data['ref_id']]) : $this->sales->getSalesQuotationItems(['id'=>$data['ref_id']]);
        }
        $this->data['bTypeList'] = $this->configuration->getBusinessTypeList();
        $this->data['termsData'] = $this->configuration->getTermsList(['type'=>'Sales']); 
        $this->load->view($this->sales_form,$this->data);
	}
	
	public function saveSalesData(){
        $data = $this->input->post();
        $errorMessage = array();
        if(empty($data['itemData'])){
            $errorMessage['itemData'] = "Item Details is required.";
        }else{
            $i=1;
            if($data['module_type'] != 1){
                foreach($data['itemData'] as $row){
                    if(empty($row['price'])){
                        $errorMessage['price'.$i] = "Price is required.";
                    }
                    $i++;
                }
				
				/*if(empty($data['conditions'])){
                    $errorMessage['conditions'] = "Conditions is required.";
                }*/
            }
        }
        if(!empty($errorMessage)):
            $this->printJson(['status'=>0,'message'=>$errorMessage]);
        else:
            if($_FILES['order_file']['name'] != null || !empty($_FILES['order_file']['name'])):
                $this->load->library('upload');
				$_FILES['userfile']['name']     = $_FILES['order_file']['name'];
				$_FILES['userfile']['type']     = $_FILES['order_file']['type'];
				$_FILES['userfile']['tmp_name'] = $_FILES['order_file']['tmp_name'];
				$_FILES['userfile']['error']    = $_FILES['order_file']['error'];
				$_FILES['userfile']['size']     = $_FILES['order_file']['size'];
				
				$imagePath = realpath(APPPATH . '../assets/uploads/sales_order/');
				$config = ['file_name' => time()."_order_item_".$_FILES['userfile']['name'],'allowed_types' => '*','max_size' => 10240,'overwrite' => FALSE, 'upload_path'	=>$imagePath];

				$this->upload->initialize($config);
				if (!$this->upload->do_upload()):
					$errorMessage['order_file'] = $this->upload->display_errors();
					$this->printJson(["status"=>0,"message"=>$errorMessage]);
				else:
					$uploadData = $this->upload->data();
					$data['order_file'] = $uploadData['file_name'];
				endif;
			endif;
            $result = $this->sales->saveSalesData($data);
			$result['salesLog'] = $this->getSalesLog($data);
			$this->printJson($result);
        endif;
    }

    public function  getPartyOptions(){
        $data = $this->input->post();
        $partyList = $this->party->getPartyList(['business_type'=>$data['business_type']]);
        $options = '<option value="">Select</option>';
        if(!empty($partyList)){
            foreach($partyList as $row){ 
                $options .= '<option value="'.$row->id.'">'.$row->party_name.'</option>';
            }
        }
        $this->printJson(['status'=>1, 'options'=>$options]);
        
    }

    /* SO Dispatch Plan */
    /* Created By :- Sweta @03-04-2024 12:04 PM */
    public function addDispatch(){
        $data = $this->input->post();
        $this->data['dataRow'] = $data;
        $this->data['itemList'] = $this->sales->getSalesOrderItems(['trans_main_id'=>$data['id']]);
        $this->load->view($this->dispatch_form,$this->data);        
    }

    /* Created By :- Sweta @03-04-2024 14:35 PM */
    public function saveDispatch(){
        $data = $this->input->post();
        $errorMessage = array();

        if(empty($data['dispatch_date'])){
            $errorMessage['dispatch_date'] = "Dispatch Date is required.";
        }
        if(empty($data['item_id'])){
            $errorMessage['item_id'] = "Item is required.";
        }
        if(empty($data['qty'])){
            $errorMessage['qty'] = "Qty is required.";
        }
        else{
            if($data['qty'] > $data['order_qty']){
                $errorMessage['qty'] = "Invalid qty.";                
            }
        }

        if(!empty($errorMessage)):
            $this->printJson(['status'=>0,'message'=>$errorMessage]);
        else:
			$this->printJson($this->sales->saveDispatch($data));
        endif;
    }

    /* Created By :- Sweta @03-04-2024 12:04 PM */
    public function getDispatchHtml(){  
        $data = $this->input->post();
        $dispatchData = $this->sales->getDispatch($data);
		$i=1; $tbody='';
        
		if(!empty($dispatchData)):
			foreach($dispatchData as $row):
                $deleteParam = "{'postData':{'id' : ".$row->id.",'qty' : '".$row->qty."','so_trans_id' : '".$row->so_trans_id."'},'message' : 'Dispatch','res_function':'getDispatchHtml','fndelete':'deleteDispatch'}";
				$tbody.= '<tr>
						<td>'.$i++.'</td>
						<td>'.formatDate($row->dispatch_date).'</td>
						<td>'.$row->ref_no.'</td>
						<td>['.$row->item_code.'] '.$row->item_name.'</td>
						<td>'.floatval($row->qty).'</td>
						<td>'.$row->notes.'</td>
						<td class="text-center">
							<button type="button" onclick="trash('.$deleteParam.');" class="btn btn-outline-danger waves-effect waves-light permission-remove"><i class="mdi mdi-trash-can-outline"></i></button>
						</td>
					</tr>';
			endforeach;
		endif;
        $this->printJson(['status'=>1,'tbodyData'=>$tbody]);
	}
	
    /* Created By :- Sweta @03-04-2024 14:50 PM */
	public function deleteDispatch(){ 
        $data = $this->input->post();
        if(empty($data['id'])):
            $this->printJson(['status'=>0,'message'=>'Somthing went wrong...Please try again.']);
        else:
			$this->printJson($this->sales->deleteDispatch($data));
		endif;
    }

    public function checkPartyDuplicate(){
        $data = $this->input->post();
        $customWhere = "party_name = '".$data['party_name']."' OR contact_phone = '".$data['contact_phone']."'";
        $leadData = $this->party->getLead(['customWhere'=>$customWhere]);
        $this->printJson(['status'=>1,'lead_id'=>(!empty($leadData->id)?$leadData->id:"")]);
    }

    /* View Lead Details */
    public function viewLeadDetails(){
        $data = $this->input->post();
        $this->data['leadData'] = $this->party->getLead(['id'=>$data['id']]);
        $this->load->view($this->view_lead_detail,$this->data);        
    }
}
?>